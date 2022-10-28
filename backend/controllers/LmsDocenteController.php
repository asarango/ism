<?php

namespace backend\controllers;

use backend\models\LmsDocente;
use backend\models\plansemanal\RegistraHoras;
use backend\models\ScholarisClase;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ScholarisActividadController implements the CRUD actions for ScholarisActividad model.
 */
class LmsDocenteController extends Controller {

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
                    ],
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

        $semanaNumero   = $_GET['semana_numero'];
        $semanaNombre   = $_GET['nombre_semana' ];
        $claseId        = $_GET['clase_id'];  
        
        new RegistraHoras($semanaNumero, $claseId);
        
        $modelClase = ScholarisClase::findOne($claseId);
        $detalle    = $this->get_lms($claseId, $semanaNumero);

        return $this->render('index',[
            'modelClase'    => $modelClase,
            'detalle'       => $detalle,
            'semana_numero' => $semanaNumero,
            'nombre_semana' => $semanaNombre,     
            'clase_id'      => $claseId
        ]);
    }

    private function get_lms($claseId, $semanaNumero){
        $con = Yii::$app->db;
        $query = "select 	doc.id as lms_doc_id		
                        ,mat.nombre as materia
                        ,lms.semana_numero 
                        ,doc.fecha 
                        ,hor.nombre as hora 
                        ,lms.id as lms_id
                        ,lms.titulo 
                        ,lms.indicaciones 
                        ,lms.publicar 
                        ,lms.estado_activo 
                        ,lms.es_aprobado 
                        ,lms.descripcion_actividades 
                        ,lms.tarea 
                        ,lms.recursos 
                        ,lms.conceptos 
                        ,doc.se_realizo 
                        ,doc.motivo_no_realizado 
                        ,doc.justificativo 
                        ,(select count(id) from lms_actividad where lms_id = lms.id) as total_insumos
                        ,det.id as detalle_id
                from 	lms_docente doc
                        inner join lms on lms.id = doc.lms_id
                        inner join ism_area_materia iam on iam.id = lms.ism_area_materia_id 
                        inner join ism_materia mat on mat.id = iam.materia_id
                        inner join scholaris_horariov2_detalle det on det.id = doc.horario_detalle_id 
                        inner join scholaris_horariov2_hora hor on hor.id = det.hora_id 
                where 	lms.semana_numero = $semanaNumero
                        and clase_id = $claseId
                order by doc.fecha, hor.numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionUpdate(){
        $usuarioLog = Yii::$app->user->identity->usuario;
        $hoy        = date('Y-m-d H:i:s');

        $lmsDocenteId   = $_POST['lms_docente_id'];
        $seRealizo      = $_POST['se_realizo'];
        $motivo         = $_POST['motivo_no_realizado'];
        $justificativo  = $_POST['justificativo'];

        $seRealizo == true ? $realizo = true : $realizo = false;

        $model = LmsDocente::findOne($lmsDocenteId);
        $model->se_realizo          = $realizo;
        $model->motivo_no_realizado = $motivo;
        $model->justificativo       = $justificativo;
        $model->updated             = $usuarioLog;
        $model->updated_at          = $hoy;
        $model->save();

        return $this->redirect(['index1',
            'nombre_semana' => $_POST['nombre_semana'],
            'semana_numero' => $_POST['semana_numero'],
            'clase_id' => $_POST['clase_id']
        ]);
    }
    
}