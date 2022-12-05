<?php

namespace backend\controllers;

use backend\models\ScholarisBloqueComparte;
use Yii;
use backend\models\ScholarisBloqueSemanas;
use backend\models\ScholarisBloqueSemanasSearch;
use backend\models\ScholarisPeriodo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * ScholarisBloqueSemanasController implements the CRUD actions for ScholarisBloqueSemanas model.
 */
class ScholarisBloqueSemanasController extends Controller {

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
     * Lists all ScholarisBloqueSemanas models.
     * @return mixed
     */
    public function actionIndex() {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
        $modelBloques = $this->get_bloques($modelPeriodo->codigo);
        $listaB = ArrayHelper::map($modelBloques, 'id','bloque');
        $tipoBloque = ScholarisBloqueComparte::find()->orderBy('nombre')->all();
        $listaCom = ArrayHelper::map($tipoBloque, 'valor','nombre');

        $searchModel = new ScholarisBloqueSemanasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'listaB' => $listaB,
                    'listaCom' => $listaCom
        ]);
    }
    
    
    private function get_bloques($periodoCodigo){
        $con = Yii::$app->db;
        $query = "select 	blo.id 
                            ,concat(blo.name, ' | ',com.nombre) as bloque
                    from 	scholaris_bloque_semanas sem
                            inner join scholaris_bloque_actividad blo on blo.id = sem.bloque_id 
                            inner join scholaris_bloque_comparte com on cast(com.valor as varchar) = blo.tipo_uso
                    where 	blo.scholaris_periodo_codigo = '$periodoCodigo'
                            and blo.tipo_bloque in ('PARCIAL','EXAMEN')
                    group by blo.id, blo.name,com.nombre
                    order by com.nombre,blo.orden;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    

    /**
     * Displays a single ScholarisBloqueSemanas model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisBloqueSemanas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);

        $model = new ScholarisBloqueSemanas();
        
        $modelBloques = $this->get_bloques($modelPeriodo->codigo);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
                    'modelBloques' => $modelBloques
        ]);
    }

    /**
     * Updates an existing ScholarisBloqueSemanas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $modelBloques = $this->get_bloques();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'modelBloques' => $modelBloques
        ]);
    }

    /**
     * Deletes an existing ScholarisBloqueSemanas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisBloqueSemanas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisBloqueSemanas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisBloqueSemanas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
