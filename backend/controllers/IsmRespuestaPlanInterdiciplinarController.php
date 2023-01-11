<?php

namespace backend\controllers;

use backend\models\IsmContenidoPlanInterdiciplinar;
use Yii;
use backend\models\IsmRespuestaPlanInterdiciplinar;
use backend\models\IsmRespuestaPlanInterdiciplinarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\pudpai\Datos;
use phpDocumentor\Reflection\Types\This;

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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

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
                $html = $this->datos_informativos($planUnidadId);
                break;
            case '2.1.-':
                $html = $this->proposito_indagacion($idGrupoInter);
                break;
            case '2.2.-':
                $html = '';
                break;
            case '2.3.-':
                $html = $this->enunciado_indagacion($idGrupoInter);
                break;
            case '2.4.-':
                $html = $this->preguntas_indagacion($idGrupoInter);
                break;
            case '3.3.-':
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
    public function datos_informativos($planUnidadId)
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
        $html .= '<div class="col">' . $planUnidad->planCabecera->ismAreaMateria->materia->nombre . '</div>';
        $html .= '<div class="col"><b>PROFESOR(ES)</b></div>';
        //$docentes = $objDatos->get_docentes();
        //$html .= '<div class="col">';
        // foreach ($docentes as $docente) {
        //     $html .= $docente['docente'] . ' | ';
        // }
        // $html .= '</div>';
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

    //2.-
    private function proposito_indagacion($idGrupoInter)
    {
        $titulo = '2.1.- Propósito de Indagación';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '2.1.-';
        $campo = 'PROPÓSITO DE LA INTEGRACIÓN';
        $seccion = 2;


        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

        return $html;
    }
    private function enunciado_indagacion($idGrupoInter)
    {
        $titulo = '2.3.- Enunciado de la Indagación';
        $descripcion = "<p><font size=1px> : (expresa claramente una comprensión conceptual importante que tiene un profundo significado y un valor a 
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
    private function html_preguntas_indagacion($idGrupoInter)
    {
        $titulo = 'Fácticas: <font size=1px>(se basan en conocimientos y datos, ayudan a comprender terminología del enunciado, 
                    facilitan la comprensión, se pueden buscar)</font>';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '2.4.-';
        $campo = 'Fácticas';
        $seccion = 2;
        $htmlFacticas = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);
        $titulo = 'Conceptuales: <font size=1px>(conectar los datos, comparar y contrastar, explorar contradicciones, comprensión más profunda, 
                    transferir a otras situaciones, contextos e ideas, analizar y aplicar)</font>';
        $esEditable = true;
        $pestana = '2.4.-';
        $campo = 'Conceptuales';
        $seccion = 2;
        $htmlConceptuales = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);;
        $titulo = 'Debatibles: <font size=1px>(promover la discusión, debatir una posición, explorar cuestiones importantes desde múltiples perspectivas, 
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
    //3.-
    private function enfoque_actividad($idGrupoInter)
    {
        $titulo = '3.3.- Actividad';
        $idIsmGrupoInter = $idGrupoInter;
        $esEditable = true;
        $pestana = '3.3.-';
        $campo = 'ACTIVIDAD';
        $seccion = 3;

        $html = $this->generico_consulta_base_campo_texto($seccion, $campo, $esEditable, $titulo, $pestana, $idIsmGrupoInter);

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
    //5.-
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
        $html .= '<h6 class="text-center" style="color:#ffffff" ><b>' . $titulo . '</b></h6>';
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
