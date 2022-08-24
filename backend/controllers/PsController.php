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
        
        $actividades = $this->get_actividades_semanal($desde, $hasta, $usuarioLog); /** Actividades de la semana del docente **/      
        
        /******* PARA BUSCAR LA SEMANA ID *******/
        $semana = $this->get_semana_id($periodoId, $usuarioLog, $desde);
        
        if(!isset($semana['semana_id'])){
            $desde = date("Y-m-d", strtotime($desde."- 7 days"));
            return $this->redirect(['index1', 'desde' => $desde ]);
        }
      
        /////////**********///////////////////
        
        /***** PARA LOS PLANES SEMANALES ******/
        $helper = new \backend\models\helpers\Scripts();
        $cursos = $helper->get_cursos_x_periodo($periodoId, $usuarioLog);
        $planesSemanales = $this->get_planes_semanales($desde, $hasta, $semana['semana_id']);
        /**************************************/
     
        
        return $this->render('index',[
            'actividades' => $actividades,
            'cursos' => $cursos,
            'planesSemanales' => $planesSemanales,
            'desde' => $desde,            
            'hasta' => $hasta,            
            'semana' => $semana            
        ]);                
    }
    
    private function get_semana_id($periodoId, $usuario, $desde){
        $con = Yii::$app->db;
        $query = "select 	bs.id as semana_id 
                                    ,bs.semana_numero 
                                    ,bs.nombre_semana 
                    from 	scholaris_clase cl 
                                    inner join op_faculty fa on fa.id = cl.idprofesor 
                                    inner join ism_area_materia am on am.id = cl.ism_area_materia_id 
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                    inner join res_users ru on ru.partner_id = fa.partner_id 
                                    inner join scholaris_bloque_comparte bc on cast(bc.valor as varchar) = cl.tipo_usu_bloque 
                                    inner join scholaris_bloque_actividad ba on ba.tipo_uso = cast(bc.valor as varchar)
                                    inner join scholaris_bloque_semanas bs on bs.bloque_id = ba.id 
                    where	pm.scholaris_periodo_id = $periodoId
                                    and ru.login = '$usuario'
                                    and bs.fecha_inicio = '$desde' 
                    limit 1;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    private function get_planes_semanales($desde, $hasta, $semanaId){
        $con = Yii::$app->db;
        $query = "select 	p.id as planificacion_id
                                ,p.op_course_template_id
                                ,o.categoria_principal_es 
                                ,w.id as plan_semanal_id
                                ,w.semana_id
                                ,w.experiencias_aprendizaje 
                                ,w.evaluacion_continua 
                                ,w.es_aprobado 
                from 	pep_planificacion_x_unidad p
                                inner join scholaris_bloque_actividad b on b.id = p.bloque_id 
                                inner join scholaris_bloque_semanas s on s.bloque_id = b.id 
                                inner join pep_opciones o on o.id = p.tema_transdisciplinar_id 
                                left join pep_plan_semanal w on w.pep_planificacion_id = p.id 
                                        and w.semana_id = '$semanaId'
                where           s.fecha_inicio >= '$desde'
                                and s.fecha_finaliza <= '$hasta';";

        
        $res = $con->createCommand($query)->queryOne();
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
        $query = "select act.id            
                ,act.inicio 
		,hor.sigla 
		,mat.nombre as materia
		,act.title 
                ,act.descripcion as enseñanza
		,act.tareas
		,tip.nombre_nacional 
		,act.calificado as es_calificado
		,act.tipo_calificacion 
from 	scholaris_actividad act
		inner join scholaris_clase cla on cla.id = act.paralelo_id 
		inner join op_faculty fac on fac.id = cla.idprofesor 
		inner join res_users rus on rus.partner_id = fac.partner_id  
		inner join scholaris_horariov2_hora hor on hor.id = act.hora_id 
		inner join ism_area_materia am on am.id = cla.ism_area_materia_id 
		inner join ism_materia mat on mat.id = am.materia_id 
		inner join scholaris_tipo_actividad tip on tip.id = act.tipo_actividad_id 
where 	act.inicio between '$desde' and '$hasta'
		and rus.login = '$usuario'
order by act.inicio, hor.numero;";
        
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
    
    
    
    public function actionConfigurar(){
        $usuario = Yii::$app->user->identity->usuario;
        $hoy = date("Y-m-d H:i:s");
        
        $planificacionId = $_GET['pep_planificacion_id'];
        $semanaId = $_GET['semana_id'];
        $opCourseTemplateId = $_GET['op_course_template_id'];
                
        $plan = $this->buscar_plan_semanal($semanaId, $opCourseTemplateId);
        
        if(!$plan){
            $model = new \backend\models\PepPlanSemanal();
            $model->pep_planificacion_id = $planificacionId;
            $model->semana_id = $semanaId;
            $model->experiencias_aprendizaje = 'No conf';
            $model->evaluacion_continua = 'No conf';
            $model->es_aprobado = false;
            $model->created_at = $hoy;
            $model->created = $usuario;
            $model->updated_at = $hoy;
            $model->updated = $usuario;
            $model->save();
        }else{
            $model = \backend\models\PepPlanSemanal::findOne($plan['id']);            
        }
        
        return $this->render('configuracion',[
            'model' => $model
        ]);
        
    }
    
    private function buscar_plan_semanal($semanId, $opCourseTemplateId){
        $con = \Yii::$app->db;
        $query = "select 	s.id 
                    from 	pep_plan_semanal s
                                    inner join pep_planificacion_x_unidad p on p.id = s.pep_planificacion_id 
                    where 	s.semana_id = $semanId
                                    and p.op_course_template_id = $opCourseTemplateId;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    public function actionUpdate(){
        $usuario = Yii::$app->user->identity->usuario;
        $hoy = date("Y-m-d H:i:s");
        
        $planSemanalId = $_POST['plan_semanal_id'];
        $experiencia = $_POST['experiencia'];
        $evaluacion  = $_POST['evaluacion'];
        
        $model = \backend\models\PepPlanSemanal::findOne($planSemanalId);
        
        $model->experiencias_aprendizaje = $experiencia;
        $model->evaluacion_continua = $evaluacion;
        $model->updated_at = $hoy;
        $model->updated = $usuario;
        if($model->save()){
            $resp = array(
                'status' => 'ok'           
            );
        }else{
            $resp = array(
                'status' => 'error'            
            );
        }
        
        return json_encode($resp);
    }

}
