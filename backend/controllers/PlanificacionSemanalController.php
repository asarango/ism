<?php

namespace backend\controllers;

use backend\models\diploma\PdfPlanSemana;
use backend\models\plansemanal\PsIndividual;
use backend\models\ScholarisActividad;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisBloqueSemanas;
use backend\models\ScholarisClase;
use backend\models\ScholarisTipoActividad;
use Yii;
use backend\models\PlanificacionSemanal;
use backend\models\PlanificacionSemanalSearch;
use backend\models\plansemanal\CopyPlanSemanal;
use backend\models\ScholarisParametrosOpciones;
use PharIo\Manifest\CopyrightElement;
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
        
        $bloqueId = $_GET['bloque_id'];
        $claseId = $_GET['clase_id'];
        $pudOrigen = $_GET['pud_origen'];
        $idOrigen = $_GET['plan_bloque_unidad_id'];
        
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
        

        // renderiza la vista
        return $this->render('index', [
            'clase'     => $clase,
            'bloque'    => $bloque,
            'semanas'   => $semanas,
            'semana'    => $semana,
            'planSemanal' => $planSemanal,
            'pud_origen' => $pudOrigen,
            'plan_bloque_unidad_id' => $idOrigen
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
            $pudOrigen = $_GET['pud_origen'];
            $planOrigenId = $_GET['plan_bloque_unidad_id'];

            $id = $_GET['id'];
            $model = $this->findModel($id);
        }        

        if($_POST){
            $user = Yii::$app->user->identity->usuario;
            $today = date('Y-m-d H:i:s');

            $id = $_POST['id'];
            
            $pudOrigen = $_POST['pud_origen'];
            $planOrigenId = $_POST['plan_bloque_unidad_id'];

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
                'bloque_id' => $model->semana->bloque->id,
                'pud_origen' => $pudOrigen,
                'plan_bloque_unidad_id' => $planOrigenId
            ]);
            
        }

        return $this->render('update', [
            'model' => $model,
            'pud_origen' => $pudOrigen,
            'plan_bloque_unidad_id' => $planOrigenId
        ]);
    }


    public function actionTasks(){
        // print_r($_GET);
        $id = $_GET['id'];
        
        $semana = PlanificacionSemanal::findOne($id); //Para datos de la semana, bloque y clase
        $seccion = $semana->clase->paralelo->course->section0->code;

        $ods = $this->get_ods($id);

        if($seccion <> 'PAI'){
            $tipoActividades = $tipoActividades = $this->get_tipos_actividad_nacional();
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

            $this->insert_actividad($semana, $model, $tipoAct);
            
            return $this->redirect(['index1', 'clase_id' => $semana->clase_id, 'bloque_id' => $semana->semana->bloque->id]);
        }


        return $this->render('tasks', [
            'semana' => $semana,
            'model' => $model,
            'tipoActividades' => $listActividades,
            'ods' => $ods
        ]);

    }


    private function get_tipos_actividad_nacional(){
        $con = Yii::$app->db;
        $query = "select id
                            ,concat(nombre_nacional, ' ( ', categoria, ' / ', tipo_aporte, ' )') as nombre_nacional 
                    from 	scholaris_tipo_actividad
                    where 	activo = true and tipo = 'N'
                    order by orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
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
    }

     /**
     * MÉTODO PARA REALIZAR LA COPIA DE LOS PLANES SEMANALES
     */
    public function actionCopy(){
        $usuario  = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;

        $claseId = $_GET['clase_id'];
        $semana_id = $_GET['semana_id'];  
        $bloque_id = $_GET['bloque_id'];
        $pudOrigen = $_GET['pud_origen'];
        $pudOrigenId = $_GET['plan_bloque_unidad_id'];


        $urlPdf = ScholarisParametrosOpciones::find()
                    ->where(['codigo'=> 'rutaspdfplanes'])
                    ->one()->valor;
        $clase = ScholarisClase::findOne($claseId);
        $clases = $this->get_clases($clase->ism_area_materia_id, $clase->paralelo->course_id, $semana_id, $urlPdf,$usuario, $periodoId, $pudOrigenId, $pudOrigen);       
                    
        
        // $semana = ScholarisBloqueSemanas::findOne($semana_defecto);
        // $semanas = $this->get_semanas($bloque->clase->ism_area_materia_id, $bloque->clase->paralelo->course_id);

        return $this->render('copy', [
            'clase' => $clase,
            'clases' => $clases,
            'urlPdf' => $urlPdf,
            'semana_id' => $semana_id,
            'bloque_id' => $bloque_id,
            'plan_bloque_unidad_id' => $pudOrigenId,
            'pud_origen' => $pudOrigen

        ]);
    }

    private function get_clases($ismAreaMateriaId, $cursoId,$semanaId, $urlPdf, $usuario, $periodoId, $origenId, $pudOrigen){

        $con = Yii::$app->db;
        $query = "select 	cla.id as clase_id
                    ,cur.name as curso
                    ,par.name as paralelo
                    ,cur.x_institute 
                    ,cla.idprofesor 
                    ,concat(fac.x_first_name, ' ', fac.last_name) as docente 
                    ,concat('$urlPdf','planificacion-semanal/pdf?clase_id=', cla.id, '&semana_id=', $semanaId, 
                        '&usuario=', '$usuario', 
                        '&periodo_id=', $periodoId,
                        '&pud_origen=', '$pudOrigen',
                        '&plan_bloque_unidad_id=', $origenId) as url
            from 	scholaris_clase cla
                    inner join op_course_paralelo par on par.id = cla.paralelo_id 
                    inner join op_course cur on cur.id = par.course_id 
                    inner join op_faculty fac on fac.id = cla.idprofesor 
            where 	cla.ism_area_materia_id = $ismAreaMateriaId
                    and par.course_id = $cursoId
            order by par.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    } 

    public function actionExecuteCopy(){
        $claseIdDesde = $_GET['clase_desde'];
        $claseIdHasta  = $_GET['clase_hasta'];
        $semanaId = $_GET['semana_id'];
        $bloqueId = $_GET['bloque_id'];
        $pudOrigen = $_GET['pud_origen'];
        $pudOrigenId = $_GET['plan_bloque_unidad_id'];


        new CopyPlanSemanal($claseIdDesde, $claseIdHasta, $semanaId);
        
        return $this->redirect(['index1', 
            'bloque_id' => $bloqueId,
            'clase_id' => $claseIdHasta, 
            'semana_id' => $semanaId,
            'pud_origen' => $pudOrigen,
            'plan_bloque_unidad_id' => $pudOrigenId
        ]);                
    }

    /**
     * Método para realizar el PDF de Plan Semanal
     */
    public function actionPdf(){
        $claseId = $_GET['clase_id'];
        $semanaId = $_GET['semana_id'];
        $usuario =  $_GET['usuario'];
        $periodoId =  $_GET['periodo_id'];

        new PdfPlanSemana($claseId, $semanaId, $usuario, $periodoId);
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
