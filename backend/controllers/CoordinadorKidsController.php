<?php

namespace backend\controllers;

use backend\models\coordinador\plansemanalaprobacion\HorarioSemanal;
use backend\models\OpInstituteAuthorities;
use backend\models\PlanSemanalBitacora;
use backend\models\KidsPlanSemanal;
use backend\models\ScholarisBloqueSemanas;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisHorariov2CabeceraController implements the CRUD actions for ScholarisHorariov2Cabecera model.
 */
class CoordinadorKidsController extends Controller{
    
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
    
    
    
    public function actionIndex(){
        $user       = Yii::$app->user->identity->usuario;
        $periodId   = Yii::$app->user->identity->periodo_id;

        $sectionCoordinador = OpInstituteAuthorities::find()->where(['usuario' => $user])->one();
        $authSection = $sectionCoordinador->seccion;

        if($authSection == 'PEP'){
            $section = 'BAS';
        }else{
            $section = $authSection;
        }

        $uso    = $this->get_use($periodId, $section);
        $weeks  = $this->get_weeks($uso, $periodId);

        return $this->render('index',[
            'weeks' => $weeks
        ]);
    }

    public function get_use($periodId, $section){
        $con = Yii::$app->db;
        $query = "select 	cla.tipo_usu_bloque 
        from 	op_course cur 
                inner join op_section sec on sec.id = cur.section
                inner join op_course_paralelo par on par.course_id = cur.id 
                inner join scholaris_clase cla on cla.paralelo_id = par.id 
                inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
        where 	sec.code = '$section'
                and ipm.scholaris_periodo_id = $periodId
        group by cla.tipo_usu_bloque;";

        $res = $con->createCommand($query)->queryOne();
        return $res['tipo_usu_bloque'];
    }


    private function get_weeks($uso, $periodId){
        $con = Yii::$app->db;
        $query = "select 	sem.id, sem.nombre_semana, sem.semana_numero 
                    from	scholaris_bloque_actividad blo
                            inner join scholaris_periodo per on per.codigo = blo.scholaris_periodo_codigo 
                            inner join scholaris_bloque_semanas sem on sem.bloque_id = blo.id 
                    where 	blo.tipo_uso = '$uso'
                            and blo.tipo_bloque in ('PARCIAL', 'EXAMEN')
                            and per.id = $periodId
                    order by blo.orden, sem.semana_numero;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    

    public function actionAcciones(){
        $action = $_GET['action'];
        $weekId = $_GET['week_id'];
        $user = Yii::$app->user->identity->usuario;

        if($action == 'detail' ){
            $week = ScholarisBloqueSemanas::findOne($weekId);
            $details = $this->get_detail($weekId, $user);

            return $this->renderPartial('detail',[
                'week' => $week,
                'details' => $details
            ]);
        }           
    }

    private function get_detail($weekId, $user){
        $con = Yii::$app->db;
        $query = "select 	fac.id 
                            ,rus.login 
                            ,concat(fac.last_name, ' ', fac.x_first_name, ' ', fac.middle_name) as docente 
                            ,(select 	estado 
                                from 	kids_plan_semanal
                                where semana_id = $weekId
                                order by id desc
                                limit 1)
                    from 	scholaris_clase cla
                            inner join op_institute_authorities aut on aut.id = cla.coordinador_academico_id
                            inner join op_faculty fac on fac.id = cla.idprofesor 
                            inner join res_users rus on rus.partner_id = fac.partner_id 		 
                    where 	aut.usuario = '$user'
                    group by fac.id, rus.login, docente
                    order by 3;";

// echo $query;
// die();

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    

    public function actionView(){
        $weekId = $_GET['week_id'];
        $userTeacher = $_GET['user_teacher'];

        $week = ScholarisBloqueSemanas::findOne($weekId);
        $plans = KidsPlanSemanal::find()->where([
            'semana_id'         => $weekId,
            'created'   => $userTeacher
        ])
        ->orderBy('id DESC')
        ->limit(1)
        ->one();

        // llamar a model/HorarioSemanal
        $schedule = new HorarioSemanal($weekId, $userTeacher);
        $dates = $schedule->dates;
        $hours = $schedule->hours;

        return $this->render('view',[
            'plans' => $plans,
            'week'  => $week,
            'dates' => $dates,
            'hours' => $hours,
            'user'  => $userTeacher
        ]);
    }


    public function actionChangeState(){
        $planId = $_REQUEST['plan_id'];
        $weekId = $_REQUEST['week_id'];
        $observ = $_REQUEST['observaciones'];
        $state  = $_REQUEST['estado'];
        $user  = $_REQUEST['user_teacher'];

        $model = PlanSemanalBitacora::findOne($planId);
        $model->estado = $state;
        $model->obervaciones = $observ;
        $model->fecha_recibe = date('Y-m-d H:i:s');
        $model->save();

        return $this->redirect(['view',
            'week_id' => $weekId,
            'user_teacher' => $user
        ]);

    }        
}