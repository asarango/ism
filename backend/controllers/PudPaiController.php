<?php
namespace backend\controllers;

//use backend\models\pca\DatosInformativos as PcaDatosInformativos;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PudPai;
use backend\models\pudpai\Accion;
use backend\models\pudpai\Aspecto;
use backend\models\pudpai\Datos;
use backend\models\pudpai\Ensenara;
use backend\models\pudpai\Evaluacion;
use backend\models\pudpai\GrupoHabilidades;
use backend\models\pudpai\Habilidades;
use backend\models\pudpai\Indagacion;
use backend\models\pudpai\Indicadores;
use backend\models\pudpai\Necesidades;
use backend\models\pudpai\Pdf;
use backend\models\pudpai\PerfilesBi;
use backend\models\pudpai\Preguntas;
use backend\models\pudpai\Recursos;
use backend\models\pudpai\Reflexion;
use backend\models\pudpai\ObjetivosDesarrollo;
use backend\models\pudpai\ServicioAccion;
use backend\models\ContenidoPaiOpciones;
use backend\models\IsmContenidoPaiPlanificacion;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class PudPaiController extends Controller{

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

    public function actionIndex1()
    {
        $planBloqueUnidadId = $_GET['plan_bloque_unidad_id'];

        $planUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);

        return $this->render('index', [
            'planUnidad' => $planUnidad
        ]);
    }  


    public function actionPestana(){
        $planUnidadId   = $_GET['plan_unidad_id'];
        $pestana        = $_GET['pestana'];
        
        if($pestana == '1.1.-'){
            $datos = new Datos($planUnidadId);
            return $datos->html;
        }elseif($pestana == '2.1.-'){
            $indagacion = new Indagacion($planUnidadId);
            return $indagacion->html;
        }elseif($pestana == '2.3.-'){
            $preguntas = new Preguntas($planUnidadId);
            return $preguntas->html;
        }elseif($pestana == '5.1.-'){
            $evaluacion = new Evaluacion($planUnidadId);
            $this->ingresa_evaluacion($planUnidadId);
            return $evaluacion->html;
        }
       elseif($pestana == '3.1.-'){
           $habilidades = new Habilidades($planUnidadId);
           return $habilidades->html;
       }
        // elseif($pestana == '3.1.-'){
        //     $grupos = new GrupoHabilidades($planUnidadId);
        //     return $grupos->html;
        // }elseif($pestana == '3.2.-'){
        //     $aspectos = new Aspecto($planUnidadId);
        //     return $aspectos->html;
        // }elseif($pestana == '3.3.-'){
        //     $indicadores = new Indicadores($planUnidadId);
        //     return $indicadores->html;
        // }elseif($pestana == '3.4.-'){
        //     $ensenara = new Ensenara($planUnidadId);
        //     return $ensenara->html;
        // }elseif($pestana == '3.5.-'){
        //     $perfiles = new PerfilesBi($planUnidadId);
        //     return $perfiles->html;
        // }
        elseif($pestana == '6.1.-'){
            $accion = new Accion($planUnidadId);
            return $accion->html; 
        }elseif($pestana == '7.1.-'){
            $servicio = new ServicioAccion($planUnidadId);
            return $servicio->html; 
        }elseif($pestana == '8.1.-'){
            $accion = new Necesidades($planUnidadId);
            return $accion->html; 
        }elseif($pestana == '9.1.-'){
            $recursos = new Recursos($planUnidadId);
            return $recursos->html; 
        }elseif($pestana == '10.1.-'){
            $reflexion = new Reflexion($planUnidadId);
            return $reflexion->html;
        }elseif($pestana == '4.1.-'){
            $reflexion = new ObjetivosDesarrollo($planUnidadId);
            return $reflexion->html;
        }
    }

    private function ingresa_evaluacion($planUnidadId)
    {
       //creamos los registros para texto sumativa y formativa
       $this->insert_registros_evaluacion($planUnidadId,'eval_formativa');
       $this->insert_registros_evaluacion($planUnidadId,'eval_sumativa');
       $this->insert_registros_evaluacion($planUnidadId,'relacion-suma-eval');  
    }

    private function insert_registros_evaluacion($planUnidadId,$tipo)
    {

        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy   = date('Y-m-d H:i:s');

        $pudPai = PudPai::find()->where([
            'tipo' => $tipo,
            'planificacion_bloque_unidad_id' => $planUnidadId
        ])->one();

        if(!$pudPai){
            $model = new PudPai();
            $model->planificacion_bloque_unidad_id = $planUnidadId;
            $model->seccion_numero = 4;
            $model->tipo = $tipo;
            $model->contenido = 'sin contenido';
            $model->created = $usuarioLog;
            $model->created_at = $fechaHoy;
            $model->updated = $usuarioLog;
            $model->updated_at = $fechaHoy;
            $model->save();
       }
    }

    public function actionGeneraPdf(){
        $planUnidadId = $_GET['planificacion_unidad_bloque_id'];
        $pdf = new Pdf($planUnidadId);
        
    }
    //10.1
    public function actionGuardarCompetencias()
    {
        $planUnidadId = $_POST['planUnidadId'];
        $id_pregunta = $_POST['id_pregunta'];             
     
        $this->guardar_competencias($id_pregunta, $planUnidadId);
        $html = $this->mostrar_competencias_disponibles($planUnidadId);

        return $html;
    }
    private function guardar_competencias($idPregunta, $planUnidadId)
    {
       $modelContenido = ContenidoPaiOpciones::find()
        ->where(['id'=>$idPregunta])
        ->one(); 

        $modelIsmContenidoPaiPlan = new IsmContenidoPaiPlanificacion();

        $modelIsmContenidoPaiPlan->planificacion_bloque_unidad_id = $planUnidadId;
        $modelIsmContenidoPaiPlan->id_contenido_pai = $modelContenido->id;
        $modelIsmContenidoPaiPlan->tipo = $modelContenido->tipo;
        $modelIsmContenidoPaiPlan->contenido = $modelContenido->contenido_es;
        $modelIsmContenidoPaiPlan->mostrar = true;
        $modelIsmContenidoPaiPlan->save();  
     
    }
    private function mostrar_competencias_disponibles($planUnidadId)
    {         
        $con = Yii::$app->db;     
        $query = "select id,tipo,contenido_es,contenido_en,contenido_fr,estado from contenido_pai_opciones c
                    where id not in (
                        select id_contenido_pai from ism_contenido_pai_planificacion i
                        where planificacion_bloque_unidad_id =$planUnidadId and tipo ='competencia_pai_inter'
                    ) and tipo = 'competencia_pai_inter' ;";  
        
        $arraylPlanOpciones = $con->createCommand($query)->queryAll();              
        

        $html = "";
        $html .= '<table>';
        foreach ($arraylPlanOpciones as $array) {
            $html .= '<tr>
                <td style="font-size:15px"><a href="#" onclick="guardar_competencias(' . $array['id'] . ',\'' . strtoupper('') . '\');">' . $array['contenido_es'] . '</a>
                </td>
            </tr>';

        }
        $html .= '</table>';
        return $html;
    }
       //4
       public function actionEliminarCompetencias()
       {
           $id_pregunta = $_POST['id_pregunta'];  
           $planUnidadId = $_POST['planUnidadId'];    
         
           $model = IsmContenidoPaiPlanificacion::findOne($id_pregunta);
           $model->delete();
  
           $html = $this->mostrar_competencias_disponibles($planUnidadId, 'COMPETENCIA');
  
           return $html;
       }
       //4
       public function actionActualizarCompetencia()
      {
           $id_competencia = $_POST['id_competencia'];
          $competencia_actividad = $_POST['competencia_actividad'];
          $competencia_objetivo = $_POST['competencia_objetivo'];
          $competencia_relacion_ods = $_POST['competencia_relacion_ods'];       
  
  
          $model = IsmContenidoPaiPlanificacion::findOne($id_competencia);
          $model->actividad = $competencia_actividad;
          $model->objetivo = $competencia_objetivo;
          $model->relacion_ods = $competencia_relacion_ods;
          $model->save();
      }
    

}

?>