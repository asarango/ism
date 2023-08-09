<?php

namespace backend\controllers;

use backend\models\plansemanal\PsIndividual;
use backend\models\ScholarisActividad;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisBloqueSemanas;
use backend\models\ScholarisClase;
use backend\models\ScholarisTipoActividad;
use Yii;
use backend\models\PlanificacionSemanal;
use backend\models\PlanificacionSemanalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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

     public function actionMisClases()
     {
        // $bloqueId = $_GET['bloque_id'];
        // $claseId = $_GET['clase_id'];
        // echo '<pre>';
        // print_r($_GET);
        // die();

        // $bloque = ScholarisBloqueActividad::findOne($bloqueId);
        // $clase = ScholarisClase::findOne($claseId);

        // $semanas = ScholarisBloqueSemanas::find()
        // ->where(['bloque_id' => $bloqueId])
        // ->orderBy('semana_numero')
        // ->all();
        // // toma el dato semanal por defecto para presentar la semana
        // if(isset($_GET['semana_defecto'])){
        //     $semanaDefectoId = $_GET['semana_defecto'];
        // }else{
        //     $semanaDefectoId = $semanas[0]->id;
        // }

        // $semana = ScholarisBloqueSemanas::findOne($semanaDefectoId);
        
        // //Ingresa las horas del plan semanal, según el horario de la clase configurada
        // new PsIndividual($claseId, $semanaDefectoId);
                        
        // // Devuelve el plan semanal
        // $planSemanal = PlanificacionSemanal::find()->where([
        //     'clase_id' => $claseId,
        //     'semana_id' => $semanaDefectoId
        // ])
        // ->orderBy('orden_hora_semana')
        // ->all();
        // echo '<pre>';
        // print_r($_GET);
        // die();

     }



    public function actionIndex1()
    {

        // echo '<pre>';
        // print_r($_GET);
        // die();

        $bloqueId = $_GET['bloque_id'];
        $claseId = $_GET['clase_id'];
        
        $bloque = ScholarisBloqueActividad::findOne($bloqueId);
        $clase = ScholarisClase::findOne($claseId);

        // $insumos = ScholarisActividad::find()
        //     ->where(['plan_semanal_id'=> $planificacionSemanalId])
        //     ->all();

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

        $semana = ScholarisBloqueSemanas::findOne($semanaDefectoId);
        
        //Ingresa las horas del plan semanal, según el horario de la clase configurada
        new PsIndividual($claseId, $semanaDefectoId);
                        
        // Devuelve el plan semanal
        $planSemanal = PlanificacionSemanal::find()->where([
            'clase_id' => $claseId,
            'semana_id' => $semanaDefectoId
        ])
        ->orderBy('orden_hora_semana')
        ->all();
        // echo '<pre>';
        // print_r($planSemanal);
        // die();


        // renderiza la vista
        return $this->render('index', [
            'clase'     => $clase,
            'bloque'    => $bloque,
            'semanas'   => $semanas,
            'semana'    => $semana,
            'planSemanal' => $planSemanal
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
    public function actionUpdate()
    {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $model = $this->findModel($id);
        }        

        if($_POST){
            $user = Yii::$app->user->identity->usuario;
            $today = date('Y-m-d H:i:s');

            $id = $_POST['id'];
            $model = $this->findModel($id);
            $model->tema        = $_POST['tema'];
            $model->actividades = $_POST['actividades'];
            $model->diferenciacion_nee = $_POST['diferenciacion_nee'];
            $model->recursos    = $_POST['recursos'];
            $model->updated     = $user;
            $model->updated_at  = $today;
            $model->save();
            
            return $this->redirect(['index1',
                'clase_id' => $model->clase_id,
                'bloque_id' => $model->semana->bloque->id
            ]);
        }
        

        return $this->render('update', [
            'model' => $model
        ]);
    }


    public function actionTasks(){
        // print_r($_GET);
        $id = $_GET['id'];
        
        $semana = PlanificacionSemanal::findOne($id); //Para datos de la semana, bloque y clase
        $seccion = $semana->clase->paralelo->course->section0->code;

        // echo $seccion;
        // die();
        if($seccion <> 'PAI'){
            $tipoActividades = ScholarisTipoActividad::find()
            ->where([
                        'tipo' => 'N',
                        'activo' => true
                    ])
            ->orderBy('orden')
            ->all();
        }else{
            $tipoActividades = ScholarisTipoActividad::find()
            ->where([
                        'activo' => true
                    ])
            ->orderBy('orden')
            ->all();
        }

        $listActividades = ArrayHelper::map($tipoActividades, 'id', 'nombre_nacional');
        
        $model = new ScholarisActividad(); // Modelo para el formulario

        if ($model->load(Yii::$app->request->post())) {
            
            $tipoAct = ScholarisTipoActividad::findOne($model->tipo_actividad_id);

            $model->descripcion        = 'none';
            $model->inicio              = $semana->fecha;
            $model->fin                 = $semana->fecha;
            $model->bloque_actividad_id = $semana->semana->bloque->id;
            $model->es_aprobado        = false;
            $model->paralelo_id         = $semana->clase_id;
            $model->calificado          = true;
            $model->tipo_calificacion   = $tipoAct->tipo;
            $model->tareas              = 'none';
            $model->hora_id             = $semana->hora_id;
            $model->semana_id           = $semana->id;


            $this->insert_actividad($semana, $model, $tipoAct);
            
            return $this->redirect(['index1', 'clase_id' => $semana->clase_id, 'bloque_id' => $semana->semana->bloque->id]);
        }


        return $this->render('tasks', [
            'semana' => $semana,
            'model' => $model,
            'tipoActividades' => $listActividades
        ]);

    }

    private function insert_actividad($semana, $model, $tipoAct){
        $bloqueId = $semana->semana->bloque->id;
        $claseId = $semana->clase_id;
        $today = date('Y-m-d H:i:s');

        $con = Yii::$app->db;
        $query ="INSERT INTO scholaris_actividad
        (create_date,  create_uid, title, descripcion, inicio, fin, 
                tipo_actividad_id, bloque_actividad_id, paralelo_id, materia_id, calificado, tipo_calificacion, tareas
                , hora_id, actividad_original, semana_id, momento_detalle, con_nee, grado_nee, observacion_nee, destreza_id, formativa_sumativa
                , videoconfecia, respaldo_videoconferencia, link_aula_virtual, es_aprobado, fecha_revision
                , usuario_revisa, comentario_revisa, respuesta_revisa, lms_actvidad_id, es_heredado_lms, estado, plan_semanal_id)
        VALUES('$today', 15488,  '$model->title', 'none', '$model->inicio', '$model->fin', 
                $tipoAct->id, $bloqueId, $claseId, NULL, 'true', '$model->tipo_calificacion', 'none', 
                $model->hora_id, NULL, $model->semana_id, NULL, NULL, NULL, NULL, NULL, NULL, 
                NULL, NULL, NULL, true, NULL, 
                NULL, NULL, NULL,NULL, true, false, $model->plan_semanal_id);";

        // echo $query;
        // die();
        $con->createCommand($query)->execute();
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
