<?php

namespace backend\controllers;

use backend\models\ScholarisActividadDeber;
use backend\models\ScholarisGrupoAlumnoClase;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ScholarisActividadController implements the CRUD actions for ScholarisActividad model.
 */
class CalificacionController extends Controller {
    
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
                    ],
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
    
    public function actionIndex1(){
        $periodoId      = \Yii::$app->user->identity->periodo_id;
        $actividadId    = $_GET['actividad_id'];
        $grupoId       = $_GET['grupo_id'];
        $modelActividad = \backend\models\ScholarisActividad::findOne($actividadId);
        $claseId = $modelActividad->paralelo_id;

        $group = ScholarisGrupoAlumnoClase::findOne($grupoId);
        
        $modelMinimo = \backend\models\ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califminima'])
            ->one();

        $modelMaximo = \backend\models\ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califmmaxima'])
            ->one();

        /*** toma el deber */
        $deber = ScholarisActividadDeber::find()->where(['alumno_id' => $grupoId, 'actividad_id' => $actividadId])->one();
        /*** fin de toma el deber */
        // echo $group->estudiante_id;
        
        return $this->render('index',[
            'modelActividad'    => $modelActividad,
            'modelMinimo'       => $modelMinimo,
            'modelMaximo'       => $modelMaximo,
            'group'             => $group,
            'deber'             => $deber
        ]);
        
    }
    
    
    private function get_actual_primero($claseId, $periodoId){
        $con = \Yii::$app->db;
        $query = "select 	s.id as student_id
		,concat(s.last_name, ' ', s.first_name, ' ', s.middle_name) as student 
                ,g.id as grupo_id
                from 	scholaris_grupo_alumno_clase g
                                inner join op_student s on s.id = g.estudiante_id 
                                inner join op_student_inscription i on i.student_id = s.id 
                                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
                where	g.clase_id  = $claseId
                                and sop.scholaris_id = $periodoId
                order by s.last_name, s.first_name, s.middle_name limit 1;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    private function get_actual_x_nombre($claseId, $periodoId, $nombre){
        $con = \Yii::$app->db;
        $query = "select 	s.id as student_id
		,concat(s.last_name, ' ', s.first_name, ' ', s.middle_name) as student 
                ,g.id as grupo_id
                from 	scholaris_grupo_alumno_clase g
                                inner join op_student s on s.id = g.estudiante_id 
                                inner join op_student_inscription i on i.student_id = s.id 
                                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
                where	g.clase_id  = $claseId
                                and sop.scholaris_id = $periodoId 
                                and concat(s.last_name, ' ', s.first_name, ' ', s.middle_name) = '$nombre'
                order by s.last_name, s.first_name, s.middle_name limit 1;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    private function get_siguiente($claseId, $periodoId, $nombre){
        $con = \Yii::$app->db;
        $query = "select 	s.id as student_id
                                ,concat(s.last_name, ' ', s.first_name, ' ', s.middle_name) as student 
                                ,g.id as grupo_id
                from 	scholaris_grupo_alumno_clase g
                                inner join op_student s on s.id = g.estudiante_id 
                                inner join op_student_inscription i on i.student_id = s.id 
                                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
                where	g.clase_id  = $claseId
                                and sop.scholaris_id = $periodoId
                                and concat(s.last_name, ' ', s.first_name, ' ', s.middle_name) > '$nombre'
                limit 1;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    private function get_anterior($claseId, $periodoId, $nombre){
        $con = \Yii::$app->db;
        $query = "select s.id as student_id ,concat(s.last_name, ' ', s.first_name, ' ', s.middle_name) as student ,g.id as grupo_id 
                    from scholaris_grupo_alumno_clase g 
                    inner join op_student s on s.id = g.estudiante_id inner join op_student_inscription i on i.student_id = s.id 
                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
                    where g.clase_id = $claseId 
                    and sop.scholaris_id = $periodoId
                    and concat(s.last_name, ' ', s.first_name, ' ', s.middle_name) < '$nombre' 
                    order by 2 desc  
                    limit 1;";
        //echo $query;
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
}

?>