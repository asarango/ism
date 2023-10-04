<?php

namespace backend\controllers;

use backend\models\dece\DeceDeteccionPdf;
use Yii;
use backend\models\DeceDeteccion;
use backend\models\DeceDeteccionSearch;
use backend\models\messages\Messages;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DeceDeteccionController implements the CRUD actions for DeceDeteccion model.
 */
class DeceDeteccionController extends Controller
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
     * Lists all DeceDeteccion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeceDeteccionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeceDeteccion model.
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
     * Creates a new DeceDeteccion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /* Creado Por: Santiago     Fecha: 
            Modificado Por: Santiago        Ultima Fecha Mod: 2023-04-03
            Detalle: Crear el registro del Dece-Deteccion
         */

        // echo '<pre>';
        //     print_r($_POST);
        //     die();
        $userLog = \Yii::$app->user->identity->usuario;
        $user = \backend\models\Usuario::find()->where(['usuario' => $userLog])->one();
        $resUser = \backend\models\ResUsers::find()->where(['login' => $user->usuario])->one();

        $model = new DeceDeteccion();
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');  
        
        $array_datos_estudiante = array();

        $es_lexionario = false;        

        if($_GET)
        {
            $id_estudiante = $_GET['id_estudiante'];
            $id_caso= $_GET['id_caso'];
            if(isset($_GET['es_lexionario']))
            {
                $es_lexionario = true;    
            }
            $ultimoNumDeteccion = $this->buscaUltimoNumDeteccion($id_caso);
            $model->numero_deteccion = $ultimoNumDeteccion + 1;            
            $model->id_estudiante = $id_estudiante;
            $model->fecha_reporte = $fechaActual;
            $model->id_caso = $id_caso;
            $model->numero_caso = $model->caso->numero_caso;
            $model->descripcion_del_hecho = ' - ';
            $model->acciones_realizadas = ' - ';
            $model->lista_evidencias = ' - ';
            $model->path_archivos = ' - ';

            $array_datos_estudiante = $this->datos_estudiante($id_estudiante,$es_lexionario);


        }  

        if ($model->load(Yii::$app->request->post())) 
        {

            $ultimoNumDeteccion = $this->buscaUltimoNumDeteccion($model->id_caso);
            $model->numero_deteccion = $ultimoNumDeteccion + 1;
            $model->fecha_reporte = $model->fecha_reporte.' '.$hora;
           
            $this->enviar_correo_tutor($model);
            $model->save();
            
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'resUser'=>$resUser,
            'array_datos_estudiante' =>$array_datos_estudiante,
        ]);
    }


    private function enviar_correo_tutor($model){
        $to = array($model->caso->id_usuario_dece);
        //$to = array('direccionsistemas@ism.edu.ec');
        $estudiante = $model->estudiante->first_name.' '.$model->estudiante->last_name;

        $html = '<b>Descripci&oacute;n del Hecho: </b>'.$model->descripcion_del_hecho.'<br>';
        $html .= '<b>Acciones Realizadas: </b>'.$model->acciones_realizadas.'<br>';
        $html .= '<br><br>Mensaje generado autom&aacute;ticamente, por favor no responder este correo<br><br>';

        $message = new Messages();
        $message->send_email($to, 'info@ism.edu.ec', 'Alerta: '.$estudiante, '', $html);
        
    }


    public function buscaUltimoNumDeteccion($idCaso)
    {
        //buscamos el ultimo numero de derivacion acorde al caso indicado
        $modelDeceDeteccion= DeceDeteccion::find()
        ->where(['id_caso'=>$idCaso])        
        ->max('numero_deteccion');

        if(!$modelDeceDeteccion){
            $modelDeceDeteccion =0;
        }
        return $modelDeceDeteccion;
    }

    /**
     * Updates an existing DeceDeteccion model.
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

        if ($model->load(Yii::$app->request->post())) {
            //return $this->redirect(['view', 'id' => $model->id]);
            $model->fecha_hecho = $_POST['fecha_hecho'];
            $model->save();

            return $this->render('update', [
                'model' => $model,
                'resUser'=>$resUser,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'resUser'=>$resUser,
        ]);
    }

    /**
     * Deletes an existing DeceDeteccion model.
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
     //*****   PDF  *******
     public function actionPdf()
     {
         $id_deteccion=  $_GET['id'];
         $objDecePdf = new DeceDeteccionPdf($id_deteccion);
     }

    /**
     * Finds the DeceDeteccion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceDeteccion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceDeteccion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function datos_estudiante($id_estudiante, $es_lexionario)
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $con = Yii::$app->db;
        $query='';
        if($es_lexionario)
        {      
            //Cuando viene desde la parte del lexionario  
            $query ="select  distinct 4.id,concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as student,
                c8.name curso , c7.name paralelo
                from scholaris_clase c1 , scholaris_grupo_alumno_clase c2, 
                op_faculty c10,op_student c4 ,op_student_inscription c5, 
                scholaris_op_period_periodo_scholaris c6,op_course_paralelo c7, op_course c8,
                res_users c9         
                where c9.login ='$usuarioLog'       
                and c9.partner_id = c10.partner_id
                and c1.idprofesor = c10.id
                and c1.id = c2.clase_id 
                and c2.estudiante_id = c4.id 
                and c4.id = c5.student_id 
                and c5.period_id  = c6.op_id 
                and c6.scholaris_id ='$periodoId'
                and c7.id = c1.paralelo_id 
                and c8.id = c7.course_id 
                and c2.estudiante_id ='$id_estudiante'
                order by student;"; 
        }
        else
        {
            //Cuando viene desde el modulo de dece
                $query="select  distinct 4.id,concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as student,
                    c8.name curso , c7.name paralelo
                    from scholaris_clase c1 , scholaris_grupo_alumno_clase c2 ,
                    op_institute_authorities c3 ,op_student c4 ,op_student_inscription c5, 
                    scholaris_op_period_periodo_scholaris c6,op_course_paralelo c7, op_course c8
                    where  
                    (c3.id = c1.dece_dhi_id or c3.id =c1.coordinador_dece_id ) 
                    and c1.id = c2.clase_id 
                    and c2.estudiante_id = c4.id 
                    and c4.id = c5.student_id 
                    and c5.period_id  = c6.op_id 
                    and c6.scholaris_id = '$periodoId'
                    and c7.id = c1.paralelo_id 
                    and c8.id = c7.course_id 
                    and c2.estudiante_id ='$id_estudiante'
                    --and c3.usuario  = '$usuarioLog'
                    order by student;";

        }


        $resp = $con->createCommand($query)->queryOne();

        return $resp;

    }
}
