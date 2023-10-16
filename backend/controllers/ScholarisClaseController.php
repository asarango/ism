<?php

namespace backend\controllers;

use backend\models\PlanificacionSemanal;
use backend\models\ScholarisAsistenciaProfesor;
use Yii;
use backend\models\ScholarisClase;
use backend\models\ScholarisClaseSearch;
use backend\models\ScholarisHorariov2Detalle;
use backend\models\ScholarisHorariov2Dia;
use backend\models\ScholarisHorariov2Horario;
use backend\models\ScholarisPeriodo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisClaseController implements the CRUD actions for ScholarisClase model.
 */
class ScholarisClaseController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
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

    public function beforeAction($action) {
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
     * Lists all ScholarisClase models.
     * @return mixed
     */
    public function actionIndex() {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;

        $modelPerido = ScholarisPeriodo::find()
                ->where(['id' => $periodoId])
                ->one();

        $searchModel = new ScholarisClaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $modelPerido->codigo, $institutoId);
        
        ///
        $listaMateria = $this->get_lista_materia($periodoId);
        $listaM = \yii\helpers\ArrayHelper::map($listaMateria, 'ism_area_materia_id', 'nombre');
        
        $docentes = \backend\models\OpFaculty::find()
                ->select(['id', "concat(last_name, ' ', x_first_name) as last_name"])
                ->orderBy('last_name')->all();
        $listaT = \yii\helpers\ArrayHelper::map($docentes, 'id', 'last_name');
        
        $listaCursos = $this->get_cursos($periodoId);
        $listaC = \yii\helpers\ArrayHelper::map($listaCursos, 'id', 'paralelo');
        ///

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'modelPeriodo' => $modelPerido,
                    'institutoId' => $institutoId,
                    'listaM' => $listaM,
                    'listaT' => $listaT,
                    'listaC' => $listaC
        ]);
    }
    
    private function get_cursos($periodoId){
        $con = Yii::$app->db;
        $query = "select 	p.id 
                                    ,concat(c.name, ' ', p.name) as paralelo
                    from	op_course_paralelo p
                                    inner join op_course c on c.id = p.course_id  
                                    inner join op_section s on s.id = c.section
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                    where 	sop.scholaris_id = $periodoId
order by c.name, p.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    public function get_lista_materia($periodoId){
        $con = Yii::$app->db;
        $query = "select 	c.ism_area_materia_id 
                                    ,m.nombre 
                    from 	scholaris_clase c
                                    inner join ism_area_materia am on am.id = c.ism_area_materia_id 
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                    inner join ism_materia m on m.id = am.materia_id 
                    where 	pm.scholaris_periodo_id = $periodoId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Displays a single ScholarisClase model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }
    
    
    public function actionAjax(){        
        $bandera = $_POST['bandera'];
        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        
        if($bandera == 'paralelo'){    
            $opCourseTemplateId = $_POST['op_course_template_id'];
            $html = $this->html_paralelo($opCourseTemplateId, $periodoId);
        }elseif( $bandera == 'materia'){
            $opCourseTemplateId = $_POST['op_course_template_id'];
            $html = $this->html_ism_area_materia($opCourseTemplateId, $periodoId);
        }elseif( $bandera == 'docente'){
            $html = $this->html_docente();
        }elseif( $bandera == 'horario' ){
            $html = $this->html_horario($periodoId);
        }elseif( $bandera == 'uso' ){
            $html = $this->html_uso($institutoId);
        }elseif($bandera == 'autoridades'){
            $html = $this->html_autoridades($institutoId);
        }
        
        return $html;
    }
    
    private function html_autoridades($institutoId){
        $con = Yii::$app->db;
        $query = "select 	a.id 
                                    ,concat(rp.name, ' (', u.usuario ,')') as usuario
                                    ,r.rol 
                    from 	usuario u
                                    inner join res_users ru on ru.login = u.usuario 
                                    inner join res_partner rp on rp.id = ru.partner_id
                                    inner join rol r on r.id = u.rol_id 
                                    inner join op_institute_authorities a on a.usuario = u.usuario 
                    where	u.instituto_defecto = $institutoId
                    order by rp.name;";
        $res = $con->createCommand($query)->queryAll();        
        
        $html = '';
        foreach ($res as $r){
            $id = $r['id'];
            $usuario = $r['usuario'];
            $html .= '<option value="'.$id.'">';
            $html .= $usuario;
            $html .= '</option>';
        }
        
        return $html;
    }
    
    private function html_uso($institutoId){
        $con = Yii::$app->db;
        $query = "select id, nombre, valor  from scholaris_bloque_comparte where instituto_id = $institutoId order by nombre ;";
        $res = $con->createCommand($query)->queryAll();        
        
        $html = '';
        foreach ($res as $r){
            $valor = $r['valor'];
            $uso = $r['nombre'];
            $html .= '<option value="'.$valor.'">';
            $html .= $uso;
            $html .= '</option>';
        }
        
        return $html;
    }
    
    private function html_horario($periodoId){
        $con = Yii::$app->db;
        $query = "select 	id, descripcion  
                    from 	scholaris_horariov2_cabecera
                    where 	periodo_id = $periodoId
                    order by descripcion;";
        $res = $con->createCommand($query)->queryAll();
        
        
        $html = '';
        foreach ($res as $r){
            $id = $r['id'];
            $horario = $r['descripcion'];
            $html .= '<option value="'.$id.'">';
            $html .= $horario;
            $html .= '</option>';
        }
        
        return $html;
    }


    private function html_docente(){
        $con = Yii::$app->db;
        $query = "select 	id, concat(last_name, ' ', x_first_name)  as docente
                    from 	op_faculty
                    order by last_name, x_first_name ; ";
        $res = $con->createCommand($query)->queryAll();
        
        
        $html = '';
        foreach ($res as $r){
            $id = $r['id'];
            $docente = $r['docente'];
            $html .= '<option value="'.$id.'">';
            $html .= $docente;
            $html .= '</option>';
        }
        
        return $html;
    }
    
    private function html_ism_area_materia($opCourseTemplateId, $periodoId){
        $con = Yii::$app->db;
        $query = "select 	am.id  
                                    ,concat(m.nombre, ' | ', c.name,  ' | ', mal.nombre) as materia
                    from	ism_area_materia am
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                    inner join ism_materia m on m.id = am.materia_id 
                                    inner join ism_malla mal on mal.id = pm.malla_id 
                                    inner join op_course_template c on c.id = mal.op_course_template_id 
                    where 	pm.scholaris_periodo_id = $periodoId
                                    and c.id = $opCourseTemplateId
                    order by m.nombre, c.name, m.nombre ; ";
        $res = $con->createCommand($query)->queryAll();
        
        
        $html = '';
        foreach ($res as $r){
            $ismAreaMateriaId = $r['id'];
            $materia = $r['materia'];
            $html .= '<option value="'.$ismAreaMateriaId.'">';
            $html .= $materia;
            $html .= '</option>';
        }
        
        return $html;
    }
    
    
    private function html_paralelo($opCourseTemplateId, $periodoId){
        $con = Yii::$app->db;
        $query = "select 	p.id 
                                    ,p.name as paralelo
                    from 	op_course_paralelo p
                                    inner join op_course c on c.id = p.course_id 
                                    inner join op_section s on s.id = c.section
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                    where 	sop.scholaris_id = $periodoId
                                    and c.x_template_id = $opCourseTemplateId
                    order by p.name;";
        $res = $con->createCommand($query)->queryAll();
        
        
        $html = '';
        foreach ($res as $r){
            $paraleloId = $r['id'];
            $paralelo = $r['paralelo'];
            $html .= '<option value="'.$paraleloId.'">';
            $html .= $paralelo;
            $html .= '</option>';
        }
        
        return $html;
    }

    /**
     * Creates a new ScholarisClase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $periodoId = Yii::$app->user->identity->periodo_id;     
        
        $modelCursos = $this->get_cursosC($periodoId);
        //if ($model->load(Yii::$app->request->post())) {
        if ($_POST) {
            
            $this->insertar_clase($_POST);
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'modelCursos' => $modelCursos
        ]);
    }
    
    private function insertar_clase($post){
                        
            $idprofesor = $post['idprofesor'];
            $paralelo_id = $post['paralelo_id'];
            $asignado_horario = $post['asignado_horario'];
            $tipo_usu_bloque = $post['tipo_usu_bloque'];
            $todos_alumnos = $post['todo_alumnos'];
            $rector_id = $post['rector_id'];
            $coordinador_dece_id = $post['coordinador_dece_id'];
            $secretaria_id = $post['secretaria_id'];
            $coordinador_academico_id = $post['coordinador_academico_id'];
            $inspector_id = $post['inspector_id'];
            $dece_dhi_id = $post['dece_dhi_id'];
            $tutor_id = $post['tutor_id'];
            $ism_area_materia_id = $post['ism_area_materia_id'];
            
        $con = Yii::$app->db;
        $query = "insert into scholaris_clase(idprofesor, paralelo_id, asignado_horario, 
                                              tipo_usu_bloque, todos_alumnos, rector_id, 
                                              coordinador_dece_id, secretaria_id,
                                              coordinador_academico_id, inspector_id, dece_dhi_id, 
                                              tutor_id, ism_area_materia_id, es_activo) 
                values($idprofesor, $paralelo_id, $asignado_horario, '$tipo_usu_bloque', $todos_alumnos"
                . ", $rector_id, $coordinador_dece_id, $secretaria_id, $coordinador_academico_id
                    , $inspector_id, $dece_dhi_id, $tutor_id, $ism_area_materia_id, true)";
        $con->createCommand($query)->execute();        
    }
    
    private function get_cursosC($periodoId){
        $con = Yii::$app->db;
        $query = "select 	c.id 
                                    ,oc.name as curso
                    from	op_course_template c
                                    inner join op_course oc on oc.x_template_id = c.id 
                                    inner join op_section os on os.id = oc.section
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = os.period_id 
                    where 	sop.scholaris_id = $periodoId
                    order by oc.name;; ";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function get_materias($periodoId){
        $con = Yii::$app->db;
        $query = "select 	am.id  
                                    ,concat(m.nombre, ' | ', c.name,  ' | ', mal.nombre) as materia
                    from	ism_area_materia am
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                    inner join ism_materia m on m.id = am.materia_id 
                                    inner join ism_malla mal on mal.id = pm.malla_id 
                                    inner join op_course_template c on c.id = mal.op_course_template_id 
                    where 	pm.scholaris_periodo_id = $periodoId
                    order by m.nombre, c.name, m.nombre ; ";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Updates an existing ScholarisClase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        
        $scripts = new \backend\models\helpers\Scripts();
        $modelMallaMateria = $scripts->sql_materias_x_periodo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'modelMallaMateria' => $modelMallaMateria
        ]);
    }

    /**
     * Deletes an existing ScholarisClase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisClase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisClase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisClase::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionTodos() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $id = $_GET['id'];
        $model = $this->findModel($id);

        $sentencias = new \backend\models\SentenciasClase();
        $sentencias->ingresar_alumnos_todos($id, $model->paralelo_id);


        $modelMalla = \backend\models\ScholarisMallaCurso::find()
                ->where(['curso_id' => $model->idcurso])
                ->one();

        $modelGrupo = $sentencias->get_alumnos_clase($id, $periodoId);

        return $this->redirect(['scholaris-clase-aux/update',
                    'id' => $id
        ]);
    }


    public function actionUnitario() 
    {
        $sentencias = new \backend\models\SentenciasClase();

        if (isset($_GET['id'])) 
        {
            $id = $_GET['id'];
            $model = $this->findModel($id);
            $curso = $model->paralelo->course->id;          
        
           
            if($model->ismAreaMateria->tipo == 'PROYECTOS'){
                $modelAlumnos = $sentencias->get_alumnos_todos();  
            }else{
                $modelAlumnos = $sentencias->get_alumnos_curso($id, $curso);
            }
            
            return $this->render('unitario', [
                        'model' => $model,
                        'modelAlumnos' => $modelAlumnos
            ]);
        }else{
//            print_r($_POST);
            $id = $_POST['id'];
            $alumno = $_POST['alumno'];
            
            $modelGr = new \backend\models\ScholarisGrupoAlumnoClase();
            $modelGr->clase_id = $id;
            $modelGr->estudiante_id = $alumno;
            $modelGr->save();
            
            return $this->redirect(['scholaris-clase-aux/update',
                    'id' => $id
                ]);
        }
    }
    
    
    public function actionRetirar(){      
        
        $sentencias = new \backend\models\SentenciasClase();
        
        if(isset($_GET['grupoId'])){
            $grupoId = $_GET['grupoId'];
            $model = \backend\models\ScholarisGrupoAlumnoClase::find()->where(['id' => $grupoId])->one();
            
            $modelActividades = $sentencias->get_actividades_calificadas_alumnos($grupoId);
            
            
            return $this->render('retirar',[
                'model' => $model,
                'modelActividades' => $modelActividades
            ]);
            
        }else{
            $grupoId = $_POST['grupoId'];
            $motivo = $_POST['motivo'];
            $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()->where(['id' => $grupoId])->one();
                  
            $this->registra_retiro_alumno_clase($modelGrupo, $motivo);            
            $sentencias->eliminar_alumno_clase($grupoId);
            
            return $this->redirect(['scholaris-clase-aux/update','id' => $modelGrupo->clase_id]);
            
        }
    }
    
    private function registra_retiro_alumno_clase($modelGrupo,$motivo){
        $fecha = date("Y-m-d H:i:s");
        $usuario = Yii::$app->user->identity->usuario;
        $model = new \backend\models\ScholarisAlumnoRetiradoClase();        
        
        $model->clase_id = $modelGrupo->clase_id;
        $model->alumno_id = $modelGrupo->estudiante_id;
        $model->fecha_retiro = $fecha;
        $model->motivo_retiro = $motivo;
        $model->usuario = $usuario;
        $model->save();
    }


    /**
     *  ACCION PARA REALIZAR EL CAMBIO DE HORARIO DE UNA CLASE
     *  DE UN DIA Y HORA A OTRO DIA Y HORA
     */
    public function actionCambiarHorario() {
        $claseId = $_GET['id'];

        $clase = ScholarisClase::findOne($claseId);
        $horarioActual = $this->get_horario_clase($claseId); //Toma el horario de la clase
        $tipoHorario = $clase->asignado_horario;
        $listaHorario = $this->get_lista_horario($tipoHorario);


        return $this->render('cambiar-horario',[ 
            'clase' => $clase,
            'horarioActual' => $horarioActual,
            'listaHorario' => $listaHorario
        ]);

    }
    
    //Metodo que retorna el horario de una clase
    //se llama desde la accion cambiar-horario
    private function get_horario_clase($claseId){
        $con = Yii::$app->db;
        $query = "select 	det.id as detalle_id 
                        ,dia.id as dia_id
                        ,dia.nombre as dia
                        ,hor.id as hora_id
                        ,hor.nombre as hora
                from 	scholaris_horariov2_horario hhh 
                        inner join scholaris_horariov2_detalle det on det.id = hhh.detalle_id 
                        inner join scholaris_horariov2_hora hor on hor.id = det.hora_id 
                        inner join scholaris_horariov2_dia dia on dia.id = det.dia_id 
                where 	hhh.clase_id = $claseId
                order by dia.numero, hor.numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    // Retorna la lista de horarios de una clase para el nuevo horario
    private function get_lista_horario($cabeceraId){
        $con = Yii::$app->db;
        $query = "select 	det.id as detalle_id
                            ,concat(dia.nombre, ' | ', hor.nombre) as horario
                    from 	scholaris_horariov2_detalle det
                            inner join scholaris_horariov2_dia dia on dia.id = det.dia_id
                            inner join scholaris_horariov2_hora hor on hor.id = det.hora_id
                    where	det.cabecera_id = $cabeceraId
                    order by dia.numero, hor.numero";
        return $con->createCommand($query)->queryAll();
    }


    /**
     * ACCION PARA MOSTRAR LOS CAMBIOS DE HORARIO DE UNA CLASE
     */
    public function actionMostrarCambiosHorario(){
        $desde = $_POST['detalleId'];
        $hasta = $_POST['horaNueva'];
        $clase = $_POST['clase_id'];

        $detalleDesde = ScholarisHorariov2Detalle::findOne($desde);
        $detalleHasta = ScholarisHorariov2Detalle::findOne($hasta);

        if($detalleHasta->dia->numero > $detalleDesde->dia->numero){
            $operador  = '+';
            $diferencia = $detalleHasta->dia->numero - $detalleDesde->dia->numero;
        }elseif($detalleHasta->dia->numero < $detalleDesde->dia->numero){
            $operador  = '-';
            $diferencia = $detalleDesde->dia->numero - $detalleHasta->dia->numero;
        }else{
            $operador  = '+';
            $diferencia = 0;
        }

        $detalle = $this->consulta_asistencias_clase($clase, $detalleDesde->hora_id,$detalleDesde->dia->numero, 
                    $detalleHasta->hora->id, $detalleHasta->hora->nombre, 
                    $diferencia, $operador);

        $detallePlanes = $this->consulta_planes_semanales($clase, $detalleDesde->hora_id,$detalleDesde->dia->numero, 
                    $detalleHasta->hora->id, $detalleHasta->hora->nombre, 
                    $diferencia, $operador);

        
        return $this->renderPartial('_mostrar-cambios-horario',[
            'detalle' => $detalle,
            'detallePlanes' => $detallePlanes,
            'claseId' => $clase,
            'detalleDesde' => $detalleDesde,
            'detalleHasta' => $detalleHasta
        ]);
    }

    private function consulta_planes_semanales($claseId, $desdeHoraId, $numeroDia, $nuevaHoraId, $nuevaHora, $diasDiferencia, $operador){
        $con = Yii::$app->db;
        $query = "select  id 
                            ,hora_id 
                            ,fecha 
                            ,$numeroDia as numero_dia
                            ,(fecha $operador interval '$diasDiferencia d')::date as nueva_fecha
                            ,$nuevaHoraId as nueva_hora_id
                            ,'$nuevaHora' as hora
                    from 	planificacion_semanal
                    where 	clase_id = $claseId 
                            and EXTRACT(DOW FROM fecha::date) = $numeroDia
                            and hora_id = $desdeHoraId;";

        // echo $query;
        // die();
        
        return $con->createCommand($query)->queryAll();
    }

    private function consulta_asistencias_clase($claseId, $desdeHoraId, $numeroDia, $nuevaHoraId, $nuevaHora, $diasDiferencia, $operador){
        $con = Yii::$app->db;
        $query = "select 	id
                            ,hora_id 
                            ,hora_ingresa 
                            ,fecha 
                            ,$numeroDia as numero_dia
                            ,(fecha $operador interval '$diasDiferencia d')::date as nueva_fecha
                            ,$nuevaHoraId as nueva_hora_id
                            ,'$nuevaHora' as hora
                    from 	scholaris_asistencia_profesor 
                    where 	clase_id = $claseId and EXTRACT(DOW FROM fecha::date) = $numeroDia 
                            and hora_id = $desdeHoraId;";
        
        return $con->createCommand($query)->queryAll();

    }

    /***
     * PROCESAR LOS CAMBIOS DE HORARIO
     */
    public function actionProcesarCambios(){
        // print_r($_POST);
        $dataJson = $_POST['data'];
        $claseId = $_POST['claseId'];
        $detalleDesdeID = $_POST['detalleDesdeID'];
        $detalleHastaID = $_POST['detalleHastaID'];
        $dataJsonPlan = $_POST['dataPlan'];

        $data = json_decode($dataJson); //decodifica la data de las asistencias a rreglo php
        $dataPlan = json_decode($dataJsonPlan);  //decodifica la data del plan semanal a rreglo php

        $this->cambia_horario($claseId, $detalleDesdeID, $detalleHastaID); //realiza el cambio de la data a la tabla scholaris_horariov2_horario
        $this->cambia_novedades($data); //realiza el cambio de la data a la tabla scholaris_asistencia_profesor
        $this->cambia_plan_semanal($dataPlan);
        $this->actualizar_horas_semana_plan_semanal($claseId);
        
    } 

    private function cambia_horario($claseId, $detalleDesdeID, $detalleHastaID){
        $con = Yii::$app->db;
        $queryDelete = "delete from scholaris_horariov2_horario where clase_id = $claseId and detalle_id = $detalleDesdeID;";
        $con->createCommand($queryDelete)->execute();

        $queryInsertNuevo = "insert into scholaris_horariov2_horario (detalle_id, clase_id) values($detalleHastaID, $claseId);";
        $con->createCommand($queryInsertNuevo)->execute();
    }

    private function cambia_novedades($data){
        foreach ($data as $key => $d){
           $model = ScholarisAsistenciaProfesor::findOne($d->id);
           $model->hora_id = $d->nueva_hora_id;
           $model->fecha = $d->nueva_fecha;
           $model->modificado = date('Y-m-d');
           $model->save();
        }
    }

    private function cambia_plan_semanal($data){

        foreach ($data as $key => $d){
            $model = PlanificacionSemanal::findOne($d->id);
            $model->fecha = $d->nueva_fecha;
            $model->hora_id = $d->nueva_hora_id;
            $model->updated_at = date('Y-m-d');
            $model->updated = Yii::$app->user->identity->usuario;
            $model->save();
         }
    }


    private function actualizar_horas_semana_plan_semanal($claseId){
        $con = Yii::$app->db;
        $query = "select 	pla.id 
                            ,pla.hora_id 
                            ,extract (dow from fecha::date) as dia_numero
                    from 	planificacion_semanal pla
                            inner join scholaris_horariov2_hora hor on hor.id = pla.hora_id  
                    where 	pla.clase_id = $claseId
                    order by 3,pla.fecha,hor.numero;";
        $planes = $con->createCommand($query)->queryAll();

        foreach ($planes as $key => $p){
            $nuevoOrden = $this->encuentra_numero_semana($claseId, $p['hora_id'], $p['dia_numero']);
            $model = PlanificacionSemanal::findOne($p['id']);
            $model->orden_hora_semana = $nuevoOrden;
            $model->save();
        }
    }


    private function encuentra_numero_semana($claseId, $horaId, $diaNumero){
        $con = Yii::$app->db;
        $query = "select 	hor.id 
                            ,hor.numero 
                            ,extract (dow from fecha::date) as numero_dia
                    from 	planificacion_semanal pla
                            inner join scholaris_horariov2_hora hor on hor.id = pla.hora_id 
                    where 	pla.clase_id = $claseId
                    group by hor.id, hor.numero, 3
                    order by 3,hor.numero;";

        $data = $con->createCommand($query)->queryAll();
        
        $i = 0;
        foreach ($data as $key => $d){
            $i++;
            if($d['id'] == $horaId && $d['numero_dia'] == $diaNumero){
                return $i;
            }
        }

    }

}
