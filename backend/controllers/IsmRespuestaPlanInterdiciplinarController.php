<?php

namespace backend\controllers;

use backend\models\ContenidoPaiOpciones;
use backend\models\CurriculoMecBloque;
use backend\models\helpers\HelperGeneral;
use backend\models\IsmGrupoMateriaPlanInterdiciplinar;
use backend\models\IsmContenidoPlanInterdiciplinar;
use backend\models\IsmCriterioDescriptor;
use backend\models\IsmCriterioDescriptorArea;
use backend\models\IsmGrupoPlanInterdiciplinar;
use backend\models\IsmRespuestaPlanInterdiciplinar;
use backend\models\IsmRespuestaPlanInterdiciplinar2;
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
use backend\models\IsmRes;
use backend\models\IsmRespuestaOpcionesPaiInterdiciplinar;
use backend\models\IsmRespuestaContenidoPaiInterdiciplinar;
use backend\models\IsmRespuestaContenidoPaiInterdiciplinar2;
use backend\models\PlanificacionVerticalPaiDescriptores;
use yii\filters\AccessControl;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\helpers\Scripts;
use backend\models\interdiciplinarPai\PdfInterdiciplinarPai;
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
                $html = $this->conceptos_clave($idGrupoInter, $pestana);
                break;
            case '2.3.-':
                $html = $this->enunciado_indagacion($idGrupoInter);
                break;
            case '2.4.-':
                $html = $this->preguntas_indagacion($idGrupoInter);
                break;
            case '2.5.-':
                $html = $this->contexto_global($idGrupoInter, $pestana);
                break;
            case '3.1.-':
                $html = $this->enfoque_habilidad($idGrupoInter, $pestana);
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
            case '4.0.-':
                    $html = $this->objetivos_desarrollo_sostenible_todos($idGrupoInter);
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
            case '6.1.-':
                $html = $this->accion_ensenianza_aprendizaje($idGrupoInter);
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
    //9
    public function actionGuardarPreguntaReflexion()
    {
        $idGrupoInter = $_POST['idGrupoInter'];
        $id_pregunta = $_POST['id_pregunta'];
        $tipo_pregunta = $_POST['tipo_pregunta'];

        //insertamos datos de la pregunta
        $this->guardar_pregunta_reflexion($id_pregunta, $tipo_pregunta, $idGrupoInter);

        $html = $this->mostrar_preguntas_disponibles($idGrupoInter);

        return $html;
    }
    //9
    public function actionEliminarPreguntaReflexion()
    {
        $idGrupoInter = $_POST['idGrupoInter'];
        $id_pregunta = $_POST['id_pregunta'];

        $model = IsmRespuestaReflexionPaiInterdiciplinar::findOne($id_pregunta);


        $model->delete();

        $html = $this->mostrar_preguntas_seleccionadas($idGrupoInter);

        return $html;
    }
    //9
    public function actionActualizarPreguntaReflexion()
    {

        $id_pregunta = $_POST['id_pregunta'];
        $respuesta = $_POST['respuesta'];

        $model = IsmRespuestaReflexionPaiInterdiciplinar::findOne($id_pregunta);
        $model->respuesta = $respuesta;
        $model->save();
    }
    //9
    public function actionActualizarPreguntaOpciones()
    {

        $id_pregunta = $_POST['id_pregunta'];
        $respuesta = $_POST['respuesta'];

        $model = IsmRespuestaOpcionesPaiInterdiciplinar::findOne($id_pregunta);
        $model->actividad = $respuesta;
        $model->save();
    }
    //9
    public function actionQuitarAgregarSeleccion()
    {
        $id_Respuesta = $_POST['id_Respuesta'];
        $bandera = $_POST['bandera'];

        $model = IsmRespuestaOpcionesPaiInterdiciplinar::findOne($id_Respuesta);
        $model->mostrar = $bandera;

        $model->save();
    }
    //3                   
    public function actionAgregarAtributoPerfil()
    {
        $id_Respuesta = $_POST['idRespOpciones'];
        $idContOp = $_POST['idRespContenido'];
       
        $modelContOpc = ContenidoPaiOpciones::find()
        ->where(['id'=>$idContOp])
        ->one();

        $model = new IsmRespuestaContenidoPaiInterdiciplinar();    
        $model->id_respuesta_opciones_pai = $id_Respuesta;
        $model->id_contenido_pai =$modelContOpc->id;
        $model->mostrar = 1;
        $model->tipo =$modelContOpc->tipo;
        $model->contenido =$modelContOpc->contenido_es;         
     

        $model->save();
    }
    //3
    public function actionQuitarAtributoPerfil()
    {
        $id_Respuesta = $_POST['idRespContenido'];

        $model = IsmRespuestaContenidoPaiInterdiciplinar::findOne($id_Respuesta);        
        $model->delete();
    }
    //PDF Interdiciplinar Pai
    public function actionPdf()
    {
        $idGrupoInter = $_GET['idGrupoInter'];
        $idPlanUnidad = $_GET['idPlanUnidad'];

        $objPdf = new PdfInterdiciplinarPai($idGrupoInter,$idPlanUnidad);
    }
    //4
    public function actionGuardarCompetencias()
    {
        $idGrupoInter = $_POST['idGrupoInter'];
        $id_pregunta = $_POST['id_pregunta'];
        $tipo_pregunta = $_POST['tipo_pregunta'];    
      
        //insertamos datos de la pregunta
        $this->guardar_competencias($id_pregunta, $tipo_pregunta, $idGrupoInter);

        $html = $this->mostrar_competencias_disponibles($idGrupoInter, 'COMPETENCIA');

        return $html;
    }
     //4
     public function actionEliminarCompetencias()
     {
         $id_pregunta = $_POST['id_pregunta'];  
         $idGrupoInter = $_POST['idGrupoInter'];    
       
         $model = IsmRespuestaContenidoPaiInterdiciplinar2::findOne($id_pregunta);
         $model->delete();

         $html = $this->mostrar_competencias_disponibles($idGrupoInter, 'COMPETENCIA');

         return $html;
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
    private function conceptos_clave($idGrupoInter, $pestana)
    {
        $arrayInterno = array();
        $tipo = 'concepto_clave';
        $tipoM = 'CONCEPTO CLAVE'; //DESCRIPCION PARA LAS RESPUESTAS DEL INTER DISCIPLINAR PAI
        $tipoM2 = 'CONCEPTO CLAVE'; //DESCRIPCION PARA OPCIONES PAI

        return $this->extrae_datos_plan_vertical($idGrupoInter, $tipo, $tipoM, $tipoM2, $pestana);
    }
    //2.5.-
    private function contexto_global($idGrupoInter, $pestana)
    {
        $arrayInterno = array();
        $tipo = 'contexto_global';
        $tipoM = 'CONTEXTO GLOBAL'; //DESCRIPCION PARA LAS RESPUESTAS DEL INTER DISCIPLINAR PAI
        $tipoM2 = 'CONTEXTO GOBAL'; //DESCRIPCION PARA OPCIONES PAI

        return $this->extrae_datos_plan_vertical($idGrupoInter, $tipo, $tipoM, $tipoM2, $pestana);
    }
    // 2 y 3
    private function extrae_datos_plan_vertical($idGrupoInter, $tipo, $tipoM, $tipoM2, $pestana)
    {
        //buscamos los tipos seleccionados de las materias
        $arrayHabilidades = $this->obtener_array_opciones_pai($idGrupoInter, $tipo);

        //buscamos el id de la respuesta para planificar pai inter
        $con = Yii::$app->db;
        $query = "select i1.id ,i1.id_grupo_plan_inter,i1.id_contenido_plan_inter,i1.respuesta  
                     from ism_respuesta_plan_interdiciplinar i1,
                     ism_contenido_plan_interdiciplinar i2
                     where i1.id_contenido_plan_inter = i2.id 
                     and i2.nombre_campo ='$tipoM'
                     and i1.id_grupo_plan_inter = $idGrupoInter;";

        $arraylPlanOpciones = $con->createCommand($query)->queryOne();



        foreach ($arrayHabilidades as $item) {
            //buscamos si existe el ism respuesta, con los datos de concepto clave
            $modelBusqueda = IsmRespuestaOpcionesPaiInterdiciplinar::find()
                ->where(['id_respuesta_plan_inter_pai' => $arraylPlanOpciones['id']])
                ->andWhere(['id_plan_vert_opciones' => $item['id']])
                ->all();

            if (!$modelBusqueda) {
                $modelRespOp = new IsmRespuestaOpcionesPaiInterdiciplinar();
                $modelRespOp->id_respuesta_plan_inter_pai = $arraylPlanOpciones['id'];
                $modelRespOp->id_plan_vert_opciones = $item['id'];
                $modelRespOp->mostrar = true;
                $modelRespOp->tipo = $item['tipo'];
                $modelRespOp->contenido = $item['contenido'];
                $modelRespOp->save();
            }
        }
        switch ($tipo) {
            case 'habilidad_enfoque':
                $html = $this->html_enfoque_habilidad($arrayHabilidades, $idGrupoInter, $tipoM, $tipoM2, $arraylPlanOpciones['id'], $pestana);
                break;
            case 'concepto_clave':
                $html = $this->html_concepto_clave_global($arrayHabilidades, $idGrupoInter, $tipoM, $tipoM2, $arraylPlanOpciones['id'], $pestana);
                break;
            case 'contexto_global':
                $html = $this->html_concepto_clave_global($arrayHabilidades, $idGrupoInter, $tipoM, $tipoM2, $arraylPlanOpciones['id'], $pestana);
                break;
        }

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
    private function enfoque_habilidad($idGrupoInter, $pestana)
    {
        $html = '';
        $arrayInterno = array();
        $tipo = 'habilidad_enfoque';
        $tipoM = 'HABILIDAD'; //DESCRIPCION PARA LAS RESPUESTAS DEL INTER DISCIPLINAR PAI
        $tipoM2 = ''; //DESCRIPCION PARA OPCIONES PAI

        $html = $this->extrae_datos_plan_vertical($idGrupoInter, $tipo, $tipoM, $tipoM2, $pestana);

   

        return $html;
    }
    //2
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
                        'id' => $modelOpcion->id,
                        'tipo' => $modelOpcion->tipo2,
                        'contenido'  => $modelOpcion->contenido
                    );
                    $arrayHabilidades[] = $arrayInterno;
                }
            }
        }
        return $arrayHabilidades;
    }
    //2
    private function html_concepto_clave_global($arrayHabilidades, $idGrupoInter, $titulo, $tipo, $idRespuestPlanInterPai, $pestana)
    {
        //extraigo los datos que estan seleccionados
        $modelOpcionesSeleccionadas = IsmRespuestaOpcionesPaiInterdiciplinar::find()
            //->where(['tipo'=>$tipo])
            ->where(['mostrar' => true])
            ->andWhere(['id_respuesta_plan_inter_pai' => $idRespuestPlanInterPai])
            ->all();

        $modelOpcionesNoSeleccionadas = IsmRespuestaOpcionesPaiInterdiciplinar::find()
            //->where(['tipo'=>$tipo])
            ->where(['mostrar' => false])
            ->andWhere(['id_respuesta_plan_inter_pai' => $idRespuestPlanInterPai])
            ->all();
        $html = "";
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">
                    <div class="card" style="width: 90%; margin-top:20px">
                        <div class="card-header" style="background-color:#800834">                           
                                <h6 class="text-center" style="color:#ffffff">' . $titulo . '</h6>                         
                        </div>';

        $html .= '<div class="card-body">
                            <h3>Seleccionado</h3>
                            <table class="table table-striped table-bordered">
                                <tr style="font-size:15px;"> 
                                    <td><b>TIPO</b></td>
                                    <td><b>CONTENIDO</b></td>
                                    <td><b>ACCION</b></td>
                                </tr>';

        foreach ($modelOpcionesSeleccionadas as $model) {
            $html .= '<tr style="font-size:13px;">
                                                    <td>';
            $html .= $model->tipo;
            $html .= '</td>
                                                      <td>';
            $html .= $model->contenido;
            $html .= '</td>
                                                      <td>';
            $html .= '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModal" onclick="quitar_agregar_seleccion(0,' . $model->id . ',\'' . $pestana . '\')"> 
                                                            <i style="color:red;" class="fas fa-trash"></i> 
                                                    </a>';
            $html .= '</a>';
            $html .= '</td>                                            
                                                   </tr>';
        }
        $html .= '  </table>
                        </div>';
        $html .= '<div class="card-body">
                        <h3>No Seleccionado</h3>
                        <table class="table table-striped table-bordered">
                            <tr style="font-size:15px;"> 
                                <td><b>TIPO</b></td>
                                <td><b>CONTENIDO</b></td>
                                <td><b>ACCION</b></td>
                            </tr>';

        foreach ($modelOpcionesNoSeleccionadas as $model) {
            $html .= '<tr style="font-size:13px;">
                                                <td>';
            $html .= $model->tipo;
            $html .= '</td>
                                                  <td>';
            $html .= $model->contenido;
            $html .= '</td>
                                                  <td>';
            $html .= '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModal" onclick="quitar_agregar_seleccion(1,' . $model->id . ',\'' . $pestana . '\')"> 
                                                        <i style="color:green;" class="fas fa-check-circle"></i>
                                                    </a>';
            $html .= '</td>                                            
                                               </tr>';
        }
        $html .= '  </table>
                    </div>';
        $html .= '</div>
                 </div>';
        return $html;
    }
    //3
    private function html_enfoque_habilidad($arrayHabilidades, $idGrupoInter, $titulo, $tipo, $idRespuestPlanInterPai, $pestana)
    {
        //extraigo los datos que estan seleccionados
        $modelOpcionesSeleccionadas = IsmRespuestaOpcionesPaiInterdiciplinar::find()
            //->where(['tipo'=>$tipo])
            ->where(['mostrar' => true])
            ->andWhere(['id_respuesta_plan_inter_pai' => $idRespuestPlanInterPai])
            ->all();

        $modelOpcionesNoSeleccionadas = IsmRespuestaOpcionesPaiInterdiciplinar::find()
            //->where(['tipo'=>$tipo])
            ->where(['mostrar' => false])
            ->andWhere(['id_respuesta_plan_inter_pai' => $idRespuestPlanInterPai])
            ->all();
        $html = "";
         //*********************************************************************************************** */
        //tabla de la parte superior donde se muestran las opciones de habilidades que  estan seleccionadas
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">
                    <div class="card" style="width: 90%; margin-top:20px">
                        <div class="card-header" style="background-color:#800834">                           
                                <h6 class="text-center" style="color:#ffffff">' . $titulo . '</h6>                         
                        </div>';

        $html .= '<div class="card-body">
                            <h3>Seleccionado</h3>
                            <table class="table table-striped table-bordered; " >
                                <tr style="font-size:15px;"> 
                                    <td><b>HABILIDAD</b></td>
                                    <td style="width:20%"><b>EXPLORACIÓN</b></td>
                                    <td><b>ACTIVIDAD</b></td>
                                    <td><b>ATRIBUTOS DEL PERFIL</b></td>
                                </tr>';

        foreach ($modelOpcionesSeleccionadas as $model) 
        {
            $html .= '<tr style="font-size:13px;">
                            <td>';
                        $html .= $model->tipo;
                    $html .= '</td>
                            <td>';                                
                                $html .= '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModal" onclick="quitar_agregar_seleccion(0,' . $model->id . ',\'' . $pestana . '\')"> 
                                                <i style="color:red;" class="fas fa-trash"></i> 
                                        </a>';
                                $html .= $model->contenido;
                    $html .= '</td>';
                    $html .= '<td>
                                    <textarea id="respuesta_op' . $model->id . '" class="form-control" style="max-width: 100%;" 
                                    onchange="actualizar_pregunta_opciones(' . $model->id. ')">' . $model->actividad . '</textarea>
                                </td>';
                    $html .= '<td style="text-align:right;">';
                                    $html .= '<a href="#"  data-bs-toggle="modal" data-bs-target="#modalOpciones_'.$model->id.'" > 
                                                        <i class="fas fa-object-ungroup"> Selecionar</i>                                                     
                                              </a>';
                                    $html .= $this->mostrar_atributos_perfil_seleccionados($model->id);
                                    
                                   
                    $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '  </table>
                        </div>';
        //*********************************************************************************************** */
        //tabla de la parte inferior donde se muestran las opciones de habilidades que no estan seleccionadas
        $html .= '<div class="card-body">
                        <h3>No Seleccionado</h3>
                        <table class="table table-striped table-bordered">
                            <tr style="font-size:15px;"> 
                                <td><b>TIPO</b></td>
                                <td><b>CONTENIDO</b></td>
                                <td><b>ACCION</b></td>
                            </tr>';

        foreach ($modelOpcionesNoSeleccionadas as $model) 
        {
            $html .= '<tr style="font-size:13px;">';                  
                    $html .= '<td>';                                                
                         $html .= $model->tipo;
                    $html .= '</td>';
                    $html .= '<td>';
                        $html .= $model->contenido;
                    $html .= '</td>';    
                    $html .= '<td>';
                    $html .= '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModal" onclick="quitar_agregar_seleccion(1,' . $model->id . ',\'' . $pestana . '\')"> 
                                                            <i style="color:green;" class="fas fa-check-circle"></i>
                                                        </a>';
                     $html .= '</td>';                                                   
            $html .= '</tr>';
        }
        $html .= '  </table>
                    </div>';
        $html .= '</div>
                 </div>';
        
    
        //*********************************************************************************************** */
        //empieza modal para seleccionar los atributos del perfil
        foreach ($modelOpcionesSeleccionadas as $model) 
        {

                $html .= '<div class="modal fade" id="modalOpciones_'.$model->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-x">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Enfoques del Aprendizaje</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="ver_detalle(\'' . $pestana . '\')"></button>                      
                                        </div>'; //FIN DE MODAL -HEADER

                                //Inicio de modal-body
                                $html .= '<div class="modal-body">'; 
                                        $html .= '<div class="table table-responsive">';
                                            $html .= '<table class="table table-condensed table-bordered">';
                                            $html .= '<thead>';
                                            $html .= '<tr>';
                                            $html .= '<th class="text-center" style="background-color: #0a1f8f; color: white">ATRIBUTOS DE PERFIL</th>';
                                            $html .= '</tr>';
                                            $html .= '</thead>';
                                            $html .= '<tbody id="table-reflexion-disponibles">';
                                            $html .= '<tr>';
                                            $html .= '<td>' . $this->mostrar_atributos_perfil_no_seleccionados($model->id) . '</td>';
                                            $html .= '</tr>';
                                            $html .= '</tbody>';
                                            $html .= '</table>';
                                        $html .= '</div>';
                                $html .= '</div>'; 
                            $html .= '</div>'; 
                        $html .= '</div>'; 
                $html .= '</div>'; 
        }
        // fin de modal opciones 3

        return $html;
    }
    //3
    private function mostrar_atributos_perfil_seleccionados($idRespOpciones)
    {
        $con = Yii::$app->db;
        $query = "select i1.id ,i2.id as idRespuesta,i1.tipo,i1.contenido,i1.mostrar
                    from ism_respuesta_contenido_pai_interdiciplinar i1,
                    ism_respuesta_opciones_pai_interdiciplinar i2,
                    contenido_pai_opciones i3
                    where i1.id_contenido_pai = i3.id 
                    and i1.id_respuesta_opciones_pai = i2.id 
                    and i1.mostrar = true
                    and i2.mostrar = true 
                    and i1.id_respuesta_opciones_pai = '$idRespOpciones';";

        $arraylRespContenido = $con->createCommand($query)->queryAll();
        

        $html = "";
        $html .= '<table >';
        foreach ($arraylRespContenido as $array) {
            $html .= '<tr>';
               
                $html .= '<td style="text-align:left;">';                 
                 $html .= '<a href="#" onclick="quitar_atributo_perfil('.$array['id'].')"><i style="color:red;" class="fas fa-trash"></i></a>';
                 $html .= ' --> '.$array['contenido'];
                $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        
        return $html;

    }
    //3
    private function mostrar_atributos_perfil_no_seleccionados($idRespOpciones)
    {
        $con = Yii::$app->db;
        $query = "select id,tipo,contenido_es ,contenido_en ,contenido_fr 
                    from contenido_pai_opciones cpo 
                    where id not in (
                    select i1.id_contenido_pai
                    from ism_respuesta_contenido_pai_interdiciplinar i1
                    where i1.id_respuesta_opciones_pai = $idRespOpciones
                    )
                    and tipo ='atributos_perfil';";

        $arraylRespContenido = $con->createCommand($query)->queryAll();
       
        $html = "";
        $html .= '<table>';
        foreach ($arraylRespContenido as $array) {
            $html .= '<tr>';
               
                $html .= '<td>';                 
                 $html .= '<a href="#" onclick="agregar_atributo_perfil('.$idRespOpciones.','.$array['id'].')">'.$array['contenido_es'].'</a>';
                $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        
        return $html;

    }
    //4.0-
    private function objetivos_desarrollo_sostenible_todos($idGrupoInter)
    {
        $html = "";
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 90%; margin-top:20px">';

        $html .= '<div class="card-header" style="background-color:#800834;">';
            $html .= '<h5 class="" style="color: #ffffff;"><b>4.1.- OBJETIVOS DE DESARROLLO SOSTENIBLE </b></h5>';
        $html .= '</div>';

        $html .= '<div class="card-body">';
        $html .= '<div class="ocultar">';
        $html .= $this->modal_competencias($idGrupoInter);
        $html .= '</div>';
        $html .= '<div class="table table-responsive">';

        $html .= '<table class="table table-hover table-condensed table-bordered">';
        $html .= '<div class="table table-responsive">';
        $html .= $this->objetivos_desarrollo_sostenible_competencia2($idGrupoInter);
        // $html .= '<table class="table table-condensed table-bordered">';
        // $html .= '<thead>';
        //     $html .= '<tr style="background-color:#CCC">';
        //             $html .= '<th class="text-center" style="background-color: #0a1f8f; color: white">COMPETENCIA</th>';
        //             $html .= '<th class="text-center" style="background-color: #9e28b5; color: white">ACTIVIDAD</th>';
        //             $html .= '<th class="text-center" style="background-color: #ab0a3d; color: white">OBJETIVO</th>';
        //             $html .= '<th class="text-center" style="background-color: #ab0a3d; color: white">RELACION ODS-IB</th>';
        //     $html .= '</tr>';
        // $html .= '</thead>';
        // $html .= '<tbody id="table-competencias-selecionadas">';
        //     $html .= '<tr>';
        //         $html .= '<td>'.$this->objetivos_desarrollo_sostenible_competencia2($idGrupoInter).'</td>';
        //     $html .= '</tr>';
        // $html .= '</tbody>';
        // $html .= '</table>';
        $html .= '</div>';

        $html .= '</table>';
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';


        return $html;
    }
    //4.0
    private function modal_competencias($idGrupoInter)
    {
        $pestana = '4.0.-';
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModal" onclick="show_reflexion_disponibles()"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ab0a3d; font-size:13px;"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                              Seleccionar Competencia
                              </span>';
        $html .= '</a>';

        $html .= '<div class="modal fade" id="reflexionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-x">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">SELECCIONAR COMPETENCIAS</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="ver_detalle(\'' . $pestana . '\')"></button>                      
                    </div>'; //FIN DE MODAL -HEADER

        $html .= '<div class="modal-body">'; //Inicio de modal-body

        $html .= '<div class="table table-responsive">';
        $html .= '<table class="table table-condensed table-bordered">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class="text-center" style="background-color: #0a1f8f; color: white">COMPETENCIA</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody id="table-competencia-disponibless">';
        $html .= '<tr>';
        $html .= '<td>' . $this->mostrar_competencias_disponibles($idGrupoInter, 'COMPETENCIA') . '</td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';


        $html .= '</div>'; // fin de modal-body

        $html .= '<div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="ver_detalle(\'' . $pestana . '\');">Cerrar</button>                      
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }
    //4.0
    private function mostrar_competencias_disponibles($idGrupoInter, $tipo_pregunta)
    { 
        
        $con = Yii::$app->db;
        $modelContenido = IsmContenidoPlanInterdiciplinar::find()
            ->where(['nombre_campo'=>'COMPETENCIA'])
            ->andWhere(['id_seccion_interdiciplinar'=>4])
            ->andWhere(['activo'=>true])
            ->one();
        
        $modelRespuesta = IsmRespuestaPlanInterdiciplinar::find()
            ->where(['id_contenido_plan_inter'=>$modelContenido->id])
            ->andWhere(['id_grupo_plan_inter'=>$idGrupoInter])
            ->one();
        
        $query = "select id,tipo,contenido_es,contenido_en,contenido_fr,estado from contenido_pai_opciones c
                    where id not in (
                        select id_contenido_pai from ism_respuesta_contenido_pai_interdiciplinar2 i
                        where id_respuesta_pai_interdisciplinar =$modelRespuesta->id and tipo ='competencia_pai_inter'
                    ) and tipo = 'competencia_pai_inter' ;";    
                

                    // ECHO '<pre>';
                    // print_r($query);
                    // die();
        
        $arraylPlanOpciones = $con->createCommand($query)->queryAll();              
        

        $html = "";
        $html .= '<table>';
        foreach ($arraylPlanOpciones as $array) {
            $html .= '<tr>
                <td style="font-size:15px"><a href="#" onclick="guardar_competencias(' . $array['id'] . ',\'' . strtoupper($tipo_pregunta) . '\');">' . $array['contenido_es'] . '</a>
                </td>
            </tr>';

        }
        $html .= '</table>';
        return $html;
    }
    //4.0
    private function guardar_competencias($idPregunta, $tipo_pregunta,$idIsmGrupoInter)
    {
        $con = Yii::$app->db;
        //script para mostar id correspondiente a la ´respuesta en el plan interdiciplinar pai para reflexion

        $query = "select id  from ism_respuesta_plan_interdiciplinar irpi 
                    where id_contenido_plan_inter in 
                    (select id from ism_contenido_plan_interdiciplinar icpi where nombre_campo = upper('$tipo_pregunta') )
                    and id_grupo_plan_inter  = $idIsmGrupoInter;";                    

        $resp = $con->createCommand($query)->queryOne();        

        $modelContenido = ContenidoPaiOpciones::find()
        ->where(['id'=>$idPregunta])
        ->one();        

        $modelRespuestaContenido = new IsmRespuestaContenidoPaiInterdiciplinar2();

        $modelRespuestaContenido->id_respuesta_pai_interdisciplinar = $resp['id'];
        $modelRespuestaContenido->id_contenido_pai = $modelContenido->id;
        $modelRespuestaContenido->tipo = $modelContenido->tipo;
        $modelRespuestaContenido->contenido = $modelContenido->contenido_es;
        $modelRespuestaContenido->mostrar = true;
        $modelRespuestaContenido->save();

        // echo '<pre>';
        // print_r($modelRespuestaContenido);
        // die();

        
    }
    //4.0
    private function mostrar_competencias($idGrupoInter)
    {
        $html = "";
        $html .= '<tr>';
        $html .= '<td>' . $this->mostrar_competencias_disponibles($idGrupoInter, 'COMPETENCIA') . '</td>';
        $html .= '</tr>';
        return $html;
    }
    //4.1.- NUEVO
    private function objetivos_desarrollo_sostenible_competencia2($idIsmGrupoInter)
    {
        $con = Yii::$app->db;
        //script para mostar id correspondiente a la ´respuesta en el plan interdiciplinar pai para reflexion

        $query = "select id  from ism_respuesta_plan_interdiciplinar irpi 
                    where id_contenido_plan_inter in 
                    (select id from ism_contenido_plan_interdiciplinar icpi where nombre_campo = upper('COMPETENCIA') )
                    and id_grupo_plan_inter  = $idIsmGrupoInter;";                    

        $resp = $con->createCommand($query)->queryOne();       
        $html ="";    

        $modelCompetenciasSelect = IsmRespuestaContenidoPaiInterdiciplinar2::find()
            ->where(['id_respuesta_pai_interdisciplinar'=>$resp['id']])
            ->andWhere(['tipo'=>'competencia_pai_inter'])
            ->all();

        $html .='<table class="table table-condensed table-bordered">
                     <tr>';
                     $html .= '<th class="text-center" style="background-color: #0a1f8f; color: white">COMPETENCIA</th>';
                     $html .= '<th class="text-center" style="background-color: #9e28b5; color: white">ACTIVIDAD</th>';
                     $html .= '<th class="text-center" style="background-color: #ab0a3d; color: white">OBJETIVO</th>';
                     $html .= '<th class="text-center" style="background-color: #ab0a3d; color: white">RELACION ODS-IB</th>';
                     $html .= '</tr>';
                    foreach( $modelCompetenciasSelect as $model)
                    {
                        $html .= '<tr><td style="font-size:14px"><a href="#" onclick="eliminar_competencias(' . $model->id . ');"><i style="color:red;" class="fas fa-trash"></i></a>
                        <span style="color:blue;">' . $model->contenido . '</span></td>';
                            $html .='<td>'.$model->actividad.'</td>'; 
                            $html .='<td>'.$model->objetivo.'</td>'; 
                            $html .='<td>'.$model->relacion_ods.'</td>'; 
                        $html .='</tr>';                     
                    }
        $html .= '</table>';
        return $html;
    }    
    //4.1-
    private function objetivos_desarrollo_sostenible_competencia($idGrupoInter)
    {
        $titulo = '4.1.- Competencia: <font size=3px>Se seleccionará la competencia del listado de competencias de ODS</font>';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '4.1.-';
        $campo = 'COMPETENCIA';
        $seccion = 4;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    //4.2.-
    private function objetivos_desarrollo_sostenible_actividad($idGrupoInter)
    {
        $titulo = '4.2.- Actividad: <font size=3px>Detallar la actividad que contribuye al desarrollo de la competencia</font>';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '4.2.-';
        $campo = 'ACTIVIDAD';
        $seccion = 4;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    //4.3
    private function objetivos_desarrollo_sostenible_objetivo($idGrupoInter)
    {
        $titulo = '4.3.- Objetivo: <font size=3px>(Se incluirá la imagen del icono del ODS que se desarrolle)</font>';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '4.3.-';
        $campo = 'OBJETIVO';
        $seccion = 4;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    //4.4
    private function objetivos_desarrollo_sostenible_relacion_ods($idGrupoInter)
    {
        $titulo = '4.4.- Relación ODS-IB: <font size=3px>(Se realizará una breve descripción entre la actividad ODS con la evaluación sumativa)</font>';
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
    //6.1.-
    private function accion_ensenianza_aprendizaje($idGrupoInter)
    {
        // $modelIsmGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
        // ->where(['id_grupo_plan_inter'=>$idGrupoInter])
        // ->all();

        $html='';
        $html .= '<div class="card-header" style="background-color:#800834;">';                    
                $html .= '<h5 class="text-center" style="color: #ffffff;"><b>BASE DISCIPLINARIA</b></h5>';                        
        $html .= '</div>';
        $html .='<table>';

        //foreach($modelIsmGrupoMaterias as $modelIsmGM)
        //{            
            $html .='<tr>'; 
                $html.='<table>';
                    $html.='<tr>';
                        $html.='<td style ="border: 1px solid grey; text-align:center"  colspan="2">
                                    <font size="4px">ASIGNATURAS</font>
                                </td>';
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td style ="border: 1px solid grey">Objetivo específico del PAI</td>';
                        $html.='<td style ="border: 1px solid grey">'.$this->obtener_objetivo_especifico_pai($idGrupoInter).'</td>';
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td style ="border: 1px solid grey">Conceptos relacionados</td>';
                        $html.='<td style ="border: 1px solid grey">'.$this->obtener_concepto_relacionado_pai($idGrupoInter).'</td>';
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td style ="border: 1px solid grey">Contenidos</td>';
                        $html.='<td style ="border: 1px solid grey">'.$this->obtener_contenido_pai($idGrupoInter).'</td>';
                    $html.='</tr>';
                    $html.='<tr>';
                        $html.='<td style ="border: 1px solid grey">Actividades de Aprendizaje y estrategias de enseñanza disciplinarias</td>';
                        $html.='<td style ="border: 1px solid grey">'.$this->obtener_actividad_aprendizaje_pai($idGrupoInter).'</td>';
                    $html.='</tr>';
                $html.='</table>';       
            $html .='</tr>'; 
        //}
        $html .='</table>';

        return $html;
        
    }
    //6
    private function obtener_objetivo_especifico_pai($idGrupoInter)
    {
        $arrayInterno = array();
        $arrayHabilidades = array();
        $html='';

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
        foreach ($modelGrupoMaterias as $modelGrupo) 
        {
            $modelPlanDesagCabecera = PlanificacionDesagregacionCabecera::find()
                ->where(['ism_area_materia_id' => $modelGrupo->id_ism_area_materia])
                ->one();

            if ($modelPlanDesagCabecera) 
            {
                $modelPlanBloqueUndiad = PlanificacionBloquesUnidad::find()
                    ->where(['plan_cabecera_id' => $modelPlanDesagCabecera->id])
                    ->andWhere(['curriculo_bloque_id' => $modelCurriculoMec->id])
                    ->one();

                $modelPlanVertPaiDesc = PlanificacionVerticalPaiDescriptores::find()
                    ->where(['plan_unidad_id'=>$modelPlanBloqueUndiad->id])
                    ->all();              

                foreach ($modelPlanVertPaiDesc as $modelDescri) 
                {
                    //buscamos en ism_criterio_descriptores_area
                    $modelCriterioDesc = IsmCriterioDescriptorArea::findOne($modelDescri->descriptor_id);
                    $area = $modelCriterioDesc->area->nombre;
                    $curso =$modelCriterioDesc->curso->name;
                    $criterio =$modelCriterioDesc->criterio->nombre;
                    $criterio_literal =$modelCriterioDesc->literalCriterio->nombre_espanol;
                    $descriptor =$modelCriterioDesc->descriptor->nombre;
                    $descriptor_literal =$modelCriterioDesc->literalDescriptor->descripcion;

                    $html.='<table  >';
                        $html.='<tr  >
                                    <td style ="border: 1px solid grey;padding: 15px;">'.$area.'</td>
                                    <td style ="border: 1px solid grey;padding: 15px;">'.$criterio.'</td>   
                                    <td style ="border: 1px solid grey;padding: 15px;">'.$descriptor_literal.'</td>
                                </tr>';
                    $html.='</table>';
                }
            }
        }
        return $html;
    }
    //6
    private function obtener_concepto_relacionado_pai($idGrupoInter)
    {
        $arrayInterno = array();
        $arrayHabilidades = array();
        $html='';

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
        foreach ($modelGrupoMaterias as $modelGrupo) 
        {
            $modelPlanDesagCabecera = PlanificacionDesagregacionCabecera::find()
                ->where(['ism_area_materia_id' => $modelGrupo->id_ism_area_materia])
                ->one();

            if ($modelPlanDesagCabecera) 
            {
                $modelPlanBloqueUndiad = PlanificacionBloquesUnidad::find()
                    ->where(['plan_cabecera_id' => $modelPlanDesagCabecera->id])
                    ->andWhere(['curriculo_bloque_id' => $modelCurriculoMec->id])
                    ->one();

                $modelPlanVertPaiOp = PlanificacionVerticalPaiOpciones::find()
                    ->where(['plan_unidad_id'=>$modelPlanBloqueUndiad->id])
                    ->andWhere(['tipo'=>'concepto_relacionado'])
                    ->all();              

                foreach ($modelPlanVertPaiOp as $modelOpciones) 
                {
                    $materia = $modelOpciones->planUnidad->planCabecera->ismAreaMateria->materia->nombre;
                    $id = $modelOpciones->id;
                    $tipo = $modelOpciones->tipo;
                    $contenido = $modelOpciones->contenido;

                    $html.='<table >';
                        $html.='<tr>
                                    <td style ="border: 1px solid grey;padding: 15px;">'.$materia.'</td>                                     
                                    <td style ="border: 1px solid grey;padding: 15px;">'.$contenido.'</td>   
                                </tr>';
                    $html.='</table>';
                }
            }
        }
        return $html;
    }
    //6
    private function obtener_contenido_pai($idGrupoInter)
    {
        $arrayInterno = array();
        $arrayHabilidades = array();
        $html='';

        $modelGrupoInte = IsmGrupoPlanInterdiciplinar::findOne($idGrupoInter);
        $abreviaturaBloque = $modelGrupoInte->bloque->abreviatura;

        $modelCurriculoMec = CurriculoMecBloque::find()
            ->where(['shot_name' => $abreviaturaBloque])
            ->one();

        //1.-buscamos los id_are_materias
        $modelGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
            ->where(['id_grupo_plan_inter' => $idGrupoInter])
            ->all();

        $temario = array();

        //2.- buscamos plan desag cab , con el ism_area_materia
        foreach ($modelGrupoMaterias as $modelGrupo) 
        {
            $modelPlanDesagCabecera = PlanificacionDesagregacionCabecera::find()
                ->where(['ism_area_materia_id' => $modelGrupo->id_ism_area_materia])
                ->one();

            if ($modelPlanDesagCabecera) 
            {
                $modelPlanBloqueUndiad = PlanificacionBloquesUnidad::find()
                    ->where(['plan_cabecera_id' => $modelPlanDesagCabecera->id])
                    ->andWhere(['curriculo_bloque_id' => $modelCurriculoMec->id])
                    ->one();
                    
                    $objScripts = new Scripts();
                    $subtitulos = $objScripts->selecciona_subtitulos($modelPlanBloqueUndiad->id);
            
                    foreach($subtitulos as $subtitulo)
                    {                        
                        $subtitulo2 = PlanificacionBloquesUnidadSubtitulo2::find()->where([
                            'subtitulo_id' => $subtitulo['id']
                        ])->orderBy('orden')->all();
            
                        $subtitulo['subtitulos'] = $subtitulo2;
                    
                        array_push($temario, $subtitulo);
                    }
            }
        }


        $html.='<table>';
        foreach ($temario as $temario) 
        {                                                   
            $html.='<tr>';
                $html.='<td style ="border: 1px solid grey;padding: 15px;"> '.$temario['subtitulo'].'</td>
                <td style ="border: 1px solid grey;padding: 15px;">';                
                    foreach ($temario['subtitulos'] as $subtitulos) 
                    {
                    $html.='<li>'.
                            $subtitulos['contenido']
                        .'</li>';                                   
                    }                             
                $html.='</td>';
            $html.='</tr>';        
        }
        $html.='</table>';
       
        return $html;
    }
    //6.-
    private function obtener_actividad_aprendizaje_pai($idGrupoInter)
    {
        $titulo = 'Actividades de Aprendizaje y estrategias de enseñanza disciplinarias';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '6.1.-';
        $campo = 'ACCIÓN';
        $seccion = 6;

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
        $html = "";
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
        $html .= '<div class="card" style="width: 90%; margin-top:20px">';

        $html .= '<div class="card-header" style="background-color:#800834;">';
        $html .= '<h5 class="" style="color: #ffffff;"><b>9.1.- REFLEXIÓN: </b></h5>';
        $html .= '<small style="color: #ffffff; font-size:12px">(Consideración de la planificación, el proceso y el impacto de la indagación. En el proceso de reflexión, garantizar dar respuesta a varias de la preguntas planteadas en cada momento.)</small>';
        $html .= '</div>';

        $html .= '<div class="card-body">';
        $html .= '<div class="ocultar">';
        $html .= $this->modal_reflexion($idGrupoInter);
        $html .= '</div>';
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
        $html .= '<tbody id="table-reflexion-selecionadas">';
        $html .= '<tr>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_seleccionadas($idGrupoInter, 'antes') . '</td>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_seleccionadas($idGrupoInter, 'mientras') . '</td>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_seleccionadas($idGrupoInter, 'despues') . '</td>';
        $html .= '</tr>';
        $html .= '</tbody>';
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
    private function modal_reflexion($idGrupoInter)
    {
        $pestana = '9.1.-';
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModal" onclick="show_reflexion_disponibles()"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ab0a3d; font-size:13px;"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                              Seleccionar Preguntas
                              </span>';
        $html .= '</a>';

        $html .= '<div class="modal fade" id="reflexionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">SELECCIONAR PREGUNTAS</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="ver_detalle(\'' . $pestana . '\')"></button>                      
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
        $html .= '<tr>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_disponibles($idGrupoInter, 'antes') . '</td>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_disponibles($idGrupoInter, 'mientras') . '</td>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_disponibles($idGrupoInter, 'despues') . '</td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';


        $html .= '</div>'; // fin de modal-body

        $html .= '<div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="ver_detalle(\'' . $pestana . '\');">Cerrar</button>                      
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }
    //9
    private function mostrar_preguntas_reflexion_disponibles($idGrupoInter, $tipo_pregunta)
    {
        $con = Yii::$app->db;
        $query = "select id,tipo ,categoria ,opcion  from planificacion_opciones po 
                where id not in (
                        select i1.id_planificacion_opciones  from ism_respuesta_reflexion_pai_interdiciplinar i1, 
                        ism_respuesta_plan_interdiciplinar i2
                        where i1.id_respuesta_plan_inter_pai = i2.id and i2.id_grupo_plan_inter =$idGrupoInter
                ) and tipo='REFLEXION' and categoria='$tipo_pregunta' and estado = true order by id ;";

        $arraylPlanOpciones = $con->createCommand($query)->queryAll();

        $html = "";
        $html .= '<table>';
        foreach ($arraylPlanOpciones as $array) {
            $html .= '<tr><td style="font-size:15px"><a href="#" onclick="guardar_pregunta_reflexion(' . $array['id'] . ',\'' . strtoupper($tipo_pregunta) . '\');">' . $array['opcion'] . '</a></td></tr>';
        }
        $html .= '</table>';
        return $html;
    }
    //9
    private function mostrar_preguntas_reflexion_seleccionadas($idIsmGrupoInter, $tipo_pregunta)
    {
        $con = Yii::$app->db;

        //script para mostar id correspondiente a la ´respuesta en el plan interdiciplinar pai para reflexion
        $query = "select i1.id,i1.id_respuesta_plan_inter_pai ,i1.id_planificacion_opciones ,
                    i1.respuesta ,i2.tipo ,i2.categoria,i2.opcion  
                    from ism_respuesta_reflexion_pai_interdiciplinar i1,
                    planificacion_opciones i2,ism_respuesta_plan_interdiciplinar i3
                    where i1.id_planificacion_opciones  = i2.id 
                    and i1.id_respuesta_plan_inter_pai  = i3.id 
                    and i3.id_grupo_plan_inter  = $idIsmGrupoInter
                    and i2.seccion ='PAI' and i2.categoria ='$tipo_pregunta';";

        $resp = $con->createCommand($query)->queryAll();

        $html = "";
        $html .= '<table>';
        foreach ($resp as $r) {

            $html .= '<tr><td><a href="#" onclick="eliminar_pregunta_reflexion(' . $r['id'] . ');"><i style="color:red;" class="fas fa-trash"></i></a>
            <span style="color:blue;">' . $r['opcion'] . '</span></td></tr>';
            $html .= '<tr><td>
                    <textarea id="respuesta_' . $r['id'] . '" class="form-control" style="max-width: 100%;" 
                    onchange="actualizar_pregunta(' . $r['id'] . ')">' . $r['respuesta'] . '</textarea>
                </td></tr>';
        }
        $html .= '</table>';

        return $html;
    }
    //9
    private function guardar_pregunta_reflexion($idPregunta, $tipo_pregunta, $idIsmGrupoInter)
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

    private function mostrar_preguntas_seleccionadas($idGrupoInter)
    {
        $html = "";
        $html .= '<tr>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_seleccionadas($idGrupoInter, 'antes') . '</td>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_seleccionadas($idGrupoInter, 'mientras') . '</td>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_seleccionadas($idGrupoInter, 'despues') . '</td>';
        $html .= '</tr>';

        return $html;
    }
    private function mostrar_preguntas_disponibles($idGrupoInter)
    {
        $html = "";
        $html .= '<tr>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_disponibles($idGrupoInter, 'antes') . '</td>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_disponibles($idGrupoInter, 'mientras') . '</td>';
        $html .= '<td>' . $this->mostrar_preguntas_reflexion_disponibles($idGrupoInter, 'despues') . '</td>';
        $html .= '</tr>';

        return $html;
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
