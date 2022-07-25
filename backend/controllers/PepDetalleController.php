<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * PlanificacionDesagregacionCabeceraController implements the CRUD actions for PlanificacionDesagregacionCabecera model.
 */
class PepDetalleController extends Controller {

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

    public function actionIndex1() {
        $temaId = $_GET['tema_id'];
        $tema = \backend\models\PepPlanificacionXUnidad::findOne($temaId);
        
        $registros = \backend\models\PepUnidadDetalle::find()->where(['pep_planificacion_unidad_id' => $temaId])->orderBy('id')->all();
        
        $this->ingresa_todas_opciones($registros, $temaId);
        
        return $this->render('index', [        
           'tema' => $tema,
           'registros' => $registros
        ]);
    }
    
    private function ingresa_todas_opciones($modelRegistros, $temaId){
        
        /* verifica si existe la idea principal */
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'idea_central', 'info_general','texto');        
        
        /* verifica si existe Líneas de indagación */
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'linea_indagacion', 'info_general','texto');
        
        /* verifica si existe Conceptos clave */
        $this->ingresa_opciones_generico_seleccion($temaId, 'concepto_clave', 'conceptos_atributos', 'seleccion');
        
        /* verifica si existe Conceptos relacionados */
        $this->ingresa_opciones_generico_seleccion($temaId, 'concepto_relacionado', 'conceptos_atributos', 'seleccion');
        
        /* verifica si existe atributos del perfil de la comunidad de aprendizaje */
        $this->ingresa_opciones_generico_seleccion($temaId, 'atributos_perfil', 'conceptos_atributos', 'seleccion');  
        
        /* verifica si existe enfoques de aprendizaje */
        $this->ingresa_opciones_generico_seleccion($temaId, 'enfoques_aprendizaje', 'enfoques_aprendizaje', 'seleccion');   
        
        /* verifica si existe ACCION */
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'accion', 'info_general','texto');
        
        
        /******** REFLEXIONESZ ************************/
        /* verifica si existe REFLEXIONES INICIALES*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'reflexiones_iniciales', 'reflexion_planificacion','texto');
        
        /* verifica si existe REFLEXIONES INICIALES*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'conocimientos_previos', 'reflexion_planificacion','texto');
        
        /* verifica si existe TRANSDISCIPLINARIAS CON EL PASADO*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'transdisciplinarias_pasado', 'reflexion_planificacion','texto');
        
        /* verifica si existe Objetivos de aprendizaje*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'objetivos_aprendizaje', 'reflexion_planificacion','texto');
        
        /* verifica si existe Preguntas de los maestros*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'preguntas_maestros', 'reflexion_planificacion','texto');
        
        /* verifica si existe Preguntas de los alumnos*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'preguntas_alumnos', 'reflexion_planificacion','texto');
        
        
        /******** DISE;O E IMPLEMENTACION ************************/
        /* verifica si existe Diseñar experiencias de aprendizaje*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'experiencias_aprendizaje', 'diseño_implementacion','texto');
        
        /* verifica si existe Apoyo a la agencia de alumnos*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'agencia_alumnos', 'diseño_implementacion','texto');
        
        /* verifica si existe preguntas de los mestros y alumnos*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'preguntas_mestros_alumnos', 'diseño_implementacion','texto');
        
        /* verifica si existe evaluación continua*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'evaluacion_continua', 'diseño_implementacion','texto');
        
        /* verifica si existe uso flexible de recursos*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'recursos', 'diseño_implementacion','texto');
        
        /* verifica si existe autoevaluación*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'autoevaluacion', 'diseño_implementacion','texto');
        
        /* verifica si existe continua de todos los maestros*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'evaluacion_maestros', 'diseño_implementacion','texto');
        
        /* verifica si existe reflexiones adicionales específicas de una asignatura*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'adicionales', 'diseño_implementacion','texto');
        
        
        /******** REFLEXIÓN ************************/
        /* verifica si existe Reflexiones de los maestros*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'reflexion_maestros', 'reflexion','texto');
        
        /* verifica si existe Reflexiones de los alumnos*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'reflexion_alumnos', 'reflexion','texto');
        
        /* verifica si existe Reflexiones sobre la evaluación*/
        $this->ingresa_opciones_generico_texto($temaId, $modelRegistros, 'reflexion_evaluacion', 'reflexion','texto');
        
    }
    
    private function ingresa_opciones_generico_seleccion($temaId, $tipo, $referencia, $campoDe){
        
        $con = \Yii::$app->db;
        $query = "insert into  pep_unidad_detalle (pep_planificacion_unidad_id, tipo, referencia, campo_de, contenido_texto, contenido_opcion)
                    select $temaId, '$tipo', '$referencia', '$campoDe'
                                    ,op.contenido_es
                                    ,false
                    from 	pep_opciones op
                    where 	op.tipo = '$tipo'
                                    and op.contenido_es not in (select  contenido_texto  
                                                                from 	pep_unidad_detalle
                                                                where   pep_planificacion_unidad_id = $temaId
                                                                                and contenido_texto = op.contenido_es)
                    order by op.id;";
        
        $con->createCommand($query)->execute();
        
    }
    
    
    private function ingresa_opciones_generico_texto($temaId, $modelRegistros, $tipo, $referencia, $campoDe){        
                        
        $palabra = array_search($tipo, array_column($modelRegistros, 'tipo')); //busca si esta configurado

        if($palabra > 0 || $palabra === 0){
            //no se hace nada
        }else{
            $model = new \backend\models\PepUnidadDetalle();
            $model->pep_planificacion_unidad_id = $temaId;
            $model->tipo = $tipo;
            $model->referencia = $referencia;
            $model->campo_de = $campoDe;
            $model->contenido_texto = 'No conf';
            $model->save();
        }        
        
    }
    
    
    /**
     * ACTUALIZA EL REGISTRO DEL DETALLE DE LA PLANIFICACION PEP
     * @return type
     */
    public function actionUpdate(){
        $id = $_POST['registro_id'];
        $contenidoTexto = $_POST['contenido_texto'];

        $model = \backend\models\PepUnidadDetalle::findOne($id);
        $model->contenido_texto = $contenidoTexto;
        if($model->save()){
            $response = array(
                'status' => 'ok'
            );
        }else{
            $response = array(
                'status' => 'error'
            );
        }
        
        return json_encode($response);
    }
    
    
    public function actionUpdateSelection(){
        $id = $_POST['id'];
        
        $model = \backend\models\PepUnidadDetalle::findOne($id);
        $model->contenido_opcion == true ? $model->contenido_opcion = false : $model->contenido_opcion = true;
        
        if($model->save()){
            $response = array(
                'status' => 'ok'
            );
        }else{
            $response = array(
                'status' => 'error',
            );
            
        }
        
        return json_encode($response);
        
    }
}