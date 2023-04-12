<?php

namespace backend\controllers;

use backend\models\Lms;
use Yii;
use backend\models\LmsActividad;
use backend\models\LmsActividadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LmsActividadController implements the CRUD actions for LmsActividad model.
 */
class LmsActividadController extends Controller
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
     * Lists all LmsActividad models.
     * @return mixed
     */
    public function actionIndex1()
    {
        $lmsId = $_GET['lms_id'];
        $planBloqueUnidadId = $_GET['plan_bloque_unidad_id'];        

        $lms = Lms::findOne($lmsId);
        $lmsActivities = LmsActividad::find()->where(['lms_id' => $lmsId])->all();

        $searchModel = new LmsActividadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $lmsId);

        $week = $this->get_week($lms->semana_numero, $lms->tipo_bloque_comparte_valor);

        return $this->render('index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'lms'           => $lms,
            'lmsActivities' => $lmsActivities,
            'planBloqueUnidadId' => $planBloqueUnidadId, 
            'week'          => $week
        ]);
    }

    private function get_week($semanaNumero, $uso){
        $con = Yii::$app->db;
        $query = "select 	sem.nombre_semana 
                            ,sem.fecha_inicio 
                            ,sem.fecha_finaliza 
                    from 	scholaris_bloque_semanas sem
                            inner join scholaris_bloque_actividad blo on blo.id = sem.bloque_id 
                    where 	blo.tipo_uso = '$uso'
                            and sem.semana_numero = $semanaNumero
                            and blo.tipo_bloque = 'PARCIAL';";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }


    /**
     * Displays a single LmsActividad model.
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
     * Creates a new LmsActividad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $lmsId = $_GET['lms']; 
        $planBloqueUnidadId = $_GET['planBloqueUnidadId']; 
        $actionBack = $_GET['actionBack']; 

        $model = new LmsActividad();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 
                'lms_id' => $lmsId,
                'plan_bloque_unidad_id' => $planBloqueUnidadId,
                'action-back' => $actionBack
            ]);
        }

        return $this->render('create', [
            'model'                 => $model,
            'lms'                   => $lmsId,
            'planBloqueUnidadId'    => $planBloqueUnidadId,
            'actionBack'           => $actionBack
        ]);
    }

    /**
     * Updates an existing LmsActividad model.
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
     * Deletes an existing LmsActividad model.
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
     * Finds the LmsActividad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LmsActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LmsActividad::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
