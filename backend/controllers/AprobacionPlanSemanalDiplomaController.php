<?php

namespace backend\controllers;

use backend\models\diploma\PdfPlanSemanaDocente;
use backend\models\PlanSemanalBitacora;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisBloqueSemanas;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisHorariov2CabeceraController implements the CRUD actions for ScholarisHorariov2Cabecera model.
 */
class AprobacionPlanSemanalDiplomaController extends Controller
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

    public function actionIndex1()
    {
        $userCoordinador    = \Yii::$app->user->identity->usuario;
        $periodoId  = \Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::findOne($periodoId);
        $template = $_GET['template_id'];

        // recupera los trimestres para la caja select de trimestres
        $trimestres = ScholarisBloqueActividad::find()
            ->where([
                'scholaris_periodo_codigo' => $periodo->codigo
            ])
            ->all();

        // Pregunnta si existe semana por defecto sino coloca
        // la primera
        if (isset($_GET['trimestre_defecto'])) {
            $trimestreDefecto = $_GET['trimestre_defecto'];
        } else {
            $trimestreDefecto = $trimestres[0]->id;
        }

        // $toma los bloque segun el bloque_id
        $trimestre = ScholarisBloqueActividad::findOne($trimestreDefecto);

        $semanas = ScholarisBloqueSemanas::find()
            ->where([
                'bloque_id' => $trimestre->id
            ])
            ->orderBy('semana_numero')
            ->all();

        $docentes = $this->get_docentes_x_coordinador($userCoordinador, $periodoId);


        return $this->render('index', [
            'trimestres' => $trimestres,
            'trimestre' => $trimestre,
            'template' => $template,
            'semanas' => $semanas,
            'docentes' => $docentes
        ]);
    }

    private function get_docentes_x_coordinador($coordinador, $periodoId)
    {
        $con = Yii::$app->db;
        $query = "select 	fac.id as faculty_id
                            ,concat(fac.last_name, ' ', fac.x_first_name, ' ', fac.middle_name) as docente  
                            ,rus.login
                    from 	scholaris_clase cla 
                            inner join op_institute_authorities aut on aut.id = cla.coordinador_academico_id
                            inner join op_faculty fac on fac.id = cla.idprofesor 
                            inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                            inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                            inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                            inner join res_users rus on rus.partner_id = fac.partner_id 
                    where 	aut.usuario = '$coordinador'
                            and ipm.scholaris_periodo_id = $periodoId
                    group by 1,2,3;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



    public function actionAprobarPlanSemanal()
    {        
        $weekId = $_GET['semana_id'];
        $user = $_GET['docentes'];
        $periodoId = \Yii::$app->user->identity->periodo_id;
        // $template = $_GET['template_id'];
        $bitacora = PlanSemanalBitacora::find()
            ->where(['semana_id' => $weekId, 'docente_usuario' => $user])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        return $this->render('aprobar-plan-semanal', [
            'semanaId' => $weekId,
            'user' => $user,
            'periodo' => $periodoId,
            // 'template' => $template,
            'bitacora' => $bitacora,
            'trimestre_name' => $_GET['trimestre_name']
        ]);
    }
    // muestra el pdf del plan semanal en vista de coordinador
    public function actionVerPlanSemanal()
    {

        $weekId = $_GET['semanaId'];
        $user = $_GET['user'];
        $periodoId = $_GET['periodo'];
        // $docente = $_GET['docentes'];    

        return $this->render('ver-plan-semanal', [
            'semanaId' => $weekId,
            'user' => $user,
            'periodo' => $periodoId,
            
        ]);
    }

    // en caso de aprobar el plan botones del formulario
    public function actionAprobacion()
    {   
        $semanaId = $_GET['semana_id'];
        $docentePlanifica = $_GET['docente_planificacion'];
        

        $model = new PlanSemanalBitacora();
        $model->semana_id = $semanaId;
        $model->docente_usuario = $docentePlanifica;
        $model->estado = 'APROBADO';
        $model->obervaciones = 'Felicitaciones, tu planificación se ha aprobado.';
        $model->fecha_envio = date('Y-m-d H:i:s');
        $model->usuario_envia = \Yii::$app->user->identity->usuario;
        $model->usuario_recibe = $docentePlanifica;
        $model->save();

        return $this->redirect(['aprobar-plan-semanal', 
            'semana_id' => $semanaId, 
            'docentes' => $docentePlanifica,
            'trimestre_name' => $_GET['trimestre_name']
        ]);
        
    }


    public function actionDevolver(){
        // echo '<pre>';
        // print_r($_POST);
        // die();

        $model = new PlanSemanalBitacora();
        $model->semana_id = $_POST['semana_id'];
        $model->docente_usuario = $_POST['docente_usuario'];
        $model->estado = 'DEVUELTO';
        $model->obervaciones = $_POST['revision_coordinacion_observaciones'];
        $model->fecha_envio = date('Y-m-d H:i:s');
        $model->usuario_envia = \Yii::$app->user->identity->usuario;
        $model->usuario_recibe = $_POST['docente_usuario'];
        $model->save();

        return $this->redirect(['aprobar-plan-semanal', 
            'semana_id' => $_POST['semana_id'], 
            'docentes' => $_POST['docente_usuario'],
            'trimestre_name' => $_POST['trimestre_name']
        ]);
    }
}
