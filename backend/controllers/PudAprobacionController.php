<?php

namespace backend\controllers;

use backend\models\PlanificacionDesagregacionCabecera;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * PlanificacionDesagregacionCabeceraController implements the CRUD actions for PlanificacionDesagregacionCabecera model.
 */
class PudAprobacionController extends Controller{
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
    
    public function actionIndex1(){
        $opCourseTemplateId = $_GET['template_id'];
        $detalle = array();
        
        $curso = \backend\models\OpCourseTemplate::findOne($opCourseTemplateId);
        
        $materias = $this->get_asignaturas($opCourseTemplateId);
        
        foreach ($materias as $mat){
            $mat['porcentajes'] = $this->get_porcentajes($mat['id']);
            array_push($detalle, $mat);
        }    
        
        $bloques = \backend\models\CurriculoMecBloque::find()
                ->where(['is_active' => true])
                ->orderBy('code')
                ->all();
        
        return $this->render('index', [
            'detalle' => $detalle,
            'bloques' => $bloques,
            'curso' => $curso
        ]);
    }
    
    private function get_asignaturas($opCourseTemplateId){
        $con = \Yii::$app->db;
        $query = "select 	am.id 
                                    ,m.nombre 
                    from	ism_area_materia am
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                    inner join ism_malla mal on mal.id = pm.malla_id
                                    inner join ism_materia m on m.id = am.materia_id 
                    where 	mal.op_course_template_id = $opCourseTemplateId
                    order by m.nombre asc;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function get_porcentajes($ismAreaMateriaId){
        $con = \Yii::$app->db;
        $query = "select 	b.id 
                                    ,u.avance_porcentaje 
                    from 	planificacion_bloques_unidad u
                                    inner join planificacion_desagregacion_cabecera c on c.id = u.plan_cabecera_id 
                                    inner join curriculo_mec_bloque b on b.id = u.curriculo_bloque_id 
                    where 	c.ism_area_materia_id = $ismAreaMateriaId
                                    and b.is_active = true
                    order by b.code;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function actionDetalle(){
        $ismAreaMateriaId   = $_GET['ism_area_materia_id'];
        $curriculoBloqueId  = $_GET['bloque_id'];
        
        $dataMateria = $this->get_materia($ismAreaMateriaId, $curriculoBloqueId);
        
        return $this->render('detalle',[
            'dataMateria' => $dataMateria
        ]);
        
        
    }
    
    private function get_materia($ismAreaMateriaId, $curriculoBloqueId){
        $con = Yii::$app->db;
        $query = "select 	u.id 
                                    ,b.last_name 
                                    ,m.nombre as materia
                                    ,t.name as curso
                                    ,u.avance_porcentaje 
                                    ,u.pud_status 
                                    ,u.is_open 
                    from	planificacion_desagregacion_cabecera cab
                                    inner join planificacion_bloques_unidad u on u.plan_cabecera_id = cab.id 
                                    inner join curriculo_mec_bloque b on b.id = u.curriculo_bloque_id 
                                    inner join ism_area_materia am on am.id = cab.ism_area_materia_id 
                                    inner join ism_materia m on m.id = am.materia_id 
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                    inner join ism_malla mal on mal.id = pm.malla_id 
                                    inner join op_course_template t on t.id = mal.op_course_template_id 
                    where 	cab.ism_area_materia_id  = $ismAreaMateriaId
                                    and b.id = $curriculoBloqueId;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
}