<?php

namespace backend\controllers;

use backend\models\ResUsers;
use Yii;
use backend\models\ScholarisAsistenciaAlumnosNovedades;
use backend\models\ScholarisAsistenciaAlumnosNovedadesSearch;
use backend\models\ScholarisAsistenciaComportamientoDetalle;
use backend\models\ViewNovedadesEstudianteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * ScholarisAsistenciaAlumnosNovedadesController implements the CRUD actions for ScholarisAsistenciaAlumnosNovedades model.
 */
class ScholarisAsistenciaAlumnosNovedadesController extends Controller
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
     * Lists all ScholarisAsistenciaAlumnosNovedades models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $user = Yii::$app->user->identity->usuario;

        $searchModel = new ViewNovedadesEstudianteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $periodoId, $user);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            // 'listaS' => $listaS,
            // 'listaNoveades' => $listaNovedades
        ]);
    }

   

    /**
     * Updates an existing ScholarisAsistenciaAlumnosNovedades model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionJustificar($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            $model->acuerdo_justificacion = $_POST['contenido'];
            
            $model->save();
            return $this->redirect(['index1']);
        }

        return $this->render('update', [
            'model' => $model,
            'docente' => ResUsers::findOne($model->asistenciaProfesor->user_id)
        ]);
    }

    

    /**
     * Finds the ScholarisAsistenciaAlumnosNovedades model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisAsistenciaAlumnosNovedades the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisAsistenciaAlumnosNovedades::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
