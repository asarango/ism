<?php

namespace backend\controllers;

use backend\models\PlanificacionDesagregacionCabecera;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class AprobacionPlanVerticalController extends Controller
{
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

    public function actionIndex1()
    {
        $templateId = $_GET['template_id'];

        $curso = \backend\models\OpCourseTemplate::findOne($templateId);
        $materias = $this->get_asignaturas($templateId);


        // print_r($templateId);

        return $this->render(
            'index',
            [
                'curso' => $curso,
                'materias' => $materias
            ]
        );
    }

    private function get_asignaturas($templateId)
    {
        $con = \Yii::$app->db;
        $query = "select 	am.id 
                                    ,m.nombre 
                    from	ism_area_materia am
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                    inner join ism_malla mal on mal.id = pm.malla_id
                                    inner join ism_materia m on m.id = am.materia_id 
                    where 	mal.op_course_template_id = $templateId
                    order by m.nombre asc;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
}
?>