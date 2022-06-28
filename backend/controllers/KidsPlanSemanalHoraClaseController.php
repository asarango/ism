<?php

namespace backend\controllers;

use backend\models\KidsPlanSemanal;
use Yii;
use backend\models\KidsPlanSemanalHoraClase;
use backend\models\KidsPlanSemanalHoraClaseSearch;
use backend\models\ScholarisHorariov2Detalle;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KidsPlanSemanalHoraClaseController implements the CRUD actions for KidsPlanSemanalHoraClase model.
 */
class KidsPlanSemanalHoraClaseController extends Controller
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
     * Lists all KidsPlanSemanalHoraClase models.
     * @return mixed
     */
    public function actionIndex1()
    {

        $planSemanalId  = $_GET['plan_semanal_id'];
        $claseId        = $_GET['clase_id'];
        $detalleId      = $_GET['detalle_id'];

        $detalle = ScholarisHorariov2Detalle::findOne($detalleId);
        $planSemanal = KidsPlanSemanal::findOne($planSemanalId);
        $fechaInicia = $planSemanal->semana->fecha_inicio;

        $fecha = $this->calcula_fecha($detalle->dia->numero, $fechaInicia);
        
        $model = KidsPlanSemanalHoraClase::find()
                ->where([
                    'plan_semanal_id' => $planSemanalId, 
                    'clase_id' => $claseId, 
                    'detalle_id' => $detalleId
                    ])
                ->one();

        if($model){
            return $this->render('index',[
                'model' => $model
            ]);
        }else{
            $today = date('Y-m-d H:i:s');
            $user = Yii::$app->user->identity->usuario;
            $modelN = new KidsPlanSemanalHoraClase();
            $modelN->plan_semanal_id = $planSemanalId;
            $modelN->clase_id = $claseId;
            $modelN->detalle_id = $detalleId;
            $modelN->fecha = $fecha;
            $modelN->created_at = $today;
            $modelN->created = $user;
            $modelN->save();

            return $this->render('index',[
                'model' => $modelN
            ]);
        }        
    }

    private function calcula_fecha($numeroNuevoDia, $fechaInicia){
        
        $diasParaSumar = $numeroNuevoDia-1;

        $nuevaFecha = strtotime($fechaInicia.'+ '.$diasParaSumar.' days');

        return date("Y-m-d", $nuevaFecha);
    }

    /**
     * Displays a single KidsPlanSemanalHoraClase model.
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
     * Creates a new KidsPlanSemanalHoraClase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KidsPlanSemanalHoraClase();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KidsPlanSemanalHoraClase model.
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
     * Deletes an existing KidsPlanSemanalHoraClase model.
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
     * Finds the KidsPlanSemanalHoraClase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KidsPlanSemanalHoraClase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KidsPlanSemanalHoraClase::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
