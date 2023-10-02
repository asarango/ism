<?php

namespace backend\controllers;

//use backend\models\pca\DatosInformativos as PcaDatosInformativos;

use backend\models\DipOpciones;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionVerticalDiploma;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use backend\models\puddip\Pdf;
use backend\models\helpers;
use backend\models\helpers\Scripts;
use backend\models\Lms;
use backend\models\lms\LmsColaborativo;
use backend\models\PudAprobacionBitacora;
use backend\models\puddip\DatosInformativos;
use backend\models\ScholarisClase;
use backend\models\ScholarisPeriodo;
use Codeception\Lib\Generator\Helper;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use DateTime;

class PudDipController extends Controller {

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

    /*     * **************************************************************************************************************************************** */
    /*     * * ACCIONES  */

    public function actionIndex1() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $planBloqueUnidadId = $_GET['plan_bloque_unidad_id'];
        $planUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        
        $opCourseTemplateId = $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;        

        $bitacora = PudAprobacionBitacora::find()->where([
                    'unidad_id' => $planBloqueUnidadId
                ])
                ->orderBy(['id' => SORT_DESC])
                ->one();

        if ($bitacora) {
            $usuarioResponde = $bitacora->usuario_responde;
            $fechaResponde = $bitacora->fecha_responde;
            $usuarioNotifica = $bitacora->usuario_notifica;
            $fechaNotifica = $bitacora->fecha_notifica;
        } else {
            $usuarioResponde = null;
            $fechaResponde = null;
            $usuarioNotifica = null;
            $fechaNotifica = null;
        }

        $scripts = new Scripts();
        $firmaAprobado = $scripts->firmar_documento($usuarioResponde, $fechaResponde);

        $firmaDocente = $scripts->firmar_documento($usuarioNotifica, $fechaNotifica);
               

