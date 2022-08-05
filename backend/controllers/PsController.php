<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * PlanificacionDesagregacionCabeceraController implements the CRUD actions for PlanificacionDesagregacionCabecera model.
 */
class PsController extends Controller {

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
    
    public function actionIndex1(){
        
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        /** Para tomar la fecha desde **/
        if(isset($_GET['desde'])){
            $desde = $_GET['desde'];            
        }else{
            $desde = $this->calcula_fecha_desde();
        }
                
        $hasta = date("Y-m-d", strtotime($desde."+ 4 days")); /** fecha hasta **/ 
        
        /** Para tomar el calendario de la semana **/
        $helper = new \backend\models\helpers\CalendarioSemanal($desde, $hasta, $usuarioLog); 
        $calendario = $helper->fechas;
        /*******************************/
        
        $actividades = $this->get_actividades_semanal($desde, $hasta, $usuarioLog); /** Actividades de la semana del docente **/             
        
        /***** PARA LOS PLANES SEMANALES ******/
        $helper = new \backend\models\helpers\Scripts();
        $cursos = $helper->get_cursos_x_periodo($periodoId, $usuarioLog);
        $planesSemanales = $this->get_planes_semanales($desde, $hasta);
        /**************************************/
        
        return $this->render('index',[
            'calendario' => $calendario,
            'actividades' => $actividades,
            'cursos' => $cursos,
            'planesSemanales' => $planesSemanales
        ]);                
    }
    
    private function get_planes_semanales($desde, $hasta){
        $con = Yii::$app->db;
        $query = "select 	p.id 
                                ,p.op_course_template_id
                                ,o.categoria_principal_es 
                                ,w.id 
                                ,w.experiencias_aprendizaje 
                                ,w.evaluacion_continua 
                                ,w.es_aprobado 
                from 	pep_planificacion_x_unidad p
                                inner join scholaris_bloque_actividad b on b.id = p.bloque_id 
                                inner join scholaris_bloque_semanas s on s.bloque_id = b.id 
                                inner join pep_opciones o on o.id = p.tema_transdisciplinar_id 
                                left join pep_plan_semanal w on w.pep_planificacion_id = p.id 
                where           s.fecha_inicio >= '$desde'
                                and s.fecha_finaliza <= '$hasta';";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    private function calcula_fecha_desde(){
        $hoy = date('Y-m-d');
        $hoyNumero = date('N');
        
        if ($hoyNumero == 1){
            return $hoy;
        }else{
            $totalRestar = $hoyNumero - 1;
            return date("Y-m-d", strtotime($hoy."- $totalRestar days"));
        }
    }   
    
    private function get_actividades_semanal($desde, $hasta, $usuario){
        $con = Yii::$app->db;
        $query = "select substring(cast(ac.inicio as varchar),0,11) as inicio 
		,count(ac.id) as total_actividades
                from	scholaris_actividad ac
                                inner join scholaris_clase cl on cl.id = ac.paralelo_id
                                inner join op_faculty f on f.id = cl.idprofesor 
                                inner join res_users u on u.partner_id = f.partner_id
                where 	ac.inicio between '$desde' and '$hasta'
                                and u.login = '$usuario'
                group by ac.inicio;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    public function actionDetalle(){
        $usuario = \Yii::$app->user->identity->usuario;
        $fecha = $_GET['fecha'];
        
        $actividades = $this->get_actividades_dia($fecha, $usuario);
        
        return $this->renderPartial('_ajax-detalle',[
            'fecha' => $fecha,
            'actividades' => $actividades
        ]);
        
        
    }
    
    private function get_actividades_dia($fecha, $usuario){
        $con = \Yii::$app->db;
        $query = "select 	ac.id as actividad_id
		,mat.nombre as materia
		,ac.inicio 
		,hor.sigla 
		,ac.title 
from	scholaris_actividad ac
		inner join scholaris_clase cl on cl.id = ac.paralelo_id 
		inner join op_faculty fa on fa.id = cl.idprofesor 
		inner join res_users us on us.partner_id = fa.partner_id 
		inner join ism_area_materia am on am.id = cl.ism_area_materia_id 
		inner join ism_malla_area ma on ma.id = am.malla_area_id 
		inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id
		inner join ism_materia mat on mat.id = am.materia_id 
		inner join scholaris_horariov2_horario hh on hh.clase_id = cl.id
				and hh.clase_id = ac.paralelo_id 
		inner join scholaris_horariov2_detalle hd on hd.id = hh.detalle_id 
		inner join scholaris_horariov2_dia dia on dia.id = hd.dia_id 
		inner join scholaris_horariov2_hora hor on hor.id = hd.hora_id 
where 	ac.inicio = '$fecha'
		and us.login = '$usuario'
		and pm.scholaris_periodo_id = 1
		and date_part('dow', '$fecha'::timestamp) = dia.numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}
