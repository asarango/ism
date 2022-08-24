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
    
    
    
    
}