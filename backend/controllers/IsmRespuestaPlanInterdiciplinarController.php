<?php

namespace backend\controllers;

use backend\models\CurriculoMecBloque;
use backend\models\helpers\HelperGeneral;
use backend\models\IsmGrupoMateriaPlanInterdiciplinar;
use backend\models\IsmContenidoPlanInterdiciplinar;
use backend\models\IsmGrupoPlanInterdiciplinar;
use backend\models\IsmRespuestaPlanInterdiciplinar;
use backend\models\IsmRespuestaPlanInterdiciplinarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanificacionOpciones;
use backend\models\pudpai\Datos;
use phpDocumentor\Reflection\Types\This;
use backend\models\PlanificacionVerticalPaiOpciones;
use backend\models\IsmRespuestaReflexionPaiInterdiciplinar;
use yii\filters\AccessControl;
use Yii;

/**
 * IsmRespuestaPlanInterdiciplinarController implements the CRUD actions for IsmRespuestaPlanInterdiciplinar model.
 */
class IsmRespuestaPlanInterdiciplinarController extends Controller
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

    // public function beforeAction($action)
    // {
    //     if (!parent::beforeAction($action)) {
    //         return false;
    //     }

    //     if (Yii::$app->user->identity) {

    //         //OBTENGO LA OPERACION ACTUAL
    //         list($controlador, $action) = explode("/", Yii::$app->controller->route);
    //         $operacion_actual = $controlador . "-" . $action;
    //         //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
    //         if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
    //             echo $this->render('/site/error', [
    //                 'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
    //                 'name' => 'Acceso denegado!!',
    //             ]);
    //         }
    //     } else {
    //         header("Location:" . \yii\helpers\Url::to(['site/login']));
    //         exit();
    //     }
    //     return true;
    // }

    /**
     * Lists all IsmRespuestaPlanInterdiciplinar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IsmRespuestaPlanInterdiciplinarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Lists all IsmRespuestaPlanInterdiciplinar models.
     * @return mixed
     */

    public function actionIndex1()
    {
        $planBloqueUnidadId = $_GET['plan_bloque_unidad_id'];
        $idGrupoInter = $_GET['idgrupointer'];

        $habilidadesSeleccionadas = PlanificacionVerticalPaiOpciones::find()
            ->where([
                'plan_unidad_id' => $planBloqueUnidadId,
                'tipo' => 'habilidad_enfoque'
            ])->all();

        $planUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        //crea el catalogo de respuestas si estas no existen
        $this->crea_respuestas_en_blanco($idGrupoInter);
        return $this->render('index1', [
            'planUnidad' => $planUnidad,
            'idGrupoInter' => $idGrupoInter,
        ]);
    }

    /**
     * Displays a single IsmRespuestaPlanInterdiciplinar model.
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
     * Creates a new IsmRespuestaPlanInterdiciplinar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IsmRespuestaPlanInterdiciplinar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing IsmRespuestaPlanInterdiciplinar model.
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
     * Deletes an existing IsmRespuestaPlanInterdiciplinar model.
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
     * Finds the IsmRespuestaPlanInterdiciplinar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IsmRespuestaPlanInterdiciplinar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IsmRespuestaPlanInterdiciplinar::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionMostrarPantallas()
    {
        $html = '';
        $planUnidadId = $_POST['plan_unidad_id'];
        $pestana = $_POST['pestana'];
        $idGrupoInter = $_POST['idgrupointer'];


        switch ($pestana) {
            case '1.1.-':
                $html = $this->datos_informativos($planUnidadId, $idGrupoInter);
                break;
            case '2.1.-':
                $html = $this->proposito_integracion($idGrupoInter);
                break;
            case '2.2.-':
                $html = $this->conceptos_clave($idGrupoInter);
                break;
            case '2.3.-':
                $html = $this->enunciado_indagacion($idGrupoInter);
                break;
            case '2.4.-':
                $html = $this->preguntas_indagacion($idGrupoInter);
                break;
            case '2.5.-':
                $html = $this->contexto_global($idGrupoInter);
                break;
            case '3.1.-':
                $html = $this->enfoque_habilidad($idGrupoInter);
                break;
                // case '3.2.-':
                //     $html = $this->enfoque_exploracion($idGrupoInter);
                //     break;
            case '3.2.-':
                $html = $this->enfoque_actividad($idGrupoInter);
                break;
            case '3.4.-':
                $html = $this->enfoque_actividad($idGrupoInter);
                break;
            case '4.1.-':
                $html = $this->objetivos_desarrollo_sostenible_competencia($idGrupoInter);
                break;
            case '4.2.-':
                $html = $this->objetivos_desarrollo_sostenible_actividad($idGrupoInter);
                break;
            case '4.3.-':
                $html = $this->objetivos_desarrollo_sostenible_objetivo($idGrupoInter);
                break;
            case '4.4.-':
                $html = $this->objetivos_desarrollo_sostenible_relacion_ods($idGrupoInter);
                break;
            case '5.1.-':
                $html = '';
                break;
            case '5.2.-':
                $html = $this->evaluacion_formativas_disciplinar($idGrupoInter);
                break;
            case '5.3.-':
                $html = $this->evaluacion_formativas_interdisciplinar($idGrupoInter);
                break;
            case '5.4.-':
                $html = $this->evaluacion_formativas_sumativa($idGrupoInter);
                break;
            case '7.1.-':
                $html = $this->proceso_experiencia_aprendizaje($idGrupoInter);
                break;
            case '7.2.-':
                $html = $this->proceso_necesidades_especiales($idGrupoInter);
                break;
            case '8.1.-':
                $html = $this->recursos($idGrupoInter);
                break;
            case '9.1.-':
                $html = $this->reflexion($idGrupoInter);
                break;
        }
        return $html;
    }
    public function actionUpdateRespuesta()
    {
        $html = '';
        $idRespuesta = $_POST['idRespuesta'];
        $nuevoDato = $_POST['nuevoDato'];
        $planUnidadId = $_POST['planUnidadId'];

        //buscamos el registro de respuesta
        $modelRespuesta = IsmRespuestaPlanInterdiciplinar::findOne($idRespuesta);
        $modelRespuesta->respuesta = $nuevoDato;
        $modelRespuesta->save();
    }
    public function actionGuardarPreguntaReflexion()
    {
        $idGrupoInter = $_POST['idGrupoInter'];
        $id_pregunta = $_POST['id_pregunta'];
        $tipo_pregunta = $_POST['tipo_pregunta'];
       
        //insertamos datos de la pregunta
        $this->guardar_pregunta_reflexion($id_pregunta,$tipo_pregunta,$idGrupoInter);


    }
    /*************************************************************************************************************** */
    /*************************************************************************************************************** */
    /*************************************************************************************************************** */
    private function crea_respuestas_en_blanco($idGrupoInter)
    {
        //busca si existe respuestas ya registradas para este grupo, caso contrario crea el catalogo de respuestas

        $modelRespuesta = IsmRespuestaPlanInterdiciplinar::find()
            ->select(['id_contenido_plan_inter'])
            ->where(['id_grupo_plan_inter' => $idGrupoInter])
            ->all();

        $arrayIdRespuestas = array();

        foreach ($modelRespuesta as $model) {
            $arrayIdRespuestas[] = $model->id_contenido_plan_inter;
        }

        $modelIsmContenido = IsmContenidoPlanInterdiciplinar::find()
            ->where(['activo' => true])
            ->andWhere(['not in', 'id', $arrayIdRespuestas])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        foreach ($modelIsmContenido as $model) {
            $objModelRespuesta = new IsmRespuestaPlanInterdiciplinar();
            $objModelRespuesta->id_grupo_plan_inter = $idGrupoInter;
            $objModelRespuesta->id_contenido_plan_inter = $model->id;
            $objModelRespuesta->respuesta = "-";
            $objModelRespuesta->save();
        }
    }

    //1.-
    public function datos_informativos($planUnidadId, $idGrupoInter)
    {
        $titulo = '1.- DATOS INFORMATIVOS';
        $esEditable = false;
        $objDatos = new Datos($planUnidadId);
        $planUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);


        $tiempo = $objDatos->calcula_horas(
            $planUnidad->planCabecera->ismAreaMateria->materia_id,
            $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id
        );

        $html = '';
        // inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>GRUPO DE ASIGNATURAS</b></div>';
        $html .= '<div class="col">' . $this->get_materias($idGrupoInter) . '</div>';
        $html .= '<div class="col"><b>PROFESOR(ES)</b></div>';
        $html .= '<div class="col">' . $this->get_docentes($idGrupoInter) . '</div>';
        $html .= '</div>';
        // //******finaliza row
        $html .= '<hr>';
        //inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>UNIDAD Nº</b></div>';
        $html .= '<div class="col">' . $planUnidad->curriculoBloque->last_name . '</div>';
        $html .= '<div class="col"><b>TÍTULO DE LA UNIDAD</b></div>';
        $html .= '<div class="col">' . $planUnidad->unit_title . '</div>';
        $html .= '</div>';
        // //******finaliza row
        $html .= '<hr>';
        //inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>AÑO PAI:</b></div>';
        $html .= '<div class="col">' . $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name . '</div>';
        $html .= '<div class="col"><b>DURACIÓN EN HORAS:</b></div>';
        $html .= '<div class="col">' . $tiempo['horas'] . '</div>';
        $html .= '</div>';
        // //******finaliza row
        $html .= '<hr>';
        // //inicia row
        $html .= '<div class="row">';
        $html .= '<div class="col"><b>FECHA INICIO:</b></div>';
        $html .= '<div class="col">' . $tiempo['fecha_inicio'] . '</div>';
        $html .= '<div class="col"><b>FECHA FINALIZACIÓN:</b></div>';
        $html .= '<div class="col">' . $tiempo['fecha_final'] . '</div>';
        $html .= '</div>';

        $html = $this->generico_marco_general(0, '', '1.1.-', $titulo, $esEditable, $html);

        return $html;
    }
    private function get_materias($idGrupoInter)
    {
        $modelIsmGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
            ->where(['id_grupo_plan_inter' => $idGrupoInter])
            ->all();

        $html = "";

        $html .= '<div class="card ">
                    <div class="card-header">
                        <div class="row">                           
                            <div class="col"><span style="color:red">Materia</div>
                        </div>
                    </div>
                    <div class="card-body">';
        foreach ($modelIsmGrupoMaterias as $modelGrupo) {
            $html .= '<div class="col"> - ' .
                $modelGrupo->ismAreaMateria->materia->nombre
                . '</div>';
        }
        $html .= '</div>
                </div>';

        return $html;
    }
    public function get_docentes($idGrupoInter)
    {
        $objHelper = new HelperGeneral();
        $modelIsmGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
            ->where(['id_grupo_plan_inter' => $idGrupoInter])
            ->all();

        $html = "";
        $periodoId = \Yii::$app->user->identity->periodo_id;

        $html .= '<div class="card ">
                    <div class="card-header">
                        <div class="row">                           
                            <div class="col"><span style="color:red">Profesores</div>
                        </div>
                    </div>
                    <div class="card-body">';
        foreach ($modelIsmGrupoMaterias as $modelGrupo) {
            $areaMateriaId = $modelGrupo->ismAreaMateria->id;
            $templateId = $modelGrupo->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;

            $resp = $objHelper->obtener_docentes_por_curso($areaMateriaId, $periodoId, $templateId);

            foreach ($resp as $r) {
                $html .= '<div class="col"> - ' .
                    $r['docente']
                    . '</div>';
            }
        }
        $html .= '</div>
                </div>';

        return $html;
    }

    //2.1.-
    private function proposito_integracion($idGrupoInter)
    {
        $titulo = '2.1.- Propósito de Integración';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '2.1.-';
        $campo = 'PROPÓSITO DE LA INTEGRACIÓN';
        $seccion = 2;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    //2.2.-
    private function conceptos_clave($idGrupoInter)
    {        
        $arrayInterno = array();
        $arrayHabilidades = $this->obtener_array_opciones_pai($idGrupoInter, 'concepto_clave');

        $html = $this->html_enfoque_habilidad($arrayHabilidades, 'CONCEPTO CLAVE', 'contenido');

        return $html;
    }
    //2.5.-
    private function contexto_global($idGrupoInter)
    {        
        $arrayInterno = array();
        $arrayHabilidades = $this->obtener_array_opciones_pai($idGrupoInter, 'contexto_global');

        $html = $this->html_enfoque_habilidad($arrayHabilidades, 'CONTEXTO CLAVE', 'contenido');

        return $html;
    }
    //2.3.-
    private function enunciado_indagacion($idGrupoInter)
    {
        $titulo = '2.3.- Enunciado de la Indagación';
        $descripcion = "<p><font size=3px> : (expresa claramente una comprensión conceptual importante que tiene un profundo significado y un valor a 
        largo plazo para los alumnos. Incluye claramente un concepto clave, conceptos relacionados y una exploración del contexto global 
        específica, que da una perspectiva creativa y compleja del mundo real; describe una comprensión transferible y a la vez importante 
        para la asignatura; establece un propósito claro para la indagación).</font></p>";
        $titulo = $titulo . ' ' . $descripcion;
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '2.3.-';
        $campo = 'ENUNCIADO DE LA INDAGACIÓN';
        $seccion = 2;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    //2.4.-
    private function preguntas_indagacion($idGrupoInter)
    {
        $titulo = '2.4.- Preguntas de Indagación';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '2.4.-';
        $campo = 'PREGUNTAS DE INDAGACÍON';
        $seccion = 2;

        $html = $this->html_preguntas_indagacion($idGrupoInter);

        return $html;
    }
    //2
    private function html_preguntas_indagacion($idGrupoInter)
    {
        $titulo = 'Fácticas: <font size=3px>(se basan en conocimientos y datos, ayudan a comprender terminología del enunciado, 
                    facilitan la comprensión, se pueden buscar)</font>';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '2.4.-';
        $campo = 'Fácticas';
        $seccion = 2;
        $htmlFacticas = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);
        $titulo = 'Conceptuales: <font size=3px>(conectar los datos, comparar y contrastar, explorar contradicciones, comprensión más profunda, 
                    transferir a otras situaciones, contextos e ideas, analizar y aplicar)</font>';
        $esEditable = true;
        $pestana = '2.4.-';
        $campo = 'Conceptuales';
        $seccion = 2;
        $htmlConceptuales = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);;
        $titulo = 'Debatibles: <font size=3px>(promover la discusión, debatir una posición, explorar cuestiones importantes desde múltiples perspectivas, 
        deliberadamente polémicas, presentar tensión, evaluar)</font>';
        $esEditable = true;
        $pestana = '2.4.-';
        $campo = 'Debatibles';
        $seccion = 2;
        $htmlDebatibles = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);;
        $html = "";

        $html .= '<div class="content">
                    <h4 style="text-align:center;">Preguntas de Indagación</h4>
                    <p style="text-align:center;">(inspiradas en el enunciado de indagación. Su fin es explorar el enunciado en mayor detalle. Ofrecen andamiajes).</p>
                    <div class="row"> 
                        <div class="col">';
        $html .= $htmlFacticas;
        $html .= '</div>
                    </div>
                    <div class="row"> 
                        <div class="col">';
        $html .= $htmlConceptuales;
        $html .= '</div>                        
                    </div>
                    <div class="row"> 
                        <div class="col">';
        $html .= $htmlDebatibles;
        $html .= '</div>
                    </div>
                </div>';


        return $html;
    }
    //3.2.-
    private function enfoque_actividad($idGrupoInter)
    {
        $titulo = '3.2.- Actividad';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '3.2.-';
        $campo = 'ACTIVIDAD';
        $seccion = 3;



        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    //3.1
    private function enfoque_habilidad($idGrupoInter)
    {
        $arrayInterno = array();
        $arrayHabilidades = $this->obtener_array_opciones_pai($idGrupoInter, 'habilidad_enfoque');

        $html = $this->html_enfoque_habilidad($arrayHabilidades, 'HABILIDADES', 'tipo');

        return $html;
    }
    //3.2
    private function enfoque_exploracion($idGrupoInter)
    {
        $arrayInterno = array();
        $arrayHabilidades = $this->obtener_array_opciones_pai($idGrupoInter, 'habilidad_enfoque');

        $html = $this->html_enfoque_habilidad($arrayHabilidades, 'EXPLORACION', 'contenido');

        return $html;
    }

    private function obtener_array_opciones_pai($idGrupoInter, $tipo)
    {
        $arrayInterno = array();
        $arrayHabilidades = array();

        $modelGrupoInte = IsmGrupoPlanInterdiciplinar::findOne($idGrupoInter);
        $abreviaturaBloque = $modelGrupoInte->bloque->abreviatura;

        $modelCurriculoMec = CurriculoMecBloque::find()
            ->where(['shot_name' => $abreviaturaBloque])
            ->one();

        //1.-buscamos los id_are_materias
        $modelGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
            ->where(['id_grupo_plan_inter' => $idGrupoInter])
            ->all();

        //2.- buscamos plan desag cab , con el ism_area_materia
        foreach ($modelGrupoMaterias as $modelGrupo) {
            $modelPlanDesagCabecera = PlanificacionDesagregacionCabecera::find()
                ->where(['ism_area_materia_id' => $modelGrupo->id_ism_area_materia])
                ->one();

            if ($modelPlanDesagCabecera) {
                $modelPlanBloqueUndiad = PlanificacionBloquesUnidad::find()
                    ->where(['plan_cabecera_id' => $modelPlanDesagCabecera->id])
                    ->andWhere(['curriculo_bloque_id' => $modelCurriculoMec->id])
                    ->one();

                $modelPlanVertOpPai = PlanificacionVerticalPaiOpciones::find()
                    ->where(['plan_unidad_id' => $modelPlanBloqueUndiad->id])
                    ->andWhere(['tipo' => $tipo])
                    ->all();

                foreach ($modelPlanVertOpPai as $modelOpcion) {
                    $arrayInterno = array(
                        'tipo' => $modelOpcion->tipo2,
                        'contenido'  => $modelOpcion->contenido
                    );
                    $arrayHabilidades[] = $arrayInterno;
                }
            }
        }
        return $arrayHabilidades;
    }
    private function html_enfoque_habilidad($arrayHabilidades, $titulo, $index)
    {
        $html = "";
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">
                    <div class="card" style="width: 90%; margin-top:20px">
                        <div class="card-header" style="background-color:#800834">                           
                                <h6 class="text-center" style="color:#ffffff">' . $titulo . '</h6>                         
                        </div>
                    
                        <div class="card-body">
                            <table class="table table-striped table-bordered">
                                <tr style="font-size:15px;"> 
                                    <td><b>TIPO</b></td>
                                    <td><b>CONTENIDO</b></td>
                                </tr>';

        foreach ($arrayHabilidades as $array) {
            $html .= '<tr style="font-size:13px;"> 
                                                <td>';
            $html .= $array['tipo'];
            $html .= '</td>
                                                <td>';
            $html .= $array['contenido'];
            $html .= '</td>                                            
                                            </tr>';
        }
        $html .= '          </table>
                        </div>
                    </div>
                 </div>';
        return $html;
    }
    //4.-
    private function objetivos_desarrollo_sostenible_competencia($idGrupoInter)
    {
        $titulo = '4.1.- Competencia: <font size=1px>Se seleccionará la competencia del listado de competencias de ODS</font>';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '4.1.-';
        $campo = 'COMPETENCIA';
        $seccion = 4;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    private function objetivos_desarrollo_sostenible_actividad($idGrupoInter)
    {
        $titulo = '4.2.- Actividad: <font size=1px>Detallar la actividad que contribuye al desarrollo de la competencia</font>';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '4.2.-';
        $campo = 'ACTIVIDAD';
        $seccion = 4;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    private function objetivos_desarrollo_sostenible_objetivo($idGrupoInter)
    {
        $titulo = '4.3.- Objetivo: <font size=1px>(Se incluirá la imagen del icono del ODS que se desarrolle)</font>';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '4.3.-';
        $campo = 'OBJETIVO';
        $seccion = 4;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    private function objetivos_desarrollo_sostenible_relacion_ods($idGrupoInter)
    {
        $titulo = '4.4.- Relación ODS-IB: <font size=1px>(Se realizará una breve descripción entre la actividad ODS con la evaluación sumativa)</font>';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '4.4.-';
        $campo = 'RELACION ODS-IB';
        $seccion = 4;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    //5.2.-
    private function evaluacion_formativas_disciplinar($idGrupoInter)
    {
        $titulo = '5.2.- Evaluación Formativa Disciplinar';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '5.2.-';
        $campo = 'EVALUACIONES FORMATIVAS DISCIPLINARIAS';
        $seccion = 5;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    //5.3.-
    private function evaluacion_formativas_interdisciplinar($idGrupoInter)
    {
        $titulo = '5.3.- Evaluación Formativa Interdisciplinar';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '5.3.-';
        $campo = 'EVALUACIONES FORMATIVAS INTERDISCIPLINARIAS';
        $seccion = 5;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    //5.4.-
    private function evaluacion_formativas_sumativa($idGrupoInter)
    {
        $titulo = '5.4.- Evaluación Sumativa';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '5.4.-';
        $campo = 'EVALUACION SUMATIVA';
        $seccion = 5;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    //7.-
    private function proceso_experiencia_aprendizaje($idGrupoInter)
    {
        $titulo = '7.1.- Experiencia de Aprendizaje y Estrategia de Enseñanza Interdiciplinarios';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '7.1.-';
        $campo = 'EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS DE ENSEÑANZA INTERDISCIPLINARIOS';
        $seccion = 7;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    private function proceso_necesidades_especiales($idGrupoInter)
    {
        $titulo = '7.2.- Atención a las Necesidades Especiales';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '7.2.-';
        $campo = 'ATENCIÓN A LAS NECESIDADES EDUCATIVAS ESPECIALES';
        $seccion = 7;

        $html = $this->html_necesidades_especiales($idGrupoInter);

        return $html;
    }
    private function html_necesidades_especiales($idGrupoInter)
    {
        $titulo = 'Grado 1';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '7.2.-';
        $campo = 'GRADO 1';
        $seccion = 7;
        $htmlGrado1 = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);
        $titulo = 'Grado 2';
        $esEditable = true;
        $pestana = '7.2.-';
        $campo = 'GRADO 2';
        $seccion = 7;
        $htmlGrado2 = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);;
        $titulo = 'Grado 3';
        $esEditable = true;
        $pestana = '7.2.-';
        $campo = 'GRADO 3';
        $seccion = 7;
        $htmlGrado3 = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);;
        $html = "";

        $html .= '<div class="content">
                    <h4 style="text-align:center;">ATENCIÓN A LAS NECESIDADES EDUCATIVAS ESPECIALES</h4>
                        <p style="text-align:center;">
                            (Detalle las estrategias de trabajo a realizar para cada caso, las especificadas 
                            por el Tutor Psicólogo y las propias de su asignatura o enseñanza)
                        </p>
                    <div class="row"> 
                        <div class="col">';
        $html .= $htmlGrado1;
        $html .= '</div>
                    </div>
                    <div class="row"> 
                        <div class="col">';
        $html .= $htmlGrado2;
        $html .= '</div>                        
                    </div>
                    <div class="row"> 
                        <div class="col">';
        $html .= $htmlGrado3;
        $html .= '</div>
                    </div>
                </div>';


        return $html;
    }
    //8.-
    private function recursos($idGrupoInter)
    {
        $titulo = '8.1.- Recursos';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '8.1.-';
        $campo = 'RECURSOS';
        $seccion = 8;

        $html = $this->html_recursos($idGrupoInter);

        return $html;
    }
    private function html_recursos($idGrupoInter)
    {
        $titulo = 'Bibliográfico';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '8.1.-';
        $campo = 'BIBLIOGRÁFICO';
        $seccion = 8;
        $htmlBibliografia = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);
        $titulo = 'Tecnológico';
        $esEditable = true;
        $pestana = '8.1.-';
        $campo = 'TECNOLÓGICO';
        $seccion = 8;
        $htmlTecnologico = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);;
        $titulo = 'Otros';
        $esEditable = true;
        $pestana = '8.1.-';
        $campo = 'OTROS';
        $seccion = 8;
        $htmlOtros = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);;
        $html = "";

        $html .= '<div class="content">
                     <h4 style="text-align:center;">RECURSOS</h4>
                         <p style="text-align:center;">
                            En esta sección especificar claramente cada recurso que se utilizará. Podría mejorarse incluyendo recursos 
                            que pudieran utilizarse para llevar a cabo la diferenciación, así como también agregando, por ejemplo, oradores 
                            y entornos que pudieran generar mayor profundidad en el trabajo reflexivo sobre el enunciado de la unidad.
                         </p>
                     <div class="row"> 
                         <div class="col">';
        $html .= $htmlBibliografia;
        $html .= '</div>
                     </div>
                     <div class="row"> 
                         <div class="col">';
        $html .= $htmlTecnologico;
        $html .= '</div>                        
                     </div>
                     <div class="row"> 
                         <div class="col">';
        $html .= $htmlOtros;
        $html .= '</div>
                     </div>
                 </div>';


        return $html;
    }
    //9.
    private function reflexion($idGrupoInter)
    {
         $html="";
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 90%; margin-top:20px">';
        
            $html .= '<div class="card-header" style="background-color:#800834;">';                    
                $html .= '<h5 class="" style="color: #ffffff;"><b>9.1.- REFLEXIÓN: '.$idGrupoInter.' </b></h5>';                        
                $html .= '<small style="color: #ffffff; font-size:12px">(Consideración de la planificación, el proceso y el impacto de la indagación. En el proceso de reflexión, garantizar dar respuesta a varias de la preguntas planteadas en cada momento.)</small>';
            $html .= '</div>';
                
            $html .= '<div class="card-body">';
                $html .= '<div class="ocultar">';
                $html .= $this->modal_refelxion($idGrupoInter);
                $html .='</div>';
                $html .= '<div class="table table-responsive">';     

                $html .= '<table class="table table-hover table-condensed table-bordered">';          
                        $html .= '<div class="table table-responsive">';
                            $html .= '<table class="table table-condensed table-bordered">';
                                $html .= '<thead>';
                                    $html .= '<tr style="background-color:#CCC">';
                                        $html .= '<th class="text-center" style="background-color: #0a1f8f; color: white">ANTES DE ENSEÑAR LA UNIDAD</th>';
                                        $html .= '<th class="text-center" style="background-color: #9e28b5; color: white">MIENTRAS SE ENSEÑA LA UNIDAD</th>';
                                        $html .= '<th class="text-center" style="background-color: #ab0a3d; color: white">DESPUÉS DE ENSEÑAR LA UNIDAD</th>';
                                    $html .= '</tr>';
                                $html .= '</thead>';
                                $html .= '<tbody id="table-reflexion-disponibles">';
                                        $html.= '<tr>';
                                            $html.='<td>'.$this->mostrar_preguntas_reflexion_seleccionadas($idGrupoInter,'antes').'</td>';
                                            $html.='<td>'.$this->mostrar_preguntas_reflexion_seleccionadas($idGrupoInter,'mientras').'</td>';
                                            $html.='<td>'.$this->mostrar_preguntas_reflexion_seleccionadas($idGrupoInter,'despues').'</td>';
                                        $html.= '</tr>';
                                     $html.='</tbody>';
                            $html .= '</table>';
                        $html .= '</div>';

                $html .= '</table>';            
                $html .= '</div>';

            $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>'; 

        
        return $html;
    }
    //9
    private function modal_refelxion($idGrupoInter){
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModal" onclick="show_reflexion_disponibles()"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ab0a3d"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                              Seleccionar Preguntas
                              </span>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="reflexionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">SELECCIONAR PREGUNTAS</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                      
                    </div>'; //FIN DE MODAL -HEADER

                    $html .= '<div class="modal-body">'; //Inicio de modal-body
                
                        $html .= '<div class="table table-responsive">';
                            $html .= '<table class="table table-condensed table-bordered">';
                                $html .= '<thead>';
                                    $html .= '<tr>';
                                        $html .= '<th class="text-center" style="background-color: #0a1f8f; color: white">ANTES DE ENSEÑAR LA UNIDAD</th>';
                                        $html .= '<th class="text-center" style="background-color: #9e28b5; color: white">MIENTRAS SE ENSEÑA LA UNIDAD</th>';
                                        $html .= '<th class="text-center" style="background-color: #ab0a3d; color: white">DESPUÉS DE ENSEÑAR LA UNIDAD</th>';
                                    $html .= '</tr>';
                                $html .= '</thead>';
                                $html .= '<tbody id="table-reflexion-disponibles">';
                                        $html.= '<tr>';
                                            $html.='<td>'.$this->mostrar_preguntas_reflexion_disponibles($idGrupoInter,'antes').'</td>';
                                            $html.='<td>'.$this->mostrar_preguntas_reflexion_disponibles($idGrupoInter,'mientras').'</td>';
                                            $html.='<td>'.$this->mostrar_preguntas_reflexion_disponibles($idGrupoInter,'despues').'</td>';
                                        $html.= '</tr>';
                                $html.='</tbody>';
                            $html .= '</table>';
                        $html .= '</div>';

                      
                    $html .= '</div>';// fin de modal-body

                    $html .= '<div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="recargar_pagina()">Cerrar</button>                      
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }
    //9
    private function mostrar_preguntas_reflexion_disponibles($idGrupoInter,$tipo_pregunta)
    {
        $con = Yii::$app->db;
        $query="select id,tipo ,categoria ,opcion  from planificacion_opciones po 
        where id not in (
        select id_planificacion_opciones  from ism_respuesta_reflexion_pai_interdiciplinar irrpi where id_respuesta_plan_inter_pai =$idGrupoInter
        ) and tipo='REFLEXION' and categoria='$tipo_pregunta' and estado = true";      

        $arraylPlanOpciones = $con->createCommand($query)->queryAll();

        $html ="";
        $html.='<table>';
                    foreach($arraylPlanOpciones as $array)
                    {
                        $html.='<tr><td><a href="#" onclick="guardar_pregunta_reflexion('.$array['id'].',\''.strtoupper($tipo_pregunta).'\');">'.$array['opcion'].'</a></td></tr>';
                    }
        $html.='</table>';
        return $html;
    }
    //9
    private function mostrar_preguntas_reflexion_seleccionadas($idIsmGrupoInter,$tipo_pregunta)
    {     
        $con = Yii::$app->db;   

        //script para mostar id correspondiente a la ´respuesta en el plan interdiciplinar pai para reflexion
        $query = "select i1.id_respuesta_plan_inter_pai ,i1.id_planificacion_opciones ,
                    i1.respuesta ,i2.tipo ,i2.categoria,i2.opcion  
                    from ism_respuesta_reflexion_pai_interdiciplinar i1,
                    planificacion_opciones i2
                    where i1.id_planificacion_opciones  = i2.id 
                    and i1.id_respuesta_plan_inter_pai = $idIsmGrupoInter
                    and i2.seccion ='PAI' and i2.categoria ='$tipo_pregunta';";
   
        $resp = $con->createCommand($query)->queryAll();   

        $html="";
        $html.='<table>';
        foreach($resp as $r)
        {     
            $html.='<tr><td><a href="#">'.$r['opcion'].'</a></td></tr>';
        }
        $html.='</table>';    

        return $html;       
    }
    //9
    private function guardar_pregunta_reflexion($idPregunta,$tipo_pregunta,$idIsmGrupoInter)
    {
        $con = Yii::$app->db;

        //script para mostar id correspondiente a la ´respuesta en el plan interdiciplinar pai para reflexion

        $query = "select id  from ism_respuesta_plan_interdiciplinar irpi 
                    where id_contenido_plan_inter in 
                    (select id from ism_contenido_plan_interdiciplinar icpi where nombre_campo = upper('$tipo_pregunta') )
                    and id_grupo_plan_inter  = $idIsmGrupoInter;";
        $resp = $con->createCommand($query)->queryOne();

     

        $modelRespuestaReflexion = new IsmRespuestaReflexionPaiInterdiciplinar();
        $modelRespuestaReflexion->id_respuesta_plan_inter_pai = $resp['id'];
        $modelRespuestaReflexion->id_planificacion_opciones = $idPregunta;
        $modelRespuestaReflexion->respuesta = " - ";
    
        $modelRespuestaReflexion->save();
    }
    /********************************************************************************************************* */
    /********************************************************************************************************* */
    /********************************************************************************************************* */

    //metodos genericos para todos los campos de texto
    private function generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter)
    {

        //extraemos el Id de la pregunta
        $modelPreguntaContenido = IsmContenidoPlanInterdiciplinar::find()
            ->where(['id_seccion_interdiciplinar' => $seccion])
            ->andWhere(['nombre_campo' => $campo])
            ->andWhere(['activo' => true])
            ->andWhere(['heredado' => false])
            ->one();


        //Extraemos la respuesta
        $modelRespuesta = IsmRespuestaPlanInterdiciplinar::find()
            ->where(['id_grupo_plan_inter' => $idIsmGrupoInter])
            ->andwhere(['id_contenido_plan_inter' => $modelPreguntaContenido->id])
            ->one();

        $html = "";

        if ($modelRespuesta) {
            $respuesta = $modelRespuesta->respuesta;
            $idRespuesta = $modelRespuesta->id;

            $html = $this->generico_marco_general($idRespuesta, $respuesta, $pestana, $titulo, $esEditable, $respuesta);
        } else {
            $html = "<h1> no hay información</h1>";
        }


        return $html;
    }
    private function generico_marco_general($idRespuesta, $respuesta, $pestana, $titulo, $esEditable, $htmlEntrada)
    {
        $html = '';
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 90%; margin-top:20px">';
        $html .= '<div class="card-header" style="background-color:#800834">';
        $html .= '<h6 class="text-center" style="color:#ffffff" >' . $titulo . '</h6>';
        $html .= '</div>';
        $html .= '<div class="card-body">';
        if ($esEditable) {
            $html .= $this->generico_editar_texto($idRespuesta, $respuesta, $pestana, $titulo);
            $html .= '<br>';
        }
        $html .= $htmlEntrada;
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
    private function generico_editar_texto($id, $respuesta, $pestana, $titulo)
    {
        $html = '<br><a href="#"  data-bs-toggle="modal" data-bs-target="#modalS2' . $id . '"> 
                    <i class="fas fa-edit"></i>Editar';
        $html .= '</a>';

        $html .= '<div class="modal fade" id="modalS2' . $id . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">' . $titulo . '</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>';
        $html .= '<div class="modal-body"> <hr>               
                                        <textarea id="editor-sumativa2' . $id . '" name="sumativas" " class="form-control">' . $respuesta . '</textarea>
                                            <script>
                                            CKEDITOR.replace("editor-sumativa2' . $id . '", {
                                            customConfig: "/ckeditor_settings/config.js"
                                            })
                                            </script>';
        $html .= '</div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update_campo(' . $id . ',\'' . $pestana . '\')">Actualizar</button>
                                    </div>
                                </div>
                        </div>
                </div>';
        $html .= '<br>';
        return $html;
    }
}
