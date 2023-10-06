<?php

namespace backend\controllers;

use backend\models\Lms;
use backend\models\LmsActividad;
use backend\models\LmsActividadXArchivo;
use backend\models\messages\Messages;
use backend\models\notas\RegistraNotas;
use backend\models\notas\RegistraNotasV1;
use backend\models\PlanificacionSemanal;
use backend\models\ResUsers;
use backend\models\ScholarisActividadDescriptor;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisBloqueSemanas;
use backend\models\ScholarisCalificaciones;
use backend\models\ScholarisClase;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisParametrosOpciones;
use backend\models\ScholarisResumenParciales;
use backend\models\ScholarisTipoActividad;
use backend\models\ScholarisActividad;
use backend\models\ScholarisActividadSearch;
use backend\models\ScholarisArchivosprofesor;
use backend\models\ScholarisGrupoOrdenCalificacion;
use backend\models\ScholarisHorariov2Detalle;
use backend\models\ScholarisPeriodo;
use backend\models\SentenciasSql;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ScholarisActividadController implements the CRUD actions for ScholarisActividad model.
 */
class ScholarisActividadController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->identity) {

            //OBTENGO LA OPERACION ACTUAL
            list($controlador, $action) = explode("/", Yii::$app->controller->route);
            $operacion_actual = $controlador . "-" . $action;
            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
                echo $this->render('/site/error', [
                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
                    'name' => 'Acceso denegado!!',
                ]);
            }
        } else {
            header("Location:" . \yii\helpers\Url::to(['site/login']));
            exit();
        }
        return true;
    }

    /**
     * Lists all ScholarisActividad models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScholarisActividadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScholarisActividad model.
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
     * Creates a new ScholarisActividad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $userLog    = Yii::$app->user->identity->usuario;
        $periodoId  = Yii::$app->user->identity->periodo_id;
        $periodo    = ScholarisPeriodo::findOne($periodoId);

        $trimestres = ScholarisBloqueActividad::find()->where([
            'scholaris_periodo_codigo' => $periodo->codigo
        ])
        ->orderBy('orden')
        ->all();

        // $modelBloques = ScholarisBloqueActividad::find()
        //     ->where(['id' => $bloqueId])
        //     ->one();

        // $estado = $this->estado($modelBloques->hasta);
        // $modelClase = ScholarisClase::find()->where(['id' => $claseId])->one();
        // //Toma las semanas del bloque
        // $weeks = ScholarisBloqueSemanas::find()->where(['bloque_id' => $bloqueId])
        //     ->orderBy('semana_numero')
        //     ->all();

        // $sentencias = new SentenciasSql();
        // $modelHorarios = $sentencias->fechasDisponibles($modelBloques->bloque_inicia, $modelBloques->bloque_finaliza, $claseId, $bloqueId);

        // //si existe la semana renderiza a la misma vista, pero con los datos de la semana
        // if (isset($_GET['week_id'])) {

        //     $detailWeek = $this->get_detail_week($_GET['week_id'], $claseId);
        //     return $this->render('create', [
        //         'modelClase' => $modelClase,
        //         'modelHorarios' => $modelHorarios,
        //         'estado' => $estado,
        //         'bloqueId' => $bloqueId,
        //         'weeks' => $weeks,
        //         'detailWeek' => $detailWeek
        //     ]);
        // }

        // return $this->render('create', [
        //     'modelClase' => $modelClase,
        //     'modelHorarios' => $modelHorarios,
        //     'estado' => $estado,
        //     'bloqueId' => $bloqueId,
        //     'weeks' => $weeks,
        //     'calificado' => $calificado
        // ]);

        return $this->render('create',[
            'trimestres' => $trimestres,
        ]);
    }


    private function get_plan_semanal($docente){

    }


    private function get_detail_week($weekId, $classId)
    {

        $data = array();


        //$modelClase = ScholarisClase::findOne($classId); 
        $modelSemana = ScholarisBloqueSemanas::findOne($weekId);

        $bloqueId = $modelSemana->bloque_id;

        $sentencias = new SentenciasSql();

        $fechasDisponibles = $sentencias->fechasDisponiblesSemana($modelSemana->fecha_inicio, $modelSemana->fecha_finaliza, $classId, $bloqueId, $weekId);

        $disponibilidad = array();


        foreach ($fechasDisponibles as $dispo) {
            $totalActividades = $this->get_cantidad_actividades($dispo['fecha'], $classId);
            $dispo['total_actividades'] = $totalActividades;

            array_push($disponibilidad, $dispo);
        }

        $data = array(
            'week'              => $modelSemana,
            'disponibilidad'    => $disponibilidad
        );

        return $data;
    }


    private function get_cantidad_actividades($fecha, $claseId)
    {
        $periodoId = Yii::$app->user->identity->periodo_id;

        $con = Yii::$app->db;
        $query = "select 	sum(total_actividades) as total_actividades 
                    from 	dw_total_actividades_paralelo
                    where	paralelo_id in (
                    select 	cla.paralelo_id
                    from 	scholaris_grupo_alumno_clase gru
                            inner join scholaris_clase cla on cla.id = gru.clase_id 
                            inner join scholaris_periodo per on per.codigo = cla.periodo_scholaris
                            inner join op_course cur on cur.id = cla.idcurso 
                    where 	per.id = $periodoId
                            and gru.estudiante_id in (
                                                            select 	 estudiante_id 										
                                                from	scholaris_grupo_alumno_clase g
                                                        inner join scholaris_clase c on c.id = g.clase_id 
                                                where 	clase_id = $claseId
                            )
                    group by cla.paralelo_id, cur.name
                    ) and fecha_presentacion >= '$fecha' and fecha_presentacion <= '$fecha';";

        $res = $con->createCommand($query)->queryOne();

        isset($res['total_actividades']) ? $total = $res['total_actividades'] : $total = 0;
        return $total;
    }


    public function actionCrear1($clase, $fecha, $bloqueId, $tipo, $semana)
    {
        $fecha = $fecha . ' 23:59:59';
        $modelClase = ScholarisClase::find()->where(['id' => $clase])->one();

        $horas = $this->horas($fecha, $clase);

        if ($semana == 'NA') {
            $modelSemana = 0;
        } else {
            $modelSemana = ScholarisBloqueSemanas::find()
                ->where(['bloque_id' => $bloqueId, 'nombre_semana' => $semana])
                ->one();
        }

        $model = new \backend\models\ScholarisActividad();

        if ($tipo == 'P') {
            $modelInsumo = ScholarisTipoActividad::find()
                ->where(['in', 'nombre_pai', ['SUMATIVA', 'FORMATIVA']])
                ->andWhere(['activo' => true])
                ->orderBy('orden')
                ->all();
        } else {
            $modelInsumo = ScholarisTipoActividad::find()
                ->where(['not in', 'nombre_pai', ['SUMATIVA', 'FORMATIVA']])
                ->andWhere(['activo' => true])
                ->orderBy('orden')
                ->all();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['actividad', 'actividad' => $model->id]);
        }


        $modelAulas = ScholarisActividad::find()
            ->select(['link_aula_virtual'])
            ->where(['paralelo_id' => $clase])
            ->andWhere(['not', ['link_aula_virtual' => null]])
            ->groupBy(['link_aula_virtual'])
            ->one();

        $modelVideoConf = ScholarisActividad::find()
            ->select(['videoconfecia'])
            ->where(['paralelo_id' => $clase])
            ->andWhere(['not', ['videoconfecia' => null]])
            ->groupBy(['videoconfecia'])
            //                 ->orderBy(['id' => SORT_DESC])
            ->limit(5)
            ->all();

        return $this->render('_formcreate', [
            'model' => $model,
            'modelClase' => $modelClase,
            'bloque' => $bloqueId,
            'inicio' => $fecha,
            'modelInsumo' => $modelInsumo,
            'horas' => $horas,
            'modelSemana' => $modelSemana,
            'tipo' => $tipo,
            'modelAulas' => $modelAulas,
            'modelVideoConf' => $modelVideoConf
        ]);
    }

    /**
     * Updates an existing ScholarisActividad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $sentencias = new \backend\models\SentenciasNotas();
        $model = $this->findModel($id);
        $modelCalificaciones = ScholarisCalificaciones::find()
            ->where(['idactividad' => $model->id])
            ->all();

        $horas = $this->horas($model->inicio, $model->paralelo_id);
        if ($model->load(Yii::$app->request->post())) {

            $model->fin = $model->inicio;
            $modelCalif = ScholarisCalificaciones::find()
                ->where(['idactividad' => $model->id])
                ->all();
            if ($modelCalif) {
                $modelGrupoCalif = \backend\models\ScholarisGrupoOrdenCalificacion::find()
                    ->where(['codigo_tipo_actividad' => $model->tipo_actividad_id])
                    ->one();

                $model->save();
                $sentencias->cambia_grupo_actividad($id, $modelGrupoCalif->grupo_numero, $model->tipo_actividad_id);
            } else {
                $model->save();
            }
            return $this->redirect(['actividad', 'actividad' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'modelCalificaciones' => $modelCalificaciones,
            'horas' => $horas
        ]);
    }

    /**
     * Deletes an existing ScholarisActividad model.
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
     * Finds the ScholarisActividad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisActividad::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Administra la edición de una actividad
     * @param integer $id
     * @return ScholarisActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * MÉTODO ACTUALIZADO POR Arturo Sarango el 2023-05-15
     * (se coloca información del pequeño LMS)
     */
    public function actionActividad($actividad)
    {
        $sentencia = new \backend\models\SentenciasNotas();
        $modelActividad = \backend\models\ScholarisActividad::find()
            ->where(['id' => $actividad])
            ->one();

        $modelCalificaciones = ScholarisCalificaciones::find()
            ->where(['idactividad' => $actividad])
            ->andWhere(['not', ['calificacion' => null]])
            ->all();

        $sentencias = new SentenciasSql();
        $modelCalificaciones2 = ScholarisCalificaciones::find()
            ->where(['idactividad' => $actividad])
            ->all();
        //Trae los descriptores que estan asignados a esta actividad y los que no estan asigandos
        $asignados = $sentencias->criteriosAsignados($modelActividad->bloque_actividad_id, $modelActividad->id);
        $noAsignados = $sentencias->criteriosNoAsignados($modelActividad->bloque_actividad_id, $modelActividad->id);
        //Revisa atraves de la fecha del bloque si puede o no agregar o quitar descriptores
        $estado = $this->estado($modelActividad->bloque->hasta);
        //tiene la relacion entre la actividad y los descriptores
        $modelCriterios = ScholarisActividadDescriptor::find()
            ->where(['actividad_id' => $actividad])
            ->all();

        //Busca los adjuntos a las tareas por actividad
        $modelArchivos = \backend\models\ScholarisArchivosprofesor::find()->where(['idactividad' => $actividad])->all();

        $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()
            ->innerJoin("op_student_inscription i", "i.student_id = scholaris_grupo_alumno_clase.estudiante_id")
            ->where([
                'clase_id' => $modelActividad->paralelo_id,
                'i.inscription_state' => 'M'
            ])
            ->all();

        //Cuenta el Numero de criterios que estan calificados  asociados a una actividad            
        $modelTotalCali = $sentencia->toma_total_calificados($actividad, count($modelGrupo));
        //Cuenta el numero de calificacion con valor nulo, asociadas a una actividad
        $modelTotalCalificados = $sentencia->toma_total_calificados_con_nulos($actividad);

        if ($modelTotalCalificados == 0) {
            $totalCalificaciones = count($modelGrupo);
        } else {
            $totalCalificaciones = $modelTotalCalificados;
        }

        $modelTotal = $modelTotalCali - $modelTotalCalificados;

        $estadisticas = $this->get_valores_estadisticos($modelActividad->paralelo_id);

        $model = new ScholarisArchivosprofesor();
        //ingresa en este IF, para generar el update del registro de tareas en una actividad 
        //                                               1 = update      
        if (isset($_GET['bandera']) && $_GET['bandera'] == 1) {
            $idMatApoyo = $_GET['idMatApoyo'];
            $model = ScholarisArchivosprofesor::findOne($idMatApoyo);
        }
        //ingresa en este IF, para eliminar la tarea asociada a una actividad 
        //                                              2 = delete        
        if (isset($_GET['bandera']) && $_GET['bandera'] == 2) {
            $idMatApoyo = $_GET['idMatApoyo'];
            $model = ScholarisArchivosprofesor::findOne($idMatApoyo);
            if (isset($model)) {
                $model->delete();
            }
            $model = new ScholarisArchivosprofesor();
        }

        /***
         * envia los datos del tipo LMS pequeño
         */
        // $section = $modelActividad->clase->paralelo->course->section0->code;
        // if ($section != "DIPL") {
            $lmsActividad = LmsActividad::findOne($modelActividad->lms_actvidad_id);
            $materialApoyo = LmsActividadXArchivo::find()->where(['lms_actividad_id' => $modelActividad->lms_actvidad_id])->all();
        // }else{
            // echo('lms de diploma');
        // }

        /**************************** */

        $clasesParaCopiar = $this->get_clases_para_copiar($modelActividad->clase->paralelo->course->id, 
                                                          $modelActividad->clase->ism_area_materia_id, 
                                                          $modelActividad->paralelo_id, 
                                                          $modelActividad->id);


        if ($modelActividad->tipo_calificacion == 'P') {
            return $this->render('actividad', [
                'modelActividad' => $modelActividad,
                'estado' => $estado,
                'modelCriterios' => $modelCriterios,
                'modelCalificaciones' => $modelCalificaciones,
                'modelArchivos' => $modelArchivos,
                'modelTotal' => $modelTotal,
                'estadisticas' => $estadisticas,
                'modelCalificaciones2' => $modelCalificaciones2,
                'noAsignados' => $noAsignados,
                'asignados' => $asignados,
                'model' =>  $model,
                'lmsActividad' => $lmsActividad,
                'materialApoyo' => $materialApoyo,
                'clasesParaCopiar' => $clasesParaCopiar
            ]);
        } else {

            return $this->render('actividad', [
                'modelActividad' => $modelActividad,
                'estado' => $estado,
                'modelCriterios' => $modelCriterios,
                'modelCalificaciones' => $modelCalificaciones,
                'modelArchivos' => $modelArchivos,
                'modelTotal' => $modelTotal,
                'modelCalificaciones2' => $modelCalificaciones2,
                'noAsignados' => $noAsignados,
                'asignados' => $asignados,
                'model' =>  $model,
                'lmsActividad' => $lmsActividad,
                'materialApoyo' => $materialApoyo,
                'clasesParaCopiar' => $clasesParaCopiar
            ]);
        }
    }



    private function get_clases_para_copiar($cursoId, $ismAreaMateriaId, $claseId, $actividadId){
        $con = Yii::$app->db;
        $query = "select 	cla.id  
                            ,cur.name as curso
                            ,par.name as paralelo
                            ,concat(fac.x_first_name, ' ', fac.middle_name, ' ', fac.last_name) as docente 
                            ,(
                                select 	actividad_original 
                                from 	scholaris_actividad 
                                where 	actividad_original = $actividadId
                                        and paralelo_id = cla.id 
                            )
                    from 	scholaris_clase cla
                            inner join op_course_paralelo par on par.id = cla.paralelo_id 
                            inner join op_course cur on cur.id = par.course_id 
                            inner join op_faculty fac on fac.id = cla.idprofesor 
                    where 	cur.id = $cursoId
                            and cla.ism_area_materia_id = $ismAreaMateriaId
                            and cla.id <> $claseId
                    order by par.name;";

    
        return $con->createCommand($query)->queryAll();
    }


    public function actionCopiarActividad(){
        print_r($_GET);
        
        $claseOriginalId = $_GET['clase_original_id'];
        $claseDestinoId = $_GET['clase_destino_id'];
        $actividadId = $_GET['actividad_id'];

        $actividadOrigen = ScholarisActividad::findOne($actividadId);
        $planSemanalOrigen = PlanificacionSemanal::findOne($actividadOrigen->plan_semanal_id);
        
        $planSemanalDestino = PlanificacionSemanal::find()->where([
                'semana_id' => $planSemanalOrigen->semana_id,
                'clase_id' => $claseDestinoId,
                'orden_hora_semana' => $planSemanalOrigen->orden_hora_semana,
            ])->one();

        if($planSemanalDestino != null){
            $nuevoId = $this->clonar_actividad($actividadId);

            $model = ScholarisActividad::findOne($nuevoId);
            $model->paralelo_id = $claseDestinoId;
            $model->actividad_original = $actividadId;
            $model->plan_semanal_id = $planSemanalDestino->id;
            $model->hora_id = $planSemanalDestino->hora_id;
            $model->save();

            return $this->redirect(['actividad', 'actividad' => $actividadId]);
        }else{
            return $this->render('error');
        }
        
    }



    private function clonar_actividad($actividadId){
        $con = Yii::$app->db;
        $query = "insert into scholaris_actividad (create_date, write_date, create_uid, write_uid, title, descripcion, archivo, descripcion_archivo, color, inicio, fin, tipo_actividad_id, bloque_actividad_id, a_peso, b_peso, c_peso, d_peso, paralelo_id, materia_id, calificado, tipo_calificacion, tareas, hora_id, actividad_original, semana_id, momento_detalle, con_nee, grado_nee, observacion_nee, destreza_id, formativa_sumativa, videoconfecia, respaldo_videoconferencia, link_aula_virtual, es_aprobado, fecha_revision, usuario_revisa, comentario_revisa, respuesta_revisa, lms_actvidad_id, es_heredado_lms, estado, plan_semanal_id, ods_pud_dip_id )
        select 	create_date, write_date, create_uid, write_uid, title, descripcion, archivo, descripcion_archivo, color, inicio, fin, tipo_actividad_id, bloque_actividad_id, a_peso, b_peso, c_peso, d_peso, paralelo_id, materia_id, calificado, tipo_calificacion, tareas, hora_id, actividad_original, semana_id, momento_detalle, con_nee, grado_nee, observacion_nee, destreza_id, formativa_sumativa, videoconfecia, respaldo_videoconferencia, link_aula_virtual, es_aprobado, fecha_revision, usuario_revisa, comentario_revisa, respuesta_revisa, lms_actvidad_id, es_heredado_lms, estado, plan_semanal_id, ods_pud_dip_id  
        from 	scholaris_actividad where id = $actividadId;";

        $con->createCommand($query)->execute();
        return $con->getLastInsertID();

    }


    private function get_valores_estadisticos($claseId)
    {

        $con = Yii::$app->db;
        $queryCriterios = "select 	c.nombre as criterio
                            from 	ism_criterio c 
                            group by c.nombre
                            order by c.nombre";

        $criterios = $con->createCommand($queryCriterios)->queryAll();

        $queryCriUsados = "select 	curso, paralelo, docente, materia, clase_id, bloque_id, bloque, tipo_actividad, criterio, total 
                            from 	dw_estadisticas_criterios_pai
                            where 	clase_id = $claseId
                            order by bloque_id, criterio, tipo_actividad;";
        $criUsados = $con->createCommand($queryCriUsados)->queryAll();

        $queryParciales = "select 	b.id 
                                    ,b.abreviatura 
                            from 	scholaris_bloque_actividad b
                                    inner join scholaris_actividad a on a.bloque_actividad_id = b.id 
                            where 	a.paralelo_id = $claseId
                            group by b.id, b.abreviatura
                            order by b.orden;";
        $parciales = $con->createCommand($queryParciales)->queryAll();

        return array(
            'criterios' => $criterios,
            'criUsados' => $criUsados,
            'parciales' => $parciales
        );
    }



    public function actionDescargar($ruta)
    {
        $path = "../web/imagenes/instituto/archivos-profesor/";

        return \Yii::$app->response->sendFile($path . $ruta);


        //Si el archivo existe
        //     if (is_file($path))
        //     {
        //                 //Procedemos a descargar el archivo
        //                 // Definir headers
        //                 //$size = filesize($path);
        //                 header("Content-Type: application/force-download");
        //                 header("Content-Disposition: attachment; filename=$path");
        //                 header("Content-Transfer-Encoding: binary");
        //                 header("Content-Length: " . $path);
        //                 // Descargar archivo
        //                 readfile($path);
        //                 //Correcto
        //                 return true;
        // }
        //readfile($file);
    }

    /**
     * Toma el estado de un bloque
     * @param integer $id
     * @return ScholarisActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function estado($fechaHasta)
    {
        $fecha = date("Y-m-d H:i:m");

        if ($fecha > $fechaHasta) {
            $estado = 'cerrado';
        } else {
            $estado = 'abierto';
        }
        return $estado;
    }

    /**
     * Realiza la calificacion de las actividades
     * @param integer $id
     * @return ScholarisActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCalificar($id)
    {
        $con = Yii::$app->db;

        $sentencias = new \backend\models\SentenciasClase();

        /* crea los espacios para las calificaciones */
        $this->espaciosCalificacion($id);

        $modelActividad = \backend\models\ScholarisActividad::find()
            ->where(['id' => $id])
            ->one();

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelGrupo = $sentencias->get_alumnos_clase($modelActividad->paralelo_id, $periodoId);

        $modelMinimo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califminima'])
            ->one();

        $modelMaximo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califmmaxima'])
            ->one();

        if ($modelActividad->tipo_calificacion == 'P') {
            $modelCalificaciones = ScholarisCalificaciones::find()
                ->innerJoin('ism_criterio', 'ism_criterio.id = scholaris_calificaciones.criterio_id')
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->orderBy('ism_criterio.nombre', 'op_student.last_name', 'op_student.first_name', 'op_student.middle_name')
                ->all();

            $queryIdsCriterios = "select distinct id_criterio
                                from scholaris_actividad_descriptor s
                                inner join planificacion_vertical_pai_descriptores d on s.plan_vert_pai_descriptor_id = d.id 
                                inner join ism_criterio_descriptor_area i on  d.descriptor_id = i.id 
                                where s.actividad_id = $id;";

            $modelCriterios = $con->createCommand($queryIdsCriterios)->queryAll();

            $modelCalificarUnitario = ScholarisCalificaciones::find()
                //->select(['op_student.last_name','op_student.first_name','op_student.middle_name','scholaris_criterio.criterio'])
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->innerJoin('ism_criterio', 'ism_criterio.id = scholaris_calificaciones.criterio_id')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->andWhere(['is', 'scholaris_calificaciones.calificacion', null])
                ->orderBy([
                    'op_student.last_name' => SORT_ASC,
                    'op_student.first_name' => SORT_ASC,
                    'op_student.middle_name' => SORT_ASC,
                    'ism_criterio.nombre' => SORT_ASC,
                ])
                //,'op_student.first_name','op_student.middle_name','scholaris_criterio.criterio'
                ->limit(1)
                ->one();
        } else {

            /* * * aqui model de criterios para nacionales ** */

            $modelCalificaciones = ScholarisCalificaciones::find()
                ->where(['idactividad' => $id])
                ->all();

            $modelCalificarUnitario = ScholarisCalificaciones::find()
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->andWhere(['is', 'scholaris_calificaciones.calificacion', null])
                ->orderBy('op_student.last_name', 'scholaris_calificaciones.criterio_id')
                ->one();
        }

        /* para bloques */
        $modelBloques = ScholarisBloqueActividad::find()
            ->where(['id' => $modelActividad->bloque_actividad_id])
            ->one();


        $estado = $this->estado($modelBloques->hasta);


        if ($modelActividad->tipo_calificacion == 'P') {
            return $this->render('calificar', [
                'modelCalificaciones' => $modelCalificaciones,
                'modelActividad' => $modelActividad,
                'modelGrupo' => $modelGrupo,
                'modelCriterios' => $modelCriterios,
                'modelCalificarUnitario' => $modelCalificarUnitario,
                'modelMinimo' => $modelMinimo,
                'modelMaximo' => $modelMaximo,
                'estado' => $estado,
            ]);
        } else {

            return $this->render('calificar', [
                'modelCalificaciones' => $modelCalificaciones,
                'modelActividad' => $modelActividad,
                'modelGrupo' => $modelGrupo,
                'modelCalificarUnitario' => $modelCalificarUnitario,
                'modelMinimo' => $modelMinimo,
                'modelMaximo' => $modelMaximo,
                'estado' => $estado,
            ]);
        }
    }


    public function actionEstadisticas(){
        $actividadId = $_GET['actividad_id'];

        $calificaciones = ScholarisCalificaciones::find()->where(['idactividad' => $actividadId])->all();        

        $totalSobresalientes = 0;
        $totalRegulares = 0;
        $totalBajos = 0;
        $totalReportadosPadres = 0;

        foreach ($calificaciones as $calificacion) {
            if($calificacion->calificacion < 70){
                $totalBajos++;

                if($calificacion->aviso_padre_menos_70 == 1){
                    $totalReportadosPadres++;                    
                }

            }elseif($calificacion->calificacion >= 70 && $calificacion->calificacion < 95){
                $totalRegulares++;
            }else{
                $totalSobresalientes++;
            }
        }

        return $this->renderPartial('_estadisticas', [
            'sobresalientes' => $totalSobresalientes,
            'regulares' => $totalRegulares,
            'bajos' => $totalBajos,
            'reportadosPadres' => $totalReportadosPadres,
            'totalCalificaciones' => count($calificaciones),
            'actividadId' => $actividadId
        ]);
    }


    public function actionNotificarPadres(){
        $actividadId = $_GET['actividadId'];
        $noEnviados = $this->consulta_no_enviados_bajos($actividadId);

        foreach ($noEnviados as $noEnvio) {
            $to = [$noEnvio['padre']];
            // $to = ['desarrollo@ism.edu.ec'];
            $email = $this->construye_email($to, $noEnvio);
            $message = new Messages();
            $message->send_email($to, 'info@paxdem.com', 'Ups!!!. Nada de que preocuparse', '', $email);
            $model = ScholarisCalificaciones::findOne($noEnvio['id']);
            $model->aviso_padre_menos_70 = true;
            $model->save();
        }

        return $this->redirect(['calificar', 'id' => $actividadId]);
    
    }


    private function construye_email($arrayTo, $noEnvio){
        $html = '<br><b>Estimado/a representante</b><br><br>';
        $html .= "<br>Tú representado ha sacado una calificación por debajo del permitido.";
        $html .= "<br><br>";

        $html .= "<b>Asignatura: </b>".$noEnvio['materia'].'<br>';
        $html .= "<b>Insumo: </b>".$noEnvio['nombre_nacional'].'<br>';
        $html .= "<b>Actividad disponible desde: </b>".$noEnvio['inicio'].'<br>';
        $html .= "<b>Titulo de la actividad: </b>".$noEnvio['title'].'<br>';
        $html .= "<b>Calificación: </b>".$noEnvio['calificacion'].'<br>';

        $html .= "<br><br>";

        $html .= "<br>Pero nada esta perdido todavía, te aconsejamos realizar los siguientes pasos:<br><br>";

        $html .= "<ol>";
        $html .= "<li>Conversa con tu hijo/a, y comentale que solo fué un mal momento, que puede hacer una recuperación</li>";
        $html .= "<li>Comunícate con el docente de la asignatura, y pídele que te permita enviar un trabajo de recuperación</li>";
        $html .= "<li>Entreguen el trabajo de recuperación</li>";
        $html .= "<li>Y, listo la calificación aumentará </li>";
        $html .= "</ol>";

        $html .= "<br><br>";

        $html .= "<b>Saludos cordiales.</b>";
        $html .= "<br><br>";

        $html .= "Sistema automático de mensajería Edux";
        $html .= "<br><br>";


        $html .= "<b>¡¡¡No responder a este correo!!!</b>";

        return $html;

    }

    /**
     * Summary of consulta_no_enviados_bajos
     * @param mixed $actividadId
     * @return array
     */
    private function consulta_no_enviados_bajos($actividadId){
        $con = Yii::$app->db;
        $query = "select 	cal.id 
                            ,concat('f',os.x_codigo_relacion,'@ism.edu.ec') as padre
                            ,cal.calificacion 
                            ,tip.nombre_nacional
		                    ,act.title
                            ,act.inicio
                            ,mat.nombre as materia
                    from 	scholaris_calificaciones cal
                            inner join op_student os on os.id = cal.idalumno 
                            inner join op_parent_op_student_rel rel on rel.op_student_id = os.id 
                            inner join op_parent opa on opa.id = rel.op_parent_id 
                            inner join res_partner rpa on rpa.id = opa.name
                            inner join scholaris_actividad act on act.id = cal.idactividad
                            inner join scholaris_clase cla on cla.id = act.paralelo_id 
                            inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                            inner join ism_materia mat on mat.id = iam.materia_id 
                            inner join scholaris_tipo_actividad tip on tip.id = act.tipo_actividad_id 
                    where 	cal.calificacion < 70
                            and cal.idactividad = $actividadId
                            and (cal.aviso_padre_menos_70 <> true or cal.aviso_padre_menos_70 is null)
                    group by cal.id, 2, act.title, mat.nombre, tip.nombre_nacional,act.inicio;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



    public function actionAnularcalificaciones($id)
    {

        $sentencias = new \backend\models\SentenciasClase();

        /* crea los espacios para las calificaciones */
        $this->espaciosCalificacion($id);

        $modelActividad = \backend\models\ScholarisActividad::find()
            ->where(['id' => $id])
            ->one();

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        //        $modelGrupo = ScholarisGrupoAlumnoClase::find()
        //                // ->select(['op_student.id','op_student.last_name', 'op_student.first_name'])
        //                ->innerJoin('op_student', 'op_student.id = scholaris_grupo_alumno_clase.estudiante_id')
        //                ->innerJoin('op_student_inscription', 'op_student_inscription.student_id = op_student.id')
        //                ->innerJoin('scholaris_clase', 'scholaris_clase.id = scholaris_grupo_alumno_clase.clase_id')
        //                ->innerJoin('scholaris_op_period_periodo_scholaris', 'scholaris_op_period_periodo_scholaris.op_id = op_student_inscription.period_id')
        //                ->where([
        //                    'scholaris_grupo_alumno_clase.clase_id' => $modelActividad->paralelo_id,
        //                    'op_student_inscription.inscription_state' => 'M',
        //                    'scholaris_clase.periodo_scholaris' => $modelPeriodo->codigo
        //                ])
        //                ->orderBy('op_student.last_name', 'op_student.first_name')
        //                ->all();

        $modelGrupo = $sentencias->get_alumnos_clase($modelActividad->paralelo_id, $periodoId);

        $modelMinimo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califminima'])
            ->one();

        $modelMaximo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califmmaxima'])
            ->one();

        if ($modelActividad->tipo_calificacion == 'P') {
            $modelCalificaciones = ScholarisCalificaciones::find()
                ->innerJoin('scholaris_criterio', 'scholaris_criterio.id = scholaris_calificaciones.criterio_id')
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->orderBy('scholaris_criterio.criterio', 'op_student.last_name', 'op_student.first_name', 'op_student.middle_name')
                ->all();

            $modelCriterios = ScholarisActividadDescriptor::find()
                ->select(['criterio_id'])
                ->innerJoin('scholaris_criterio', 'scholaris_criterio.id = scholaris_actividad_descriptor.criterio_id')
                ->where(['actividad_id' => $id])
                ->groupBy(['criterio_id', 'scholaris_criterio.criterio'])
                ->orderBy('scholaris_criterio.criterio')
                ->all();

            $modelCalificarUnitario = ScholarisCalificaciones::find()
                //->select(['op_student.last_name','op_student.first_name','op_student.middle_name','scholaris_criterio.criterio'])
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->innerJoin('scholaris_criterio', 'scholaris_criterio.id = scholaris_calificaciones.criterio_id')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->andWhere(['is', 'scholaris_calificaciones.calificacion', null])
                ->orderBy([
                    'op_student.last_name' => SORT_ASC,
                    'op_student.first_name' => SORT_ASC,
                    'op_student.middle_name' => SORT_ASC,
                    'scholaris_criterio.criterio' => SORT_ASC,
                ])
                //,'op_student.first_name','op_student.middle_name','scholaris_criterio.criterio'
                ->limit(1)
                ->one();
        } else {

            /*             * * aqui model de criterios para nacionales ** */

            $modelCalificaciones = ScholarisCalificaciones::find()
                ->where(['idactividad' => $id])
                ->all();

            $modelCalificarUnitario = ScholarisCalificaciones::find()
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->andWhere(['is', 'scholaris_calificaciones.calificacion', null])
                ->orderBy('op_student.last_name', 'scholaris_calificaciones.criterio_id')
                ->one();
        }

        /* para bloques */
        $modelBloques = ScholarisBloqueActividad::find()
            ->where(['id' => $modelActividad->bloque_actividad_id])
            ->one();

        $fecha = date("Y-m-d");
        if ($fecha > $modelBloques->hasta) {
            $estado = 'cerrado';
        } else {
            $estado = 'abierto';
        }

        if ($modelActividad->tipo_calificacion == 'P') {
            return $this->render('anular', [
                'modelCalificaciones' => $modelCalificaciones,
                'modelActividad' => $modelActividad,
                'modelGrupo' => $modelGrupo,
                'modelCriterios' => $modelCriterios,
                'modelCalificarUnitario' => $modelCalificarUnitario,
                'modelMinimo' => $modelMinimo,
                'modelMaximo' => $modelMaximo,
                'estado' => $estado,
            ]);
        } else {
            return $this->render('anular', [
                'modelCalificaciones' => $modelCalificaciones,
                'modelActividad' => $modelActividad,
                'modelGrupo' => $modelGrupo,
                'modelCalificarUnitario' => $modelCalificarUnitario,
                'modelMinimo' => $modelMinimo,
                'modelMaximo' => $modelMaximo,
                'estado' => $estado,
            ]);
        }
    }

    /**
     * Metodo que realiza el llenado de espacios para la calificación
     * @param integer $id
     * @return ScholarisActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function espaciosCalificacion($id)
    {
        $sentencias = new SentenciasSql();
        $modelActividad = \backend\models\ScholarisActividad::find()
            ->where(['id' => $id])
            ->one();

        $tipo = $modelActividad->insumo->nombre_pai;

        $grupo = \backend\models\ScholarisGrupoOrdenCalificacion::find()
            ->where(['codigo_tipo_actividad' => $modelActividad->tipo_actividad_id])
            ->one();

        if ($modelActividad->tipo_calificacion == 'P') {

            $con = Yii::$app->db;
            //consulta los criterios asociados a la actividad
            $queryIdCriterios = "select distinct id_criterio
                        from scholaris_actividad_descriptor s
                        inner join planificacion_vertical_pai_descriptores d on s.plan_vert_pai_descriptor_id = d.id 
                        inner join ism_criterio_descriptor_area i on  d.descriptor_id = i.id 
                        where s.actividad_id = $id;";
            // echo $queryIdCriterios;
            // die();

            $arrayIdCriterios = $con->createCommand($queryIdCriterios)->queryAll();

            foreach ($arrayIdCriterios as $criterio) {
                if ($tipo == 'SUMATIVA') {
                    $sentencias->insertarEspaciosCalificacionPaiSumativa(
                        $id,
                        $modelActividad->tipo_actividad_id,
                        $criterio['id_criterio'],
                        $modelActividad->paralelo_id,
                        $grupo->grupo_numero
                    );
                } else {
                    $sentencias->insertarEspaciosCalificacionPai($id, $modelActividad->tipo_actividad_id, $criterio['id_criterio'], $modelActividad->paralelo_id);
                }
            }
        } else {

            $sentencias->insertarEspaciosCalificacionNac($id, $modelActividad->tipo_actividad_id, $modelActividad->paralelo_id);
        }
    }

    public function actionRegistra()
    {

        $nota = $_POST['nota'];
        $notaId = $_POST['notaId'];
        $grupoId = $_POST['grupo_id'];
        $user = Yii::$app->user->identity->usuario;
        $idPeriodo = \yii::$app->user->identity->periodo_id;

        //$model = \app\models\ScholarisActividad::findOne($id));
        $model = ScholarisCalificaciones::findOne($notaId);
        $model->calificacion = $nota;
        //1.- Se guarda la nota
        $model->save();

        //captura datos para tabla de promedio de insumos (lib_promedios_insumos)
        $grupo_numero = $model->grupo_numero;
        $idAlumno =  $model->idalumno;
        $modelActividad = ScholarisActividad::findOne($model->idactividad);
        $modelBloqueActividad = ScholarisBloqueActividad::findOne($modelActividad->bloque_actividad_id);
        $idclase = $modelActividad->paralelo_id;
        $idBloque = $modelBloqueActividad->id;
        $tipo_uso = $modelBloqueActividad->tipo_uso;

        $modelGrupoAlumnoClase = ScholarisGrupoAlumnoClase::find()
            ->where(['clase_id' => $idclase, 'estudiante_id' => $idAlumno])
            ->one();
        $modelGrupoCalifiaciones = ScholarisGrupoOrdenCalificacion::find()
            ->where(['codigo_tipo_actividad' => $modelActividad->tipo_actividad_id])
            ->one();


        /***Proceso mediante clases para registrar notas en los reportes*/
        $periodo = ScholarisPeriodo::findOne($idPeriodo);
        if($periodo->version_calculo_notas == 'V1'){
            $scores = new RegistraNotasV1($grupoId, $notaId, $nota);
        }
        
        /***Fin de proceso mediante clases para registrar notas en los reportes */


        //proceso de guardado        
        //2.- Se ejecuta funcion para insertar promedios de insumos
        // $con = Yii::$app->db;       

        // $query = "select insert_lib_promedio_insumo ($modelGrupoAlumnoClase->id,$idBloque,$modelGrupoCalifiaciones->id,$idPeriodo,'$user');";
        // $con->createCommand($query)->execute();     
        // $query = "select insert_lib_bloques_grupo_clase ($modelGrupoAlumnoClase->id,$idBloque,$idPeriodo,'$user','$tipo_uso');";
        // $con->createCommand($query)->execute();   
        // $query = "select insert_promedios_lib_bloques_grupo_clase ($modelGrupoAlumnoClase->id,$idPeriodo,'$user', '$tipo_uso');";
        // $con->createCommand($query)->execute(); 


        // $this->actualizaParcial($notaId);       
    }

    protected function actualizaParcial($notaId)
    {
        $sentencias = new SentenciasSql();
        $sentencias2 = new \backend\models\Notas();

        $modelCalificaciones = ScholarisCalificaciones::findOne($notaId);

        $this->registraParcial($modelCalificaciones->idalumno, $modelCalificaciones->actividad->bloque_actividad_id, $modelCalificaciones->actividad->paralelo_id);

        /****metodo nuevo con reforzamiento ** */
        $sentencias2->actualiza_parcial($modelCalificaciones->actividad->bloque_actividad_id, $modelCalificaciones->idalumno, $modelCalificaciones->actividad->paralelo_id);
    }

    protected function registraParcial($alumno, $bloqueId, $claseId)
    {
        $fecha = date("Y-m-d H:i:s");
        $sentencias = new SentenciasSql();

        $notaParcial = $sentencias->notaParcial($alumno, $bloqueId, $claseId);


        $model = ScholarisResumenParciales::find()
            ->where(['alumno_id' => $alumno, 'clase_id' => $claseId, 'bloque_id' => $bloqueId])
            ->one();



        if ($model) {
            $model->actualizacion_fecha = $fecha;
            $model->calificacion = $notaParcial['nota'];
            $model->save();
        } else {
            $model1 = new ScholarisResumenParciales();
            $model1->actualizacion_fecha = $fecha;
            $model1->alumno_id = $alumno;
            $model1->clase_id = $claseId;
            $model1->bloque_id = $bloqueId;
            $model1->calificacion = $notaParcial['nota'];
            $model1->save();
        }
    }

    protected function horas($fecha, $clase)
    {

        $sentencia = new SentenciasSql();

        //$fecha="2018-11-16" ; // fecha.
        #separas la fecha en subcadenas y asignarlas a variables
        #relacionadas en contenido, por ejemplo dia, mes y anio.

        $dia = substr($fecha, 8, 2);
        $mes = substr($fecha, 5, 2);
        $anio = substr($fecha, 0, 4);

        $diaNumero = date('w', mktime(0, 0, 0, $mes, $dia, $anio));
        //donde:
        #W (mayúscula) te devuelve el número de semana
        #w (minúscula) te devuelve el número de día dentro de la semana (0=domingo, #6=sabado)

        $modelHoras = $sentencia->horasDia($clase, $diaNumero);

        return $modelHoras;
    }

    public function actionCriterios($id)
    {
        $sentencias = new SentenciasSql();

        $modelActividad = \backend\models\ScholarisActividad::find()
            ->where(['id' => $id])
            ->one();

        $modelCalificaciones = ScholarisCalificaciones::find()
            ->where(['idactividad' => $id])
            ->all();

        $noAsignados = $sentencias->criteriosNoAsignados($modelActividad->clase->course->id, $modelActividad->clase->materia->area_id, $id);
        $asignados = $sentencias->criteriosAsignados($id);


        return $this->render('criterios', [
            'modelActividad' => $modelActividad,
            'noAsignados' => $noAsignados,
            'modelCalificaciones' => $modelCalificaciones,
            'asignados' => $asignados,
        ]);
    }

    public function actionAsignarcriterio($id_actividad, $id_plan_vert_descriptor)
    {

        $model = new ScholarisActividadDescriptor();

        $model->actividad_id = $id_actividad;
        $model->plan_vert_pai_descriptor_id = $id_plan_vert_descriptor;
        $model->save();
        $idModelSchoActDesc = $model->id;

        //**************************************************************************************************************************/
        //INICIO PROCESO Guarda datos en la tabla dw_estadisticas_criterios_pai
        //curso / paralelo / docente / materia/clase_id/bloque_id / bloque / tipo_activdad / criterio / total/ periodo
        $con = Yii::$app->db;

        $periodoId = \Yii::$app->user->identity->periodo_id;

        //extraemos ID bloque y clase, por la actividad
        $modelActividad = ScholarisActividad::findOne($id_actividad);
        $bloque_id = $modelActividad->bloque_actividad_id;
        $clase_id = $modelActividad->paralelo_id;
        //obtenemos nombre bloque
        $modelSchoBloqueActividad = ScholarisBloqueActividad::findOne($bloque_id);
        $nombreBloque = $modelSchoBloqueActividad->name;

        //extraemos el profesor,a travez de la clase
        $modelScholarisClase = ScholarisClase::find()
            ->where(['id' => $clase_id])
            ->one();

        $idProfesor = $modelScholarisClase->idprofesor;
        $querryProfesor = "select concat(last_name,' ',x_first_name,' ',middle_name) as nombre  from op_faculty of2 where id = $idProfesor;";
        $nombreProfesor = $con->createCommand($querryProfesor)->queryOne();

        //Obtenemos tipo de actividad
        $modelTipoActividad = ScholarisTipoActividad::findOne($modelActividad->tipo_actividad_id);
        $nombreActividad =  $modelTipoActividad->nombre_pai;

        // echo '<pre>';
        // print_r($modelTipoActividad);
        // die();

        //Obtenemos curso y paralelo
        $query = 'select o."name" as CURSO,p."name" as PARALELO   
                    from  scholaris_clase c 
                    inner join op_course_paralelo p  on p.id=c.paralelo_id 
                    inner join op_course o on o.id = p.course_id 
                    where c.id =' . $clase_id;

        $nombreCursoParalelo = $con->createCommand($query)->queryOne();

        //obtenemos nombre de la materia
        $queryMateria = 'select m.nombre  as Materia   
                        from  scholaris_clase c 
                        inner join ism_area_materia a on c.ism_area_materia_id  = a.id
                        inner join ism_materia m on a.materia_id  = m.id
                        where c.id = ' . $clase_id;
        $materia = $con->createCommand($queryMateria)->queryOne();

        //busca el criterio que se esta ingresando
        $queryCriterio = "select l.nombre 
                        from planificacion_vertical_pai_descriptores p
                        inner join ism_criterio_descriptor_area  c on p.descriptor_id = c.id
                        inner join ism_criterio  l on c.id_criterio  = l.id
                        where p.id =$id_plan_vert_descriptor;";
        $letraCriterio = $con->createCommand($queryCriterio)->queryOne();

        //Insert para ejecucion en la tabla
        $insertEstadistica = "Insert Into dw_estadisticas_criterios_pai " .
            "(curso, paralelo, docente, materia, clase_id, bloque_id,bloque, tipo_actividad, criterio,total, scholaris_periodo_id,id_scholaris_actividad_descriptor) 
         VALUES " .
            "('" . $nombreCursoParalelo['curso'] . "','" . $nombreCursoParalelo['paralelo'] . "','" . $nombreProfesor['nombre'] . "','" .
            $materia['materia'] . "','" . $clase_id . "','" . $bloque_id . "','" . $nombreBloque . "','" . $nombreActividad . "','" . $letraCriterio['nombre'] . "'," . '1' . "," . $periodoId . "," . $idModelSchoActDesc . ")";

        $insertar = $con->createCommand($insertEstadistica)->queryOne();

        return $this->redirect([
            'actividad',
            'actividad' => $id_actividad,
        ]);
    }

    public function actionQuitarcriterio($id)
    {
        //1.- Eliminacion de tabla dw_estadisticas_criterios_pai
        $con = Yii::$app->db;
        $queryDelete = "delete from  dw_estadisticas_criterios_pai where id_scholaris_actividad_descriptor = " . $id;
        $delete = $con->createCommand($queryDelete)->queryOne();

        //2.- Eliminacion de tabla scholaris_actividad_descriptor
        $model = ScholarisActividadDescriptor::find()
            ->where(['id' => $id])
            ->one();

        $actividad = $model->actividad_id;
        $model->delete();

        //return $this->redirect(['criterios', 'id' => $actividad]);
        return $this->redirect(['actividad', 'actividad' => $actividad]);
    }

    /* INICIO ANULAR */

    public function actionAnular($actividadId, $alumnoId)
    {
        $sentencia = new SentenciasSql();

        $model = \backend\models\ScholarisActividad::find()
            ->where(['id' => $actividadId])
            ->one();

        $sentencia->eliminaCalificaciones($actividadId, $alumnoId);

        $this->registraParcial($alumnoId, $model->bloque_actividad_id, $model->paralelo_id);

        return $this->redirect(['calificar', 'id' => $actividadId]);
    }

    /* FIN ANULAR */

    /** INICIO CALIFICACION  INDIVIDUAL */
    public function actionIndividual($alumnoId, $actividadId)
    {
        $model = ScholarisCalificaciones::find()
            ->where(['idactividad' => $actividadId, 'idalumno' => $alumnoId])
            ->all();

        $modelActividad = \app\models\ScholarisActividad::find()
            ->where(['id' => $actividadId])
            ->one();

        $modelMinimo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califminima'])
            ->one();

        $modelMaximo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califmmaxima'])
            ->one();

        return $this->render('individual', [
            'model' => $model,
            'modelActividad' => $modelActividad,
            'modelMinimo' => $modelMinimo,
            'modelMaximo' => $modelMaximo,
        ]);
    }

    /** FIN CALIFICACION  INDIVIDUAL */

    /**
     * Elimina la Actividad.
     * Este proceso elimina la actividad y calcula los nuevos promedios alumno por alumno
     */
    public function actionEliminar($id)
    {

        $modelActividad = ScholarisActividad::find()->where(['id' => $id])->one();

        if (isset($_GET['mensaje']) == 'SI') {
            $mensaje = 'SI';

            $this->elimina_deberes($id);

            $modelActividad->delete();
            $this->actualizaParcialesPorEliminarActividad($modelActividad);

            return $this->render('eliminar', [
                'modelActividad' => $modelActividad,
                'mensaje' => 'SI'
            ]);
        } else {

            return $this->render('eliminar', [
                'modelActividad' => $modelActividad,
                'mensaje' => 'NO'
            ]);
        }
    }

    private function elimina_deberes($actividadId)
    {
        $con = Yii::$app->db;
        $query = "delete from scholaris_actividad_deber where actividad_id = $actividadId;";
        $con->createCommand($query)->execute();
    }

    private function actualizaParcialesPorEliminarActividad($modelActividad)
    {

        $modelAlumnos = ScholarisGrupoAlumnoClase::find()->where(['clase_id' => $modelActividad->paralelo_id])->all();

        foreach ($modelAlumnos as $data) {
            //echo $data->clase_id.'--';
            $this->registraParcial($data->estudiante_id, $modelActividad->bloque_actividad_id, $modelActividad->paralelo_id);
        }
    }

    public function actionDuplicar()
    {
        $actividadId = $_GET['id'];

        $modelActividad = \backend\models\ScholarisActividad::findOne($actividadId);
        $ismAreaMateriaId = $modelActividad->clase->ism_area_materia_id;
        $cursoId = $modelActividad->clase->paralelo->course_id;
        $claseId = $modelActividad->clase->id;


        $modelClases = $this->get_clases_para_duplicar($ismAreaMateriaId, $cursoId, $claseId);

        return $this->render('duplicar', [
            'modelClases' => $modelClases,
            'modelActividad' => $modelActividad
        ]);
    }


    /**
     * 
     * @param type $ismAreaMateria
     * @param type $cursoId
     * @param type $claseId
     * @return typeMETODO QUE DEVUELVE LAS CLASES PARA DUPLICAR DEL MISMO CURSO Y DE LA MISMA MATERIA
     */
    private function get_clases_para_duplicar($ismAreaMateriaId, $cursoId, $claseId)
    {
        $con = \Yii::$app->db;
        $query = "select 	c.id 
                                ,cur.id as curso_id
                                ,cur.name as curso
                                ,p.name as paralelo
                                ,concat(f.x_first_name, ' ', f.last_name) as docente 
                from 	scholaris_clase c
                                inner join op_course_paralelo p on p.id = c.paralelo_id 
                                inner join op_course cur on cur.id = p.course_id
                                inner join op_faculty f on f.id = c.idprofesor 
                where 	c.ism_area_materia_id = $ismAreaMateriaId
                                and p.course_id = $cursoId
                                and c.id <> $claseId
                order by p.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionDuplicaraqui()
    {

        $clase = $_GET['clase'];
        $inicio = $_GET['inicio'];
        $hora = $_GET['hora'];

        $usuario = \Yii::$app->user->identity->usuario;
        $modelUsuario = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();

        $modelActividad = \backend\models\ScholarisActividad::find()->where(['id' => $_GET['actividadId']])->one();

        $this->save_actividad($modelActividad, $modelUsuario, $clase, $inicio, $hora);

        return $this->redirect([
            'duplicar',
            'id' => $modelActividad->id
        ]);
    }

    private function save_actividad($modelActividad, $modelUsuario, $clase, $inicio, $hora)
    {
        $fechaHoy = date("Y-m-d H:i:m");

        $sentencias = new SentenciasSql();
        //        $modelHora = $sentencias->hora_para_duplicar($clase, $inicio);

        $model = new \backend\models\ScholarisActividad();
        $model->create_date = $fechaHoy;
        $model->write_date = $fechaHoy;
        $model->create_uid = $modelUsuario->id;
        $model->write_uid = $modelUsuario->id;
        $model->title = $modelActividad->title;
        $model->descripcion = $modelActividad->descripcion;
        $model->inicio = $inicio;
        $model->fin = $inicio;
        $model->fin = $inicio;
        $model->tipo_actividad_id = $modelActividad->tipo_actividad_id;
        $model->bloque_actividad_id = $modelActividad->bloque_actividad_id;
        $model->paralelo_id = $clase;
        $model->materia_id = $modelActividad->materia_id;
        $model->calificado = $modelActividad->calificado;
        $model->tipo_calificacion = $modelActividad->tipo_calificacion;
        $model->tareas = $modelActividad->tareas;
        $model->hora_id = $hora;
        $model->actividad_original = $modelActividad->id;
        $model->semana_id = $modelActividad->semana_id;
        //        echo '<pre>';
        //        print_r($model);
        $model->save();

        $ultimoId = $model->primaryKey;

        $sentencias->duplicar_criterios($ultimoId, $modelActividad->id);
        $sentencias->duplicar_archivos($ultimoId, $modelActividad->id);
    }

    public function actionParcial()
    {

        $clase = $_GET['clase'];
        $orden = $_GET['orden'];
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $sentencias = new \backend\models\SentenciasClase();
        $sentencias2 = new SentenciasSql();

        $modelAlumnos = $sentencias->get_alumnos_clase($clase, $periodoId);
        $modelClase = ScholarisClase::find()->where(['id' => $clase])->one();
        $modelBloque = ScholarisBloqueActividad::find()
            ->where([
                'orden' => $orden,
                'tipo_uso' => $modelClase->tipo_usu_bloque,
                'scholaris_periodo_codigo' => $modelPeriodo->codigo
            ])
            ->one();

        $modelTipo = $sentencias2->get_insumos($clase, $modelBloque->id);
        $modelMinimo = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $minima = $modelMinimo->valor;

        return $this->render('parcial', [
            'modelAlumnos' => $modelAlumnos,
            'modelClase' => $modelClase,
            'modelBloque' => $modelBloque,
            'modelTipo' => $modelTipo,
            'minima' => $minima
        ]);
    }

    public function actionExtraordinarios()
    {

        $modelMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $minima = $modelMinima->valor;

        if (isset($_GET['grupo'])) {
            $grupo = $_GET['grupo'];


            if (isset($_GET['accion'])) {
                $accion = $_GET['accion'];
            } else {
                $accion = 'reporte-sabana-profesor/index1';
            }

            $modelLibreta = \backend\models\ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();

            $modelSupletorio = \backend\models\ScholarisQuimestre::find()->where(['codigo' => 'SUPLETORIO'])->one();
            $modelRemedial = \backend\models\ScholarisQuimestre::find()->where(['codigo' => 'REMEDIAL'])->one();
            $modelGracia = \backend\models\ScholarisQuimestre::find()->where(['codigo' => 'GRACIA'])->one();
            $modelRecuperacion = \backend\models\ScholarisQuimestre::find()->where(['codigo' => 'RECUPERACION'])->one();


            return $this->render('extraordinarios', [
                'modelLibreta' => $modelLibreta,
                'minima' => $minima,
                'modelSupletorio' => $modelSupletorio,
                'modelRemedial' => $modelRemedial,
                'modelGracia' => $modelGracia,
                'modelRecuperacion' => $modelRecuperacion,
                'accion' => $accion
            ]);
        } else {
            $grupo = $_POST['grupo'];
            $supletorio = $_POST['supletorio'];
            $remedial = $_POST['remedial'];
            $gracia = $_POST['gracia'];
            $mejora_q1 = $_POST['mejora_q1'];
            $mejora_q2 = $_POST['mejora_q2'];
            $accion = $_POST['accion'];
            $modelLibreta = \backend\models\ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();


            $modelLibreta->mejora_q1 = $mejora_q1;
            $modelLibreta->mejora_q2 = $mejora_q2;
            $modelLibreta->supletorio = $supletorio;
            $modelLibreta->remedial = $remedial;
            $modelLibreta->gracia = $gracia;
            $modelLibreta->final_total = null;
            $modelLibreta->estado = null;
            if ($mejora_q1 || $mejora_q2) {
                $mejorado = $this->calcula_mejora_quimestres($modelLibreta->q1, $modelLibreta->q2, $mejora_q1, $mejora_q2);
            } else {
                $mejorado = null;
            }

            $modelLibreta->final_con_mejora = $mejorado;
            $modelLibreta->save();

            $this->calcula_final_despues_extras($grupo, $minima);

            return $this->redirect([$accion, 'id' => $modelLibreta->grupo->clase_id]);
        }
    }

    private function calcula_mejora_quimestres($q1, $q2, $mejora1, $mejora2)
    {


        $sentencias = new \backend\models\Notas();

        if ($mejora1 > $q1) {
            $mq1 = $mejora1;
        } else {
            $mq1 = $q1;
        }

        echo $mq1;

        if ($mejora2 > $q2) {
            $mq2 = $mejora2;
        } else {
            $mq2 = $q2;
        }

        $promedio = ($mq1 + $mq2) / 2;
        $promedio = $sentencias->truncarNota($promedio, 2);



        return $promedio;
    }

    private function calcula_final_despues_extras($grupo, $minima)
    {

        $modelGrupo = \backend\models\ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();


        if ($modelGrupo->final_con_mejora > 0) {
            $modelGrupo->final_total = $modelGrupo->final_con_mejora;
            $modelGrupo->estado = 'APROBADO';
        } else {

            if ($modelGrupo->supletorio >= $minima || $modelGrupo->remedial >= $minima || $modelGrupo->gracia >= $minima) {
                $modelGrupo->final_total = $minima;
                $modelGrupo->estado = 'APROBADO';
            }

            if ($modelGrupo->supletorio < $minima && $modelGrupo->supletorio != null && $modelGrupo->remedial == null && $modelGrupo->gracia == null) {
                $modelGrupo->final_total = $modelGrupo->final_ano_normal;
                $modelGrupo->estado = 'REMEDIAL';
            }

            if ($modelGrupo->remedial < $minima && $modelGrupo->remedial != null && $modelGrupo->gracia == null) {
                $modelGrupo->final_total = $modelGrupo->final_ano_normal;
                $modelGrupo->estado = 'GRACIA';
            }

            if ($modelGrupo->gracia < $minima && $modelGrupo->gracia != null) {
                $modelGrupo->final_total = $modelGrupo->final_ano_normal;
                $modelGrupo->estado = 'PIERDE EL AÑO';
            }
        }

        $modelGrupo->save();
    }

    public function actionTerminar()
    {

        $sentencias = new \backend\models\Notas();

        $clase = $_GET['clase'];

        if (isset($_GET['accion'])) {
            $accion = $_GET['accion'];
        } else {
            $accion = 'reporte-sabana-profesor/index1';
        }

        $modelClase = ScholarisClase::find()->where(['id' => $clase])->one();

        if (isset($_GET['ejecutar'])) {
            $accion = $_GET['accion'];

            $sentencias->ejecutar_termino_ano_clase($clase);


            $modelClase->estado_cierre = true;
            $modelClase->save();
            //            return $this->redirect(['reporte-sabana-profesor/index1','id' => $clase]);
            return $this->redirect([$accion, 'id' => $clase]);
        } else {
            return $this->render('terminar', [
                'modelClase' => $modelClase,
                'accion' => $accion
            ]);
        }
    }


    public function actionEliminararchivo()
    {
        //print_r($_GET);
        $model = ScholarisArchivosprofesor::findOne($_GET['id']);
        $actividad = $model->idactividad;
        $model->delete();

        return $this->redirect([
            'actividad',
            'actividad' => $actividad
        ]);
    }

    public function actionVerarchivos()
    {
        $actividad = $_GET['actividadId'];
        $alumno = $_GET['alumnoId'];

        $modelActividad = ScholarisActividad::findOne($actividad);
        $modelAlumno = \backend\models\OpStudent::findOne($alumno);

        $modelEntregados = \backend\models\ScholarisActividadDeber::find()
            ->where([
                'actividad_id' => $actividad,
                'alumno_id' => $alumno
            ])
            ->all();

        return $this->render('verarchivos', [
            'modelActividad' => $modelActividad,
            'modelAlumno' => $modelAlumno,
            'modelEntregados' => $modelEntregados
        ]);
    }

    public function actionUpdateobservacion()
    {
        //        print_r($_GET);

        $alumnoId = $_GET['alumnoId'];
        $actividadId = $_GET['actividadId'];


        $model = ScholarisCalificaciones::find()
            ->where([
                'idalumno' => $alumnoId,
                'idactividad' => $actividadId
            ])
            ->one();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['anularcalificaciones', 'id' => $actividadId]);
        }


        return $this->render('updateobservacion', [
            'model' => $model
        ]);
    }



    /**
     * ACCIÓN PARA ENTREGAR LA LISTA DE ACTIVIDADES
     */
    public function actionLista()
    {
        $claseId        = $_GET['clase_id'];
        $semanaNumero    = $_GET['semana_numero'];
        $detalleHorarioId = $_GET['detalle_horario_id'];
        $lmsId            = $_GET['lms_id'];

        $data = $this->inserta_actividades_lms($_GET);

        $modelClase = ScholarisClase::findOne($claseId);

        $actividades = ScholarisActividad::find()
            ->innerJoin('lms_actividad l', 'l.id = scholaris_actividad.lms_actvidad_id')
            ->where([
                'bloque_actividad_id' => $data['bloque_id'],
                'l.lms_id' => $lmsId
            ])
            ->orderBy('create_date')
            ->All();

        $temas = Lms::find()->where([
            'semana_numero' => $semanaNumero,
            'ism_area_materia_id' => $modelClase->ism_area_materia_id
        ])
            ->orderBy('hora_numero')
            ->all();

        $semanas = ScholarisBloqueSemanas::find()->where([
            'bloque_id' => $data['bloque_id']
        ])->orderBy('semana_numero')->all();

        $lms = Lms::findOne($lmsId);

        return $this->render('lista', [
            'actividades'   => $actividades,
            'semanas'       => $semanas,
            'temas'         => $temas,
            'semanaId'      => $data['semana_id'],
            'bloqueId'      => $data['bloque_id'],
            'lms'           => $lms,
            'clase_id'      => $claseId,
            'detalle_horario_id' => $detalleHorarioId,
            'semana_numero' => $semanaNumero,
            'nombre_semana' => $semanas[0]->nombre_semana,
            'modelClase'    => $modelClase
        ]);
    }

    private function inserta_actividades_lms($get)
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $usuario = ResUsers::find()->where(['login' => $usuarioLog])->one();
        $usuarioId = $usuario->id;

        $hoy = date('Y-m-d H:i:s');

        $modelClase     = ScholarisClase::findOne($get['clase_id']);
        $bloque       = $this->consulta_bloque_id($get['semana_numero'], $modelClase->tipo_usu_bloque);
        $detalleHorario = ScholarisHorariov2Detalle::findOne($get['detalle_horario_id']);

        $bloqueId   = $bloque['bloque_id'];
        $semanaId   = $bloque['semana_id'];
        $horaId     = $detalleHorario->hora->id;

        $this->inserta_actividades($hoy, $usuarioId, $bloqueId, $modelClase->id, $horaId, $semanaId, $get['lms_id']);

        return array(
            'bloque_id' => $bloqueId,
            'semana_id' => $semanaId
        );
    }

    private function consulta_bloque_id($semanaNumero, $uso)
    {
        $con = Yii::$app->db;
        $query = "select 	blo.id as bloque_id, sem.id as semana_id
                    from	scholaris_bloque_semanas sem
                            inner join scholaris_bloque_actividad blo on blo.id = sem.bloque_id 
                    where 	sem.semana_numero  = $semanaNumero
                            and blo.tipo_uso = '$uso'
                            and blo.tipo_bloque in ('PARCIAL', 'EXAMEN');";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function inserta_actividades($hoy, $usuarioId, $bloqueId, $claseId, $horaId, $semanaId, $lmsId)
    {
        $con = Yii::$app->db;
        $query = "insert into scholaris_actividad (create_date, create_uid, title, descripcion, inicio, fin, tipo_actividad_id, bloque_actividad_id
                            , paralelo_id, calificado, tipo_calificacion, tareas, hora_id, semana_id, lms_actvidad_id, es_heredado_lms, estado)
                    select 	'$hoy'
                            ,$usuarioId
                            ,lma.titulo 
                            ,lma.descripcion 
                            ,'$hoy'
                            ,'$hoy'
                            ,lma.tipo_actividad_id 
                            ,$bloqueId as bloque_actividad_id
                            ,$claseId as clase_id
                            ,lma.es_calificado 
                            ,tip.tipo 
                            ,lma.tarea 
                            ,$horaId as hora_id
                            ,$semanaId as semana_id
                            ,lma.id 
                            ,true 
                            ,false 
                    from	lms_actividad lma
                            inner join scholaris_tipo_actividad tip on tip.id = lma.tipo_actividad_id 
                    where 	lma.lms_id = $lmsId
                            and lma.id not in (select lms_actvidad_id  from scholaris_actividad where lms_actvidad_id = lma.id  and paralelo_id = $claseId);";
        $con->createCommand($query)->execute();
    }

    /**
     * FIN ACCIÓN PARA ENTREGAR LA LISTA DE ACTIVIDADES
     */


    public function actionDownload()
    {
        $model = ScholarisParametrosOpciones::find()->where([
            'codigo' => 'pathfiles'
        ])->one();

        $path = $model->valor . $_GET['path'];

        try {
            return Yii::$app->response->sendFile($path)->send();
        } catch (\Exception $ex) {
            echo 'No existe el archivo!!!';
        }
    }
}
