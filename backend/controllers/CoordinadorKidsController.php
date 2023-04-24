<?php

namespace backend\controllers;

use backend\models\coordinador\plansemanalaprobacion\HorarioSemanal;
use backend\models\OpInstituteAuthorities;
use backend\models\PlanSemanalBitacora;
use backend\models\KidsPlanSemanal;
use backend\models\ScholarisBloqueSemanas;
use backend\models\KidsDestrezaTarea;
use backend\models\KidsPlanSemanalHoraClase;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisHorariov2CabeceraController implements the CRUD actions for ScholarisHorariov2Cabecera model.
 */
class CoordinadorKidsController extends Controller
{

    /**
     * {@inheritdoc}
     */
    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::className(),
    //             'rules' => [
    //                 [
    //                     'allow' => true,
    //                     'roles' => ['@'],
    //                 ]
    //             ],
    //         ],
    //         'verbs' => [
    //             'class' => VerbFilter::className(),
    //             'actions' => [
    //                 'delete' => ['POST'],
    //             ],
    //         ],
    //     ];
    // }

    // public function beforeAction($action)
    // {
    //     if (!parent::beforeAction($action)) {
    //         return false;
    //     }

    //     if (Yii::$app->user->identity) {

    //         //OBTENGO LA OPERACION ACTUAL
    //         list($controlador, $action) = explode("/", Yii::$app->controller->route);
    //         $operacion_actual = $controlador . "-" . $action;
    //         //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
    //         if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
    //             echo $this->render('/site/error', [
    //                 'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
    //                 'name' => 'Acceso denegado!!',
    //             ]);
    //         }
    //     } else {
    //         header("Location:" . \yii\helpers\Url::to(['site/login']));
    //         exit();
    //     }
    //     return true;
    // }



    public function actionIndex()
    {
        $user = Yii::$app->user->identity->usuario;
        $periodId = Yii::$app->user->identity->periodo_id;

        $sectionCoordinador = OpInstituteAuthorities::find()->where(['usuario' => $user])->one();
        $authSection = $sectionCoordinador->seccion;

        if ($authSection == 'PEP') {
            $section = 'BAS';
        } else {
            $section = $authSection;
        }

        $uso = $this->get_use($periodId, $section);
        $weeks = $this->get_weeks($uso, $periodId);

        return $this->render('index', [
            'weeks' => $weeks
        ]);
    }

