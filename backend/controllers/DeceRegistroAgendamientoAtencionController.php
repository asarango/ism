<?php

namespace backend\controllers;

use Yii;
use backend\models\DeceRegistroAgendamientoAtencion;
use backend\models\DeceRegistroAgendamientoAtencionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeceRegistroAgendamientoAtencionController implements the CRUD actions for DeceRegistroAgendamientoAtencion model.
 */
class DeceRegistroAgendamientoAtencionController extends Controller
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
     * Lists all DeceRegistroAgendamientoAtencion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeceRegistroAgendamientoAtencionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeceRegistroAgendamientoAtencion model.
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
     * Creates a new DeceRegistroAgendamientoAtencion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idSeguimiento)
    {
        $model = new DeceRegistroAgendamientoAtencion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['create', 'idSeguimiento' => $model->id_reg_seguimiento]);
        }

        return $this->render('create', [
            'model' => $model,
            'idSeguimiento'=>$idSeguimiento,
        ]);
    }

    /**
     * Updates an existing DeceRegistroAgendamientoAtencion model.
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
            'idSeguimiento'=>$model->id_reg_seguimiento,
        ]);
    }

    /**
     * Deletes an existing DeceRegistroAgendamientoAtencion model.
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
     * Finds the DeceRegistroAgendamientoAtencion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceRegistroAgendamientoAtencion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceRegistroAgendamientoAtencion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
