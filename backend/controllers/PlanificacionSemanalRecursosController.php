<?php

namespace backend\controllers;

use backend\models\PlanificacionSemanal;
use Illuminate\Support\Facades\Auth;
use Yii;
use backend\models\PlanificacionSemanalRecursos;
use backend\models\PlanificacionSemanalRecursosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PlanificacionSemanalRecursosController implements the CRUD actions for PlanificacionSemanalRecursos model.
 */
class PlanificacionSemanalRecursosController extends Controller
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
     * Lists all PlanificacionSemanalRecursos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $planificacionSemanalId = $_GET['id'];
        $userLogin = Yii::$app->user->identity->usuario;
        $planificacionSemanal = PlanificacionSemanal::findOne($planificacionSemanalId);
        // echo '<pre>'; print_r($planificacionSemanal); die();
        // print_r($planificacionSemanalId);

        $searchModel = new PlanificacionSemanalRecursosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $userLogin, $planificacionSemanalId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'planificacionSemanal' => $planificacionSemanal
        ]);
    }

    /**
     * Displays a single PlanificacionSemanalRecursos model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PlanificacionSemanalRecursos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // echo '<pre>';
        // print_r($_POST);

        if (isset($_POST['plan_semanal_id'])) {
            // Realizamos la insercion de registro
            // Con este metodo se realizan las preguntas del tipo de recursos
            //que consta en el formulario
            $this->guardar_recursos($_POST);

            // Redireccionamos a index luego de grabar
            return $this->redirect(['index', 
                'id' => $_POST['plan_semanal_id']
            ]);
        } else {
            $planificacionSemanalId = $_GET['planificacion_semanal_id'];
            return $this->render('create', [
                'planificacionSemanalId' => $planificacionSemanalId
            ]);
        }
    }
    private function guardar_recursos($post)
    {
        // echo '<pre>';
        // print_r($post);
        // die();
    //    print_r($_FILES);
        if ($post['bandera'] == 'file') {
            $fecha=date('YmdHis');
            $nombreArchivo = $_FILES['documento']['name'];
            $rutaArchivo = $_FILES['documento']['tmp_name'];
            $destino = '/var/www/html/files/docentes/lms/' . $fecha . $nombreArchivo;
            if(move_uploaded_file($rutaArchivo, $destino)){
                $planificacionSemanalId=$post['plan_semanal_id'];
                $planificacionSemanalTema=$post['tema1'];
                $planificacionSemanalTipoRecurso=$post['bandera'];                
                $model=new PlanificacionSemanalRecursos();
                $model->plan_semanal_id=$planificacionSemanalId;
                $model->tema=$planificacionSemanalTema;
                $model->tipo_recurso=$planificacionSemanalTipoRecurso;
                $model->url_recurso=$destino;
                $model->estado=true;
                $model->save();
                
            }else{
                echo"no se guardo, volver a intentar caso contrario comunciarse con ADMINISTRADOR";
                
            }
        }elseif($post['bandera'] == 'link'){
            $planificacionSemanalId=$post['plan_semanal_id'];
            $planificacionSemanalTema=$post['tema1'];
            $planificacionSemanalTipoRecurso=$post['bandera'];
            $planificacionSemanalUrl=$post['url'];                
            $model=new PlanificacionSemanalRecursos();
            $model->plan_semanal_id=$planificacionSemanalId;
            $model->tema=$planificacionSemanalTema;
            $model->tipo_recurso=$planificacionSemanalTipoRecurso;
            $model->url_recurso=$planificacionSemanalUrl;
            $model->estado=true;
            $model->save();
        }elseif($post['bandera']=='video-conferencia'){
            $planificacionSemanalId=$post['plan_semanal_id'];
            $planificacionSemanalTema=$post['tema1'];
            $planificacionSemanalTipoRecurso=$post['bandera'];
            $planificacionSemanalVideo=$post['url'];                
            $model=new PlanificacionSemanalRecursos();
            $model->plan_semanal_id=$planificacionSemanalId;
            $model->tema=$planificacionSemanalTema;
            $model->tipo_recurso=$planificacionSemanalTipoRecurso;
            $model->url_recurso=$planificacionSemanalVideo;
            $model->estado=true;
            $model->save();
        }elseif($post['bandera']== 'texto'){
            $planificacionSemanalId=$post['plan_semanal_id'];
            $planificacionSemanalTema=$post['tema1'];
            $planificacionSemanalTipoRecurso=$post['bandera'];
            $planificacionSemanalTexto=$post['url'];                
            $model=new PlanificacionSemanalRecursos();
            $model->plan_semanal_id=$planificacionSemanalId;
            $model->tema=$planificacionSemanalTema;
            $model->tipo_recurso=$planificacionSemanalTipoRecurso;
            $model->url_recurso=$planificacionSemanalTexto;
            $model->estado=true;
            $model->save();
        };

        }

    /**
     * Updates an existing PlanificacionSemanalRecursos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PlanificacionSemanalRecursos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PlanificacionSemanalRecursos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlanificacionSemanalRecursos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlanificacionSemanalRecursos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}