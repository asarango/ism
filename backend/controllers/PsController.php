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
        
        return $this->render('index',[
            'calendario' => $calendario,
            'actividades' => $actividades
        ]);                
    }
    
//    private function buscar_totales($arrayActividades, $fecha){
//        $key = array_search($fecha, array_column($arrayActividades, 'inicio'));
//        if($key){
//            return $arrayActividades[$key]['total_actividades'];
//        }else{
//            return 0;
//        }        
//    } 
    
    
    
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
        print_r($_GET);
    }

}
