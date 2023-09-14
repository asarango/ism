<?php

namespace backend\controllers;

use backend\models\PlanificacionSemanal;
use Illuminate\Support\Facades\Auth;
use Yii;
use backend\models\PlanificacionSemanalRecursos;
use backend\models\PlanificacionSemanalRecursosSearch;
use backend\models\ScholarisActividad;
use backend\models\ScholarisParametrosOpciones;
use Mpdf\Tag\Select;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PlanificacionSemanalRecursosController implements the CRUD actions for PlanificacionSemanalRecursos model.
 */
class PlanificacionSemanalRecursosController extends Controller
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
     * Lists all PlanificacionSemanalRecursos models.
     * @return mixed
     */
    public function actionIndex()
    { 
        

        $planBloqueId = $_GET['plan_bloque_unidad_id'];
        $planificacionSemanalId = $_GET['id']; // Se recibe parametro de planificacion ID
        $bloqueId = $_GET['bloque_id'];

        $userLogin = Yii::$app->user->identity->usuario;
        $planificacionSemanal = PlanificacionSemanal::findOne($planificacionSemanalId);
        $recursos= PlanificacionSemanalRecursos::find()
            ->where(['plan_semanal_id' => $planificacionSemanalId])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        $insumos = ScholarisActividad::find()
            ->where(['plan_semanal_id'=> $planificacionSemanalId])
            ->all();

        return $this->render('index', [
            'planificacionSemanal' => $planificacionSemanal,
            'recursos' => $recursos,
            'insumos' => $insumos,
            'bloqueId' => $bloqueId,
            'planBloqueId' => $planBloqueId
        ]);
    }

    /**
     * Displays a single PlanificacionSemanalRecursos model.
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
     * Creates a new PlanificacionSemanalRecursos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // echo '<pre>';
        // print_r($_POST);

        if (isset($_POST['plan_semanal_id'])) {           
            // Realizamos la insercion de registro
            // Con este metodo se realizan las preguntas del tipo de recursos
            //que consta en el formulario
            
            $planificacionSemanalId = $_POST['plan_semanal_id'];
            $planificacionSemanal = PlanificacionSemanal::findOne($planificacionSemanalId);
            $bloqueId = $planificacionSemanal->semana->bloque_id;
            
            $this->guardar_recursos($_POST);

            // Redireccionamos a index luego de grabar
            return $this->redirect(['index', 
                'id' => $_POST['plan_semanal_id'],
                'bloque_id' => $bloqueId
            ]);
        } else {
            
            $planificacionSemanalId = $_GET['planificacion_semanal_id'];
            $planificacionSemanal = PlanificacionSemanal::findOne($planificacionSemanalId);
            return $this->render('create', [
                'planificacionSemanalId' => $planificacionSemanalId,
                'planificacionSemanal' => $planificacionSemanal
            ]);
        }
    }
    private function get_url_repositorio ()
    {
        //Obtener el valor de la BDD del dominio donde se guardara el archivo
        $dominioRepositorio = ScholarisParametrosOpciones::find()
            ->where([
                'codigo'=>'dominio'
            ])
            ->one();
        $dominio=$dominioRepositorio->valor;
        //fin dominio


        //Obtener el segundo valor para contruir ruta donde guardar archivo (repositorio)
        $dominioDirectorio = ScholarisParametrosOpciones::find()
            ->where([
                'codigo'=>'repositorio'
            ])
            ->one();
        $repositorio=$dominioDirectorio->valor;
        //fin repositorio
        
        $url=array(
            'dominio' => $dominio,
            'repositorio' => $repositorio 

        );
        return $url;
    
    }
    private function guardar_recursos($post)
    {
        // echo '<pre>';
        // print_r($post);
        // die();
    //    print_r($_FILES);
        if ($post['bandera'] == 'file') {
            $fecha=date('YmdHis');
            $nombreArchivo = $_FILES['documento']['name'];
            $rutaArchivo = $_FILES['documento']['tmp_name'];
            $repo = $this->get_url_repositorio(); //solicitamos URL de Repositorio
            $repositorio=$repo['repositorio'];            
            $destino = '/var/www/html' . $repositorio . '/' . $fecha . $nombreArchivo;
            // echo $destino;
            // die();
            if(move_uploaded_file($rutaArchivo, $destino)){
                $planificacionSemanalId=$post['plan_semanal_id'];
                $planificacionSemanalTema=$post['tema1'];
                $planificacionSemanalTipoRecurso=$post['bandera'];                
                $model=new PlanificacionSemanalRecursos();
                $model->plan_semanal_id=$planificacionSemanalId;
                $model->tema=$planificacionSemanalTema;
                $model->tipo_recurso=$planificacionSemanalTipoRecurso;
                $model->url_recurso=$repositorio . '/' . $fecha . $nombreArchivo;
                $model->estado=true;
                $model->save();
                
            }else{
                echo"no se guardo, volver a intentar caso contrario comunciarse con ADMINISTRADOR";
                
            }
        }elseif($post['bandera'] == 'link'){
            $planificacionSemanalId=$post['plan_semanal_id'];
            $planificacionSemanalTema=$post['tema1'];
            $planificacionSemanalTipoRecurso=$post['bandera'];
            $planificacionSemanalUrl=$post['url'];                
            $model=new PlanificacionSemanalRecursos();
            $model->plan_semanal_id=$planificacionSemanalId;
            $model->tema=$planificacionSemanalTema;
            $model->tipo_recurso=$planificacionSemanalTipoRecurso;
            $model->url_recurso=$planificacionSemanalUrl;
            $model->estado=true;
            $model->save();
        }elseif($post['bandera']=='video-conferencia'){
            $planificacionSemanalId=$post['plan_semanal_id'];
            $planificacionSemanalTema=$post['tema1'];
            $planificacionSemanalTipoRecurso=$post['bandera'];
            $planificacionSemanalVideo=$post['video-conferencia'];                
            $model=new PlanificacionSemanalRecursos();
            $model->plan_semanal_id=$planificacionSemanalId;
            $model->tema=$planificacionSemanalTema;
            $model->tipo_recurso=$planificacionSemanalTipoRecurso;
            $model->url_recurso=$planificacionSemanalVideo;
            $model->estado=true;
            $model->save();
        }elseif($post['bandera']== 'texto'){
            $planificacionSemanalId=$post['plan_semanal_id'];
            $planificacionSemanalTema=$post['tema1'];
            $planificacionSemanalTipoRecurso=$post['bandera'];
            $planificacionSemanalTexto=$post['texto'];                
            $model=new PlanificacionSemanalRecursos();
            $model->plan_semanal_id=$planificacionSemanalId;
            $model->tema=$planificacionSemanalTema;
            $model->tipo_recurso=$planificacionSemanalTipoRecurso;
            $model->url_recurso=$planificacionSemanalTexto;
            $model->estado=true;
            $model->save();
        };

        }

    /**
     * Updates an existing PlanificacionSemanalRecursos model.
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
     * Deletes an existing PlanificacionSemanalRecursos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id){
        $recurso = PlanificacionSemanalRecursos::findOne($id);
        $planSemanalRecursoId= $recurso->plan_semanal_id;
        $recurso->delete();
        if($recurso->tipo_recurso=='file'){
            unlink('/var/www/html' . $recurso->url_recurso);
        }
        return $this->redirect(['index','id'=>$planSemanalRecursoId]);
        }

    /**
     * Finds the PlanificacionSemanalRecursos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlanificacionSemanalRecursos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlanificacionSemanalRecursos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}