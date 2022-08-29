<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisHorariov2CabeceraController implements the CRUD actions for ScholarisHorariov2Cabecera model.
 */
class AprobacionPlanSemanalController extends Controller{
    
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
        
        $userLog    = \Yii::$app->user->identity->usuario;
        $periodoId  = \Yii::$app->user->identity->periodo_id;
        
        $script = new \backend\models\helpers\Scripts();
        $docentes = $script->get_docentes_x_coordinador_academico($userLog, $periodoId);
                
        $uso = $docentes[0]['tipo_usu_bloque'];
                
        $semanas = $this->get_semanas($uso);
        
        return $this->render('index',[
            'docentes' => $docentes,
            'semanas' => $semanas
        ]);
    }
    
    
    private function get_semanas($uso){
        $con = Yii::$app->db;
        $query = "select 	sem.id 
                    ,sem.nombre_semana  
                from	scholaris_bloque_semanas sem
                                inner join scholaris_bloque_actividad blo on blo.id = sem.bloque_id 
                where 	blo.tipo_uso = '$uso'
                order by sem.fecha_inicio;";
        $res  = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    public function actionAjaxDetalle(){
        $idProfesor = $_POST['fac_id'];
        $semanaId   = $_POST['semana_id'];
        
        $semana = \backend\models\ScholarisBloqueSemanas::findOne($semanaId);
        $actividades = $this->get_actividades($idProfesor, $semanaId, $semana->fecha_inicio, $semana->fecha_finaliza);
        $docente = \backend\models\OpFaculty::findOne($idProfesor);
        $resUser = \backend\models\ResUsers::find()->where(['partner_id' => $docente->partner_id])->one();
        
        $planSemanal = \backend\models\PepPlanSemanal::find()->where([
            'created' => $resUser->login,
            'semana_id' => $semanaId
        ])->one();
        
        return $this->renderPartial('ajax-detalle',[
            'actividades' => $actividades,
            'docente' => $docente,
            'semana' => $semana,
            'planSemanal' => $planSemanal
        ]);
        
    }
    
    private function get_actividades($idProfesor, $semanaId, $fechaDesde, $fechaHasta){
        $con = Yii::$app->db;
        $query = "select 	act.id ,act.inicio 
                                ,hor.sigla
                                ,mat.nombre as materia
                                ,act.title 
                                ,act.descripcion 
                                ,act.tareas 
                                ,tip.nombre_nacional 
                                ,act.calificado 
                                ,act.tipo_calificacion 
                                ,concat(fac.x_first_name,' ',fac.last_name) as docente
                from	scholaris_actividad act
                                inner join scholaris_bloque_actividad blo on blo.id = act.bloque_actividad_id
                                inner join scholaris_bloque_semanas sem on sem.bloque_id = blo.id 
                                inner join scholaris_clase cla on cla.id = act.paralelo_id 
                                inner join op_faculty fac on fac.id = cla.idprofesor 
                                inner join ism_area_materia am on am.id = cla.ism_area_materia_id 
                                inner join ism_materia mat on mat.id = am.materia_id 
                                inner join scholaris_horariov2_hora hor on hor.id = act.hora_id 
                                inner join scholaris_tipo_actividad tip on tip.id = act.tipo_actividad_id 
                where 	sem.id = $semanaId
                        and act.inicio between '$fechaDesde' and '$fechaHasta' 
                                and fac.id = $idProfesor
                order by act.inicio;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function actionUpdateRetro(){
       
        $planSemanalId      = $_POST['plan_semanal_id'];
        $retroalimentacion  = $_POST['retroalimentacion'];
        
        $model = \backend\models\PepPlanSemanal::findOne($planSemanalId);
        $model->retroalimentacion = $retroalimentacion;
        
        $model->save();
    }
    
    public function actionAprobar(){
        $hoy = date('Y-m-d H:i:s');
        $planSemanalId = $_POST['plan_semanal_id'];
        $model = \backend\models\PepPlanSemanal::findOne($planSemanalId);
        $model->es_aprobado = true;
        $model->fecha_aprobacion = $hoy;
        $model->save();
    }
    
    
}