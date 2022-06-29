<?php

namespace backend\controllers;

use backend\models\KidsPlanSemanal;
use Yii;
use backend\models\KidsPlanSemanalHoraClase;
use backend\models\KidsPlanSemanalHoraClaseSearch;
use backend\models\ScholarisHorariov2Detalle;
use backend\models\KidsPlanSemanalHoraDestreza;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
        $fechaInicia = $planSemanal->semana->fecha_inicio; //devuelve la fecha que inicia

        $fecha = $this->calcula_fecha($detalle->dia->numero, $fechaInicia); //calculo de la fecha para obtener la fecha que se planifica
        
        $model = KidsPlanSemanalHoraClase::find()
                ->where([
                    'plan_semanal_id' => $planSemanalId, 
                    'clase_id' => $claseId, 
                    'detalle_id' => $detalleId
                    ])
                ->one();

        $unidadMicroId = $planSemanal->kids_unidad_micro_id;
        $destrezasDisponibles = $this->get_destrezas_disponibles($unidadMicroId);//destrezas disponibles planificadas en la
                                                                    //tablakids_micro_destreza
                                                                       

        if($model){  //se cumple acuando el modelo existe, caso contrario realiza el ingreso del registro
            
            //destrezas planificadas
            $modelDestrezas = $this->get_destrezas_planificadas($model->id);

            return $this->render('index',[
                'model' => $model,
                'modelDestrezas' => $modelDestrezas,
                'destrezasDisponibles' => $destrezasDisponibles
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

            //destrezas planificadas
            $modelDestrezas = $this->get_destrezas_planificadas($modelN->id);

            return $this->render('index',[
                'model' => $modelN,
                'modelDestrezas' => $modelDestrezas,
                'destrezasDisponibles' => $destrezasDisponibles
            ]);
        }        
    } // termina accion Index1


    private function calcula_fecha($numeroNuevoDia, $fechaInicia){        
        $diasParaSumar = $numeroNuevoDia-1;
        $nuevaFecha = strtotime($fechaInicia.'+ '.$diasParaSumar.' days');
        return date("Y-m-d", $nuevaFecha);
    }


    /**
     * Toma las destrezas planificadas
     */
    private function get_destrezas_planificadas($horaClaseId){
        $con = Yii::$app->db;
        $query = "select 	pd.id 
                        ,a.nombre as ambito
                        ,concat(cd.codigo,' ',cd.nombre) as destreza 
                from 	kids_plan_semanal_hora_destreza pd
                        inner join kids_micro_destreza md on md.id = pd.micro_destreza_id 
                        inner join cur_curriculo_destreza cd on cd.id = md.destreza_id 
                        inner join cur_curriculo_ambito a on a.id = cd.ambito_id 
                where 	pd.hora_clase_id = $horaClaseId;";
                        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Para tomar las destrezas disponibles
     */
    private function get_destrezas_disponibles($unidadMicro){
        $con = Yii::$app->db;
        $query = "select  md.id 
                    ,concat(d.codigo, ' ', d.nombre) as destreza 
            from 	kids_micro_destreza md
                    inner join cur_curriculo_destreza d on d.id = md.destreza_id 
            where 	md.micro_id = $unidadMicro;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionIngresaDestreza(){
        $model = new KidsPlanSemanalHoraDestreza();
        $model->hora_clase_id = $_POST['hora_clase_id'];
        $model->micro_destreza_id = $_POST['micro_destreza_id'];
        $model->save();

        $modelHoraClase = KidsPlanSemanalHoraClase::findOne($model->hora_clase_id);


        return $this->redirect(['index1',
            'plan_semanal_id' => $modelHoraClase->plan_semanal_id,
            'clase_id' => $modelHoraClase->clase_id,
            'detalle_id' => $modelHoraClase->detalle_id
        ]);
    }


    public function actionActualizarActividad(){
        print_r($_POST);
    }

    // /**
    //  * Displays a single KidsPlanSemanalHoraClase model.
    //  * @param integer $id
    //  * @return mixed
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // public function actionView($id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }

    // /**
    //  * Creates a new KidsPlanSemanalHoraClase model.
    //  * If creation is successful, the browser will be redirected to the 'view' page.
    //  * @return mixed
    //  */
    // public function actionCreate()
    // {
    //     $model = new KidsPlanSemanalHoraClase();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    // /**
    //  * Updates an existing KidsPlanSemanalHoraClase model.
    //  * If update is successful, the browser will be redirected to the 'view' page.
    //  * @param integer $id
    //  * @return mixed
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    // /**
    //  * Deletes an existing KidsPlanSemanalHoraClase model.
    //  * If deletion is successful, the browser will be redirected to the 'index' page.
    //  * @param integer $id
    //  * @return mixed
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    // /**
    //  * Finds the KidsPlanSemanalHoraClase model based on its primary key value.
    //  * If the model is not found, a 404 HTTP exception will be thrown.
    //  * @param integer $id
    //  * @return KidsPlanSemanalHoraClase the loaded model
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // protected function findModel($id)
    // {
    //     if (($model = KidsPlanSemanalHoraClase::findOne($id)) !== null) {
    //         return $model;
    //     }

    //     throw new NotFoundHttpException('The requested page does not exist.');
    // }
}
