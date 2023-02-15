<?php

namespace backend\controllers;

use yii\filters\AccessControl;
use backend\models\DeceCasos;
use Yii;
use backend\models\PlanificacionOpciones;
use backend\models\DeceDerivacion;
use backend\models\DeceDerivacionInstitucionExterna;
use backend\models\DeceDerivacionSearch;
use backend\models\DeceInstitucionExterna;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeceDerivacionController implements the CRUD actions for DeceDerivacion model.
 */
class DeceDerivacionController extends Controller
{
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

    /**
     * Lists all DeceDerivacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeceDerivacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeceDerivacion model.
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
     * Creates a new DeceDerivacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $userLog = \Yii::$app->user->identity->usuario;
        $user = \backend\models\Usuario::find()->where(['usuario' => $userLog])->one();
        $resUser = \backend\models\ResUsers::find()->where(['login' => $user->usuario])->one();

        $model = new DeceDerivacion();
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');        

        if($_GET)
        {
            $id_estudiante = $_GET['id_estudiante'];
            $id_caso= $_GET['id_caso'];
            $model->id_estudiante = $id_estudiante;
            $model->fecha_derivacion = $fechaActual;
            $model->id_casos = $id_caso;
        }       

        if ($model->load(Yii::$app->request->post()))
        {   
   
            $ultimoNumDerivacion = $this->buscaUltimoNumDerivacion($_POST['DeceDerivacion']['id_casos'],$_POST['DeceDerivacion']['id_estudiante']);
            $numeroCaso = $this->buscaNumeroCaso($_POST['DeceDerivacion']['id_casos']);
            $fecha_derivacion = $_POST['fecha_derivacion'] ;
            $arrayAuxPost = $_POST;
            $model->fecha_derivacion = $fecha_derivacion .' ' .$hora;
            $model->numero_derivacion =  $ultimoNumDerivacion + 1;
            $model->numero_casos=  $numeroCaso;

            $model->save();
            

           foreach($arrayAuxPost as $aux)
           {          
               if (!is_array($aux))//COMO EL $_POST, tiene el array nativo de yii2, lo excluimos
                {
                    $idInstExterna = DeceInstitucionExterna::find()
                    ->where(['code'=>$aux])
                    ->one();

                    if($idInstExterna)
                    {                      
                        
                        $modelDerInsExterno = new DeceDerivacionInstitucionExterna();
                        
                        $modelDerInsExterno->id_dece_derivacion = $model->id;
                        $modelDerInsExterno->id_dece_institucion_externa =  $idInstExterna->id;
                        $modelDerInsExterno->save();
                    }
                }               
           } 
                  
           return $this->redirect(['update', 'id' => $model->id]);
        }      

        return $this->render('create', [
            'model' => $model,
            'resUser'=>$resUser,
        ]);
    }
    public function buscaUltimoNumDerivacion($idCaso,$idEstudiante)
    {
        //buscamos el ultimo numero de derivacion acorde al caso indicado
        $modelDeceDerivacion = DeceDerivacion::find()
        ->where(['id_casos'=>$idCaso])
        ->andWhere(['id_estudiante'=>$idEstudiante])
        ->max('numero_derivacion');

        if(!$modelDeceDerivacion){
            $modelDeceDerivacion =0;
        }
        return $modelDeceDerivacion;
    }
    public function buscaNumeroCaso($idCaso)
    {
        //buscamos el ultimo numero de derivacion acorde al caso indicado
        $modelNumeroCaso = DeceCasos::find()
        ->where(['id'=>$idCaso])
        ->max('numero_caso');

        if(!$modelNumeroCaso){
            $modelNumeroCaso =0;
        }
        return $modelNumeroCaso;
    }
   
    /**
     * Updates an existing DeceDerivacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $userLog = \Yii::$app->user->identity->usuario;
        $user = \backend\models\Usuario::find()->where(['usuario' => $userLog])->one();
        $resUser = \backend\models\ResUsers::find()->where(['login' => $user->usuario])->one();

        $model = $this->findModel($id);
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');         
        
        $arrayInstExtUpdate = $this->buscaInstitucionExterna($model->id);
        if ($model->load(Yii::$app->request->post())) 
        {  
            $fecha_modificacion = $_POST['fecha_modificacion'] ;
            $model->fecha_modificacion = $fecha_modificacion.' '.$hora;
            $model->save();
            $arrayAuxPost = $_POST; 

            //ELIMINAMOS TODOS LOS REGISTROS DE LOS INST. EXTERNOS PARA VOLVER AGREGAR NUEVAMENTE TODOS LOS SELECCIONADOS
            $x = Yii::$app->db->createCommand("
                DELETE FROM dece_derivacion_institucion_externa 
                WHERE id_dece_derivacion = '$model->id'                
            ")->execute();

            // echo '<pre>';
            // print_r($arrayAuxPost);
            // die();

           foreach($arrayAuxPost as $aux)
           {  
                if (!is_array($aux))//COMO EL $_POST, tiene el array nativo de yii2, lo excluimos
                {
                    $idInstExterna = DeceInstitucionExterna::find()
                    ->where(['code'=>$aux])
                    ->one();
                    
                    if($idInstExterna)
                    {   
                        $modelDerInsExterno = new DeceDerivacionInstitucionExterna();
                       
                        $modelDerInsExterno->id_dece_derivacion = $model->id;
                        $modelDerInsExterno->id_dece_institucion_externa =  $idInstExterna->id;
                        $modelDerInsExterno->save();
                    }
                }               
           }
           return $this->redirect(['update', 'id' => $model->id]);
        }

        if($model)
        {
            $model->fecha_modificacion = $fechaActual;
        } 

        return $this->render('update', [
            'model' => $model,
            'resUser'=>$resUser,
            'arrayInstExtUpdate' => $arrayInstExtUpdate
        ]);
    }
    //busca la institucion externa por el ID, de la derivación
    public function buscaInstitucionExterna($idDerivacion)
    {
       $con =yii::$app->db;
        $query="select i.id,i.nombre,i.code,'si' as Seleccionado 
       from dece_derivacion d1 , dece_derivacion_institucion_externa d2,
       dece_institucion_externa i
       where d1.id = d2.id_dece_derivacion 
       and d2.id_dece_institucion_externa  = i.id 
       and d1.id = '$idDerivacion'
       union all
        select dd.id,dd.nombre,dd.code,'no' as Seleccionado
        from dece_institucion_externa dd 
        where id not in
        ( select id_dece_institucion_externa from dece_derivacion_institucion_externa dr 
        where id_dece_derivacion = '$idDerivacion') order by id;";

       $resp = $con->createCommand($query)->queryAll();
     
       return $resp;
    }

    /**
     * Deletes an existing DeceDerivacion model.
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
     * Finds the DeceDerivacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceDerivacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceDerivacion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
