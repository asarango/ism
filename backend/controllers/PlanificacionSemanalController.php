<?php

namespace backend\controllers;

use backend\models\plansemanal\PsIndividual;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisBloqueSemanas;
use backend\models\ScholarisClase;
use Yii;
use backend\models\PlanificacionSemanal;
use backend\models\PlanificacionSemanalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PlanificacionSemanalController implements the CRUD actions for PlanificacionSemanal model.
 */
class PlanificacionSemanalController extends Controller
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
     * Lists all PlanificacionSemanal models.
     * @return mixed
     */
    public function actionIndex1()
    {

        $bloqueId = $_GET['bloque_id'];
        $claseId = $_GET['clase_id'];

        $bloque = ScholarisBloqueActividad::findOne($bloqueId);
        $clase = ScholarisClase::findOne($claseId);

        $semanas = ScholarisBloqueSemanas::find()
        ->where(['bloque_id' => $bloqueId])
        ->orderBy('semana_numero')
        ->all();

        // toma el dato semanal por defecto para presentar la semana
        if(isset($_GET['semana_defecto'])){
            $semanaDefectoId = $_GET['semana_defecto'];
        }else{
            $semanaDefectoId = $semanas[0]->id;
        }
        
        //Ingresa las horas del plan semanal, según el horario de la clase configurada
        new PsIndividual($claseId, $semanaDefectoId);
                        
        // Devuelve el plan semanal
        $semana = PlanificacionSemanal::find()->where([
            'clase_id' => $claseId,
            'semana_id' => $semanaDefectoId
        ])
        ->orderBy('orden_hora_semana')
        ->all();

        // renderiza la vista
        return $this->render('index', [
            'clase' => $clase,
            'bloque' => $bloque,
            'semanas' => $semanas,
            'semana' => $semana
        ]);
    }


    /**
     * Displays a single PlanificacionSemanal model.
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
     * Creates a new PlanificacionSemanal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlanificacionSemanal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PlanificacionSemanal model.
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
     * Deletes an existing PlanificacionSemanal model.
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
     * Finds the PlanificacionSemanal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlanificacionSemanal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlanificacionSemanal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
