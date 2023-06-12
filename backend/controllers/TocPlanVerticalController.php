<?php

namespace backend\controllers;

use backend\models\ScholarisClase;
use backend\models\TocPlanUnidad;
use backend\models\TocPlanVertical;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ScholarisActividadController implements the CRUD actions for ScholarisActividad model.
 */
class TocPlanVerticalController extends Controller {

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

    /** ACCIÓN PARA REALIZAR LA PANTALLA DE OPCIONES DE PLANIFICACIÓN TOC
     * Creado por Arturo Sarango 2023-06-02
     * Actualizado por Arturo Sarango 2023-06-02
    */
    public function actionIndex1() {
        $classId = $_GET['clase_id'];
        $this->inyecta_opciones($classId);
        $this->inyecta_unidades($classId);

        $vertical = TocPlanVertical::find()->where(['clase_id' => $classId])->all();
        $unidades = TocPlanUnidad::find()->where(['clase_id' => $classId])->all();

        return $this->render('index', [
            'vertical' => $vertical,
            'unidades' => $unidades
        ]);
    }

    private function inyecta_unidades($claseId){
        $user = Yii::$app->user->identity->usuario;
        $today = date('Y-m-d H:i:s');

        $clase = ScholarisClase::findOne($claseId);
        $uso = $clase->tipo_usu_bloque;

        $con = Yii::$app->db;
        $query = "insert into toc_plan_unidad (bloque_id, clase_id, created, created_at, updated, updated_at)
                    select 	blo.id, $claseId, '$user', '$today', '$user', '$today'
                    from 	scholaris_bloque_actividad blo
                    where 	blo.tipo_uso = '$uso'
                            and tipo_bloque = 'PARCIAL'
                            and blo.id not in (
                                select bloque_id from toc_plan_unidad where clase_id = $claseId and bloque_id = blo.id 			
                            )
                    order by blo.orden;";
        $con->createCommand($query)->execute();
    }

    private function inyecta_opciones($claseId){
        $user = Yii::$app->user->identity->usuario;
        $today = date('Y-m-d H:i:s');

        $con = Yii::$app->db;
        $query = "insert into toc_plan_vertical (clase_id, opcion_descripcion, contenido, tipo, created_at, created, updated_at, updated)
                    select 	$claseId, op.descripcion, 'none', op.tipo ,'$today', '$user', '$today', '$user' 
                    from 	toc_opciones op
                    where 	op.planificacion = 'VERTICAL'
                            and estado = true
                            and descripcion not in (select 	opcion_descripcion 
                                from 	toc_plan_vertical
                                where 	clase_id = $claseId);";

        $res = $con->createCommand($query)->execute();
    }



    public function actionUpdateField(){
        $user = Yii::$app->user->identity->usuario;
        $today = date("Y-m-d H:i:s");
        $id = $_POST['id'];
        $content = $_POST['contenido'];

        $model = TocPlanVertical::findOne($id);

        $classId = $model->clase->id;

        $model->contenido = $content;
        $model->updated = $user;
        $model->updated_at = $today;
        $model->save();

        return $this->redirect(['index1', 'clase_id' => $classId]);


    }


    /**
     * MÉTODO PARA ACTUALIZAR CAMPOS DE PLAN DE UNIDAD
     * creado       por: Arturo Sarango  el 2023-06-09
     * actualizado  por: Arturo Sarango  el 2023-06-09
     */
    public function actionUpdatePud(){
        print_r($_POST);
    }

}