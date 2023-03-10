<?php

namespace backend\controllers;

use backend\models\PepOpciones;
use backend\models\PepUnidadDetalle;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * PlanificacionDesagregacionCabeceraController implements the CRUD actions for PlanificacionDesagregacionCabecera model.
 */
class PepDetalleController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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

    public function beforeAction($action) {
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

    public function actionIndex1() {
        $temaId = $_GET['tema_id'];
        
        
        $opCourseTemplateId = $_GET['op_course_template_id'];
        $tema = \backend\models\PepPlanificacionXUnidad::findOne($temaId);
        
        $this->ingresa_todas_opciones($temaId);
        
        $registros = \backend\models\PepUnidadDetalle::find()
        ->where(['pep_planificacion_unidad_id' => $temaId])
        ->orderBy('id')->all();
        
        $semanas = $this->get_semanas($temaId);
        $planesSemanales = $this->get_planes_semanales($temaId);
//        $planesSemanales = \backend\models\PepPlanSemanal::find()->where(['pep_planificacion_id' => $tema->id])->all();
               
        return $this->render('index', [        
           'tema'               => $tema,
           'registros'          => $registros,
           'registros'          => $registros,
           'semanas'            => $semanas,
           'planesSemanales'    => $planesSemanales
        ]);
    }
       
    
    private function get_semanas($planId){
        $con = Yii::$app->db;
        $query = "select 	lms.semana_numero 
                                    ,sem.nombre_semana  
                    from	pep_planificacion_x_unidad plan
                                    inner join scholaris_bloque_actividad blo on blo.id = plan.bloque_id 
                                    inner join scholaris_bloque_semanas sem on sem.bloque_id = blo.id 
                                    inner join lms on lms.semana_numero = sem.semana_numero 
                                            and lms.tipo_bloque_comparte_valor = 1
                    where 	plan.id = $planId
                    group by lms.semana_numero, sem.nombre_semana
                    order by sem.nombre_semana ;";
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
        
    }
    
    
    private function get_planes_semanales($planId){
        $con = Yii::$app->db;
        $query = "select 	lms.semana_numero, lms.titulo, lms.indicaciones, lms.tarea  
                    from	pep_planificacion_x_unidad plan
                                    inner join scholaris_bloque_actividad blo on blo.id = plan.bloque_id 
                                    inner join scholaris_bloque_semanas sem on sem.bloque_id = blo.id 
                                    inner join lms on lms.semana_numero = sem.semana_numero 
                                            and lms.tipo_bloque_comparte_valor = 1
                    where 	plan.id = $planId
                    order by sem.nombre_semana, lms.hora_numero;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function ingresa_todas_opciones($temaId){
        
        $modelRegistros = \backend\models\PepUnidadDetalle::find()->where(['pep_planificacion_unidad_id' => $temaId])->orderBy('id')->all();
        
        /* verifica si existe la idea principal */
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'idea_central', 'info_general','texto');        
        
        
        /* verifica si existe Líneas de indagación */
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'linea_indagacion', 'info_general','texto');
        
        /* verifica si existe Conceptos clave */
        $this->ingresa_opciones_generico_seleccion($temaId, 'concepto_clave', 'conceptos_atributos', 'seleccion');
        
        /* verifica si existe Conceptos relacionados */
        $this->ingresa_opciones_conceptos_relacionados($temaId, 'concepto_relacionado', 'conceptos_atributos', 'seleccion');
        
        /* verifica si existe atributos del perfil de la comunidad de aprendizaje */
        $this->ingresa_opciones_generico_seleccion($temaId, 'atributos_perfil', 'conceptos_atributos', 'seleccion');  
        
        /* verifica si existe enfoques de aprendizaje */
        $this->ingresa_opciones_enfoques($temaId, 'enfoques_aprendizaje', 'enfoques_aprendizaje', 'seleccion');   
        
        /* verifica si existe ACCION */
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'accion', 'info_general','texto');
        
        
        /******** REFLEXIONESZ ************************/
        /* verifica si existe REFLEXIONES INICIALES*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'reflexiones_iniciales', 'reflexion_planificacion','texto');
        
        /* verifica si existe REFLEXIONES INICIALES*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'conocimientos_previos', 'reflexion_planificacion','texto');
        
        /* verifica si existe TRANSDISCIPLINARIAS CON EL PASADO*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'transdisciplinarias_pasado', 'reflexion_planificacion','texto');
        
        /* verifica si existe Objetivos de aprendizaje*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'objetivos_aprendizaje', 'reflexion_planificacion','texto');
        
        /* verifica si existe Preguntas de los maestros*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'preguntas_maestros', 'reflexion_planificacion','texto');
        
        /* verifica si existe Preguntas de los alumnos*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'preguntas_alumnos', 'reflexion_planificacion','texto');
        
        
        /******** DISE;O E IMPLEMENTACION ************************/
        /* verifica si existe Diseñar experiencias de aprendizaje*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'experiencias_aprendizaje', 'diseño_implementacion','texto');
        
        /* verifica si existe Apoyo a la agencia de alumnos*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'agencia_alumnos', 'diseño_implementacion','texto');
        
        /* verifica si existe preguntas de los mestros y alumnos*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'preguntas_mestros_alumnos', 'diseño_implementacion','texto');
        
        /* verifica si existe evaluación continua*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'evaluacion_continua', 'diseño_implementacion','texto');
        
        /* verifica si existe uso flexible de recursos*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'recursos', 'diseño_implementacion','texto');
        
        /* verifica si existe autoevaluación*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'autoevaluacion', 'diseño_implementacion','texto');
        
        /* verifica si existe continua de todos los maestros*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'evaluacion_maestros', 'diseño_implementacion','texto');
        
        /* verifica si existe reflexiones adicionales específicas de una asignatura*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'adicionales', 'diseño_implementacion','texto');
        
        
        /******** REFLEXIÓN ************************/
        /* verifica si existe Reflexiones de los maestros*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'reflexion_maestros', 'reflexion','texto');
        
        /* verifica si existe Reflexiones de los alumnos*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'reflexion_alumnos', 'reflexion','texto');
        
        /* verifica si existe Reflexiones sobre la evaluación*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'reflexion_evaluacion', 'reflexion','texto');
        
    }
    
    
    private function ingresa_opciones_enfoques($temaId, $tipo, $referencia, $campoDe){
        $con = \Yii::$app->db;
        $query = "insert into pep_unidad_detalle (pep_planificacion_unidad_id, tipo, referencia, campo_de, contenido_texto, contenido_opcion) 
                    select $temaId, '$tipo', '$referencia', '$campoDe' ,op.categoria_principal_es,false 
                    from pep_opciones op 
                    where op.tipo = '$tipo'
                    and op.categoria_principal_es  not in (select   contenido_texto 
                                                            from    pep_unidad_detalle 
                                                            where   pep_planificacion_unidad_id = $temaId 
                                                                    and contenido_texto = op.categoria_principal_es
                                                                    and tipo = '$tipo') group by op.categoria_principal_es; ";       
        
        $query = "insert into pep_unidad_detalle (pep_planificacion_unidad_id, tipo, referencia, campo_de, contenido_texto, contenido_opcion) 
                    select 	$temaId, '$tipo', op.categoria_principal_es, '$campoDe' ,op.contenido_es,false 
                    from 	pep_opciones op 
                    where 	op.tipo = '$tipo' 
                                    and op.contenido_es  not in (select contenido_texto 
                                                                    from pep_unidad_detalle 
                                                                    where pep_planificacion_unidad_id = $temaId
                                                                        and contenido_texto = op.contenido_es  
                                                                        and tipo = '$tipo')
                    order by op.id,op.tipo;";       
        
        $con->createCommand($query)->execute();
    }
    
    private function ingresa_opciones_generico_seleccion($temaId, $tipo, $referencia, $campoDe){
        
        $con = \Yii::$app->db;
        $query = "insert into pep_unidad_detalle (pep_planificacion_unidad_id, tipo, referencia, campo_de, contenido_texto, contenido_opcion) 
                    select $temaId, '$tipo', '$referencia', '$campoDe' ,op.categoria_principal_es,false 
                    from pep_opciones op 
                    where op.tipo = '$tipo'
                    and op.categoria_principal_es  not in (select   contenido_texto 
                                                            from    pep_unidad_detalle 
                                                            where   pep_planificacion_unidad_id = $temaId 
                                                                    and contenido_texto = op.categoria_principal_es
                                                                    and tipo = '$tipo') group by op.categoria_principal_es; ";       
        
        $con->createCommand($query)->execute();
        
    }
    
    private function ingresa_opciones_conceptos_relacionados($temaId, $tipo, $referencia, $campoDe){
        
        $con = \Yii::$app->db;
        $query = "insert into pep_unidad_detalle (pep_planificacion_unidad_id, tipo, referencia, campo_de, contenido_texto, contenido_opcion) 
                    select $temaId, '$tipo', '$referencia', '$campoDe' ,op.contenido_es, false 
                    from    pep_opciones op 
                            inner join pep_opciones opr on opr.contenido_es = op.contenido_es
                    where op.tipo = '$tipo'
                    and op.contenido_es  not in (select   contenido_texto 
                                                            from    pep_unidad_detalle 
                                                            where   pep_planificacion_unidad_id = $temaId 
                                                                    and contenido_texto = op.contenido_es
                                                                    and tipo = '$tipo') group by op.contenido_es, opr.categoria_principal_es "
                . "order by opr.categoria_principal_es; ";  
        
               
        $con->createCommand($query)->execute();
        
    }
    
    
    
    
    private function ingresa_opciones_generico_texto($temaId, $modelRegistros, $tipo, $referencia, $campoDe){        
                      
        $palabra = array_search($tipo, array_column($modelRegistros, 'tipo')); //busca si esta configurado

        if($palabra > 0 || $palabra === 0){
            //no se hace nada
        }else{
            $model = new \backend\models\PepUnidadDetalle();
            $model->pep_planificacion_unidad_id = $temaId;
            $model->tipo = $tipo;
            $model->referencia = $referencia;
            $model->campo_de = $campoDe;
            $model->contenido_texto = 'No conf';
            $model->save();
        }        
        
    }
    
    
    /**
     * ACTUALIZA EL REGISTRO DEL DETALLE DE LA PLANIFICACION PEP
     * @return type
     */
    public function actionUpdate(){
        $id = $_POST['registro_id'];
        $contenidoTexto = $_POST['contenido_texto'];

        $model = \backend\models\PepUnidadDetalle::findOne($id);
        $model->contenido_texto = $contenidoTexto;
        if($model->save()){
            $response = array(
                'status' => 'ok'
            );
        }else{
            $response = array(
                'status' => 'error'
            );
        }
        
        return json_encode($response);
    }
    
    
    public function actionUpdateSelection(){
        $id = $_POST['id'];
        
        $model = \backend\models\PepUnidadDetalle::findOne($id);
        $model->contenido_opcion == true ? $model->contenido_opcion = false : $model->contenido_opcion = true;
        
        if($model->save()){
            $response = array(
                'status' => 'ok'
            );
        }else{
            $response = array(
                'status' => 'error',
            );            
        }        
        return json_encode($response);        
    }

    public function actionCreateConceptoRelacionado()
    {
        
        $resp = false;
        $conceptoClase = $_GET['conceptoClave'];
        $conceptoRelacionado = $_GET['conceptoRelacionado']; 
        $temaId = $_GET['temaId'];        
        
        if(!($this->buscar_concepto('concepto_relacionado',$conceptoRelacionado)))
        {     
            //CREA EL ITEM EN LAS OPCIONES   
            $model = new PepOpciones();
            $model->tipo = 'concepto_relacionado';
            $model->categoria_principal_es = $conceptoClase;
            $model->categoria_secundaria_es = '¿Cómo se está transformando?';
            $model->contenido_es = $conceptoRelacionado;
            $model->campo_de = 'texto';
            $model->es_activo = true;

            $model->save();

            //AGREGA EL ITEM EN EL LISTADO DE LA PLANIFICACION
            $model = new PepUnidadDetalle();
            $model->pep_planificacion_unidad_id = $temaId;
            $model->tipo = 'concepto_relacionado';
            $model->referencia='conceptos_atributos';
            $model->campo_de='seleccion';
            $model->contenido_texto = $conceptoRelacionado;
            $model->contenido_opcion = true;

            $model->save();
            $resp = true;
        }
        return $resp;
              
    }
    private function buscar_concepto($tipoConcepto,$nombreConcepto)
    {
        //busca si ya existe el concepto relacionado
        $resp=false;
        $model= PepOpciones::find()
        ->where(['tipo'=>$tipoConcepto])
        ->andWhere(['contenido_es'=>$nombreConcepto])
        ->one();

        if($model)
        {
            $resp = true;
        }
        return $resp;
    }
    

