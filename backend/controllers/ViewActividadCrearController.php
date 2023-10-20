<?php

namespace backend\controllers;

use backend\models\PlanificacionSemanal;
use backend\models\ScholarisActividad;
use backend\models\ScholarisTipoActividad;
use Yii;
use backend\models\ViewActividadCrear;
use backend\models\ViewActividadCrearSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ViewActividadCrearController implements the CRUD actions for ViewActividadCrear model.
 */
class ViewActividadCrearController extends Controller
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
     * Lists all ViewActividadCrear models.
     * @return mixed
     */
    public function actionIndex1()
    {
        $userLog = Yii::$app->user->identity->usuario;
        $searchModel = new ViewActividadCrearSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $userLog);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ViewActividadCrear model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($actividad_id)
    {

        $model = ScholarisActividad::findOne($actividad_id);

       
        return $this->render('view', [
            'model' => $model
        ]);
    }


    




    /**
     * Creates a new ViewActividadCrear model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $planSemanalId = $_GET['plan_id'];
        $model = new ScholarisActividad();


        $tipoActividades = ScholarisTipoActividad::find()
            ->select(["id", "CONCAT(nombre_nacional, ' (', tipo_aporte, ')') as nombre_nacional"])
            ->where([
                        'activo' => true
                    ])
            ->orderBy('orden')
            ->all();   

        $listaActividades = ArrayHelper::map($tipoActividades, 'id', 'nombre_nacional');
        $ods = $this->get_ods($planSemanalId);
        $semana = PlanificacionSemanal::findOne($planSemanalId); //Para datos de la semana, bloque y clase


        if ($model->load(Yii::$app->request->post())) {

            $tipoAct = ScholarisTipoActividad::findOne($model->tipo_actividad_id);

            $model->descripcion        = 'none';
            $model->inicio              = $_POST['inicio'];
            $model->fin                 = $_POST['fin'];
            $model->bloque_actividad_id = $semana->semana->bloque->id;
            $model->es_aprobado        = false;
            $model->paralelo_id         = $semana->clase_id;
            $model->calificado          = true;
            $model->tipo_calificacion   = $tipoAct->tipo;
            $model->tareas              = 'none';
            $model->hora_id             = $semana->hora_id;
            $model->semana_id           = $semana->semana_id;

            $nuevoId = $this->insert_actividad($semana, $model, $tipoAct);
            return $this->redirect(['scholaris-actividad/actividad', 'actividad' => $nuevoId]);
        }

        return $this->render('create', [
            'planSemanalId' => $planSemanalId,
            'model' => $model,
            'listaActividades' => $listaActividades,
            'ods' => $ods
        ]);
    }

    private function insert_actividad($semana, $model, $tipoAct){
        $bloqueId = $semana->semana->bloque->id;
        $claseId = $semana->clase_id;
        $today = date('Y-m-d H:i:s');

        if(!$model->ods_pud_dip_id){
            $ods = 0;
        }else{
            $ods = $model->ods_pud_dip_id;
        }

        $con = Yii::$app->db;
        $query ="INSERT INTO scholaris_actividad
        (create_date,  create_uid, title, descripcion, inicio, fin, 
                tipo_actividad_id, bloque_actividad_id, paralelo_id, materia_id, calificado, tipo_calificacion, tareas
                , hora_id, actividad_original, semana_id, momento_detalle, con_nee, grado_nee, observacion_nee, destreza_id, formativa_sumativa
                , videoconfecia, respaldo_videoconferencia, link_aula_virtual, es_aprobado, fecha_revision
                , usuario_revisa, comentario_revisa, respuesta_revisa, lms_actvidad_id, es_heredado_lms, estado, plan_semanal_id
                , ods_pud_dip_id)
        VALUES('$today', 15488,  '$model->title', 'none', '$model->inicio', '$model->fin', 
                $tipoAct->id, $bloqueId, $claseId, NULL, 'true', '$model->tipo_calificacion', 'none', 
                $model->hora_id, NULL, $model->semana_id, NULL, NULL, NULL, NULL, NULL, NULL, 
                NULL, NULL, NULL, true, NULL, 
                NULL, NULL, NULL,NULL, true, false, $model->plan_semanal_id, $ods);";

        $con->createCommand($query)->execute();
        return Yii::$app->db->getLastInsertID();
    }


    private function get_ods($planificacionSemanalId){
        $con = Yii::$app->db;
        $query = "select 	pud.opcion_texto, pud.id as ods_pud_dip_id
                    from planificacion_semanal pse
                    inner join scholaris_bloque_semanas sem on sem.id = pse.semana_id 
                    inner join scholaris_bloque_actividad blo on blo.id = sem.bloque_id 
                    inner join curriculo_mec_bloque cbl on cbl.shot_name = blo.abreviatura 
                    inner join scholaris_clase cla  on cla.id = pse.clase_id 
                    inner join planificacion_bloques_unidad bun on bun.curriculo_bloque_id = cbl.id
                    inner join planificacion_desagregacion_cabecera cab on cab.id = bun.plan_cabecera_id 
                    and cla.ism_area_materia_id = cab.ism_area_materia_id 
                    inner join pud_dip pud  on pud.planificacion_bloques_unidad_id =  bun.id 
            where 	pse.id = $planificacionSemanalId
                    and pud.codigo =  'ODS'
                    and pud.opcion_boolean;";

        $res = $con->createCommand($query)->queryAll();
        return $res;

    }



    /**
     * Updates an existing ViewActividadCrear model.
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
     * Deletes an existing ViewActividadCrear model.
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
     * Finds the ViewActividadCrear model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ViewActividadCrear the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ViewActividadCrear::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
