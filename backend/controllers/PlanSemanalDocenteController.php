<?php

namespace backend\controllers;

use backend\models\helpers\CalendarioSemanal;
use backend\models\ScholarisBloqueSemanas;
use backend\models\ScholarisHorariov2Dia;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Mpdf\Mpdf;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class PlanSemanalDocenteController extends Controller {
    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ]
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//
//    public function beforeAction($action) {
//        if (!parent::beforeAction($action)) {
//            return false;
//        }
//
//        if (Yii::$app->user->identity) {
//
//            //OBTENGO LA OPERACION ACTUAL
//            list($controlador, $action) = explode("/", Yii::$app->controller->route);
//            $operacion_actual = $controlador . "-" . $action;
//            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
//            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
//                echo $this->render('/site/error', [
//                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
//                    'name' => 'Acceso denegado!!',
//                ]);
//            }
//        } else {
//            header("Location:" . \yii\helpers\Url::to(['site/login']));
//            exit();
//        }
//        return true;
//    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {
        $user = Yii::$app->user->identity->usuario;
        $periodId = Yii::$app->user->identity->periodo_id;
        $period = ScholarisPeriodo::findOne($periodId);

        $blocks = $this->get_blocks($user, $period->codigo);

        return $this->render('index',[
            'blocks' => $blocks
        ]);
    }    

    private function get_blocks($user, $periodCode){
        $con = Yii::$app->db;
        $query = "select 	id, name as bloque, blo.orden  
        from 	scholaris_bloque_actividad blo
        where 	blo.scholaris_periodo_codigo = '$periodCode'
                and blo.tipo_uso in (
                        select cla.tipo_usu_bloque 
                         from 	scholaris_clase cla
                                 inner join scholaris_bloque_comparte com on com.valor = cast(cla.tipo_usu_bloque as int)
                                 inner join op_faculty fac on fac.id = cla.idprofesor
                                 inner join res_users rus on rus.partner_id = fac.partner_id
                         where 	rus.login = '$user'
                        group by cla.tipo_usu_bloque
                )
                and blo.tipo_bloque in ('PARCIAL', 'EXAMEN')
        order by blo.orden ;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



    public function actionAcciones(){       
        $user       = Yii::$app->user->identity->usuario;
        $periodId   = Yii::$app->user->identity->periodo_id; 
        $action = $_GET['action'];

        
        if($action == 'weeks'){ //para semanas
            $blockId = $_GET['block_id'];
            $weeks = ScholarisBloqueSemanas::find()
            ->where(['bloque_id' => $blockId])
            ->orderBy('semana_numero')
            ->all();

            $html = '';
            $html.= '<select name="semanas" 
                        id="select-semanas"
                        onchange="showWeek(this)" 
                        class="form-control">';
            $html.= '<option value="">Semanas...</option>';

            foreach($weeks as $week){
                $html.= '<option value="'.$week->id.'">'.$week->nombre_semana.'</option>';
            }

            $html.= '</select>';

            return $html;
        }///fin de para semanas
        else if($action == 'detail-week'){
            $weekId = $_GET['week_id'];

            $week = ScholarisBloqueSemanas::findOne($weekId);

            $dates = $this->get_dates($week->fecha_inicio, $week->fecha_finaliza, $user, $periodId);
            $hours = $this->get_hours($user, $periodId);

            return $this->renderPartial('detail-week', [
                'dates' => $dates,
                'hours' => $hours,
                'user'  => $user,
                'bloqueId' => $week->bloque->id,
                'weekNumber' => $week->semana_numero
            ]);
        }

        
    }

    private function get_dates($fechaInicio, $fechaFinaliza,  $user, $periodId){                
        $days = new CalendarioSemanal($fechaInicio, $fechaFinaliza, $user);        
        return $days->fechas;
    }

    private function get_hours($user, $periodId){
        $con = Yii::$app->db;
        $query = "select 	det.id as detalle_id 
                        ,cla.id as clase_id 
                        ,dia.id as dia_id
                        ,dia.nombre as dia
                        ,dia.numero as dia_numero
                        ,hor.id as hora_id
                        ,hor.nombre as hora
                        ,hor.numero as hora_numero
                        ,hor.desde 
                        ,hor.hasta 
                        ,iam .id as ism_area_materia_id
                        ,mat.id as materia_id
                        ,mat.nombre as materia
                        ,cur.name as curso
                        ,par.name as paralelo
                        ,iam.responsable_planificacion
                from 	scholaris_horariov2_horario hho
                        inner join scholaris_horariov2_detalle det on det.id = hho.detalle_id 
                        inner join scholaris_horariov2_dia dia on dia.id = det.dia_id 
                        inner join scholaris_horariov2_hora hor on hor.id = det.hora_id 
                        inner join scholaris_clase cla on cla.id = hho.clase_id 
                        inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                        inner join ism_materia mat on mat.id = iam.materia_id 
                        inner join op_course_paralelo par on par.id = cla.paralelo_id
                        inner join op_course cur on cur.id = par.course_id
                where 	hho.clase_id in (select 	c.id as clase_id 
                            from	scholaris_clase c
                                    inner join op_faculty f on f.id = c.idprofesor 
                                    inner join res_users r on r.partner_id = f.partner_id 
                                    inner join ism_area_materia i on i.id = c.ism_area_materia_id 
                                    inner join ism_malla_area ma on ma.id = i.malla_area_id 
                                    inner join ism_periodo_malla ip on ip.id = ma.periodo_malla_id 
                            where 	r.login = '$user'
                                    and ip.scholaris_periodo_id = $periodId)
                order by dia.numero, hor.numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}
