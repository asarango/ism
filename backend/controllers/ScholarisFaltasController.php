<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisFaltas;
use backend\models\ScholarisFaltasSearch;
use backend\models\ScholarisParametrosOpciones;
use backend\models\ViewFaltasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisFaltasController implements the CRUD actions for ScholarisFaltas model.
 */
class ScholarisFaltasController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ScholarisFaltas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $periodId = Yii::$app->user->identity->periodo_id;
        $searchModel = new ViewFaltasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $periodId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScholarisFaltas model.
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
     * Creates a new ScholarisFaltas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScholarisFaltas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisFaltas model.
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
     * Deletes an existing ScholarisFaltas model.
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
     * Finds the ScholarisFaltas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisFaltas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisFaltas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionJustificar(){
        $faltaId = $_GET['id'];
        $model = ScholarisFaltas::findOne($faltaId);

        $tiempoJustificacion = ScholarisParametrosOpciones::find()->where(['codigo' => 'tiempojustifica'])->one();

        if(isset($_GET['justificacion'])){
            $hoy = date('Y-m-d');
            $ahora = date('Y-m-d H:i:s');
            $user = Yii::$app->user->identity->usuario;
            $model->es_justificado = true;
            $model->fecha_justificacion = $hoy;
            $model->respuesta_justificacion = $_GET['justificacion'];
            $model->usuario_justifica = $user;
            $model->updated = $ahora;
            $model->updated_at = $user;
            $model->save();

            return $this->redirect(['index']);

        }


        return $this->render('justificar', [
            'model' => $model,
            'dias' => $tiempoJustificacion->valor
        ]);
    }
}
