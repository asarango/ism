<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\DeceCasos;
use backend\models\DeceCasosSearch;
use backend\models\DeceDeteccion;
use backend\models\DeceMotivos;
use backend\models\helpers\Scripts;
use backend\models\OpInstituteAuthorities;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


/**
 * DeceCasosController implements the CRUD actions for DeceCasos model.
 */
class DeceCasosController extends Controller
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
     * Lists all DeceCasos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;

        $estudiantes = $this->consulta_estudiantes($periodoId, $usuarioLog);
        $conteoEjesDeAccion = $this->consulta_conteo_por_eje($usuarioLog);
        //$casos = $this->mostrar_casos_por_usuario($usuarioLog);   
        $casos = $this->mostrar_casos_y_estadistica($usuarioLog);

        $searchModel = new DeceCasosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'estudiantes' => $estudiantes,
            'casos' => $casos,
            'conteoEjesDeAccion' => $conteoEjesDeAccion
        ]);
    }
    private function mostrar_casos_y_estadistica($user)
    {
        //buscamos si el usuario es coordinador
        $modelCoordinadorDece = OpInstituteAuthorities::find()
            ->where(['ilike', 'cargo_descripcion', 'dececoor'])
            ->andWhere(['usuario' => $user])
            ->one();

        $con = yii::$app->db;
        $periodoId = Yii::$app->user->identity->periodo_id;
        //extrae tdos los estudiantes asociados a un usuario de la tabla dece_casos
        if ($modelCoordinadorDece) {
            $query = "select distinct id_estudiante from dece_casos dc where id_usuario_super_dece  = '$user';";
        } else {
            $query = "select distinct id_estudiante from dece_casos dc where id_usuario_dece  = '$user';";
        }

        $usuariosCasos = $con->createCommand($query)->queryAll();

        $arrayCasos = array();
        foreach ($usuariosCasos as $usuario) {
            $id_estudiante = $usuario['id_estudiante'];

            $query2 = "select  concat(os.last_name,' ',os.middle_name,' ',os.first_name) nombre,dc.id_estudiante , 
                        (select count(*) from dece_casos dc2 where id_estudiante  = dc.id_estudiante ) casos,
                        (select count(*) from dece_registro_seguimiento drs where id_estudiante  = dc.id_estudiante ) seguimiento,
                        (select count(distinct id_caso) from dece_registro_seguimiento drs where id_estudiante = dc.id_estudiante) casos_seguimiento,
                        (select count(*) from dece_derivacion d where id_estudiante  = dc.id_estudiante ) derivacion,
                        (select count(distinct id_casos) from dece_derivacion drs where id_estudiante = dc.id_estudiante) casos_derivacion,
                        (select count(*) from dece_deteccion d where id_estudiante  = dc.id_estudiante ) deteccion,
                        (select count(distinct id_caso) from dece_deteccion drs where id_estudiante = dc.id_estudiante) casos_deteccion,
                        (select count(*) from dece_intervencion d where id_estudiante  = dc.id_estudiante ) intervencion,
                        (select count(distinct id_caso) from dece_intervencion drs where id_estudiante = dc.id_estudiante) casos_intervencion
                        from dece_casos dc, op_student os  
                        where id_estudiante =  $id_estudiante";
            if ($modelCoordinadorDece) {
                $query2 .= " and id_usuario_super_dece = '$user'";
            } else {
                $query2 .= " and id_usuario_dece = '$user'";
            }

            $query2 .= " and os.id = dc.id_estudiante 
                        group by os.last_name,os.middle_name,os.first_name,dc.id_estudiante ;";

            // echo '<pre>';
            // print_r($query2);
            // die();

            $arrayCasos[] = $con->createCommand($query2)->queryOne();
        }

        // echo '<pre>';
        // print_r($arrayCasos);
        // die();
        return $arrayCasos;
    }
    private function mostrar_casos_por_usuario($usuario)
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelCasos = DeceCasos::find()
            ->where(['id_usuario' => $usuarioLog])
            ->andWhere(['id_periodo' => $periodoId])
            ->all();
        return $modelCasos;
    }
    private function consulta_estudiantes($scholarisPeriodoId, $usuarioLog)
    {
        //buscamos si el usuario es coordinador
        $modelCoordinadorDece = OpInstituteAuthorities::find()
            ->where(['ilike', 'cargo_descripcion', 'dececoor'])
            ->andWhere(['usuario' => $usuarioLog])
            ->one();

        $con = Yii::$app->db;
        $query =
            "select  distinct c4.id,concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as student,
        concat( c8.name,' ', c7.name ) curso
        from scholaris_clase c1 , scholaris_grupo_alumno_clase c2 ,
         op_institute_authorities c3 ,op_student c4 ,op_student_inscription c5, 
         scholaris_op_period_periodo_scholaris c6,op_course_paralelo c7, op_course c8
        where c3.usuario  = '$usuarioLog' ";
        if ($modelCoordinadorDece) {
            $query .= " and c3.id = c1.coordinador_dece_id";
        } else {
            $query .= " and c3.id = c1.dece_dhi_id";
        }
        $query .= "
        and c1.id = c2.clase_id 
        and c2.estudiante_id = c4.id 
        and c4.id = c5.student_id 
        and c5.period_id  = c6.op_id 
        and c6.scholaris_id = '$scholarisPeriodoId'
        and c7.id = c1.paralelo_id 
        and c8.id = c7.course_id 
        order by student;";


        $res = $con->createCommand($query)->queryAll();

        // echo '<pre>';
        // print_r(count($res));
        // die();

        return $res;
    }
    private function consulta_conteo_por_eje($usuarioLog)
    {
        $con = Yii::$app->db;
        $query =
            "select count(*) as conteo1
        from dece_casos d1
        where d1.id_usuario = '$usuarioLog'
        union all
        select count(*) as conteo2
        from dece_casos d1, dece_registro_seguimiento   r1 
        where d1.id_usuario = '$usuarioLog'
        and r1.id_caso = d1.id 
        union all
        select count(*) as conteo3
        from dece_casos d1, dece_derivacion r1 
        where d1.id_usuario = '$usuarioLog'
        and r1.id_casos = d1.id 
        union all
        select count(*) as conteo4
        from dece_casos d1, dece_deteccion r1 
        where d1.id_usuario = '$usuarioLog'
        and r1.id_caso = d1.id 
        union all
        select count(*) as conteo5
        from dece_casos d1, dece_intervencion r1 
        where d1.id_usuario = '$usuarioLog'
        and r1.id_caso = d1.id 
        ;";

        $res = $con->createCommand($query)->queryColumn();



        return $res;
    }

    /**
     * Displays a single DeceCasos model.
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
     * Creates a new DeceCasos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idEstudiante)
    {
        $model = new DeceCasos();
        $hora = date('H:i:s');
        //la fecha de ingreso viene vacio cuando es un nuevo registro, por eso guarda solo cuando hay fecha de ingreso
        //quiere decir que vino desde la pantalla de de CREACION
        if ($model->load(Yii::$app->request->post()) && isset($_POST['fecha_inicio'])) {
            $decesPorAlumno = $this->mostrar_dece_y_super_dece_por_alumno($model->id_estudiante);

            // echo '<pre>';
            // print_r($decesPorAlumno);
            // die();

            $model->id_usuario_dece = $decesPorAlumno['hdi_dece'];
            $model->id_usuario_super_dece = $decesPorAlumno['super_dece'];
            $model->fecha_inicio = $_POST['fecha_inicio'] . ' ' . $hora;
            $model->save();
            return $this->redirect(['historico', 'id' => $model->id_estudiante]);
        }

        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $ahora = date('Y-m-d H:i:s');
        $ahora = date('Y-m-d');

        $modelDeceCasos = new DeceCasos();
        $modelDeceCasos->id_usuario = $usuario;
        $modelDeceCasos->detalle = '-';
        if ($idEstudiante == 0) //si es igual a cero, es porque biene de INDEX, CREAR CASOS
        {
            $modelDeceCasos->id_estudiante = $_POST['idAlumno'];
        } else {
            $modelDeceCasos->id_estudiante = $idEstudiante;
        }
        $modelDeceCasos->id_periodo = $periodoId;
        $modelDeceCasos->id_clase = 0;
        $modelDeceCasos->fecha_inicio = $ahora;
        $modelDeceCasos->numero_caso = $this->mostrar_numero_maximo_caso($periodoId, $idEstudiante) + 1;


        return $this->render('create', [
            'model' => $modelDeceCasos
        ]);
    }
    private function mostrar_dece_y_super_dece_por_alumno($id_estudiante)
    {
        $con = Yii::$app->db;
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;

        $query = " select  distinct c4.id,concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as student,
                    concat( c8.name,' ', c7.name ) curso, 
                    (select usuario from op_institute_authorities a where a.id=c1.coordinador_dece_id) super_dece,
                    (select usuario from op_institute_authorities a where a.id=c1.dece_dhi_id) hdi_dece,
                    from scholaris_clase c1 , scholaris_grupo_alumno_clase c2 ,
                    op_student c4 ,op_student_inscription c5, 
                    scholaris_op_period_periodo_scholaris c6,op_course_paralelo c7, op_course c8
                    where 
                    c1.id = c2.clase_id 
                    and c2.estudiante_id = c4.id 
                    and c4.id = c5.student_id 
                    and c5.period_id  = c6.op_id 
                    and c6.scholaris_id = '1'
                    and c7.id = c1.paralelo_id 
                    and c8.id = c7.course_id 
                    and c4.id = '$id_estudiante'
                    order by student;";

        $resp = $con->createCommand($query)->queryOne();

        return $resp;
    }

    public function actionCrearDeteccion()
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $fecha = date('Y-m-d H:i:s');

        $idEstudiante = $_GET['id'];
        $idClase = $_GET['id_clase'];

        /*** PROCESO 1*/
        //Buscar si tiene un caso
        $modelDeceCasos = DeceCasos::find()
            ->where(['id_estudiante' => $idEstudiante])
            ->max('numero_caso');

        $decesPorAlumno = $this->mostrar_dece_y_super_dece_por_alumno($idEstudiante);

        $consecutivoCaso = 1;

        if ($modelDeceCasos) {
            // si tiene caso, conseguir el consecutivo mas uno
            $consecutivoCaso = $modelDeceCasos + 1;
        }
        // Crear caso, mas un consecutivo
        //PROCESO 1.-
        $modelDeceCasos = new DeceCasos();
        $modelDeceCasos->numero_caso = $consecutivoCaso;
        $modelDeceCasos->id_estudiante = $idEstudiante;
        $modelDeceCasos->id_periodo = $periodoId;
        $modelDeceCasos->estado = 'PENDIENTE';
        $modelDeceCasos->fecha_inicio =  $fecha;
        $modelDeceCasos->motivo =  'DISCIPLINARIO';
        $modelDeceCasos->detalle =  '-';
        $modelDeceCasos->id_usuario =  $usuarioLog;
        $modelDeceCasos->id_clase = $idClase;
        $modelDeceCasos->id_usuario_dece = $decesPorAlumno['hdi_dece'];
        $modelDeceCasos->id_usuario_super_dece = $decesPorAlumno['super_dece'];

        $modelDeceCasos->save();


        return $this->redirect([
            '/dece-deteccion/create',
            'id_estudiante' => $modelDeceCasos->id_estudiante,
            'id_caso' => $modelDeceCasos->id,
            'es_lexionario' => true
        ]);
    }

    public function actionHistorico()
    {
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $ahora = date('Y-m-d H:i:s');
        $modelDeceCasos = new DeceCasos();

        if (isset($_GET['id'])) {
            $id_estudiante = $_GET['id'];
            $modelDeceCasos->numero_caso = $this->mostrar_numero_maximo_caso($periodoId, $id_estudiante) + 1;
            $modelDeceCasos->id_estudiante =   $id_estudiante;
            $modelDeceCasos->id_clase =   0;
            $modelDeceCasos->id_periodo =   $periodoId;
            $modelDeceCasos->estado = 'PENDIENTE';
            $modelDeceCasos->fecha_inicio = $ahora;
            $modelDeceCasos->motivo = "";
            $modelDeceCasos->detalle = "";
            $modelDeceCasos->id_usuario = $usuario;

            $modelDeceCasos->save();
        }
        return $this->render('historicos', [
            'model' => $modelDeceCasos
        ]);
    }
    public function actionRegGenAcompaniamiento()
    {
        return $this->render('reg_gen_acompaniamiento', [
            'model' => ''
        ]);
    }
    private function mostrar_numero_maximo_caso($id_periodo, $idEstudiante)
    {
        $resp = DeceCasos::find()
            ->where(['id_periodo' => $id_periodo])
            ->andWhere(['id_estudiante' => $idEstudiante])
            ->max('numero_caso');

        return $resp;
    }

    public function actionUpdate($id)
    {
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) &&  isset($_POST['fecha_fin'])) {
            $model->fecha_fin = $_POST['fecha_fin'] . ' ' . $hora;
            $model->save();
            return $this->redirect(['historico', 'id' => $model->id_estudiante]);
        }

        //se asigna la fecha de creacion del seguimiento con la fecha de modificacion, para cargar en pantalla
        if ($model) {
            $model->fecha_fin = $fechaActual;
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DeceCasos model.
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
    public function actionMostrarReporteGeneral()
    {
        /*Creado por: Santiago  Fecha: 2023-04-27
            Modificado Por:     Fecha:
            Descripcion: Metodo que despliega el reporte general dece de acompañamientos, sin filtrar
        */

        $usuario = $_POST['usuario'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $id_alumno =0;
        //id_alumno, permite saber si la consulta es filtrada por alumno o no
        if(isset($_POST['id_alumno'])){$id_alumno=$_POST['id_alumno'];}

        $html = '';
        $con = Yii::$app->db;
        //Numero de Seguimientos por Usuario
        $query = "select COUNT(a1.estado)
        from dece_registro_seguimiento a1, dece_casos a4
        where a1.id_caso = a4.id
        and a1.fecha_inicio between '$fecha_inicio' and '$fecha_fin'       
        and (a4.id_usuario_dece ilike '$usuario'  
        or a4.id_usuario_super_dece ilike '$usuario'  );";
        $total = $con->createCommand($query)->queryScalar();

        //Numero de seguimientos con estado FINALIZADO
        $query = "select COUNT(a1.estado)
        from dece_registro_seguimiento a1, dece_casos a4
        where a1.id_caso = a4.id
        and a1.estado ='FINALIZADO'
        and a1.fecha_inicio between '$fecha_inicio' and '$fecha_fin'       
        and (a4.id_usuario_dece ilike '$usuario'  
        or a4.id_usuario_super_dece ilike '$usuario'  );  ";
        $resueltos = $con->createCommand($query)->queryScalar();

        //Consulta de todos los alumnos que aparecen en la consulta, NOMBRE Y EL ID 

        //Consulta QUE DEVUELVE, NOMBRE DE LOS ESTUDIANTES Y EL ID, PARA POSTERIOR DESPELGAR EN PANTALLA POR ALUMNO LOS SEGUIMIENTOS
        $query = "select distinct 
        (select concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as estudiante from op_student c4 where c4.id = a1.id_estudiante ) as estudiante,
        a1.id_estudiante as id
        from dece_registro_seguimiento a1, dece_seguimiento_acuerdos a2,dece_casos a4
        where a2.id_reg_seguimiento  = a1.id 
        and a1.id_caso = a4.id
        and a1.fecha_inicio between '$fecha_inicio' and '$fecha_fin'      
        and (a4.id_usuario_dece ilike '$usuario' 
        or a4.id_usuario_super_dece ilike '$usuario' 
         );";

        $listEstudiantes = $con->createCommand($query)->queryAll();


        $modelPersonasA = DeceMotivos::find()
            ->select(['id', 'atencion_para'])
            ->distinct()
            ->where(['not', ['atencion_para' => null]])
            ->orderBy(['atencion_para' => SORT_ASC])
            ->all();
        $modelMotivos = DeceMotivos::find()
            ->select(['id', 'motivo'])
            ->distinct()
            ->where(['not', ['motivo' => null]])
            ->orderBy(['motivo' => SORT_ASC])
            ->all();
        $modelEstado = DeceMotivos::find()
            ->select(['id', 'estado_seg'])
            ->distinct()
            ->where(['not', ['estado_seg' => null]])
            ->orderBy(['estado_seg' => SORT_ASC])
            ->all();

        $html = "";
        $html .= '<table class="table table-striped table-bordered">';
        $html .= '<tr>
                    <td>NÚMERO DE CASOS ATENDIDOS:    <b>' . $total . '</b></td> 
                    <td> NÚMERO DE CASOS RESUELTOS:   <b>' . $resueltos . '</b></td>                   
                </tr>';
        $html .= '</table>';

        //tabla con los estudiantes para generar un filtro, en caso desplegarse Muchos estudiantes
        $html .= '
            <table class="table table-striped table-bordered">
            <tr>
                <td colspan="2">
                Total: ' . count($listEstudiantes) . '
                </td>
            </tr>
            <tr>
                <td>                
                <select id="alumno" name="alumno" class="form-control select2 select2-accessible" >
                    <option selected>Seleccione Estudiante para Filtrar</option>';
        foreach ($listEstudiantes as $model) {
            $html .= '<option value="' . $model['id'] . '">' . $model['estudiante'] . '</option>';
        }
        $html .= '</select>
                </td>
                <td>
                    <button  class="btn btn-danger" onclick="mostrar_reporte_general()">FILTRAR</button>
                </td>
            </tr>
        </table>
        ';
        return $html.$this->html_cabecera_reporte_general($modelPersonasA, $modelMotivos, $modelEstado, $listEstudiantes, $_POST,$id_alumno);
    }
    private function html_cabecera_reporte_general($modelPersonasA, $modelMotivos, $modelEstado, $listEstudiantes, $listPost,$id_alumno)
    {
        $html = '';
        $html .= '<table class="table table-striped table-bordered">
        <tr >
            <td width="20px">
                
            </td>
            <td width="20px">
                <b>No</b>
            </td>
            <td width="120px">
                <b>Fecha</b>
            </td>
            <td width="300px">
                <b>Nómina de Estudiantes</b>
            </td>
            <td width="100px">
                <span style="writing-mode: vertical-lr;transform: rotate(180deg);"><b>Año</b></span>
            </td>
            <td width="30px">
                <span style="writing-mode: vertical-lr;transform: rotate(180deg);"><b>Paralelo</b></span>
            </td>
            <td >
                <table class="border">                    
                    <tr><td colspan="' . count($modelPersonasA) . '"><b>Persona/s atendida/s</b></td></tr>';
        $html .= '<tr>';
        foreach ($modelPersonasA as $model) {
            $html .= '<td class="border" width="30px" style="writing-mode: vertical-lr;transform: rotate(180deg);"><b>'
                . $model->atencion_para .
                '</b></td>';
        }
        $html .= '</tr>';
        $html .= '                        
                </table>
            </td>
            <td width="300">
                <b>Acuerdos</b>
            </td>
            <td>
                <table class="border"> 
                    <tr>';
        foreach ($modelMotivos as $model1) {
            $html .= '<td class="border" width="30px" style="writing-mode: vertical-lr;transform: rotate(180deg);" ><b>'
                . substr($model1->motivo, 0, 20) .
                '</b></td>';
        }
        $html .= '</tr>';
        $html .= '</table>
            <td>
               <table class="border">                    
                    <tr><td colspan="' . count($modelEstado) . '"><b>Estado de los casos</b></td></tr>';
        $html .= '<tr>';
        foreach ($modelEstado as $model) {
            $html .= '<td class="border" width="30px" style="writing-mode: vertical-lr;transform: rotate(180deg);"><b>'
                . substr($model->estado_seg, 0, 20) .
                '</b></td>';
        }
        $html .= '</tr>';
        $html .= '                        
                </table>
            </td>';
        $html .= $this->cuerpo_rep_gen_seguimiento($modelPersonasA, $modelMotivos, $modelEstado, $listPost, $listEstudiantes,$id_alumno);
        $html .= '</tr>
        
    </table>';

        return $html;
    }
    private function cuerpo_rep_gen_seguimiento($modelPersonasA, $modelMotivos, $modelEstado, $arrayPost, $listEstudiantes,$id_alumno)
    {
        $usuario = $arrayPost['usuario'];
        $fecha_inicio = $arrayPost['fecha_inicio'];
        $fecha_fin = $arrayPost['fecha_fin'];

        $html = '';
        $objScript = new Scripts();
        $con = Yii::$app->db;
    

        //Consulta QUE DEVUELVE, TODA LA INFORMACION PARA EL REPORTE POR ESTUDIANTE Y SEGUIMEINTO
        $query = "select distinct a1.numero_seguimiento,a1.id as id_seguimiento ,a1.fecha_inicio,a1.id_estudiante,
        (select concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as estudiante from op_student c4 where c4.id = a1.id_estudiante ),
        '' as anio, '' as paralelo,a2.parentesco, a2.acuerdo ,
        a2.id as id_firmas , a1.motivo ,a1.estado ,a1.id_estudiante 
        from dece_registro_seguimiento a1, dece_seguimiento_acuerdos a2,dece_casos a4
        where a2.id_reg_seguimiento  = a1.id 
        and a1.id_caso = a4.id
        and a1.fecha_inicio between '$fecha_inicio' and '$fecha_fin'      
        and (a4.id_usuario_dece ilike '$usuario' 
        or a4.id_usuario_super_dece ilike '$usuario' )";
        if($id_alumno>0)
        {
            $query.=" and a1.id_estudiante  = '$id_alumno';";
        }
        $query.=';';

        $listRegAcompaniamiento = $con->createCommand($query)->queryAll();
        $cont=0;
        $nombreEstudiante ='';
        foreach ($listEstudiantes as $reg1) {
            
            
            foreach ($listRegAcompaniamiento as $reg) 
            {
                
                //para ayudar a que solo apareza una vez los datos del estudiante
                if ($reg1['estudiante'] == $reg['estudiante']) 
                {
                    $cursoParalelo = $objScript->mostrar_curso_estudiante($reg['id_estudiante']);

                    $tablePersonas = $this->mostrar_seleccion_listadp($modelPersonasA, 'atencion_para', $reg['parentesco']);
                    $tableMotivo = $this->mostrar_seleccion_listadp($modelMotivos, 'motivo', $reg['motivo']);
                    $tableEstado = $this->mostrar_seleccion_listadp($modelEstado, 'estado_seg', $reg['estado']);
                    // $cursoParalelo = explode(" ",$cursoParalelo[0]);
                    $html .= '
                    <tr >';
                    if($cont==0)
                    {
                                 
                        $html.='<td>
                        <a href="../dece-registro-seguimiento/update?id=' . $reg['id_seguimiento'] . '" target="_blank"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        </td>
                        <td width="20px">
                            ' . $reg['numero_seguimiento'] . '
                        </td>
                        <td width="100px">
                            ' . substr($reg['fecha_inicio'], 0, 10) . '
                        </td>
                        <td width="300px">
                            ' . $reg['estudiante'] . '
                        </td>
                        <td >
                            ' . $cursoParalelo[0]['curso1'] . '
                        </td>
                        <td >
                            ' . $cursoParalelo[0]['paralelo'] . '
                        </td>';
                    }else
                    {
                        $html .= '  <td></td>
                                    <td width="20px"></td>
                                    <td width="100px"> </td>
                                    <td width="300px"></td>
                                    <td ></td>
                                    <td ></td>';
                    }
                    $html.='<td >
                            ' . $tablePersonas . '
                        </td>
                        <td >
                            ' . $reg['acuerdo'] . '
                        </td>';
                    if($cont==0)
                    {        
                        $html.='<td >
                            ' . $tableMotivo . '
                        </td>
                        <td >
                            ' . $tableEstado . '
                        </td>
                        ';
                    }else
                    {
                        $html.='<td > </td>
                                <td ></td>
                                ';
                    }


                    $html .= '</tr>';
                    $cont++; 
                    

                            
                }
                
                
            }
            $cont=0;
            // if(count($listEstudiantes)>1)
            // {
            // $html .= '<tr  style="background-color:#AAE0F9"><td colspan="10"></td></tr>';
            // }
           
        }

        return $html;
    }
    private function mostrar_seleccion_listadp($modelList, $campoBusquedaSql, $palabraBusquedaArray)
    {
        $arrayPersonas = ArrayHelper::map($modelList, 'id', $campoBusquedaSql);
        $key = array_search($palabraBusquedaArray, $arrayPersonas);
        //PERSONAS ATENDIDAS
        $html = "<table >
                <tr>";
        foreach ($modelList as $model) {
            if ($key == $model->id) {
                $html .= "<td class='border' width='30px' align='center'>X</td>";
            } else {
                $html .= "<td class='border'  width='30px' align='center'></td>";
            }
        }
        $html .= "</tr>
        </table>";
        return $html;
    }


    /**
     * Finds the DeceCasos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceCasos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceCasos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
