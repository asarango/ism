<?php

namespace backend\controllers;

use backend\models\estudiante\Estudiante;
use backend\models\estudiante\DetalleNotas;
use backend\models\OpStudentInscription;
use backend\models\ScholarisPeriodo;
use Yii;
use backend\models\ViewStudiantesCv;
use backend\models\ViewStudiantesCvSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ViewStudiantesCvController implements the CRUD actions for ViewStudiantesCv model.
 */
class ViewStudiantesCvController extends Controller
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
     * Lists all ViewStudiantesCv models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ViewStudiantesCvSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionDetalle()
    {
        $inscriptionId = $_GET['inscription_id'];
        $inscription = OpStudentInscription::findOne($inscriptionId);

        return $this->render('detalle', [
            'inscription' => $inscription
        ]);
    }

    public function actionAcciones()
    {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $inscriptionId  = $_GET['inscription_id'];
        $accion         = $_GET['accion'];
        $inscription = OpStudentInscription::findOne($inscriptionId);
        $periodo = ScholarisPeriodo::findOne($periodoId);



        if ($accion == 'promedios') {
            //    $this->redirecciona_promedios($inscription->student_id, $periodo->codigo);
            $estudiante = new Estudiante();
            $promedios = $estudiante->promedios($inscription->student_id, $periodo->codigo);
            return $this->renderPartial('_ajax-promedio-general', [
                'promedios' => $promedios
            ]);
        }elseif($accion == 'chart-clases'){
            $estudiante = new Estudiante();
            $chartClases = $estudiante->chart_general_clases($inscriptionId);

            return $this->renderPartial('_ajax-chart-clases', [
                'chartClases' => $chartClases,
                'inscriptionId' => $inscriptionId
            ]);
        }elseif($accion == 'detalle-clase'){
            $nombreClase = $_GET['nombre_clase'];

            $detalle = new DetalleNotas($nombreClase, $inscriptionId);

            return $this->renderPartial('_ajax-detalle-clase', [
                'detalle' => $detalle
            ]);
        }elseif($accion == 'comportamiento'){  // Para consultar a los comportamientos del estudiante
            $comportamiento = $this->consulta_comportamiento($inscriptionId);
            return $this->renderPartial('_ajax-comportamiento', [
                'comportamiento' => $comportamiento 
            ]);
        }elseif($accion == 'faltas'){  // Para consultar a los comportamientos del estudiante
            $faltas = $this->consulta_faltas($inscriptionId);
            return $this->renderPartial('_ajax-faltas', [
                'faltas' => $faltas
            ]);
        }elseif($accion == 'chart-dece'){

            $estudiante = new Estudiante();
            $chartDece = $estudiante->chart_dece($inscriptionId, $periodoId);           

            return $this->renderPartial('_ajax-chart-dece', [
                'chartDece' => $chartDece,
                'inscriptionId' => $inscriptionId
            ]);
        }elseif($accion == 'detalle-dece'){

            $estudiante = new Estudiante();
            $dece = $estudiante->detalle_dece($inscriptionId, $periodoId);

            return $this->renderPartial('_ajax-detalle-dece', [
                'dece' => $dece
            ]);
        }
    }


    // Retorna las novedade de compotamiento de la inscriptionId
    private function consulta_comportamiento($inscriptionId){
        $con = Yii::$app->db;
        $query = "select 	asi.fecha 
                            ,concat(fac.x_first_name, ' ', fac.last_name) as docente
                            ,com.nombre as comportamiento
                            ,det.codigo 
                            ,det.nombre as detalle_comportamiento
                            ,asi.hora_id 
                            ,mat.nombre as materia
                            ,nov.es_justificado 
                            ,nov.solicitud_representante_motivo 
                            ,nov.justificacion_fecha 
                    from	scholaris_asistencia_alumnos_novedades nov
                            inner join scholaris_grupo_alumno_clase gru on gru.id = nov.grupo_id 
                            inner join op_student_inscription ins on ins.student_id = gru.estudiante_id
                            inner join scholaris_asistencia_profesor asi on asi.id = nov.asistencia_profesor_id 
                            inner join scholaris_asistencia_comportamiento_detalle det on det.id = nov.comportamiento_detalle_id 
                            inner join scholaris_asistencia_comportamiento com on com.id = det.comportamiento_id 
                            inner join scholaris_clase cla on cla.id = asi.clase_id 
                            inner join op_faculty fac on fac.id = cla.idprofesor 
                            inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                            inner join ism_materia mat on mat.id = iam.materia_id 
                    where 	ins.id = $inscriptionId
                    order by asi.fecha desc;";

        return $con->createCommand($query)->queryAll();
    }


    private function consulta_faltas($inscriptionId){
        $con = Yii::$app->db;
        $query = "select 	fal.fecha  
                        ,fal.es_justificado 
                        ,fal.motivo_justificacion 
                        ,fal.fecha_solicitud_justificacion 
                        ,fal.fecha_justificacion 
                        ,fal.respuesta_justificacion 
                from 	scholaris_faltas fal
                        inner join op_student est on est.id = fal.student_id 
                        inner join op_student_inscription ins on ins.student_id = est.id 
                where 	ins.id = $inscriptionId;";
        return $con->createCommand($query)->queryAll();
    }


    
}
