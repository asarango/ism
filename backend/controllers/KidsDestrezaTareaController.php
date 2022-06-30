<?php

namespace backend\controllers;

use Yii;
use backend\models\KidsDestrezaTarea;
use app\models\KidsDestrezaTareaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KidsDestrezaTareaController implements the CRUD actions for KidsDestrezaTarea model.
 */
class KidsDestrezaTareaController extends Controller
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
     * Lists all KidsDestrezaTarea models.
     * @return mixed
     */
    public function actionCrearTarea()
    {
        // echo '<pre>';
        // print_r($_POST);
        // print_r($_FILES);
        // die();
        $usuario    = Yii::$app->user->identity->usuario;
        $hoy        = date('Y-m-d H:i:s');

        $model = new KidsDestrezaTarea();
        $model->plan_destreza_id    = $_POST['plan_destreza_id'];
        $model->fecha_presentacion  =  $_POST['fecha_presentacion'];
        $model->titulo              =  $_POST['titulo'];
        $model->detalle_tarea       =  $_POST['detalle_tarea'];
        $model->materiales          =  $_POST['materiales'];
        $model->publicado_al_estudiante =  $_POST['publicado_al_estudiante'];
        $model->created_at          =  $hoy;
        $model->created             = $usuario;
        $model->updated_at          = $hoy;
        $model->updated             = $usuario;
        $model->save();

        return $this->redirect(['update',
            'id' => $model->id
        ]);

    }

    /**
     * Displays a single KidsDestrezaTarea model.
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
     * Creates a new KidsDestrezaTarea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KidsDestrezaTarea();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KidsDestrezaTarea model.
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
     * Deletes an existing KidsDestrezaTarea model.
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
     * Finds the KidsDestrezaTarea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KidsDestrezaTarea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KidsDestrezaTarea::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
