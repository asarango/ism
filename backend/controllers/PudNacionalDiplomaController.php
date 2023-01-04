<?php

namespace backend\controllers;

use backend\models\pudnacional\PdfNacionalPud;
use backend\models\PcaDetalle;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\ScholarisClase;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class PudNacionalDiplomaController extends Controller{
    
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

    public function actionIndex1(){
        $planUnidadId = $_GET['plan_bloque_unidad_id'];
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::findOne($periodoId);

        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $pcaId = $planUnidad->plan_cabecera_id;

        $objetivos = PcaDetalle::find()->where([
            'desagregacion_cabecera_id' => $pcaId,
            'tipo' => 'objetivos_generales'
        ])->all();

        $criterios = PlanificacionDesagregacionCriteriosEvaluacion::find()->where(['bloque_unidad_id' => $planUnidadId])->all();

        $indicators = $this->getIndicators($planUnidadId);


        $clase = ScholarisClase::find()->where([
            'ism_area_materia_id' => $planUnidad->planCabecera->ismAreaMateria->id
        ])->one();

        $totalSemanas = $this->count_weeks($clase->tipo_usu_bloque, $planUnidad->curriculo_bloque_id, $periodo->codigo);

        return $this->render('index',[
            'planUnidad'    => $planUnidad,
            'totalSemanas'  => $totalSemanas,
            'objetivos'     => $objetivos,
            'criterios'     => $criterios,
            'indicators'    => $indicators
        ]);
    } 

    private function getIndicators($bloqueUnidadId){
        $con = Yii::$app->db;
        $query = "select 	ind.code 
                            ,ind.description 
                    from 	planificacion_desagregacion_criterios_evaluacion eva
                            inner join curriculo_mec mec on mec.id = eva.criterio_evaluacion_id 
                            inner join curriculo_mec ind on ind.belongs_to = mec.code  
                    where 	eva.bloque_unidad_id = $bloqueUnidadId
                            and ind.reference_type = 'indicador';";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function count_weeks($uso, $orden, $periodoCodigo){
        $con = Yii::$app->db;
        $query = "select 	count(sem.id) as total_semanas 
        from 	scholaris_bloque_actividad blo
                inner join scholaris_bloque_semanas sem on sem.bloque_id = blo.id
        where	blo.tipo_uso = '$uso'
                and blo.orden = $orden
                and blo.scholaris_periodo_codigo = '$periodoCodigo';";
        $res = $con->createCommand($query)->queryOne();
        return $res['total_semanas'];
    }


    public function actionAddActivities(){
        $id = $_POST['id'];
        $actividades = $_POST['actividades'];

        $model = PlanificacionBloquesUnidad::findOne($id);
        $model->actividades_aprendizaje = $actividades;
        $model->save();

        return $this->redirect(['index1', 'plan_bloque_unidad_id' => $id]);
    }


    public function actionGeneraPdf(){        
        $planUnidadId = $_GET['planificacion_unidad_bloque_id'];

        new PdfNacionalPud($planUnidadId);
    }

 
}