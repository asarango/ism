<?php

namespace backend\controllers;

use backend\models\ContenidoPaiHabilidades;
use backend\models\mapaenfoques\AjaxMapaEnfoque;
use backend\models\MapaEnfoquesPai;
use backend\models\MapaEnfoquesPaiAprobacion;
use backend\models\OpInstituteAuthorities;
use backend\models\ScholarisMateria;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class MateriasPaiController extends Controller{

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

    public function actionIndex()
    {
        $materias = $this->consulta_materias_pai();
        return $this->render('index', [
            'materias' => $materias
        ]);
    }


    private function consulta_materias_pai(){
        $periodoId      = Yii::$app->user->identity->periodo_id;
        $institutoId    = Yii::$app->user->identity->instituto_defecto;
        $user           = Yii::$app->user->identity->usuario;
        $con            = Yii::$app->db;

        $query = "select 	mat.id ,mat.nombre as materia  
                    from	ism_area_materia am
                                    inner join ism_materia mat on mat.id = am.materia_id
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                    inner join ism_malla m on m.id = pm.malla_id
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.scholaris_id = pm.scholaris_periodo_id 
                                    inner join op_course_template t on t.id = m.op_course_template_id 
                                    inner join op_course cur on cur.x_template_id = t.id 
                                    inner join op_section s on s.id = cur.section
                                                                    and s.period_id = sop.op_id 
                    where 	sop.scholaris_id = $periodoId
                            and s.code = 'PAI' 
                            and am.jefe_area = '$user'
                    group by mat.id ,mat.nombre;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    } 


    /**
    *   ACCIÓN QUE MUESTRA LAS OPCIONES PARA EL MAPA DE ENFOQUES
    *   Creado por: ARTURO SARANGO - 2022-12-01
    *   Actualizado:   ARTURO SARANGO - 2023-03-15
    */

    public function actionMapaEnfoques(){ //Accion para tomar las habilidades
        $materiaId = $_GET['materia_id'];
        $periodId = Yii::$app->user->identity->periodo_id;

        $aprobacion = MapaEnfoquesPaiAprobacion::find()->where([
            'materia_id' => $materiaId,
            'periodo_id' => $periodId
        ])->one();

        $materia = \backend\models\IsmMateria::findOne($materiaId);    
        $habilidades = $this->consulta_habilidades();
        $this->procesa_hablidades_pai_a_cursos($materiaId);

        return $this->render('mapa-enfoques',[
            'aprobacion'    => $aprobacion,
            'habilidades'   => $habilidades,
            'materia'       => $materia,
            'periodoId'     => $periodId
        ]);
    }


    public function actionState(){
        
        $periodoId = $_POST['period_id'];
        $materiaId = $_POST['materia_id'];

        $user = Yii::$app->user->identity->usuario;        

        $coordinator = OpInstituteAuthorities::find()->where([
            'cargo_codigo' => 'coordinacionpai'
        ])->one();

        $model = new MapaEnfoquesPaiAprobacion();
        $model->materia_id  = $materiaId;
        $model->periodo_id  = $periodoId;
        $model->coordinador_usuario = $coordinator->usuario;
        $model->jefe_area_usuario   = $user;
        $model->fecha_envio_a_coordinado = date('Y-m-d H:i:s');
        $model->estado      = 'COORDINADOR';
        $model->save();

        return $this->redirect(['mapa-enfoques', 'materia_id' => $materiaId]);
    }


    private function consulta_habilidades(){
        $con = Yii::$app->db;
        $query = "select 	orden_titulo2, es_titulo2 
                    from 	contenido_pai_habilidades 
                    group by orden_titulo2, es_titulo2 order by orden_titulo2;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    private function procesa_hablidades_pai_a_cursos($materiaId){
        $periodoId      = Yii::$app->user->identity->periodo_id;
        $institutoId    = Yii::$app->user->identity->instituto_defecto;

        $habilidades    = ContenidoPaiHabilidades::find()->all();
        $cursosPai      = $this->consulta_cursos_pai($periodoId, $institutoId);
        
        foreach($habilidades as $habilidad){
            foreach($cursosPai as $curso){
                $model = MapaEnfoquesPai::find()->where([
                    'periodo_id'         => $periodoId,
                    'course_template_id' => $curso['id'],
                    'pai_habilidad_id'   => $habilidad->id,
                    'materia_id'         => $materiaId
                ])->one();
                if(!isset($model)){                    
                    $mapaEnfoque = new MapaEnfoquesPai();
                    $mapaEnfoque->periodo_id            = $periodoId;
                    $mapaEnfoque->course_template_id    = $curso['id'];
                    $mapaEnfoque->pai_habilidad_id      = $habilidad->id;
                    $mapaEnfoque->materia_id            = $materiaId;
                    $mapaEnfoque->save();
                }
            }
        }        
    }

    private function consulta_cursos_pai($periodoId, $institutoId){
        
        $con            = Yii::$app->db;
        $query = "select 	t.id, t.name 		
        from 	op_course c
                inner join op_section s on s.id = c.section
                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                inner join op_course_template t on t.id = c.x_template_id 
        where 	sop.scholaris_id = $periodoId
                and s.code = 'PAI'
                and c.x_institute = $institutoId
        order by t.next_course_id desc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionAjaxMapaEnfoque(){
        $materiaId      = $_GET['materia_id'];       
        $habilidadOrden = $_GET['habilidad_orden'];   
        $periodoId      = Yii::$app->user->identity->periodo_id;
        $institutoId    = Yii::$app->user->identity->instituto_defecto;

        $cursosPai      = $this->consulta_cursos_pai($periodoId, $institutoId);    
        $html = new AjaxMapaEnfoque($materiaId, $habilidadOrden, $cursosPai, $periodoId);
        return $html->html;
    }    

    public function actionActivaInactiva(){
        $id = $_GET['id'];
        $model = MapaEnfoquesPai::findOne($id);

        if($model->estado == true){
            $model->estado = false;
        }else{
            $model->estado = true;
        }

        $model->save();
    }




    /**
     * ACCION PARA MOSTRAR LA APROBACIÓN DE COORDINACION DE MAPA DE ENFOQUES
     * CREADO POR:  Arturo Sarango - 2023-03-16
     * MODIFICADOS: Arturo Sarango - 2023-03-16
     */
    public function actionListaAsignaturas(){
        $user = Yii::$app->user->identity->usuario;
        $periodId = Yii::$app->user->identity->periodo_id;

        $details = $this->get_asignaturas_detail($periodId);

        return $this->render('lista-asignaturas',[
            'details' => $details
        ]);
    }


    /**
     * MÉTODO QUE ENTREGA LAS ASIGNATURAS DEL PAI
     * CREADO POR:  Arturo Sarango - 2023-03-16
     * MODIFICADOS: Arturo Sarango - 2023-03-16
    */
    private function get_asignaturas_detail($periodoId){
        $con = Yii::$app->db;
        $query = "select    mat.id 
                            ,mat.nombre as materia
                            ,iam.jefe_area 
                            ,men.estado 
                            ,men.id as aprobacion_id 
                    from    ism_malla mal
                            inner join op_course cur on cur.x_template_id = mal.op_course_template_id
                            inner join op_section sec on sec.id = cur.section
                            inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id
                            inner join ism_periodo_malla ipm on ipm.malla_id = mal.id 
                                and ipm.scholaris_periodo_id = sop.scholaris_id 
                            inner join ism_malla_area ima on ima.periodo_malla_id = ipm.id 
                            inner join ism_area_materia iam on iam.malla_area_id = ima.id 
                            inner join ism_materia mat on mat.id = iam.materia_id
                            left join mapa_enfoques_pai_aprobacion men on men.jefe_area_usuario = iam.jefe_area 
                                    and men.materia_id = mat.id 
                    where   sop.scholaris_id = $periodoId
                            and sec.code = 'PAI'
                    group by mat.id ,mat.nombre, iam.jefe_area, men.estado, men.id
                    order by mat.nombre;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


     /**
     * ACCION PARA APROBAR EL MAPA DE ENFOQUES DE LA MATARIA SELECCIONADA
     * CREADO POR:  Arturo Sarango - 2023-03-16
     * MODIFICADOS: Arturo Sarango - 2023-03-16
     */
     public function actionAprobar(){
        $aprobacionId = $_GET['id'];
        $today = date('Y-m-d H:i:s');

        $model = MapaEnfoquesPaiAprobacion::findOne($aprobacionId);
        $model->fecha_aprobacion = $today;
        $model->estado = 'APROBADO';
        $model->save();

        return $this->redirect(['lista-asignaturas']);
     }

}