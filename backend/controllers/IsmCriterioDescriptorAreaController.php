<?php

namespace backend\controllers;

use backend\models\IsmArea;
use backend\models\IsmCriterio;
use Yii;
use backend\models\IsmCriterioDescriptorArea;
use backend\models\IsmCriterioDescriptorAreaSearch;
use backend\models\IsmCriterioLiteral;
use backend\models\IsmDescriptores;
use backend\models\IsmLiteralDescriptores;
use backend\models\OpCourseTemplate;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * IsmCriterioDescriptorAreaController implements the CRUD actions for IsmCriterioDescriptorArea model.
 */
class IsmCriterioDescriptorAreaController extends Controller
{
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

    /**
     * Lists all IsmCriterioDescriptorArea models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IsmCriterioDescriptorAreaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $listaArea = IsmArea::find()->orderBy('nombre')->all();        
        $listaA = ArrayHelper::map($listaArea, 'id', 'nombre');

        $listaCurso = OpCourseTemplate::find()->orderBy('name')->all();
        $listaC = ArrayHelper::map($listaCurso, 'id', 'name');

        $listaCriterio = IsmCriterio::find()->orderBy('nombre')->all();
        $listaCri = ArrayHelper::map($listaCriterio, 'id', 'nombre');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listaA' => $listaA,
            'listaC' => $listaC,
            'listaCri' => $listaCri
        ]);
    }    

    /**
     * Displays a single IsmCriterioDescriptorArea model.
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
     * Creates a new IsmCriterioDescriptorArea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IsmCriterioDescriptorArea();

        $areas                  = IsmArea::find()->orderBy('nombre')->all();
        $templates              = OpCourseTemplate::find()->orderBy('next_course_id')->all();
        $descriptores           = IsmDescriptores::find()->orderBy('nombre')->all();
        $descriptoresLiteral    = IsmLiteralDescriptores::find()->orderBy('descripcion')->all();
        $criteriosLiteral       = IsmCriterioLiteral::find()->orderBy('nombre_espanol')->all();
        $criterios              = IsmCriterio::find()->orderBy('nombre')->all();

       

        if (isset($_POST['id_area'])) {
        // if ($model->load(Yii::$app->request->post())) {

            $this->insert_descriptor($_POST);

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'descriptores'          => $descriptores,
            'descriptoresLiteral'   => $descriptoresLiteral,
            'criteriosLiteral'      => $criteriosLiteral,
            'criterios'             => $criterios,
            'areas'                 => $areas,
            'templates'             => $templates
        ]);
    }

    private function insert_descriptor($post){
        $id_area                 = $post['id_area'];
        $id_curso                = $post['id_curso'];
        $id_criterio             = $post['id_criterio'];
        $id_literal_criterio     = $post['id_literal_criterio'];
        $id_descriptor           = $post['id_descriptor'];
        $id_literal_descriptor   = $post['id_literal_descriptor'];

        $con = Yii::$app->db;
        $query = "insert into ism_criterio_descriptor_area (id_area, id_curso, id_criterio, id_literal_criterio, id_descriptor, id_literal_descriptor) 
                    values($id_area, $id_curso, $id_criterio, $id_literal_criterio, $id_descriptor, $id_literal_descriptor)";
        $con->createCommand($query)->execute();
    }

    /**
     * Updates an existing IsmCriterioDescriptorArea model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $descriptores           = IsmDescriptores::find()->orderBy('nombre')->all();
        $descriptoresLiteral    = IsmLiteralDescriptores::find()->orderBy('descripcion')->all();
        $criteriosLiteral       = IsmCriterioLiteral::find()->orderBy('nombre_espanol')->all();
        $criterios              = IsmCriterio::find()->orderBy('nombre')->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->id_criterio             = $_POST['id_criterio'];
            $model->id_literal_criterio     = $_POST['id_literal_criterio'];
            $model->id_descriptor           = $_POST['id_descriptor'];
            $model->id_literal_descriptor   = $_POST['id_literal_descriptor'];
            $model->save();
            
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model'                 => $model,
            'descriptores'          => $descriptores,
            'descriptoresLiteral'   => $descriptoresLiteral,
            'criteriosLiteral'      => $criteriosLiteral,
            'criterios'             => $criterios
        ]);
    }

    /**
     * Deletes an existing IsmCriterioDescriptorArea model.
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
     * Finds the IsmCriterioDescriptorArea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IsmCriterioDescriptorArea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IsmCriterioDescriptorArea::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionEliminar($id){
        $model = IsmCriterioDescriptorArea::findOne($id);
        $model->delete();
        return $this->redirect(['index']);
    }
}
