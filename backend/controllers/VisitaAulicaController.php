<?php

namespace backend\controllers;

use backend\models\ScholarisClase;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisClase as ModelsScholarisClase;
use backend\models\ViewListaVisitaAulica;
use backend\models\ViewListaVisitaAulicaSearch;
use Yii;
use backend\models\VisitaAulica;
use backend\models\VisitaAulicaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * VisitaAulicaController implements the CRUD actions for VisitaAulica model.
 */
class VisitaAulicaController extends Controller
{
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

    /**
     * Lists all VisitaAulica models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;

        $searchModel = new ViewListaVisitaAulicaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $usuario, $periodoId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VisitaAulica model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($clase_id, $bloque_id)
    {

        $periodoId = Yii::$app->user->identity->periodo_id;

        $clase = ScholarisClase::findOne($clase_id);

        $visitas = VisitaAulica::find()->where([
            'clase_id' => $clase_id
        ])->all();

        $trimestre = ScholarisBloqueActividad::findOne($bloque_id);
        $cursoId = $clase->paralelo->course_id;

        $estudiantes = $this->consulta_estudiantes_nee($cursoId, $periodoId);

        return $this->render('view', [
            'clase' => $clase,
            'visitas' => $visitas,
            'trimestre' => $trimestre,
            'estudiantes' => $estudiantes
        ]);

    }



    private function consulta_estudiantes_nee($cursoId, $periodoId){
        $con = Yii::$app->db;
        $sql = "select 	nee.student_id 
                        ,concat(est.last_name, ' ', est.first_name ) as estudiante
                from 	nee_x_clase nxc
                        inner join nee on nee.id = nxc.nee_id 
                        inner join scholaris_clase cla on cla.id = nxc.clase_id 
                        inner join op_course_paralelo par on par.id = cla.paralelo_id 
                        inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                        inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                        inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                        inner join ism_materia mat on mat.id = iam.materia_id 
                        inner join op_student est on est.id = nee.student_id 
                where 	par.course_id = $cursoId
                        and ipm.scholaris_periodo_id = $periodoId
                group by nee.student_id 
                        ,2 
                order by 2;";
        $students = $con->createCommand($sql)->queryAll();


        $arregloEstudiantes = array();

        foreach ($students as $student) {
            $materiasNee = $this->consulta_clases_nee($student['student_id'], $periodoId);
            $student['materias'] = $materiasNee;
            array_push($arregloEstudiantes, $student);
        }

        return $arregloEstudiantes;

    }


    private function consulta_clases_nee($studentId, $periodoId){
        $con = Yii::$app->db;
        $query = "select 	cla.id 
                            ,mat.nombre as materia
                            ,(
                                select 	count(e.id) as total 
                                from 	visitas_aulicas_estudiantes e
                                        inner join visita_aulica v on v.id = e.visita_id 
                                        inner join scholaris_grupo_alumno_clase g on g.id = e.grupo_id 
                                        inner join scholaris_bloque_actividad b on b.id = v.bloque_id 
                                        inner join scholaris_periodo p on p.codigo = b.scholaris_periodo_codigo 
                                where 	g.estudiante_id = nee.student_id 
                                        and p.id = ipm.scholaris_periodo_id 
                                        and g.clase_id = cla.id 
                            ) as total_visitas
                    from 	nee
                            inner join nee_x_clase nxc on nxc.nee_id = nee.id 
                            inner join scholaris_clase cla on cla.id = nxc.clase_id 
                            inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                            inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                            inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                            inner join ism_materia mat on mat.id = iam.materia_id 
                    where 	nee.student_id = $studentId
                            and ipm.scholaris_periodo_id = $periodoId 
                    order by 3;";
        return $con->createCommand($query)->queryAll();
    }



    /**
     * Creates a new VisitaAulica model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VisitaAulica();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing VisitaAulica model.
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
     * Deletes an existing VisitaAulica model.
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
     * Finds the VisitaAulica model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VisitaAulica the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VisitaAulica::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