    public function get_use($periodId, $section)
    {
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


    private function get_weeks($uso, $periodId)
    {
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


    public function actionAcciones()
    {
        $action = $_GET['action'];
        $weekId = $_GET['week_id'];
        $user = Yii::$app->user->identity->usuario;
        $periodId = Yii::$app->user->identity->periodo_id;

        if ($action == 'detail') {
            $week = ScholarisBloqueSemanas::findOne($weekId);
            $details = $this->get_detail($periodId, $user, $weekId);

            // print_r($details);
            // die();

            return $this->renderPartial('detail', [
                'week' => $week,
                'details' => $details
            ]);
        }
    }

    private function get_detail($periodId, $user, $weekId)
    {
        $con = Yii::$app->db;
        $query = "select
                        concat(of2.last_name,' ',of2.x_first_name) as docente,
                        oc.name as nivel ,
                        oc.id as curso_id,
                        ru.login,
                        (
                            select
                                kps.estado
                            from
                                kids_plan_semanal kps
                            inner join kids_unidad_micro kum on
                                kum.id = kps.kids_unidad_micro_id
                            inner join kids_pca kp on
                                kp.id = kum.pca_id
                            where
                                kps.semana_id = $weekId
                                and kp.op_course_id = oc.id 
                                and kps.created = ru.login
                        )
                    from
                        scholaris_clase sc 
                        inner join op_institute_authorities oia on oia.id = sc.coordinador_academico_id 
                        inner join op_faculty of2 on of2.id = sc.idprofesor 
                        inner join op_course_paralelo ocp on ocp.id = sc.paralelo_id 
                        inner join op_course oc on oc.id = ocp.course_id 
                        inner join op_section os on os.id = oc.section 
                        inner join scholaris_op_period_periodo_scholaris sopps on sopps.op_id = os.period_id 
                        inner join res_users ru on ru.partner_id = of2.partner_id 
                    where 
                    oia.usuario  = '$user' and sopps.scholaris_id = $periodId
                    group by docente,nivel,curso_id,ru.login 
                    order by oc.id 
                    ;";

        // echo $query;
        // die();

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    public function actionView()
    {
        $weekId = $_GET['week_id'];
        $userTeacher = $_GET['user_teacher'];
        $cursoId = $_GET['curso_id'];

        $week = ScholarisBloqueSemanas::findOne($weekId);
        $plans = KidsPlanSemanal::find()->where([
            'semana_id' => $weekId,
            'created' => $userTeacher
        ])
            ->orderBy('id ASC')
            ->limit(1)
            ->one();
        $planSemanalId = $plans->id;
        // echo "<pre>";
        // print_r($plans);
        // die();


        // llamar a model/HorarioSemanal
        $schedule = new HorarioSemanal($weekId, $userTeacher);
        $dates = $schedule->dates;
        $hours = $schedule->hours;

        $planificaciones = $this->getClass($userTeacher, $cursoId);

        //    echo "<pre>";
        //    print_r($planificaciones);
        //    die();

        //Creo array para pintar en la vista con los dias y horas
        $aDiasPlans = array();

        foreach ($dates as $keyDate => $date) {

            $aDiasPlans[$date['nombre']]['nombre'] = $date['nombre'];
            $aDiasPlans[$date['nombre']]['fecha'] = $date['fecha'];
            $aDiasPlans[$date['nombre']]['curso'] = $planificaciones[0]["curso"];

        }

        foreach ($planificaciones as $keyPlan => $plan) {
            $aDiasPlans[$plan["dia"]]["planificaciones"][$keyPlan]["hora"] = $plan["nombre"];
            $aDiasPlans[$plan["dia"]]["planificaciones"][$keyPlan]["curso"] = $plan["curso"];
            $aDiasPlans[$plan["dia"]]["planificaciones"][$keyPlan]["paralelo"] = $plan["paralelo"];
            $aDiasPlans[$plan["dia"]]["planificaciones"][$keyPlan]["materia"] = $plan["materia"];
            $aDiasPlans[$plan["dia"]]["planificaciones"][$keyPlan]["detalle_id"] = $plan["detalle_id"];
            $aDiasPlans[$plan["dia"]]["planificaciones"][$keyPlan]["clase_id"] = $plan["id"];
        }
        // echo "<pre>";
        // print_r($aDiasPlans);
        // die();



        return $this->render('view', [
            'aDiasPlans' => $aDiasPlans,
            'plans' => $plans,
            'cursoId' => $cursoId,
            'week' => $week,
            'planSemanalId' => $planSemanalId,
            // 'dates' => $dates,
            // 'hours' => $hours,
            'user' => $userTeacher
        ]);
    }


    /**
     * Created by Isaac Sarango
     * Método que trae las horas de la semana del profesor y del nivel
     */

    private function getClass($teacher, $cursoId)
    {
        $con = Yii::$app->db;
        $query = "select  dia.nombre as dia
            ,hor.nombre 
            ,cla.id 
            ,hho.detalle_id 
            ,cur.name as curso
            ,par.name as paralelo
            ,mat.nombre as materia
        from 	scholaris_clase cla
            inner join op_faculty fac on fac.id = cla.idprofesor
            inner join res_users rus on rus.partner_id = fac.partner_id
            inner join scholaris_horariov2_horario hho on hho.clase_id = cla.id 
            inner join scholaris_horariov2_detalle det on det.id = hho.detalle_id 
            inner join scholaris_horariov2_dia dia on dia.id = det.dia_id 
            inner join scholaris_horariov2_hora hor on hor.id = det.hora_id 
            inner join op_course_paralelo par on par.id = cla.paralelo_id 
            inner join op_course cur on cur.id = par.course_id 
            inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
            inner join ism_materia mat on mat.id = iam.materia_id 
        where 	rus.login = '$teacher' 
        and cur.id = $cursoId
        order by dia.numero, hor.numero ;";

        $res = $con->createCommand($query)->queryAll();
        return $res;

    }

    /*
    Created by Isaac Sarango
    Metodo que trae info de actividades de la planificacion semanal / hora
    */
    public function actionInfoPlanificacion()
    {

        $claseId = $_POST['curso_id'];
        $detalleId = $_POST['detalle_id'];
        $con = Yii::$app->db;

        // print_r($_POST);
        // die();


        $query = "select
                act.actividades ,
                des.micro_destreza_id,
                des.id as destreza_id,
                cca.codigo as ambito_codigo,
                cca.nombre as ambito_nombre,
                ccd.codigo as destreza_codigo,
                ccd.nombre as destreza_nombre
            from
                kids_plan_semanal_hora_clase act 
                inner join kids_plan_semanal_hora_destreza des on des.hora_clase_id = act.id
                inner join kids_micro_destreza kmd on kmd.id = des.micro_destreza_id 
                inner join cur_curriculo_destreza ccd on ccd.id = kmd.destreza_id 
                inner join cur_curriculo_ambito cca on cca.id = ccd.ambito_id 
            where 
            act.clase_id = $claseId
            and act.detalle_id = $detalleId;";

        $data = $con->createCommand($query)->queryAll();

        return $this->renderPartial('_ajax-actividad', [
            'data' => $data
        ]);

    }

    /*
    *Created by Isaac Sarango
    Metodo que muestra todas las tareas de la destreza seleccionada
    */

    public function actionTareas()
    {
        $destrezaId = $_POST['destreza_id'];

        $tareas = KidsDestrezaTarea::find()->where(["plan_destreza_id" => $destrezaId])->all();


        return $this->renderPartial('_ajax-tareas', [
            'tareas' => $tareas
        ]);


    }


    public function actionChangeState()
    {
        $planId = $_REQUEST['plan_id'];
        $weekId = $_REQUEST['week_id'];
        $cursoId = $_REQUEST['cursoId'];
        $observ = $_REQUEST['observaciones'];
        $state = $_REQUEST['estado'];
        $userTeacher = $_REQUEST['user_teacher'];
        $user = Yii::$app->user->identity->usuario;
        $hoy = date("Y-m-d H:i:s");
        // print_r($_POST);
        // die();

        $model = KidsPlanSemanal::findOne($planId);
        $model->estado = $state;
        $model->sent_by = $user;
        $model->sent_at = $hoy;
        $model->save();

        return $this->redirect([
            'view',
            'week_id' => $weekId,
            'curso_id' => $cursoId,
            'user_teacher' => $userTeacher
        ]);

    }
}