/**
*ACCIÓN PARA PRESENTAR LOS DESCRIPTORES DEL MEC
* Realizado por Arturo Sarango 2022-12-30
* Ultima actaulización: 2023-03-10
*/

    public function actionDesagregacion(){        
               
        $temaId = $_GET['tema_id'];
        $tema = \backend\models\PepPlanificacionXUnidad::findOne($temaId);       

        echo $tema->op_course_template_id;
        
        $searchModel = new \backend\models\ViewDestrezaMecBiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $tema->op_course_template_id);
        $destrezasSeleccionadas = $this->destrezasSeleccionadas($temaId);
        
        return $this->render('desagregacion',[
            'tema' => $tema,
            'temaId' => $temaId,
            'destrezasSeleccionadas' => $destrezasSeleccionadas,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionMostrarDestrezas()
    {
        $temaId = $_GET['tema_id'];
        $tema = \backend\models\PepPlanificacionXUnidad::findOne($temaId);  
       
        $destrezasSeleccionadas = $this->destrezasSeleccionadas($temaId);
        
        return $this->render('destrezas',[
            'tema' => $tema,
            'temaId' => $temaId,
            'destrezasSeleccionadas' => $destrezasSeleccionadas,           
        ]);
    }

    public function destrezasSeleccionadas($idUnidad)
    {
        // $con = Yii::$app->db;
        
        // $query = "select id, pep_planificacion_unidad_id, tipo, referencia, campo_de, contenido_texto, contenido_opcion 
        //           from pep_unidad_detalle pud where pep_planificacion_unidad_id  = '$idUnidad' and tipo ='destreza';";  
        
        // $respuesta = $con->createCommand($query)->queryAll();

        $respuesta = $this->get_destrezas($idUnidad);
        return $respuesta;
    }
    private function get_destrezas($unidadId)
    {
        $con = Yii::$app->db;       
        $query = "select 	ce.code as criterio_evaluacion_code
                                ,ce.description as criterio_evaluacion
                                ,cm.code as destreza_code
                                ,cm.description as destreza
                                ,asi.name as asignatura
                from 	pep_unidad_detalle pu
                                inner join curriculo_mec cm on cm.id = cast(pu.contenido_texto as integer)
                                inner join curriculo_mec ce on ce.code = cm.belongs_to 
                                inner join curriculo_mec_asignatutas asi on asi.id = cm.asignatura_id 
                where 	pu.pep_planificacion_unidad_id = $unidadId
                                and tipo = 'destreza';";
                             
        $res = $con->createCommand($query)->queryAll();
        return $res;        
    }
    
    
    public function actionAgregar(){
        $destrezaId = $_GET['destreza_id'];
        $temaId = $_GET['tema_id'];

        $model = new \backend\models\PepUnidadDetalle();
        $model->pep_planificacion_unidad_id = $temaId;
        $model->tipo = 'destreza';
        $model->referencia = 'mec';
        $model->campo_de = 'seleccion';
        $model->contenido_texto = $destrezaId;
        $model->contenido_opcion = true;
        $model->save();
        
        return $this->redirect(['desagregacion', 'tema_id' => $temaId]);
    }
    
    public function actionQuitar(){
        $id = $_GET['id'];
        $model = \backend\models\PepUnidadDetalle::findOne($id);
        $temaId = $model->pep_planificacion_unidad_id;
        $model->delete();
        
        return $this->redirect(['desagregacion', 'tema_id' => $temaId]);
    }
    
    public function actionPdf()
    {
        $planUnidadId = $_GET['planificacion_id'];        
        $pdf = new \backend\models\pudpep\PdfPlanT($planUnidadId);        
        
    }
}