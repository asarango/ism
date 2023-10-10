<?php

namespace backend\controllers;

use backend\models\estudiante\Estudiante;
use backend\models\estudiante\DetalleNotas;
use backend\models\OpStudentInscription;
use backend\models\ScholarisPeriodo;
use Yii;
use backend\models\ViewStudiantesCv;
use backend\models\ViewStudiantesCvSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ViewStudiantesCvController implements the CRUD actions for ViewStudiantesCv model.
 */
class ViewStudiantesCvController extends Controller
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

    /**
     * Lists all ViewStudiantesCv models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ViewStudiantesCvSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionDetalle()
    {
        $inscriptionId = $_GET['inscription_id'];
        $inscription = OpStudentInscription::findOne($inscriptionId);

        return $this->render('detalle', [
            'inscription' => $inscription
        ]);
    }

    public function actionAcciones()
    {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $inscriptionId  = $_GET['inscription_id'];
        $accion         = $_GET['accion'];
        $inscription = OpStudentInscription::findOne($inscriptionId);
        $periodo = ScholarisPeriodo::findOne($periodoId);



        if ($accion == 'promedios') {
            //    $this->redirecciona_promedios($inscription->student_id, $periodo->codigo);
            $estudiante = new Estudiante();
            $promedios = $estudiante->promedios($inscription->student_id, $periodo->codigo);
            return $this->renderPartial('_ajax-promedio-general', [
                'promedios' => $promedios
            ]);
        }elseif($accion == 'chart-clases'){
            $estudiante = new Estudiante();
            $chartClases = $estudiante->chart_general_clases($inscriptionId);

            return $this->renderPartial('_ajax-chart-clases', [
                'chartClases' => $chartClases,
                'inscriptionId' => $inscriptionId
            ]);
        }elseif($accion == 'detalle-clase'){
            $nombreClase = $_GET['nombre_clase'];

            $detalle = new DetalleNotas($nombreClase, $inscriptionId);

            return $this->renderPartial('_ajax-detalle-clase', [
                'detalle' => $detalle
            ]);
        }
    }
}
