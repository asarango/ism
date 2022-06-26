<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisAsistenciaAlumnosNovedades;
use backend\models\ScholarisAsistenciaAlumnosNovedadesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisAsistenciaAlumnosNovedadesController implements the CRUD actions for ScholarisAsistenciaAlumnosNovedades model.
 */
class ScholarisAsistenciaAlumnosNovedadesController extends Controller
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
     * Lists all ScholarisAsistenciaAlumnosNovedades models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $searchModel = new ScholarisAsistenciaAlumnosNovedadesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $periodoId);
        
        $students = $this->get_student_novedades($periodoId);
        $listaS = \yii\helpers\ArrayHelper::map($students, 'id', 'name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listaS' => $listaS
        ]);
    }
    
    private function get_student_novedades($periodoId){
        $con = Yii::$app->db;
        $query = "select 	g.id 
                                    ,concat(s.last_name,' ', s.first_name, ' ', s.middle_name ) as name
                    from 	scholaris_asistencia_alumnos_novedades n
                                    inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id 
                                    inner join op_student s on s.id = g.estudiante_id
                                    inner join scholaris_asistencia_profesor a on a.id = n.asistencia_profesor_id 
                                    inner join scholaris_clase cla on cla.id = a.clase_id
                                    inner join ism_area_materia am on am.id = cla.ism_area_materia_id 
                                    inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                    where 	pm.scholaris_periodo_id = $periodoId
                    order by 2;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
   

    /**
     * Updates an existing ScholarisAsistenciaAlumnosNovedades model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionJustificar($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            $model->acuerdo_justificacion = $_POST['contenido'];
            
            $model->save();
            return $this->redirect(['index1']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    

    /**
     * Finds the ScholarisAsistenciaAlumnosNovedades model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisAsistenciaAlumnosNovedades the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisAsistenciaAlumnosNovedades::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
