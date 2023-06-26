<?php

namespace backend\controllers;

use backend\models\TocOpciones;
use backend\models\TocPlanUnidad;
use backend\models\TocPlanUnidadAprendizaje;
use backend\models\TocPlanUnidadHabilidad;
use backend\models\TocPlanVertical;
use Yii;
use backend\models\TocPlanUnidadDetalle;
use backend\models\TocPlanUnidadDetalleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TocPlanUnidadDetalleController implements the CRUD actions for TocPlanUnidadDetalle model.
 */
class TocPlanUnidadDetalleController extends Controller
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
     * Lists all TocPlanUnidadDetalle models.
     * @return mixed
     */
    public function actionIndex1()
    {
        $tocPlanUnidadId = $_GET['id'];

        $unidad = TocPlanUnidad::findOne($tocPlanUnidadId);
        $classId = $unidad->clase_id;

        $vertical = TocPlanVertical::find()->where(['clase_id' => $classId])->all();

        $pud = TocPlanUnidadDetalle::find()->where([
            'toc_plan_unidad_id' => $tocPlanUnidadId
        ])->one();

        # Inyecta los campos del plan
        $this->inyectar_campos_plan($pud, $tocPlanUnidadId);

        # Proceso de Aprendizaje
        // $aprendizajes = $this->get_proceso_aprendizaje_opciones($tocPlanUnidadId);
        $aprendizajes = TocPlanUnidadAprendizaje::find()->where(['toc_plan_unidad_id' => $tocPlanUnidadId])->all();

        $habilidades = TocPlanUnidadHabilidad::find()->where(['toc_plan_unidad_id' => $tocPlanUnidadId])->all();


        return $this->render('index', [
            'unidad' => $unidad,
            'pud' => $pud,
            'vertical' => $vertical,
            'aprendizajes' => $aprendizajes,
            'habilidades' => $habilidades
        ]);
    }

    // private function get_proceso_aprendizaje_opciones($tocPlanUnidadId){
    //     $con = Yii::$app->db;
    //     $query = "select 	id, opcion, descripcion  
    //                 from 	toc_opciones op
    //                 where 	seccion = 'APRENDIZAJE'
    //                         and estado = true
    //                         and id not in (select toc_opcion_id from toc_plan_unidad_aprendizaje 
    //                                 where toc_plan_unidad_id = $tocPlanUnidadId 
    //                                         and toc_opcion_id = op.id)
    //                 order by  opcion;";
    //     $res = $con->createCommand($query)->queryAll();
    //     return $res;
    // }



    private function inyectar_campos_plan($pud, $tocPlanUnidadId)
    {
        if (!$pud) {
          $model = new TocPlanUnidadDetalle();
          $user = Yii::$app->user->identity->usuario;
          $today = date('Y-m-d H:i:s');

          $model->toc_plan_unidad_id    = $tocPlanUnidadId;
          $model->evaluacion_pd         = 'none';
          $model->descripcion_unidad    = 'none';
          $model->preguntas_conocimiento = 'none';
          $model->conocimientos_esenciales = 'none';
          $model->actividades_principales  = 'none';
          $model->enfoques_aprendizaje  = 'none';
          $model->funciono_bien         = 'none';
          $model->no_funciono_bien      = 'none';          
          $model->observaciones         = 'none';
          $model->created               = $user;
          $model->created_at            = $today;
          $model->updated               = $user;
          $model->updated_at            = $today;
          $model->save();
        }
    }

    /**
     * Displays a single TocPlanUnidadDetalle model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TocPlanUnidadDetalle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TocPlanUnidadDetalle();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TocPlanUnidadDetalle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {                
        if($_POST){
            $id =      $_POST['id']; 
            $bandera = $_POST['bandera'];
            $contenido = $_POST[$bandera];
            $seccion = $_POST['bandera_seccion'];

            $model = TocPlanUnidadDetalle::find()->where(['toc_plan_unidad_id' => $id])->one();
            $model->$bandera = $contenido;
            $model->save();
            return $this->redirect(['index1', 'id' => $model->toc_plan_unidad_id, '#' => $seccion]);
        }else{
            $id = $_GET['id']; 
            $bandera = $_GET['bandera'];
            $model = TocPlanUnidadDetalle::find()->where(['toc_plan_unidad_id' => $id])->one();
            $seccion = $_GET['bandera_seccion'];
            // echo '<pre>';
            // print_r($model);
            // die();

            return $this->render('update', [
                'model' => $model,
                'bandera' => $bandera,
                'banderaSeccion' => $seccion
            ]);
        }
        
    }

    /**
     * Deletes an existing TocPlanUnidadDetalle model.
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
     * Finds the TocPlanUnidadDetalle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TocPlanUnidadDetalle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TocPlanUnidadDetalle::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * MÉTODO PARA EL PROCESO DE APRENDIZAJE
     */
    public function actionAprendizaje(){
        $tocPlanUnidadId = $_GET['toc_plan_unidad_id'];
        $unidad = TocPlanUnidad::findOne($tocPlanUnidadId);
        $seleccionadas = TocPlanUnidadAprendizaje::find()->where([
            'toc_plan_unidad_id' => $tocPlanUnidadId
        ])->orderBy('id')->all();

        $disponibles = $this->get_aprendizaje_disponible($tocPlanUnidadId);

        return $this->render('aprendizaje', [
            'unidad' => $unidad,
            'seleccionadas' => $seleccionadas,
            'disponibles' => $disponibles
        ]);
    }

    private function get_aprendizaje_disponible($tocPlanUnidadId){
        $con = Yii::$app->db;
        $query = "select 	op.id 
                            ,op.descripcion
                    from 	toc_opciones op
                    where 	op.seccion = 'APRENDIZAJE'
                            and op.id not in (select toc_opcion_id 
                                                from toc_plan_unidad_aprendizaje 
                                                where toc_plan_unidad_id = $tocPlanUnidadId
                                                        and toc_opcion_id = op.id)
                            and op.estado = true
                    order by opcion;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    public function actionAccionAprendizaje(){        
        $bandera = $_GET['bandera'];        

        if( $bandera == 'agregar'){
            $model = new TocPlanUnidadAprendizaje();
            $model->toc_plan_unidad_id = $_GET['toc_plan_unidad_id'];
            $model->toc_opcion_id = $_GET['toc_opciones_id'];
            $model->save();
        }elseif($bandera == 'eliminar'){            
            $model = TocPlanUnidadAprendizaje::findOne($_GET['id']);
            $model->delete();
        }elseif($bandera == 'nuevo'){
            $descripcion = $_GET['descripcion'];
            $this->agregar_nuevo_aprendizaje($_GET['toc_plan_unidad_id'], $descripcion);
        }
        return $this->redirect(['aprendizaje', 'toc_plan_unidad_id' => $_GET['toc_plan_unidad_id']]);
    }


    private function agregar_nuevo_aprendizaje($tocPlanUnidadId, $descripcion){
        $model = TocOpciones::find()
            ->where(['seccion' => 'APRENDIZAJE'])
            ->orderBy(['opcion' => SORT_DESC])
            ->one();            
        $ultimo = $model->opcion+1;
        
        $insert = $this->insert_aprendizaje($ultimo, $descripcion);

        $add = new TocPlanUnidadAprendizaje();
        $add->toc_plan_unidad_id = $tocPlanUnidadId;
        $add->toc_opcion_id = $insert->id;
        $add->save();
        
    }

    private function insert_aprendizaje($ultimo, $descripcion){
        $con = Yii::$app->db;
        $query = "insert into toc_opciones (seccion, opcion, descripcion, tipo, planificacion)
         values('APRENDIZAJE','$ultimo','$descripcion','texto','VERTICAL')";
        $con->createCommand($query)->execute();

        $model = TocOpciones::find()->where([
            'seccion' => 'APRENDIZAJE', 
            'descripcion' => $descripcion
        ])->one();

        return $model;
    }
}