        return $this->render('index', [
                    'planUnidad' => $planUnidad,
                    'bitacora' => $bitacora,
                    'firmaAprobado' => $firmaAprobado,
                    'firmaDocente' => $firmaDocente      
        ]);
    }
    

    public function actionPestana() {

        $planUnidadId = $_GET['plan_unidad_id'];
        $pestana = $_GET['pestana'];
        $respuesta = '';

        switch ($pestana) {
            case '1.1.-':
                $respuesta = $this->get_datos_informativos($planUnidadId);
                break;
            case '2.1.-':
                $respuesta = $this->get_descripcion_text_unidad($planUnidadId);
                break;
            case '3.1.-':
                $respuesta = $this->get_evaluacion_pd_unidad($planUnidadId);
                break;
            case '4.1.-':
                $respuesta = $this->get_indagacion($planUnidadId);
                break;
            case '4.2.-':
                $respuesta = $this->get_accion_habilidades($planUnidadId);
                break;
            case '5.0.-':
                $respuesta = $this->get_accion_semanas($planUnidadId);
                break;
            case '5.1.-':
                $respuesta = $this->get_accion_evaluaciones($planUnidadId);
                break;
            case '5.2.-':
                $respuesta = $this->get_accion_proceso_aprendizaje($planUnidadId);
                break;
            case '5.3.-':
                $respuesta = $this->get_accion_enfoque_aprendizaje($planUnidadId);
                break;
            case '5.3.1.-':
                $respuesta = $this->get_accion_metacognicion($planUnidadId);
                break;
            case '5.3.2.-':
                $respuesta = $this->get_accion_diferenciacion($planUnidadId);
                break;
            case '5.4.-':
                $respuesta = $this->get_accion_lenguaje_aprendizaje($planUnidadId);
                break;
            case '5.4.1.-':
                $respuesta = $this->get_accion_lenguaje_aprendizaje($planUnidadId);
                break;
            case '5.5.-':
                $respuesta = $this->get_accion_conexion_tdc($planUnidadId);
                break;
            case '5.6.-':
                $respuesta = $this->get_accion_conexion_cas($planUnidadId);
                break;
            // case '5.6.1.-':
            //     $respuesta = $this->get_accion_conexion_cas($planUnidadId);
            case '5.7.-':
                $respuesta = $this->get_accion_nee($planUnidadId);
                break;
            case '5.8.-':
                $respuesta = $this->get_accion_talentos($planUnidadId);
                break;
            case '5.9.-':
                $respuesta = $this->get_accion_ods($planUnidadId);
                break;
            case '6.1.-':
                $respuesta = $this->get_accion_recurso($planUnidadId);
                break;
            case '7.1.-':
                $respuesta = $this->get_accion_lo_que_funciono($planUnidadId);
                break;
            case '7.2.-':
                $respuesta = $this->get_accion_lo_que_no_funciono($planUnidadId);
                break;
            case '7.3.-':
                $respuesta = $this->get_accion_observacion($planUnidadId);
                break;
        }
        return $respuesta;
    }

    /*     * * Actualiza porcentaje de avance deL PUD del DIP */

    private function pud_dip_actualiza_porcentaje_avance($modelPlanVertDipl) 
    {        
        $modelPlanBloqUni = PlanificacionBloquesUnidad::findOne($modelPlanVertDipl->planificacion_bloque_unidad_id);
        //consulta para extraer el porcentaje de avance del PUD DIPLOMA        

        $obj2 = new Scripts();
        $pud_dip_porc_avance = $obj2->pud_dip_porcentaje_avance($modelPlanVertDipl->id, $modelPlanVertDipl->planificacion_bloque_unidad_id);

        $modelPlanBloqUni->avance_porcentaje = $pud_dip_porc_avance['porcentaje'];        
        $modelPlanBloqUni->save();
    }

    //Retorna el Modelo de Plan Vertical Diploma
    private function retornoModelPlanVarticalDiploma($idPlanVertDip) {
        $model = PlanificacionVerticalDiploma::find()->where([
                    'id' => $idPlanVertDip
                ])->one();
        return $model;
    }

    //2.1.- Descripcion y Texto de una Unidad
    public function actionUpdateDescriTextUni() {
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido = $_GET['contenido'];
        $accion_update = $_GET['accion'];

        $model = $this->retornoModelPlanVarticalDiploma($idPlanVertDip);
        $model->ultima_seccion = $accion_update;
        $model->descripcion_texto_unidad = $contenido;

        $model->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($model);
    }

    //4.2.- Contenido, Habilidades y conceptos
    public function actionUpdateHabilidades() {
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido = $_GET['contenido'];
        $accion_update = $_GET['accion'];

        $model = $this->retornoModelPlanVarticalDiploma($idPlanVertDip);
        $model->ultima_seccion = $accion_update;

        $model->habilidades = $contenido;
        $model->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($model);
    }       

    //5.2.- Proceso de Aprendizaje
    public function actionUpdateProcesoAprendizaje() {
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido = $_GET['contenido'];
        $accion_update = $_GET['accion'];

        $model = $this->retornoModelPlanVarticalDiploma($idPlanVertDip);
        $model->ultima_seccion = $accion_update;

        $model->proceso_aprendizaje = $contenido;
        $model->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($model);
    }

    //5.4.- Lenguaje de Aprendizaje
    public function actionUpdateLenguajeAprendizaje() {
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido = $_GET['contenido'];
        $accion_update = $_GET['accion'];

        $model = $this->retornoModelPlanVarticalDiploma($idPlanVertDip);
        $model->ultima_seccion = $accion_update;

        $model->detalle_len_y_aprendizaje = $contenido;
        $model->save();
    }

    //5.4.1.- Conexion CAS
    public function actionUpdateLenguajeAprendizajeCheck() {

        $idPvd = $_GET['id_plani_vert_dip'];
        $idPvd_Op = $_GET['id_pvd_op'];
        $tipoProc = $_GET['tipo_proceso'];
        $accion_update = $_GET['accion'];

        $this->insertUpdateConexionCas($idPvd, $idPvd_Op, $tipoProc);
        //guarda el porcentaje de avance del pud dip
        $model = PlanificacionVerticalDiploma::find()->where([
                    'id' => $idPvd
                ])->one();
        $model->ultima_seccion = $accion_update;
        $model->save();
        $this->pud_dip_actualiza_porcentaje_avance($model);
    }

    //5.5.- Conexion TDC
    public function actionUpdateConexionTdc() {
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido = $_GET['contenido'];
        $accion_update = $_GET['accion'];

        $model = $this->retornoModelPlanVarticalDiploma($idPlanVertDip);
        $model->ultima_seccion = $accion_update;

        $model->conexion_tdc = $contenido;
        $model->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($model);
    }

    //5.6.- Conexion CAS
    public function actionUpdateConexionCas() {
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido = $_GET['contenido'];
        $accion_update = $_GET['accion'];

        $model = $this->retornoModelPlanVarticalDiploma($idPlanVertDip);
        $model->ultima_seccion = $accion_update;

        $model->detalle_cas = $contenido;
        $model->save();
    }

    //5.6.1.- Conexion CAS
    public function actionUpdateConexionCasCheck() {

        $idPvd = $_GET['id_plani_vert_dip'];
        $idPvd_Op = $_GET['id_pvd_op'];
        $tipoProc = $_GET['tipo_proceso'];
        $accion_update = $_GET['accion'];

        $accionUpdate = $accion_update = $_GET['accion'] == '5.6.1.-' ? '5.6.-' : $_GET['accion'];

        $this->insertUpdateConexionCas($idPvd, $idPvd_Op, $tipoProc);
        //guarda el porcentaje de avance del pud dip
        $model = PlanificacionVerticalDiploma::find()->where([
                    'id' => $idPvd
                ])->one();
        $model->ultima_seccion = $accionUpdate;
        $model->save();
        $this->pud_dip_actualiza_porcentaje_avance($model);
    }

    private function insertUpdateConexionCas($idPvd, $idPvd_Op, $tipo_proceso) {
        //realiza la actualizacion de conexion cas TDC       
        $userLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s');
        $plan_vertical_id = $idPvd;
        $pvd_tdc_id = $idPvd_Op;

        if ($tipo_proceso == 'Agregar') {
            $model = new PlanificacionVerticalDiplomaRelacionTdc();
            $model->vertical_diploma_id = $plan_vertical_id;
            $model->relacion_tdc_id = $pvd_tdc_id;
            $model->created = $userLog;
            $model->created_at = $fechaHoy;
            $model->save();
        } else {
            $model = PlanificacionVerticalDiplomaRelacionTdc::findOne($pvd_tdc_id);
            $model->delete();
        }
    }

    //6.1.- Recursos
    public function actionUpdateRecursos() {
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido = $_GET['contenido'];
        $accion_update = $_GET['accion'];

        $model = $this->retornoModelPlanVarticalDiploma($idPlanVertDip);
        $model->ultima_seccion = $accion_update;

        $model->recurso = $contenido;
        $model->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($model);
    }

    //7.1.- funciono
    public function actionUpdateFunciono() {
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido = $_GET['contenido'];
        $accion_update = $_GET['accion'];

        $model = $this->retornoModelPlanVarticalDiploma($idPlanVertDip);
        $model->ultima_seccion = $accion_update;

        $model->reflexion_funciono = $contenido;
        $model->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($model);
    }

    //7.2.- no funciono
    public function actionUpdateNoFunciono() {
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido = $_GET['contenido'];
        $accion_update = $_GET['accion'];

        $model = $this->retornoModelPlanVarticalDiploma($idPlanVertDip);
        $model->ultima_seccion = $accion_update;

        $model->reflexion_no_funciono = $contenido;
        $model->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($model);
    }

    //7.3.- no funciono
    public function actionUpdateObservacion() {
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido = $_GET['contenido'];
        $accion_update = $_GET['accion'];

        $model = $this->retornoModelPlanVarticalDiploma($idPlanVertDip);
        $model->ultima_seccion = $accion_update;

        $model->reflexion_observacion = $contenido;
        $model->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($model);
    }

    //generador pdf, pud dip
    public function actionPdfPudDip() {
        $idPlanUniBloque = $_GET['planificacion_unidad_bloque_id'];        
        new Pdf($idPlanUniBloque);
    }

    /*     * * FIN  ACCIONES  */
    /*     * **************************************************************************************************************************************** */
    /*     * * METODOS CONSULTAS  */

    /*     * * 1.-  Datos Informativos */

    private function get_datos_informativos($planUnidadId) {
        //llamada a los  modelos

        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);
        $scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;

        $tiempo = $this->calcula_horas($planBloqueUnidad->planCabecera->ism_area_materia_id,
                $planBloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id,
                $scholarisPeriodoId, $planBloqueUnidad);

        //creacion html                                           
        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 70%; margin-top:20px">';

        $html .= '<div class="card-header">';
        $html .= '<h5 class=""><b>1.- DATOS INFORMATIVOS</b></h5>';
        $html .= '</div>';

        $html .= '<div class="card-body">';
        // inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>GRUPO DE ASIGNATURAS Y DISCIPLINA</b></div>';
        $html .= '<div class="col">' . $planBloqueUnidad->planCabecera->ismAreaMateria->materia->nombre . '</div>';
        $html .= '<div class="col"><b>PROFESOR(ES)</b></div>';
        $docentes = $this->get_docentes($planBloqueUnidad, $scholarisPeriodoId);
        $html .= '<div class="col">';
        foreach ($docentes as $docente) {
            $html .= '◘ ' . $docente['docente'] . ' <br> ';
        }
        $html .= '</div>';
        $html .= '</div>';
        //******finaliza row
        $html .= '<hr>';
        //inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>UNIDAD Nº</b></div>';
        $html .= '<div class="col">' . $planBloqueUnidad->curriculoBloque->last_name . '</div>';
        $html .= '<div class="col"><b>TÍTULO DE LA UNIDAD</b></div>';
        $html .= '<div class="col">' . $planBloqueUnidad->unit_title . '</div>';
        $html .= '</div>';
        //******finaliza row
        $html .= '<hr>';
        //inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>AÑO DEL DIP:</b></div>';
        $html .= '<div class="col">' . $planBloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name . '</div>';
        $html .= '<div class="col"><b>DURACIÓN DE LA UNIDAD EN HORAS:</b></div>';
        $html .= '<div class="col">' . $tiempo['horas'] . '</div>';
        $html .= '</div>';
        //******finaliza row
        $html .= '<hr>';
        //inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>FECHA INICIO:</b></div>';
        $html .= '<div class="col">' . $tiempo['fecha_inicio'] . '</div>';
        $html .= '<div class="col"><b>FECHA FIN:</b></div>';
        $html .= '<div class="col">' . $tiempo['fecha_final'] . '</div>';
        $html .= '</div>';
        //******finaliza row
        $html .= '</div>'; //fin de card-body
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    private function get_docentes($planBloqueUnidad, $scholarisPeriodoId) {
        $materiaId = $planBloqueUnidad->planCabecera->ism_area_materia_id;
        $templateId = $planBloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;

        $con = Yii::$app->db;

        $query = "select 	concat(f.x_first_name,' ', f.last_name) as docente 
                    from 	scholaris_clase c 
                                    inner join ism_area_materia am on am.id = c.ism_area_materia_id 
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                    inner join op_course_paralelo par on par.id = c.paralelo_id  
                                    inner join op_course oc on oc.id = par.course_id  
                                    inner join op_faculty f on f.id = c.idprofesor 
                    where 	c.ism_area_materia_id = $materiaId
                                    and pm.scholaris_periodo_id  = $scholarisPeriodoId 
                                    and oc.x_template_id = $templateId
                    group by f.x_first_name, f.last_name;";

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    private function calcula_horas($materiaId, $courseTemplateId, $scholarisPeriodoId, $planBloqueUnidad) {
        $con = Yii::$app->db;

        $query = "select count(h.detalle_id) as hora_semanal ,h.clase_id ,cla.tipo_usu_bloque 
                    from scholaris_horariov2_horario h inner join scholaris_clase cla on cla.id = h.clase_id 
                    where h.clase_id = (select max(clase.id) from op_course_template t 
                                                                    inner join op_course c on c.x_template_id = t.id inner join op_course_paralelo p on p.course_id = c.id 
                                                                    inner join op_section s on s.id = c.section inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                                                                    inner join scholaris_clase clase on clase.paralelo_id = p.id 
                                                            where t.id = $courseTemplateId and sop.scholaris_id = $scholarisPeriodoId 
                                                                and clase.ism_area_materia_id = $materiaId
                                                                            and clase.id = cla.id) 
                    group by h.clase_id, cla.tipo_usu_bloque;";

        $resH = $con->createCommand($query)->queryOne();

        $horasSemana = $resH['hora_semanal'];
        $uso = $resH['tipo_usu_bloque'];
        $orden = $planBloqueUnidad->curriculoBloque->code;

        $queryFechas = "select 	b.bloque_inicia 
                                ,b.bloque_finaliza 
                        from 	scholaris_bloque_actividad b
                                inner join scholaris_periodo p on p.codigo = b.scholaris_periodo_codigo 
                        where 	b.tipo_uso = '$uso'
                                and p.id = $scholarisPeriodoId
                                and b.orden = $orden;";
        $resF = $con->createCommand($queryFechas)->queryOne();

        $fechaInicia = new DateTime($resF['bloque_inicia']);
        $fechaFinal = new DateTime($resF['bloque_finaliza']);

        $diff = $fechaInicia->diff($fechaFinal);

        return array(
            'horas' => ($diff->days) * $horasSemana,
            'fecha_inicio' => $resF['bloque_inicia'],
            'fecha_final' => $resF['bloque_finaliza']
        );
    }

    /*     * * FIN 1.- CONSULTA DATOS INFORMATIVOS */

    /*     * * 2.1.-  Descripcion y textos de la Unidad */

    private function get_descripcion_text_unidad($planBloqueUnidad) {
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "2.1.-";
        $titulo = "2.1.- DESCRIPCION Y TEXTO DE LA UNIDAD";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();

    //    echo '<pre>';
    //     print_r($planBloqueUnidad);
    //     die();

        return $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->descripcion_texto_unidad, $titulo, $accion_update, "");
    }

    /*     * * 3.1.-  EVALUACION DEL PD PARA LA UNIDAD */

    private function get_evaluacion_pd_unidad($planBloqueUnidad) {
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "3.1.-";
        $titulo = "3.1.- EVALUACION DEL PD PARA LA UNIDAD";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();
        return $this->mostrar_campo_viene_de($planifVertDipl->id, $planifVertDipl->objetivo_evaluacion, $titulo, $accion_update, "");
    }

    /*     * * 4.1.-  Indagacion */

    private function get_indagacion($planBloqueUnidad) {
        $text_intro = "OBJETIVOS DE TRANFERENCA
        <br>
        Haga una lista de uno a tres objetivos grandes, globales y de largo plazo para esta unidad. Los objetivos de transferencia 
        son aquellos que los estudiantes aplicarán, sus conocimientos, habilidades y conceptos al final de la unidad bajo circunstancias 
        nuevas / diferentes, y por si mismos sin el andamiaje del maestro. 
        <hr>";
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "4.1.-";
        $titulo = "4.1.- INDAGACION";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();
        return $this->mostrar_campo_viene_de($planifVertDipl->id, $planifVertDipl->objetivo_asignatura, $titulo, $accion_update, $text_intro);
    }

    /*     * * 4.2 Accion contenido habilidad y concepto */

    private function get_accion_habilidades($planBloqueUnidad) {
        $contenidoImp = '';
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "4.2.-";
        $titulo = "4.2.- CONTENIDO, HABILIDADES Y CONCEPTOS: CONOCIMIENTOS ESENCIALES";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();
        //bucle para capturar el contenido
        $arrayContenido = $this->select_contenidos($planBloqueUnidad->id);

        if (count($arrayContenido) > 0) {
            foreach ($arrayContenido as $arraySubContenido) {
                $contenidoImp .= '<li>';
                $contenidoImp .= '<u><b>' . $arraySubContenido['subtitulo'] . '</b></u>';
                $contenidoImp .= '<ul>';
                foreach ($arraySubContenido['subtitulos'] as $contenido) {
                    $contenidoImp .= '<li>♠ ' . $contenido['contenido'] . '</li>';
                }
                $contenidoImp .= '</ul>';
                $contenidoImp .= '</li>';
            }
        }
        //FIN bucle para capturar el contenido       
        $contenido = $this->mostrar_campo_viene_de($planifVertDipl->id, $planifVertDipl->contenido, "CONTENIDOS", $accion_update, "");
        $habilidad = $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->habilidades, "HABILIDADES", $accion_update, "");
        $concepto = $this->mostrar_campo_viene_de($planifVertDipl->id, $planifVertDipl->concepto_clave, "CONCEPTOS", $accion_update, "");
        $respuesta = $this->mostrar_campo_viene_de($planifVertDipl->id, $contenido . $habilidad . $concepto, $titulo, $accion_update, "");
        //$respuesta = $contenido.$habilidad.$concepto ; 
        return $respuesta;
    }


    /**
     * MÉTODO PARA LAS SEMANAS DEL PLAN DE UNIDAD
     * 5.0.- SEMANAS
     * Realizado por Arturo Sarango
     * 2023-03-29
     * Actualizado por Arturo Sarango
     * 2023-03-29
     */
    public function get_accion_semanas($planBloqueUnidadId){
        
        $periodId = Yii::$app->user->identity->periodo_id;
        $login = Yii::$app->user->identity->usuario;
        $periodo = ScholarisPeriodo::findOne($periodId);
        

        $planUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        $bloqueShotName = $planUnidad->curriculoBloque->shot_name;
        

        $ismAreaMateriaId = $planUnidad->planCabecera->ism_area_materia_id;
        $clase = ScholarisClase::find()->where(['ism_area_materia_id' => $ismAreaMateriaId])->one();
        $courseId = $clase->paralelo->course_id;
        
        
        $uso = $clase->tipo_usu_bloque;
        $paralelos = $this->obtener_paralelos($courseId,$login,$ismAreaMateriaId);
                

        $accion_update = "5.0.-";

        $modelPlanVertical = PlanificacionVerticalDiploma::find()->where(['planificacion_bloque_unidad_id' => $planBloqueUnidadId])->one();
        $modelPlanVertical->ultima_seccion = $accion_update;
        $modelPlanVertical->save();
        


        /***Para consultar las semanas e inyectar */
        $semanas = $this->query_semanas($periodo->codigo, $uso, $bloqueShotName);
        foreach($semanas as $semana){
             /** Para inyectar las horas al plan semanal */
            $lms = new LmsColaborativo();
            $lms->inyecta_plan_x_hora($clase->ismAreaMateria->total_horas_semana, $semana['semana_numero'], $clase->ism_area_materia_id, $uso);
            /** Fin de inyección de horas al plan semanal */
            
        }
        /***Fin consultar las semanas e inyectar */        

        $lmsColaborativo = new LmsColaborativo();
        $planesSemanales = $lmsColaborativo->planes_semanales_x_unidad($semanas, $ismAreaMateriaId, $uso);

        return $this->renderPartial('_semanas',[
            'planesSemanales' => $planesSemanales,
            'planUnidadId' => $planUnidad->id,
            'paralelos' => $paralelos,

        ]);
    }
    
    /**
     * MÉTODO PARA LAS SEMANAS DEL SQL
     * 5.0.- SEMANAS
     * Realizado por Arturo Sarango
     * 2023-03-29
     * Actualizado por Arturo Sarango
     * 2023-03-29
     */
    private function query_semanas($periodCode, $uso, $bloqueCurriculoShotName){
        $con = Yii::$app->db;
        $query = "select 	sem.id as semana_id
                            ,sem.semana_numero 
                            ,sem.nombre_semana 		
                            ,sem.fecha_inicio 
		                    ,sem.fecha_finaliza 
                    from 	scholaris_bloque_semanas sem
                            inner join scholaris_bloque_actividad blo on blo.id = sem.bloque_id 
                            inner join scholaris_periodo per on per.codigo = blo.scholaris_periodo_codigo 
                            inner join curriculo_mec_bloque mbl on mbl.shot_name = blo.abreviatura 
                    where 	per.codigo = '$periodCode'
                            and blo.tipo_uso = '$uso'
                            and mbl.shot_name = '$bloqueCurriculoShotName'
                    order by sem.semana_numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
        
    }


     /**
     * MÉTODO PARA LAS SEMANAS DEL SQL
     * 5.0.- SEMANAS
     * Realizado por Arturo Sarango
     * 2023-03-29
     * Actualizado por Arturo Sarango
     * 2023-03-29
     */
    private function obtener_paralelos($courseId, $login, $ismAreaMateriaId){
        $con = Yii::$app->db;
        $query = " select   sc.id as clase_id, ocp.name  as paralelo 
                     from   scholaris_clase sc 
                            inner join op_course_paralelo ocp on ocp.id = sc.paralelo_id 
                            inner join op_faculty of2 on of2.id  = sc.idprofesor
                            inner join res_users ru on ru.partner_id = of2.partner_id
                     where  ocp.course_id = $courseId and ru.login = '$login'
                            and sc.ism_area_materia_id = $ismAreaMateriaId
                     order by ocp.name ;";
        $res =  $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * MÉTODO PARA REDIRECCIONAR A PLAN SEMANAL UNITARIO
     * Realizado por Arturo Sarango
     * 2023-08-02
     * Actualizado por Arturo Sarango
     * 2023-08-02
     */
    public function actionRedirectPs(){
        $lmsId      = $_GET['lms_id'];
        $claseId    = $_GET['clase_id'];
        $pudOrigen  = $_GET['pud_origen'];
        $planBloqueUnidadId = $_GET['plan_bloque_unidad_id'];
        $periodoId = Yii::$app->user->identity->periodo_id;

        $periodo = ScholarisPeriodo::findOne($periodoId);
        $periodoCode = $periodo->codigo;

        $lms = Lms::findOne($lmsId);
        $uso = $lms->tipo_bloque_comparte_valor;
        $semanaNumero = $lms->semana_numero;

        $bloque = $this->obtener_bloque($uso, $semanaNumero, $periodoCode);


        return $this->redirect(['planificacion-semanal/index1',
            'bloque_id' => $bloque['bloque_id'],
            'clase_id' => $claseId,
            'semana_defecto' => $bloque['semana_id'],
            'pud_origen' => $pudOrigen,
            'plan_bloque_unidad_id' => $planBloqueUnidadId
        ]);
    }


    private function obtener_bloque($uso, $semanaNumero, $periodoCode){
        $con = Yii::$app->db;
        $query = "select 	blo.id as bloque_id
                            ,sem.id as semana_id
                    from 	scholaris_bloque_actividad blo
                            inner join scholaris_bloque_semanas sem on sem.bloque_id = blo.id 
                    where 	tipo_uso = '$uso'
                            and sem.semana_numero = $semanaNumero
                            and blo.scholaris_periodo_codigo = '$periodoCode';";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    
    /**
     * 5.1.- Contenido de Evaluaciones
     * Realizado por Arturo Sarango
     * 2022-07-12
     * Vista de la entrega de evaluaciones formativas y sumativas de la planificacion
     */
    public function get_accion_evaluaciones($planBloqueUnidadId) {
                
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        $accion_update = "5.1.-";
        $titulo = "5.1.- EVALUACIONES";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();                

        $evaluaciones = $this->valida_evaluaciones($planBloqueUnidadId);
        $formativa  = $this->mostrar_evaluaciones($planifVertDipl->id, $evaluaciones->id, $evaluaciones->formativa, $evaluaciones->sumativa, $titulo, $accion_update, "", 'formativa');

        $modelPlanVertical = PlanificacionVerticalDiploma::find()->where(['planificacion_bloque_unidad_id' => $planBloqueUnidadId])->one();
        $modelPlanVertical->ultima_seccion = $accion_update;
        $modelPlanVertical->save();

         //guarda el porcentaje de avance del pud dip
         $this->pud_dip_actualiza_porcentaje_avance($modelPlanVertical);
        
        return $formativa;
        
    }
    
    private function valida_evaluaciones($planBloqueUnidadId){
        $model = \backend\models\PudDipEvaluaciones::find()->where(['plan_unidad_id' => $planBloqueUnidadId])->one();
        if($model){
            return $model;
        }else{
            $model = new \backend\models\PudDipEvaluaciones();
            $model->plan_unidad_id = $planBloqueUnidadId;
            $model->opcion_id = 0;
            $model->formativa = 'no conf';
            $model->sumativa = 'no conf';
            $model->save();
            return $model;
        }        
    }
    
    
    /**
     * METODO PARA VENTANA DE MODIFICACION DE EVALUACIONES
     * @param type $idPlanifVertDipl
     * @param type $texto_a_mostrar
     * @param type $titulo
     * @param type $accion_update
     * @param type $text_intro_cab
     * @return string
     */
     private function mostrar_evaluaciones($idPlanifVertDipl, $evaluacionId, $formativa, $sumativa, $titulo, $accion_update, $text_intro_cab, $campo) {

        $activarModalGenerico = $this->consultaRespuestaEnvio($idPlanifVertDipl);

        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 100%; margin-top:20px">';
        $html .= '<div class="card-header">';
        $html .= '<div class="row">';
        $html .= '<h5 class=""><b>' . $titulo . '</b></h5>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="card-body" >';
        // inicia row
        $html .= '<div class="row" >';
        $html .= '<small style="color: #65b2e8">
                                <font size="2">
                                ' . $text_intro_cab . ' 
                                </font>';
        $html .= Html::beginForm(['update-evaluaciones'], 'post');
        if ($activarModalGenerico) {
            
            
            $html .= '<input type="hidden" name="evaluacion_id" value="'.$evaluacionId.'">';
            $html .= '<b>Formativas</b>';
            $html.= '<textarea name="formativa" class="form-control" >'.$formativa.'</textarea>
                            <script>
                                CKEDITOR.replace( "formativa",{
                                    customConfig: "/ckeditor_settings/config.js"                                
                                    } );
                            </script>';
            
            $html .= '<br><b>Sumativas</b>';

            $html .= '<textarea name="sumativa" class="form-control">'.$sumativa.'</textarea>
                            <script>
                                CKEDITOR.replace( "sumativa",{
                                    customConfig: "/ckeditor_settings/config.js"                                
                                    } );
                            </script>';

            
        }else{
            $html .= '<hr></small>';
            $html .= '<div class="row" style="overflow-x: scroll; overflow-y: scroll;" >';
            $html .= '<div class="col" >' . $formativa . '</div>';
            $html .= '</div>';
        }
        
        $html .= '<div style="text-align:end; margin-top:5px">
                    <button type="submit" class="btn btn-success" >Actualizar</button>
                  </div>';
        $html .= Html::endForm();
        
        $html .= '</div>';
        //******finaliza row
        $html .= '</div>'; //fin de card-body
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    
    public function actionUpdateEvaluaciones(){                
       
        $evaluacionId   = $_POST['evaluacion_id'];
        $formativa      = $_POST['formativa'];
        $sumativa       = $_POST['sumativa'];
        
        $model = \backend\models\PudDipEvaluaciones::findOne($evaluacionId);
        $model->formativa = $formativa;
        $model->sumativa = $sumativa;
        $model->save();
        
        return $this->redirect(['index1', 'plan_bloque_unidad_id' => $model->plan_unidad_id]);
        
    }
    
    
    
    /*     * * 5.2 Accion Proceso de Aprendizaje */
    private function get_accion_proceso_aprendizaje($planBloqueUnidad) {
        $textCab = '';
        $textCab .= '<br><br>';

        $modelPlanBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);

        $accion_update = "5.2.-";
        $titulo = "5.2.- PROCESO DE APRENDIZAJE";

        $this->ingresa_procesos_aprendizaje($planBloqueUnidad); //ingresa las opciones 
        $instEvaluacion = $this->mostrar_proceso_aprendizaje($planBloqueUnidad, $titulo);
        
        $modelPlanVertical = PlanificacionVerticalDiploma::find()->where(['planificacion_bloque_unidad_id' => $planBloqueUnidad])->one();
        $modelPlanVertical->ultima_seccion = $accion_update;
        $modelPlanVertical->save();
        

        return $instEvaluacion;
    }

    private function mostrar_proceso_aprendizaje($planBloqueUnidadId, $titulo) {
        $procesoAprendizaje = \backend\models\PudDipProcesoAprendizaje::find()->where(['plan_unidad_id' => $planBloqueUnidadId])->all();

        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 100%; margin-top:20px">';
        $html .= '<div class="card-header">';
        $html .= '<div class="row">';
        $html .= '<h5 class=""><b>' . $titulo . '</b></h5>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="card-body" >';
        // inicia row

        foreach ($procesoAprendizaje as $proc) {
            $proc['es_activo'] ? $check = 'checked' : $check = '';
            
            $html .= '<div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" '.$check.' onclick="registra_proceso_aprendizaje('.$proc->id.')">
                        <label class="form-check-label" for="flexSwitchCheckChecked">'.$proc->opcion->opcion.'</label>
                      </div>';
        }


        //******finaliza row
        $html .= '</div>'; //fin de card-body
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    
    
    public function actionActualizaOpcion(){
        $id = $_POST['id'];
        $model = \backend\models\PudDipProcesoAprendizaje::findOne($id);        
        !$model->es_activo ? $model->es_activo = true : $model->es_activo = false;                
        $model->save();
    }

    private function ingresa_procesos_aprendizaje($planUnidadId) {
        $con = Yii::$app->db;
        $query = "insert into pud_dip_proceso_aprendizaje(plan_unidad_id, opcion_id, es_activo)
                    select 	$planUnidadId, op.id, false 
                    from 	planificacion_opciones op
                    where 	op.tipo = 'ACCION'
                                    and op.categoria = 'PROCESO_APREN'
                                    and op.id not in (select opcion_id from pud_dip_proceso_aprendizaje pa where plan_unidad_id = $planUnidadId);";
        
        $con->createCommand($query)->execute();
    }//fin de 5.2.
    
    

    /*     * * 5.3 Enfoque del aprendizaje */
    private function get_accion_enfoque_aprendizaje($planBloqueUnidad) {
        $textItem = '';
        $textDetalle = '';
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "5.3.-";
        $titulo = "5.3.- ENFOQUE DEL APRENDIZAJE (EDA)";
        $titulo2 = "Detalles";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();
        
        $objScrip = new Scripts(); 
                
        $textItem .= $objScrip->get_enfoques($planifVertDipl->id);

        
        $impItems = $this->mostrar_campo_viene_de($planifVertDipl->id, $textItem, $titulo, $accion_update, "");
        $modelPlanVertical = PlanificacionVerticalDiploma::find()->where(['planificacion_bloque_unidad_id' => $planBloqueUnidad->id])->one();
        $modelPlanVertical->ultima_seccion = $accion_update;
        $modelPlanVertical->save();

        return $impItems;
    }
    
    
    /*     * * 5.3.1 Metacognicion */
    private function get_accion_metacognicion($planBloqueUnidadId) 
    {       
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        $accion_update = "5.3.1.-";
        $titulo = "5.3.1.- METACOGNICIÓN";

        $this->ingresa_metacognicion($planBloqueUnidadId); //ingresa las opciones 
        $mostrar = $this->mostrar_seleccion_metacognicion($planBloqueUnidadId, $titulo);        

        $modelPlanVertical = PlanificacionVerticalDiploma::find()->where(['planificacion_bloque_unidad_id' => $planBloqueUnidadId])->one();
        $modelPlanVertical->ultima_seccion = $accion_update;
        $modelPlanVertical->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($modelPlanVertical);
        return $mostrar;

    }

    private function ingresa_metacognicion($planBloqueUnidadId){
        $con = Yii::$app->db;
        $query = "insert into pud_dip (planificacion_bloques_unidad_id, codigo, campo_de, opcion_boolean, opcion_texto) 
                select 	$planBloqueUnidadId,tipo, 'seleccion', false, opcion 
                from 	dip_opciones op
                where 	op.tipo = 'METACOGNICION'
                                and op.opcion not in (select opcion_texto from pud_dip 
                                where planificacion_bloques_unidad_id = $planBloqueUnidadId 
                                and opcion_texto = opcion and codigo = 'METACOGNICION')
                                
                ;";

        $con->createCommand($query)->execute();
        
        $modelDetalle = \backend\models\PudDip::find()->where([
            'codigo' => 'METACOGNICION',
            'campo_de' => 'escrito',
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId
        ])->one();
        
        if(!$modelDetalle){
            $model = new \backend\models\PudDip();
            $model->planificacion_bloques_unidad_id = $planBloqueUnidadId;
            $model->codigo = 'METACOGNICION';
            $model->campo_de = 'escrito';
            $model->opcion_texto = 'None';
            $model->save();
        }                
    }

    private function mostrar_seleccion_metacognicion($planBloqueUnidadId, $titulo) {
        $pudDip = \backend\models\PudDip::find()->where([
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId,
            'codigo' => 'METACOGNICION'
         ])
         ->orderBy(['opcion_texto'=>SORT_ASC])
         ->all();
        

        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 100%; margin-top:20px">';
        $html .= '<div class="card-header">';
        $html .= '<div class="row">';
        $html .= '<h5 class=""><b>' . $titulo . '</b></h5>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="card-body" >';
        // inicia row
        $html .= '<div class="row">';
        foreach ($pudDip as $pud) {
            $pud->opcion_boolean ? $check = 'checked' : $check = '';
            
            if($pud->campo_de == 'seleccion'){
                $html .= '<div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" '.$check.' '
                        . 'onclick="update_pud_dip_boolean('.$pud->id.')">
                        <label class="form-check-label" for="flexSwitchCheckChecked">'.$pud->opcion_texto.'</label>
                      </div>';
            }else{
                $detalle = $pud->opcion_texto;
                $pudId = $pud->id;
            }
            
        }
        $html .= '</div>'; //FIN ROW SELECCION
        
        $html .= '<hr />'; 
        
//            $html .= '<div class="row">'; //inicia row de detalle
        $html .= Html::beginForm(['update-pud-dip'], 'post');
                $html .= '<b>Información Detallada</b>';
                $html .= '<input type="hidden" name="campo_de" value="escrito">';
                $html .= '<input type="hidden" name="id" value="'.$pudId.'">';
                $html.= '<textarea name="contenido" class="form-control" id="detalle-metacognicion" >'.$detalle.'</textarea>
                                <script>
                                    CKEDITOR.replace( "contenido",{
                                        customConfig: "/ckeditor_settings/config.js"                                
                                        } );
                                </script>';                       

            $html .= '<div style="text-align:end; margin-top:5px">
                        <button type="submit" class="btn btn-success">Actualizar</button>
                  </div>';
        
            $html .= Html::endForm();
//        $html .= '</div>';//fin de row de detalle
        //******finaliza row
        $html .= '</div>'; //fin de card-body
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    /***** fin de metacognición */
    
    
     /*     * * 5.3.2  DIFERENCIACION */
    private function get_accion_diferenciacion($planBloqueUnidadId) {
        // print_r($planBloqueUnidadId);
        // die();
       
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        $accion_update = "5.3.2.-";
        $titulo = "5.3.2.- DIFERENCIACIÓN";

        $this->ingresa_diferenciacion($planBloqueUnidadId); //ingresa las opciones 
        $mostrar = $this->mostrar_seleccion_diferenciacion($planBloqueUnidadId, $titulo);        

        $modelPlanVertical = PlanificacionVerticalDiploma::find()->where(['planificacion_bloque_unidad_id' => $planBloqueUnidadId])->one();
        $modelPlanVertical->ultima_seccion = $accion_update;
        $modelPlanVertical->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($modelPlanVertical);
        return $mostrar;
    }
    
    private function ingresa_diferenciacion($planBloqueUnidadId){
        $con = Yii::$app->db;
        $query = "insert into pud_dip (planificacion_bloques_unidad_id, codigo, campo_de, opcion_boolean, opcion_texto) 
                select 	$planBloqueUnidadId,tipo, 'seleccion', false, opcion 
                from 	dip_opciones op
                where 	op.tipo = 'APRENDIZAJE-DIFERENCIADO'
                                and op.opcion not in (select opcion_texto from pud_dip 
                                where planificacion_bloques_unidad_id = $planBloqueUnidadId 
                                and opcion_texto = opcion and codigo = 'APRENDIZAJE-DIFERENCIADO')
                                
                ;";
        $con->createCommand($query)->execute();
        
        $modelDetalle = \backend\models\PudDip::find()->where([
            'codigo' => 'APRENDIZAJE-DIFERENCIADO',
            'campo_de' => 'escrito',
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId
        ])->one();
        
        if(!$modelDetalle){
            $model = new \backend\models\PudDip();
            $model->planificacion_bloques_unidad_id = $planBloqueUnidadId;
            $model->codigo = 'APRENDIZAJE-DIFERENCIADO';
            $model->campo_de = 'escrito';
            $model->opcion_texto = 'None';
            $model->save();
        }                
    }
    
    private function mostrar_seleccion_diferenciacion($planBloqueUnidadId, $titulo) {
        $pudDip = \backend\models\PudDip::find()->where([
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId,
            'codigo' => 'APRENDIZAJE-DIFERENCIADO'
         ])
         ->orderBy(['opcion_texto'=>SORT_ASC])
         ->all();
        

        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 100%; margin-top:20px">';
        $html .= '<div class="card-header">';
        $html .= '<div class="row">';
        $html .= '<h5 class=""><b>' . $titulo . '</b></h5>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="card-body" >';
        // inicia row
        $html .= '<div class="row">';
        foreach ($pudDip as $pud) {
            $pud->opcion_boolean ? $check = 'checked' : $check = '';
            
            if($pud->campo_de == 'seleccion'){
                $html .= '<div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" '.$check.' '
                        . 'onclick="update_pud_dip_boolean('.$pud->id.')">
                        <label class="form-check-label" for="flexSwitchCheckChecked">'.$pud->opcion_texto.'</label>
                      </div>';
            }else{
                $detalle = $pud->opcion_texto;
                $pudId = $pud->id;
            }
            
        }
        $html .= '</div>'; //FIN ROW SELECCION
        
        $html .= '<hr />'; 
        
//            $html .= '<div class="row">'; //inicia row de detalle
        $html .= Html::beginForm(['update-pud-dip'], 'post');
                $html .= '<b>Información Detallada</b>';
                $html .= '<input type="hidden" name="campo_de" value="escrito">';
                $html .= '<input type="hidden" name="id" value="'.$pudId.'">';
                $html.= '<textarea name="contenido" class="form-control" id="detalle-metacognicion" >'.$detalle.'</textarea>
                                <script>
                                    CKEDITOR.replace( "contenido",{
                                        customConfig: "/ckeditor_settings/config.js"                                
                                        } );
                                </script>';                       

            $html .= '<div style="text-align:end; margin-top:5px">
                        <button type="submit" class="btn btn-success">Actualizar</button>
                  </div>';
        
            $html .= Html::endForm();
//        $html .= '</div>';//fin de row de detalle
        //******finaliza row
        $html .= '</div>'; //fin de card-body
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    
    public function actionUpdatePudDip(){
        
        $campoDe = $_POST['campo_de'];
        $id = $_POST['id'];                    
        
        $model = \backend\models\PudDip::findOne($id);  
        
        // //buscal el planVetiDip
        $modelPlanVertDipl = PlanificacionVerticalDiploma::find()
        ->where(['planificacion_bloque_unidad_id'=> $model->planificacion_bloques_unidad_id])
        ->one();             

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($modelPlanVertDipl);
        
        if($campoDe == 'seleccion'){            
            $model->opcion_boolean ? $model->opcion_boolean = false : $model->opcion_boolean = true;
            $model->save();            
        }else{            
            $model->opcion_texto = $_POST['contenido'];
            $model->save();
            return $this->redirect(['index1', 'plan_bloque_unidad_id' => $model->planificacion_bloques_unidad_id]);
        }      
    }
    

    /*     * * 5.4 Accion Lenguaje y aprendizaje */

    private function get_accion_lenguaje_aprendizaje($planBloqueUnidad) {
        $idPvdOp = '';
        $agregar = 'Agregar';
        $quitar = 'Quitar';
        $textoCab = '';
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "5.4.-";
        $accion_update_op = "5.4.1.-";
        $titulo = "5.4.- LENGUAJE Y APRENDIZAJE";
        $titulo2 = "Detalles";

        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();
        $modelPlanifVertDiplTDC = $this->consultar_lenguaje_y_aprendizaje_ckeck($planifVertDipl->id);

        $itemConexionCas = '';
        $itemConexionCas .= "<table class=\"table table-hover table-condensed table-striped table-bordered\">";
        $idPvdOp = '';

        foreach ($modelPlanifVertDiplTDC as $tdc) {
            if ($tdc['es_seleccionado']) {
                $idPvdOp = $tdc['pvd_tdc_id']; //Id de tabla planifi_vd_relacion_tdc
                $itemConexionCas .= '<tr>';
                $itemConexionCas .= '<td>';
                $itemConexionCas .= '<font size="3"><u><b>♠ ' . $tdc['opcion'] . '</b></u></font>';
                $itemConexionCas .= '</td>';
                $itemConexionCas .= '<td>';
                $itemConexionCas .= '<a href="#"   class="far fa-thumbs-up" style="color: #0a1f8f" onclick="update_campos_check(' . $planifVertDipl->id . ',' . $idPvdOp . ',\'' . $accion_update_op . '\',\'' . $quitar . '\')"></a>';
                $itemConexionCas .= '</td>';
                $itemConexionCas .= '</tr>';
            } else {
                $idPvdOp = $tdc['tdc_id']; //Id de tabla planificacion opciones
                $itemConexionCas .= '<tr>';
                $itemConexionCas .= '<td>';
                $itemConexionCas .= '<font size="3"><b>♠ ' . $tdc['opcion'] . '</b></font>';
                $itemConexionCas .= '</td>';
                $itemConexionCas .= '<td>';
                $itemConexionCas .= '<a href="#" class="fas fa-thumbs-down" style="color: #ab0a3d" onclick="update_campos_check(' . $planifVertDipl->id . ',' . $idPvdOp . ',\'' . $accion_update_op . '\',\'' . $agregar . '\')"></a>';
                $itemConexionCas .= '</td>';
                $itemConexionCas .= '</tr>';
            }
        }
        $itemConexionCas .= "</table>";

        $texto = $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->detalle_len_y_aprendizaje, $titulo2, $accion_update, "");
        $selectcion = $this->mostrar_campo_viene_de($planifVertDipl->id, $itemConexionCas, $titulo, $accion_update, $textoCab);
        return $selectcion . $texto;
    }

    /*     * * 5.5 Conexion con TDC */

    private function get_accion_conexion_tdc($planBloqueUnidad) {
        $textoImp = '';
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "5.5.-";
        $titulo = "5.5.- CONEXION CON TDC";
        $titulo2 = "Detalle";

        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();
        $arrayConexionTDC = $this->select_relacionTDC($planifVertDipl);
        foreach ($arrayConexionTDC as $conexionTDC) {
            $textoImp = '<font size="3">♠ ' . $conexionTDC->relacionTdc->opcion . '</font><br>' . $textoImp;
        }
        $textoImp .= '<br>';
        $relacionTdc2 = $this->mostrar_campo_viene_de($planifVertDipl->id, $textoImp, $titulo, $accion_update, "");
        $relacionTdc = $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->conexion_tdc, $titulo2, $accion_update, "");
        return $relacionTdc2 . $relacionTdc;
    }

    /** 5.6 Conexion con CAS */
    private function get_accion_conexion_cas($planBloqueUnidad) {
        $idPvdOp = '';
        $agregar = 'Agregar';
        $quitar = 'Quitar';
        $textoCab = 'Marque las casillas para ver si hay conexiones CAS explicitas, Si marca alguna de las casillas, proporcione 
                    una nota breve en la sección de “Detalles”, que explique cómo los estudiantes se involucraron en CAS para esta unidad.
                    <br><br>';

        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "5.6.-";
        $accion_update_op = "5.6.1.-";
        $titulo = "5.6.- CONEXION CON CAS";
        $titulo2 = "Detalles";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();

        $planifVertDipl->ultima_seccion = $accion_update;        
        $planifVertDipl->save();
        
        $modelPlanifVertDiplTDC = $this->consultar_conexion_cas_ckeck($planifVertDipl->id);

        $itemConexionCas = '';
        $itemConexionCas .= "<table class=\"table table-hover table-condensed table-striped table-bordered\">";
        $idPvdOp = '';

        foreach ($modelPlanifVertDiplTDC as $tdc) {
            if ($tdc['es_seleccionado']) {
                $idPvdOp = $tdc['pvd_tdc_id']; //Id de tabla planifi_vd_relacion_tdc
                $itemConexionCas .= '<tr>';
                $itemConexionCas .= '<td>';
                $itemConexionCas .= '<font size="3"><u><b>♠ ' . $tdc['opcion'] . '</b></u></font>';
                $itemConexionCas .= '</td>';

                $activarEnlace = $this->consultaRespuestaEnvio($planifVertDipl->id);
                if ($activarEnlace == 1) {
                    $itemConexionCas .= '<td>';
                    $itemConexionCas .= '<a href="#"   class="far fa-thumbs-up" style="color: #0a1f8f" 
                    onclick="update_campos_check(' . $planifVertDipl->id . ',' . $idPvdOp . ',\'' . $accion_update_op . '\',\'' . $quitar . '\')"></a>';
                    $itemConexionCas .= '</td>';
                } else {
                    $itemConexionCas .= '<td>';
                    $itemConexionCas .= '<i class="far fa-thumbs-up" style="color: #0a1f8f"></i>';
                    $itemConexionCas .= '</td>';
                }



                $itemConexionCas .= '</tr>';
            } else {
                $idPvdOp = $tdc['tdc_id']; //Id de tabla planificacion opciones
                $itemConexionCas .= '<tr>';
                $itemConexionCas .= '<td>';
                $itemConexionCas .= '<font size="3"><b>♠ ' . $tdc['opcion'] . '</b></font>';
                $itemConexionCas .= '</td>';

                $activarEnlace = $this->consultaRespuestaEnvio($planifVertDipl->id);
                if ($activarEnlace) {
                    $itemConexionCas .= '<td>';
                    $itemConexionCas .= '<a href="#" class="fas fa-thumbs-down" style="color: #ab0a3d" onclick="update_campos_check(' . $planifVertDipl->id . ',' . $idPvdOp . ',\'' . $accion_update_op . '\',\'' . $agregar . '\')"></a>';
                    $itemConexionCas .= '</td>';
                } else {
                    $itemConexionCas .= '<td>';
                    $itemConexionCas .= '<i class="fas fa-thumbs-down" style="color: #ab0a3d"></i>';
                    $itemConexionCas .= '</td>';
                }
                $itemConexionCas .= '</tr>';
            }
        }
        $itemConexionCas .= "</table>";

        $selectcion = $this->mostrar_campo_viene_de($planifVertDipl->id, $itemConexionCas, $titulo, $accion_update, $textoCab);
        $texto = $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->detalle_cas, $titulo2, $accion_update, "");

        return $selectcion . $texto;
    }
    
    
    /** 5.7 ESTUDIANTES CON NEE **/
    private function get_accion_nee($planBloqueUnidadId){
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        $accion_update = "5.7.-";
        $titulo = "5.7.- ESTUDIANTES CON NEE";
       
        
        $opCourseTemplateId = $planBloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;
        $mostrar = $this->mostrar_nee($opCourseTemplateId, $titulo);

        $modelPlanVertical = PlanificacionVerticalDiploma::find()->where(['planificacion_bloque_unidad_id' => $planBloqueUnidadId])->one();
        $modelPlanVertical->ultima_seccion = $accion_update;
        $modelPlanVertical->save();
        
        return $mostrar;
    }
    
    private function mostrar_nee($opCourseTemplateId, $titulo) {        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $objScritp = new Scripts();
        $nee = $objScritp->get_nee($periodoId, $opCourseTemplateId);     
                     

        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 100%; margin-top:20px">';
        $html .= '<div class="card-header">';
        $html .= '<div class="row">';
        $html .= '<h5 class=""><b>' . $titulo . '</b></h5>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="card-body" >';
        // inicia row
        $html .= '<div class="row">';
        $html .= '<ul>';
        if($nee){
            foreach ($nee as $n){
                $html .= '<p>';
                $html .= '<b><u><i class="fas fa-user-graduate"></i> '.$n['estudiante'].'</u></b><br>';
                $html .= $n['curso'].' | '.$n['paralelo'].' | '. $n['materia'].'<br>';
                $html .= 'grado: ('.$n['grado_nee'].') '.$n['diagnostico_inicia'];
                $html .= '</p>';
                $html .= '<hr>';

            }
        }else{
            $html .= '<p>No existen NEE</p>';
        }
        $html .= '</ul>';        
        $html .= '</div>'; //FIN ROW SELECCION
        
        $html .= '</div>'; //fin de card-body
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    
    
    /*     * * 5.8  ESTUDIANTES CON TALENTOS ESPECIALES*/
    private function get_accion_talentos($planBloqueUnidadId) {
       
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        $accion_update = "5.8.-";
        $titulo = "5.8.- ESTUDIANTES CON TALENTO SOBRESALIENTE";

        $this->ingresa_talento($planBloqueUnidadId); //ingresa las opciones 
        $mostrar = $this->mostrar_talento($planBloqueUnidadId, $titulo);        

        $modelPlanVertical = PlanificacionVerticalDiploma::find()->where(['planificacion_bloque_unidad_id' => $planBloqueUnidadId])->one();
        $modelPlanVertical->ultima_seccion = $accion_update;
        $modelPlanVertical->save();
        
        return $mostrar;
    }
    
    private function ingresa_talento($planBloqueUnidadId){
        $model = \backend\models\PudDip::find()->where([
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId,
            'codigo' => 'TALENTOS'
           ])->one();
        
        if(!$model){
           $modelN = new \backend\models\PudDip();
           $modelN->planificacion_bloques_unidad_id = $planBloqueUnidadId;
           $modelN->codigo = 'TALENTOS';
           $modelN->campo_de = 'escrito';
           $modelN->opcion_texto = '';
           $modelN->save();
        }
        
    }
    
    
   
    private function mostrar_talento($planBloqueUnidadId, $titulo) {
        $pudDip = \backend\models\PudDip::find()->where([
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId,
            'codigo' => 'TALENTOS'
         ])->one();
        

        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 100%; margin-top:20px">';
        $html .= '<div class="card-header">';
        $html .= '<div class="row">';
        $html .= '<h5 class=""><b>' . $titulo . '</b></h5>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="card-body" >';
        
        
//            $html .= '<div class="row">'; //inicia row de detalle
        $html .= Html::beginForm(['update-pud-dip'], 'post');
                $html .= '<b>Información Detallada</b>';
                $html .= '<input type="hidden" name="campo_de" value="escrito">';
                $html .= '<input type="hidden" name="id" value="'.$pudDip->id.'">';
                $html.= '<textarea name="contenido" class="form-control" id="detalle-metacognicion" >'.$pudDip->opcion_texto.'</textarea>
                                <script>
                                    CKEDITOR.replace( "contenido",{
                                        customConfig: "/ckeditor_settings/config.js"                                
                                        } );
                                </script>';                       

            $html .= '<div style="text-align:end; margin-top:5px">
                        <button type="submit" class="btn btn-success">Actualizar</button>
                  </div>';
        
            $html .= Html::endForm();
//        $html .= '</div>';//fin de row de detalle
        //******finaliza row
        $html .= '</div>'; //fin de card-body
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    
    /*     * * 5.9 ODS */
    private function get_accion_ods($planBloqueUnidadId) 
    {       
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        $accion_update = "5.9.-";
        $titulo = "5.9.- COMPETENCIAS PARA ODS";

        $this->ingresa_ods($planBloqueUnidadId); //ingresa las opciones 
        $mostrar = $this->mostrar_seleccion_ods($planBloqueUnidadId, $titulo);        

        $modelPlanVertical = PlanificacionVerticalDiploma::find()->where(['planificacion_bloque_unidad_id' => $planBloqueUnidadId])->one();
        $modelPlanVertical->ultima_seccion = $accion_update;
        $modelPlanVertical->save();

        //guarda el porcentaje de avance del pud dip
        $this->pud_dip_actualiza_porcentaje_avance($modelPlanVertical);
        return $mostrar;

    }

    private function ingresa_ods($planBloqueUnidadId){
        $con = Yii::$app->db;
        $query = "insert into pud_dip (planificacion_bloques_unidad_id, codigo, campo_de, opcion_boolean, opcion_texto) 
                select 	$planBloqueUnidadId,tipo, 'seleccion', false, opcion 
                from 	dip_opciones op
                where 	op.tipo = 'ODS'
                                and op.opcion not in (select opcion_texto from pud_dip 
                                where planificacion_bloques_unidad_id = $planBloqueUnidadId 
                                and opcion_texto = opcion and codigo = 'ODS')
                                
                ;";

        $con->createCommand($query)->execute();
        
        $modelDetalle = \backend\models\PudDip::find()->where([
            'codigo' => 'ODS',
            'campo_de' => 'escrito',
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId
        ])->one();
        
        if(!$modelDetalle){
            $model = new \backend\models\PudDip();
            $model->planificacion_bloques_unidad_id = $planBloqueUnidadId;
            $model->codigo = 'ODS';
            $model->campo_de = 'escrito';
            $model->opcion_texto = 'None';
            $model->save();
        }                
    }

    private function mostrar_seleccion_ods($planBloqueUnidadId, $titulo) {
        $pudDip = \backend\models\PudDip::find()->where([
            'planificacion_bloques_unidad_id' => $planBloqueUnidadId,
            'codigo' => 'ODS'
         ])
         ->orderBy(['id'=>SORT_ASC])
         ->all();
        

        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 100%; margin-top:20px">';
        $html .= '<div class="card-header">';
        $html .= '<div class="row">';
        $html .= '<h5 class=""><b>' . $titulo . '</b></h5>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="card-body" >';
        // inicia row
        $html .= '<div class="row">';
        foreach ($pudDip as $pud) {
            $pud->opcion_boolean ? $check = 'checked' : $check = '';
            
            if($pud->campo_de == 'seleccion'){
                $ods = DipOpciones::find()->where(['opcion' => $pud->opcion_texto])->one();
                $html .= '<div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" '.$check.' '
                        . 'onclick="update_pud_dip_boolean('.$pud->id.')">
                        <label class="form-check-label" for="flexSwitchCheckChecked"><b>('.$ods->categoria.')</b>: '.$pud->opcion_texto.'</label>
                      </div>';
            }else{
                $detalle = $pud->opcion_texto;
                $pudId = $pud->id;
            }
            
        }
        $html .= '</div>'; //FIN ROW SELECCION
        
        $html .= '<hr />'; 
        
//            $html .= '<div class="row">'; //inicia row de detalle
        $html .= Html::beginForm(['update-pud-dip'], 'post');
                $html .= '<b>Información Detallada</b>';
                $html .= '<input type="hidden" name="campo_de" value="escrito">';
                $html .= '<input type="hidden" name="id" value="'.$pudId.'">';
                $html.= '<textarea name="contenido" class="form-control" id="detalle-metacognicion" >'.$detalle.'</textarea>
                                <script>
                                    CKEDITOR.replace( "contenido",{
                                        customConfig: "/ckeditor_settings/config.js"                                
                                        } );
                                </script>';                       

            $html .= '<div style="text-align:end; margin-top:5px">
                        <button type="submit" class="btn btn-success">Actualizar</button>
                  </div>';
        
            $html .= Html::endForm();
//        $html .= '</div>';//fin de row de detalle
        //******finaliza row
        $html .= '</div>'; //fin de card-body
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    /***** fin de ODS */


    

    /** 6.1.- Recursos */
    private function get_accion_recurso($planBloqueUnidad) {

        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "6.1.-";
        $titulo = "6.1.- RECURSOS";

        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();

        return $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->recurso, $titulo, $accion_update, "");
    }

    /** 7.1.- REFLEXION, LO QUE FUNCINO */
    private function get_accion_lo_que_funciono($planBloqueUnidad) {
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "7.1.-";
        $titulo = "7.1.- Lo que funcionó";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();
        return $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->reflexion_funciono, $titulo, $accion_update, "");
    }

    /** 7.2.- REFLEXION, LO QUE NO FUNCINO */
    private function get_accion_lo_que_no_funciono($planBloqueUnidad) {
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "7.2.-";
        $titulo = "7.2.- Lo que no funcionó";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();
        return $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->reflexion_no_funciono, $titulo, $accion_update, "");
    }

    /** 7.3.- REFLEXION, OBSERVACION, CAMBIOS. SUGERECIAS */
    private function get_accion_observacion($planBloqueUnidad) {
        $planBloqueUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "7.3.-";
        $titulo = "7.3.- Observaciones, Cambios y Sugerencias";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
                    'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
                ])->one();
        return $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->reflexion_observacion, $titulo, $accion_update, "");
    }

    //metodo usado para 5.1.-, llamada a contenidos
    private function select_contenidos($planUnidadId) {
        $arrayResp = array();
        $contenido = PlanificacionBloquesUnidadSubtitulo::find()->where([
                    'plan_unidad_id' => $planUnidadId
                ])->asArray()->all();

        foreach ($contenido as $cont) {
            $contenidosSubnivel = PlanificacionBloquesUnidadSubtitulo2::find()->where([
                        'subtitulo_id' => $cont['id']
                    ])->asArray()->all();
            $cont['subtitulos'] = array();

            foreach ($contenidosSubnivel as $contSub) {
                array_push($cont['subtitulos'], $contSub);
            }
            array_push($arrayResp, $cont);
        }
        return $arrayResp;
    }

    // metodo usado para 5.3.- llamada a habilidades
    private function select_habilidadesTDC($planVerticalDiplId, $tipo_consulta) {
        //muestra todas las habilidades, segun el codigo del plan vertical diploma, que este asociada       
        $con = Yii::$app->db;
        switch ($tipo_consulta) {
            case 'titulos':
                $query = "select distinct cph.es_titulo2 
                    from contenido_pai_habilidades cph , planificacion_vertical_diploma_habilidades pvdh 
                    where cph.id = pvdh .habilidad_id  
                    and pvdh.vertical_diploma_id = $planVerticalDiplId
                    order by cph.es_titulo2;";
                break;
            case 'detalles':
                $query = "select  (cph.es_titulo2 || ': ' ||cph.es_exploracion) as dato  
                from contenido_pai_habilidades cph , planificacion_vertical_diploma_habilidades pvdh 
                where cph.id = pvdh .habilidad_id  
                and pvdh.vertical_diploma_id = $planVerticalDiplId
                order by cph.es_titulo2;";

                break;
        }
        $resultado = $con->createCommand($query)->queryAll();
        return $resultado;
    }

    //metodo usado para 5.5.- llamada a relacion tdc
    private function select_relacionTDC($planVerticalDipl) {
        //muestra todas las relacion tdc, segun el codigo del plan vertical diploma, que este asociada
        $idPlanVertDipl = $planVerticalDipl->id;
        $planVertDip_Relacion = PlanificacionVerticalDiplomaRelacionTdc::find()->where([
                    'vertical_diploma_id' => $idPlanVertDipl
                ])->all();

        return $planVertDip_Relacion;
    }

    //metodo usado para 5.6.- llamada a Conexion CAS
    private function consultar_conexion_cas_ckeck($planVertDiplId) {
        //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados
        $con = Yii::$app->db;
        $obj = new Scripts();
        $resultado = $obj->pud_dip_consultar_conexion_cas_ckeck($planVertDiplId);
        return $resultado;
    }

    //metodo usado para 5.4.- llamada a lenguaje y aprendizaje
    private function consultar_lenguaje_y_aprendizaje_ckeck($planVertDiplId) {
        //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados
        $con = Yii::$app->db;
        $obj = new Scripts();
        $resultado = $obj->pud_dip_consultar_lenguaje_y_aprendizaje_ckeck($planVertDiplId);
        return $resultado;
    }

    //metodo para consultar en la bitacora de PUD , si envio o tiene respuesta el profesor
    private function consultaRespuestaEnvio($idPlanifVertDipl) {
        $modelPlanVer = PlanificacionVerticalDiploma::findOne($idPlanifVertDipl);
        $modelPudAprBit = PudAprobacionBitacora::find()
                ->where(['unidad_id' => $modelPlanVer->planificacion_bloque_unidad_id])
                ->orderBy(['fecha_notifica' => SORT_DESC])
                ->one();

        $activar = true;

        if ($modelPudAprBit) {
            if ($modelPudAprBit->estado_jefe_coordinador == 'ENVIADO' || $modelPudAprBit->estado_jefe_coordinador == 'APROBADO') {
                $activar = false;
            }
        }
        //    echo '<pre>';
        //    print_r($activarModalGenerico);
        //    die();

        return $activar;
    }

    // metodos genericos
    private function mostrar_campo_simple($idPlanifVertDipl, $texto_a_mostrar, $titulo, $accion_update, $text_intro_cab) {

        $activarModalGenerico = $this->consultaRespuestaEnvio($idPlanifVertDipl);

        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 100%; margin-top:20px">';
        $html .= '<div class="card-header">';
        $html .= '<div class="row">';
        $html .= '<h5 class=""><b>' . $titulo . '</b></h5>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="card-body" >';
        // inicia row
        $html .= '<small style="color: #65b2e8">
                                <font size="2">
                                ' . $text_intro_cab . ' 
                                </font>';
        if ($activarModalGenerico) {
            $html .= $this->modal_generico($idPlanifVertDipl, $texto_a_mostrar, $titulo, $accion_update);
            $html .= '<font size="2"><u>EDITAR</u></font>';
        }
        $html .= '<hr></small>';
        $html .= '<div class="row" style="overflow-x: scroll; overflow-y: scroll;" >';
        $html .= '<div class="col" >' . $texto_a_mostrar . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        //******finaliza row
        $html .= '</div>'; //fin de card-body
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    private function mostrar_campo_viene_de($idPlanifVertDipl, $texto_a_mostrar, $titulo, $accion_update, $text_intro) {
        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 100%; margin-top:20px">';
        $html .= '<div class="card-header">';
        $html .= '<div class="row">';
        $html .= '<h5 class=""><b>' . $titulo . '</b></h5>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="card-body">';
        // inicia row
        $html .= '<small style="color: #898b8d">                                
                                <font size="2">
                                ' . $text_intro . ' 
                                </font>
                                </small>
                                ';
        $html .= '<div class="row">';
        $html .= '<div class="col">' . $texto_a_mostrar . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        //******finaliza row
        $html .= '</div>'; //fin de card-body
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    private function modal_generico($id, $texto, $titulo, $accion_update) {

//        print_r($accion_update);
//        die();

        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#modalS2' . $id . '"> 
                    <i class="fas fa-edit"></i>';
        $html .= '</a>';
        $html .= '<div class="modal fade" id="modalS2' . $id . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">' . $titulo . '</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                         <hr>';
        $html .= '<textarea id="editor-text-unidad" name="sumativas" " class="form-control">' . $texto . '</textarea>
                            <script>
                                CKEDITOR.replace("editor-text-unidad", {
                                    customConfig: "/ckeditor_settings/config.js"
                                    })
                            </script>';

        $html .= '</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update_campo_simple_pud_dip(' . $id . ',\'' . $accion_update . '\')">Actualizar</button>
                        </div>
                    </div>
                    </div>
                </div>';
        return $html;
    }



    public function actionFormActividad(){

        $lmsId = $_GET['lms_id'];
        $planUnidadId = $_GET['plan_bloque_unidad_id'];

        $lms = Lms::findOne($lmsId);

        if(isset($_GET['inicio'])){
            $user = Yii::$app->user->identity->usuario;
            $today = date('Y-m-d H:i:s');

            $todaLaSemana = Lms::find()->where([
                'ism_area_materia_id' => $lms->ism_area_materia_id,
                'tipo_bloque_comparte_valor' => $lms->tipo_bloque_comparte_valor,
                'semana_numero' => $lms->semana_numero
                ]
            )
            ->orderBy("hora_numero")
            ->all();

            foreach($todaLaSemana as $dia){
                    strlen($dia->dip_inicio) < 10 ? $dia->dip_inicio = $_GET['inicio'] : $dia->dip_inicio = $lms->dip_inicio;
                    strlen($dia->dip_desarrollo) < 10 ? $dia->dip_desarrollo = $_GET['desarrollo'] : $dia->dip_desarrollo = $lms->dip_desarrollo;
                    strlen($dia->dip_cierre) < 10 ? $dia->dip_cierre = $_GET['cierre'] : $dia->dip_cierre = $lms->dip_cierre;
                    $dia->updated = $user;
                    $dia->updated_at = $today;
                    $dia->save();
            }
            
            $lms->dip_inicio =  $_GET['inicio'];
            $lms->dip_desarrollo =  $_GET['desarrollo'];
            $lms->dip_cierre =  $_GET['cierre'];
            $lms->updated = $user;
            $lms->updated_at = $today;
            $lms->save();            
        }

        return $this->redirect(['index1', 'plan_bloque_unidad_id' => $planUnidadId]);
    }

}

?>