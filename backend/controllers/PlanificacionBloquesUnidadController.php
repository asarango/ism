<?php

namespace backend\controllers;

use backend\models\CurriculoMec;
use backend\models\CurriculoMecAsignatutas;
use backend\models\CurriculoMecNiveles;
use backend\models\OpCourse;
use backend\models\ScholarisMateria;
use backend\models\IsmAreaMateria;
use Yii;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanificacionDesagregacionCabeceraSearch;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\diplomaphpv\Pdf;
use backend\models\diplomaphpv\PdfPh;
use backend\models\pudpai\PdfPhPai;
use backend\models\pudpai\PdfPvPai;
use backend\models\pudpai\Pdf as PudpaiPdf;

use backend\models\helpers\HelperGeneral;
use backend\models\PlanificacionVerticalDiploma;
use backend\models\PlanificacionVerticalPaiOpciones;
use backend\models\PudAprobacionBitacora;
use backend\models\Usuario;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class PlanificacionBloquesUnidadController extends Controller{
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->identity) {

            //OBTENGO LA OPERACION ACTUAL
            list($controlador, $action) = explode("/", Yii::$app->controller->route);
            $operacion_actual = $controlador . "-" . $action;
            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
                echo $this->render('/site/error', [
                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
                    'name' => 'Acceso denegado!!',
                ]);
            }
        } else {
            header("Location:" . \yii\helpers\Url::to(['site/login']));
            exit();
        }
        return true;
    }

    public function actionIndex1()
    {
        $institutoId        = Yii::$app->user->identity->instituto_defecto;
        $usuarioLogueado    = Yii::$app->user->identity->usuario;
        $usuario = Usuario::findOne($usuarioLogueado);
        $perfil = $usuario->rol->rol;                        

        $cabeceraId = $_GET['id'];
        $cabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId); //toma datos de la cabecera
        $opCourseTemplateId = $cabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;
        //$materia = ScholarisMateria::findOne($cabecera->scholaris_materia_id);// toma datos de la materia scholaris
        $materia = IsmAreaMateria::findOne($cabecera->ismAreaMateria->materia_id);// toma datos de la materia scholaris
        $ismAreaMateria = IsmAreaMateria::findOne($cabecera->ism_area_materia_id);// toma datos de la materia scholaris

        $seccion = $this->consultar_section($opCourseTemplateId, 
                                            $cabecera->scholaris_periodo_id, $institutoId);        

        $this->inserta_bloques($cabeceraId); //Inserta los bloques para la planificacion de las unidades
        $unidades = PlanificacionBloquesUnidad::find()
                    ->innerJoin('curriculo_mec_bloque c','c.id = planificacion_bloques_unidad.curriculo_bloque_id')
                    ->where([
                        'plan_cabecera_id' => $cabeceraId,
                        'c.is_active' => true
                           ])
                    ->orderBy('curriculo_bloque_id')
                    ->all(); 
        
        $scripts = new \backend\models\helpers\Scripts();
        
        if($cabecera->coordinador_user){
             $firmaAprueba = $scripts->firmar_documento($cabecera->coordinador_user, $cabecera->fecha_aprobacion_coordinacion);
            $firmaElaborado = $scripts->firmar_documento($usuarioLogueado, $cabecera->fecha_envio_coordinador);
        }else{
            $firmaAprueba = $scripts->firmar_documento('admin', '2000-01-01');
            $firmaElaborado = $scripts->firmar_documento('admin', '2000-01-01');
        }       
             
        return $this->render('index', [
            'materia'   => $materia,
            'unidades'  => $unidades,
            'cabecera'  => $cabecera,
            'seccion'   => $seccion,
            'perfil'    => $perfil,
            'firmaAprueba' => $firmaAprueba,
            'firmaElaborado' => $firmaElaborado,
            'ismAreaMateria' => $ismAreaMateria
        ]);
    } 

    private function consultar_section($templateId, $scholarisPeriodoId, $institutoId){
        $con = Yii::$app->db;
        $query = "select 	s.code 
                            from op_course c
                    inner join op_section s on s.id = c.section
                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                    where c.x_template_id = $templateId and sop.scholaris_id = $scholarisPeriodoId and c.x_institute = $institutoId";

        $res = $con->createCommand($query)->queryOne();
            return $res['code'];
    }

    private function inserta_bloques($cabeceraId){
        $con = Yii::$app->db;
        $query = "insert into planificacion_bloques_unidad(curriculo_bloque_id, plan_cabecera_id, unit_title, settings_status, is_open)
                    select 	b.id
                            ,$cabeceraId
                            ,'sin titulo'
                            ,'no-configurado'
                            ,true
                    from 	curriculo_mec_bloque b
                    where 	id not in (select 	curriculo_bloque_id 
                    from 	planificacion_bloques_unidad
                    where 	plan_cabecera_id = $cabeceraId
                            and curriculo_bloque_id = b.id)
                            and b.is_active = true
                    order by b.code;";
                
        $con->createCommand($query)->execute();
        
    }

    public function actionUpdate()
    {
        $unidadId = $_GET['unidad_id'];
        $model = PlanificacionBloquesUnidad::findOne($unidadId);
        $modelSubtitulo = PlanificacionBloquesUnidadSubtitulo::find()->where([
            'plan_unidad_id' => $unidadId
        ])->all();

        if($model->load(Yii::$app->request->post()) ){            

            $model->save();

            return $this->redirect(['index1', 'id' => $model->plan_cabecera_id]);
        }            

        return $this->render('update', [
            'model' => $model,
            'modelSubtitulo' => $modelSubtitulo
        ]);
    }

    public function actionContenido(){
        
        $planUnidadId = $_GET['unidad_id'];
        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);         

        return $this->render('contenido', [
            'planUnidad' => $planUnidad
        ]);
    }

    public function actionArbol(){

        $planUnidadId = $_GET['plan_unidad_id'];
        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);
        $subtitulos = PlanificacionBloquesUnidadSubtitulo::find()->where([
            'plan_unidad_id' => $planUnidadId
        ])
        ->orderBy('orden')
        ->all();
       
        return $this->renderPartial('_arbol',[            
            'subtitulos' => $subtitulos,
            'planUnidad' => $planUnidad
        ]);
    }

    public function actionTema(){      
        
        $planUnidadId = $_GET['plan_unidad_id'];
        $subtituloId = $_GET['subtitulo_id'];
        $subtitulosTema = PlanificacionBloquesUnidadSubtitulo::find()
        ->where([ 'id' => $subtituloId ])
        ->orderBy('orden')
        ->all();

        return $this->renderPartial('_temas',[
            'subtitulosTema' => $subtitulosTema,
            'planUnidadId' => $planUnidadId
        ]);
    }

    public function actionGuardar(){
        // echo '<pre>';
        // print_r($_POST);
        // die();
                
        $id = $_POST['PlanificacionBloquesUnidadSubtitulo']['id'];
        $planUnidadId = $_POST['PlanificacionBloquesUnidadSubtitulo']['planUnidadId'];
        $model =  PlanificacionBloquesUnidadSubtitulo::findOne($id);
            $model->experiencias = $_POST['experiencia_update'];
            $model->evaluacion_formativa = $_POST['evaluacion_update'];
            $model->diferenciacion = $_POST['diferenciacion_update']; 
            $model->save();
            return $this->redirect(['contenido', 'unidad_id' => $planUnidadId]);    
    }

    public function actionCreateTitle(){
        // print_r($_POST);
        // die();        

        $planUnidadId = $_POST['plan_unidad_id'];
        $orden = $_POST['total_subtitulos'];

        $model = new PlanificacionBloquesUnidadSubtitulo();
        $model->plan_unidad_id = $planUnidadId;
        $model->subtitulo = 'none';
        $model->orden = $orden+1;
        $model->trazabilidad = 'Seleccione Uno';
        $model->verificacion = 'no';
        
        $model->save();

        // $id = $_POST['PlanificacionBloquesUnidadSubtitulo']['id'];
        // $planUnidadId = $_POST['PlanificacionBloquesUnidadSubtitulo']['planUnidadId'];        
        // $subtitulo = $_POST['PlanificacionBloquesUnidadSubtitulo']['subtitulo'];
        // $orden = $_POST['PlanificacionBloquesUnidadSubtitulo']['orden'];
        // $trazabilidad = $_POST['PlanificacionBloquesUnidadSubtitulo']['trazabilidad'];
        // $verificacion = $_POST['PlanificacionBloquesUnidadSubtitulo']['verificacion'];

        // $model =  PlanificacionBloquesUnidadSubtitulo::findOne($id);
        // $model->subtitulo = $subtitulo;
        // $model->orden = $orden;
        // $model->trazabilidad = $trazabilidad;
        // $model->verificacion = $verificacion;            
        // $model->save();

        //     $model->experiencias = $_POST['experiencia_update'];
        //     $model->evaluacion_formativa = $_POST['evaluacion_update'];
        //     $model->diferenciacion = $_POST['diferenciacion_update']; 

        // return $this->redirect(['contenido', 'unidad_id' => $planUnidadId]);
                
    }

    public function actionDeleteSubtitle(){

        $id = $_GET['id'];
        $model = PlanificacionBloquesUnidadSubtitulo::findOne($id);
        $planUnidadId = $model->plan_unidad_id;

        $model->delete();

        return $this->redirect(['contenido', 'unidad_id' => $planUnidadId ]);
    }

    public function actionUpdateSubtitle()
    {            
        
        $id = $_POST['PlanificacionBloquesUnidadSubtitulo']['id'];
        $subtitulo = $_POST['PlanificacionBloquesUnidadSubtitulo']['subtitulo'];
        $orden = $_POST['PlanificacionBloquesUnidadSubtitulo']['orden'];
        $trazabilidad = $_POST['PlanificacionBloquesUnidadSubtitulo']['trazabilidad'];
        $verificacion = $_POST['PlanificacionBloquesUnidadSubtitulo']['verificacion'];
        $experiencias = $_POST['experiencia_update'];
        $evaluaciones = $_POST['evaluacion_update'];
        $diferenciacion = $_POST['diferenciacion_update'];
        
        $model = PlanificacionBloquesUnidadSubtitulo::findOne($id);
        $model->subtitulo = $subtitulo;
        $model->orden = $orden;
        $model->experiencias = $experiencias;
        $model->evaluacion_formativa = $evaluaciones;
        $model->diferenciacion = $diferenciacion;
        $model->trazabilidad = $trazabilidad;
        $model->verificacion = $verificacion;
       
        $model->save();

        return $this->redirect(['contenido', 'unidad_id' => $model->plan_unidad_id]);
    }

    public function actionCreateSubtitle2(){     
    
        $subtituloId            = $_POST['subtitulo_id'];
        $subtitulo2Contenido    = $_POST['contenido'];
        $subtitulo2Orden        = $_POST['orden'];
        $planUnidadId           = $_POST['planUnidadId'];        
        
        $model = new PlanificacionBloquesUnidadSubtitulo2();
        $model->subtitulo_id = $subtituloId;
        $model->contenido = $subtitulo2Contenido;
        $model->orden = $subtitulo2Orden;
  
        $model->save();

        return $this->redirect(['contenido', 'unidad_id' => $planUnidadId]);
    }

    public function actionDeleteSubtitle2(){
        $id = $_GET['id'];
        $model = PlanificacionBloquesUnidadSubtitulo2::findOne($id);
        $planUnidadId = $model->subtitulo->plan_unidad_id;
        $model->delete();

        return $this->redirect(['contenido', 'unidad_id' => $planUnidadId]);
    }

    public function actionEnviaCoordinador(){
        
        $cabeceraId = $_GET['cabecera_id'];
        $hoy = date('Y-m-d H:i:s');

        $planCabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);

        if($planCabecera->estado == 'DEVUELTO'){
            $planCabecera->fecha_de_cambios = $hoy;
        }

        $planCabecera->estado = 'EN_COORDINACION';
        $planCabecera->coordinador_user = 'admin';
        $planCabecera->fecha_envio_coordinador = $hoy;
        $planCabecera->save();
        
        return $this->redirect(['index1', 'id' => $cabeceraId]);

    }

    public function actionAbrirBloque(){
        $planUnidadId = $_GET['plan_unidad_id'];        

        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $planUnidad->is_open = true;
        $planUnidad->pud_status = false;
        $planUnidad->settings_status = 'en-proceso';
        $planUnidad->save();

        $cabecera = PlanificacionDesagregacionCabecera::findOne($planUnidad->plan_cabecera_id);
        $cabecera->estado = 'DEVUELTO';
        $cabecera->save();

        return $this->redirect(['index1', 'id' => $planUnidad->plan_cabecera_id]);
    }


    /****PARA GENERAR PDF DE PLAN VERTICAL Y HORIZONTAL DE DIPLOMA */
    public function actionPdfPvDp(){
        $cabeceraId = $_GET['cabecera_id'];
        new Pdf($cabeceraId);
    }
   
    public function actionPdfPhDp()
    {
        $cabeceraId = $_GET['cabecera_id'];             
        new PdfPh($cabeceraId);       
    }
    /****FIN PARA GENERAR PDF DE PLAN HORIZONTAL DE DIPLOMA */

    /****PARA GENERAR PDF DE PLAN VERTICAL Y HORIZONTAL DE PAI */
    public function actionPdfPvPai()
    {
        $cabeceraId = $_GET['cabecera_id'];
        new PdfPvPai($cabeceraId);
    }
   
    public function actionPdfPhPai()
    {
        $cabeceraId = $_GET['cabecera_id'];             
        new PdfPhPai($cabeceraId);       
    }
    /****FIN PARA GENERAR PDF DE PLAN HORIZONTAL DE DIPLOMA */

    /**METODO PARA EL ENVIO DE LA APROBACION DEL PUD DIP */
    public function actionEnvioAprobacion(){
        $mensaje ="PUD CONCLUIDO, POR FAVOR SU REVISIÓN";
        $planBloqUnidadId = $_GET['modelPlanBloqUnidad'];

        $modelPlanBloqUnidad = PlanificacionBloquesUnidad::findOne($planBloqUnidadId); 

        $hoy = date('Y-m-d H:i:s');
        $modelAprobBitacoraPud = new PudAprobacionBitacora();
        $modelAprobBitacoraPud->unidad_id = $planBloqUnidadId;
        $modelAprobBitacoraPud->notificacion=$mensaje;
        $modelAprobBitacoraPud->usuario_notifica=Yii::$app->user->identity->usuario;
        $modelAprobBitacoraPud->fecha_notifica = $hoy;
        $modelAprobBitacoraPud->estado_jefe_coordinador = 'ENVIADO';    
        $modelAprobBitacoraPud->save();    
        
        return $this->redirect(['index1', 'id' =>  $modelPlanBloqUnidad->plan_cabecera_id]);
    }



    public function actionPudNacional(){
        $planificacionBloquesUnidadId = $_GET['plan_bloque_unidad_id'];

        return $this->redirect(['pud-nacional-diploma/index1', 'plan_bloque_unidad_id' => $planificacionBloquesUnidadId]);
    }

}