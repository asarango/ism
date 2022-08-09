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
class PepPlanificacionController extends Controller {

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
        $opCourseTemplateId = $_GET['op_course_template_id'];
        $course = \backend\models\OpCourseTemplate::findOne($opCourseTemplateId);
        $periodoId = Yii::$app->user->identity->periodo_id;

        $this->insertar_temas($opCourseTemplateId, $periodoId);

        $temas = \backend\models\PepPlanificacionXUnidad::find()->where([
                    'op_course_template_id' => $opCourseTemplateId,
                    'scholaris_periodo_id' => $periodoId
                ])->all();

        return $this->render('index', [
                    'course' => $course,
                    'temas' => $temas
        ]);
    }

    private function insertar_temas($opCourseTemplateId, $scholarisPeriodoId) {
        $hoy = date('Y-m-d H:i:s');
        $usuario = Yii::$app->user->identity->usuario;

        $con = Yii::$app->db;
        $query = "insert into pep_planificacion_x_unidad (op_course_template_id, scholaris_periodo_id
                                ,tema_transdisciplinar_id, desde, hasta, porcentaje_planificado
                                ,created_at, created)	
                select 	$opCourseTemplateId, $scholarisPeriodoId, op.id , '1999-01-01', '1999-01-01',0,'$hoy', '$usuario'
                from 	pep_opciones op
                where 	op.tipo = 'tema'
                                and op.id not in (select 	tema_transdisciplinar_id 
                from 	pep_planificacion_x_unidad
                where 	op_course_template_id = $opCourseTemplateId
                                and scholaris_periodo_id = $scholarisPeriodoId
                                and tema_transdisciplinar_id = op.id);";
        $con->createCommand($query)->execute();
    }

    /**
     * Metodo para generar las acciones ajax con consultas
     * por GET
     */
    public function actionAjaxGet() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $accion = $_GET['accion'];

        switch ($accion) {
            case 'temas':
                $temas = $this->get_temas($_GET);
                $bloques = $this->get_bloques($_GET['op_course_id'], $periodoId);
                
                return $this->renderPartial('_ajax-temas', [
                    'temas' => $temas,
                    'bloques' => $bloques,
                    'op_course_template_id' => $_GET['op_course_id']
                ]);
                break;
        }
    }

    private function get_temas($get) {
        $opCourseTemplateId = $get['op_course_id'];
        $periodoId = Yii::$app->user->identity->periodo_id;

        $temas = \backend\models\PepPlanificacionXUnidad::find()->where([
                    'op_course_template_id' => $opCourseTemplateId,
                    'scholaris_periodo_id' => $periodoId
                ])->all();

        return $temas;
    }
    
    private function get_bloques($opcourseTemplateId, $periodoId){
        $helper = new \backend\models\helpers\Scripts();
        $uso = $helper->get_tipo_uso_op_course_template($opcourseTemplateId);
        
        $con = Yii::$app->db;
        $query = "select 	b.id as bloque_id
                                    ,b.name as bloque
                    from	scholaris_bloque_actividad b
                                    inner join scholaris_periodo p on p.codigo = b.scholaris_periodo_codigo 
                    where 	b.tipo_uso = '$uso'
                                    and p.id = $periodoId
                                    and b.tipo_bloque in ('PARCIAL')
                    order by b.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * ACTUALIZA EL BLOQUE
     * Metodo para ingresar informacion por ajax
     * por POST
     */
    public function actionUpdate() {
        
        $temaId = $_GET['id'];
        $bloque = $_POST['bloque'];
        
        $model = \backend\models\PepPlanificacionXUnidad::findOne($temaId);
        $model->bloque_id = $bloque;
        $model->save();
        
        return $this->redirect(['index1', 'op_course_template_id' => $model->op_course_template_id]);
    }

}
