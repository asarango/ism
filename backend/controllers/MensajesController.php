<?php

namespace backend\controllers;

use backend\models\MessageHeader;
use backend\models\MessageHeaderSearch;
use backend\models\services\WebServicesUrls;
use backend\models\MessagePara;
use backend\models\MessageParaSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class MensajesController extends Controller
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
     * Lists all PlanArea models.
     * @return mixed
     */
    public function actionIndex()
    {

        $userLog = Yii::$app->user->identity->usuario;

        $searchModel = new MessageHeaderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $userLog);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionDetalle()
    {
        $id = $_GET['id'];

        $mensaje = MessagePara::findOne($id);

        if (!$mensaje->fecha_lectura) {
            $mensaje->fecha_lectura = date('Y-m-d H:i:s');
            $mensaje->estado = 'leído';
            $mensaje->save();
        }


        return $this->render('detalle', [
            'mensaje' => $mensaje
        ]);
    }

    public function actionCambiarEstado()
    {
        $id       = $_GET['id'];
        $estado   = $_GET['estado'];

        $model = MessagePara::findOne($id);
        $model->estado = $estado;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionCreate()
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s');
        $appOrigen = 'Educandi';
        $tablaOrigen = 'message_header';
        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $cursos = $this->consulta_cursos($periodoId, $institutoId);

        $arrayPersonas = array();
        $arrayCursos = array();
        $arrayParalelos = array();

        $model = new MessageHeader();

        if ($model->load(Yii::$app->request->post())) {
            $model->remite_usuario  = $usuarioLog;
            $model->created_at      = $fechaHoy;
            $model->updated_at      = $fechaHoy;
            $model->texto           = $_POST['texto'];
            $model->aplicacion_origen = $appOrigen;
            $model->tabla_origen    = $tablaOrigen;            
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'cursos' => $cursos,
            'arrayPersonas' => $arrayPersonas,
            'arrayCursos' => $arrayCursos,
            'arrayParalelos' => $arrayParalelos
        ]);
    }

    private function consulta_cursos($periodoId, $intituteId)
    {
        $con = Yii::$app->db;
        $query = "select 	c.id 
                        ,c.name as course
                from 	op_course c
                        inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = c.period_id 
                        inner join op_course_template t on t.id = c.x_template_id 
                where 	sop.scholaris_id = $periodoId
                        and c.x_institute = $intituteId
                order by t.next_course_id desc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function enviar_a_personas($arrayPersonas, $messageId)
    {
        $fechaHoy = date('Y-m-d H:i:s');
        foreach ($arrayPersonas as $persona) {
            $model = new MessagePara();
            $model->message_id = $messageId;
            $model->para_usuario = $persona;
            $model->estado = 'recibido';
            $model->fecha_recepcion = $fechaHoy;
            $model->save();
        }
    }


    // private function enviar_grupos($grupo, $arrayParalelos)
    // {
    //     if ($grupo == 'DOCENTES') {
    //     }
    // }



    public function actionView($id)
    {
        $model = MessageHeader::findOne($id);
        $para = MessagePara::find()->where(['message_id' => $id])->all();
        $totalEnviado = MessagePara::find()->where(['message_id' => $id, 'estado' => 'false'])->all();
        $enviados = MessagePara::find()->where(['message_id' => $id, 'estado' => 'enviado'])->all();

        $to = $this->get_to($id);
        $toUsers = $this->get_to_users($id);
        $toStudents = $this->get_to_students($id);

        return $this->render('view', [
            'model' => $model,
            'to' => $to,
            'toUsers' => $toUsers,
            'toStudents' => $toStudents,
            'para' => $para,
            'totalEnviado' => $totalEnviado,
            'enviados' => $enviados
        ]);
    }

    private function get_to_users($messageHeaderId){
        $con = Yii::$app->db;
        $query = "select 	par.id, par.para_usuario 
                            , rp.name
                    from 	message_para par
                            inner join res_users ru on ru.login = par.para_usuario 
                            inner join res_partner rp on rp.id = ru.partner_id 
                    where 	par.message_id = $messageHeaderId
                            and par.grupo_id is null
                    order 	by rp.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    private function get_to_students($messageHeaderId){
        $con = Yii::$app->db;
        $query = "select 	para.id, para.para_usuario, rp.name
                    from 	message_para para
                            inner join op_student os on os.x_institutional_email = para.para_usuario 
                            inner join res_partner rp on rp.id = os.partner_id 
                    where 	para.message_id = $messageHeaderId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    private function get_to($messageHeaderId){
        $con = Yii::$app->db;
        $query = "select 	par.grupo_id 
                        ,gro.nombre 
                from 	message_para par
                        inner join message_group gro on gro.id = par.grupo_id  
                where 	par.message_id = $messageHeaderId
                group by grupo_id,gro.nombre;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



    public function actionAcciones(){
        $periodoId = Yii::$app->user->identity->periodo_id;

        $messageHeaderId    = $_GET['message_header_id'];
        $tipoBusqueda       = $_GET['tipo_busqueda'];
        $word               = $_GET['word'];

        if($tipoBusqueda == 'group'){
            $groups = $this->search_groups($periodoId, $messageHeaderId, $word);
            return $groups;
        }else if($tipoBusqueda == 'grabar_grupo'){
            $groupId = $_GET['grupo_id'];
            $this->save_group($messageHeaderId, $groupId);
            return $this->redirect(['view', 'id' => $messageHeaderId]);
        }else if($tipoBusqueda == 'delete_group'){            
            $this->delete_para($messageHeaderId, $_GET['group_id']);
            return $this->redirect(['view', 'id' => $messageHeaderId]);
        }else if($tipoBusqueda == 'search_user'){
            $users = $this->searc_by_user($word, $messageHeaderId);
            return $users;
        }else if($tipoBusqueda == 'grabar_user'){
           $model = new MessagePara();
           $model->message_id   = $messageHeaderId;
           $model->para_usuario = $_GET['user_id'];
           $model->estado       = 'false';
           $model->fecha_recepcion = date('Y-m-d H:i:s');
           $model->save();
           return $this->redirect(['view', 'id' => $messageHeaderId]);
        }else if($tipoBusqueda == 'delete_para'){
            $paraId = $_GET['para_id'];
            $model = MessagePara::findOne($paraId);
            $model->delete();
            return $this->redirect(['view', 'id' => $messageHeaderId]);
        }else if($tipoBusqueda == 'enviar_para'){
            $this->send_message($messageHeaderId);
            return $this->redirect(['view', 'id' => $messageHeaderId]);
        }else if($tipoBusqueda == 'read'){
            
            $id = $_GET['id'];
            $model = MessagePara::findOne($id);
            $model->estado = 'recibido';
            $model->fecha_lectura = date('Y-m-d H:i:s');
            $model->save();
            
            return $this->redirect(['view','id' => $messageHeaderId]); 
        }else if($tipoBusqueda == 'search_student'){
            $groups = $this->search_students($periodoId, $messageHeaderId, $word);
            return $groups;
        }
                
    }

    private function send_message($messageHeaderId){
        $hoy = date('Y-m-d H:i:s');
        $con = Yii::$app->db;
        $query = "update message_para set estado = 'enviado', fecha_recepcion = '$hoy' where message_id = $messageHeaderId";

        $con->createCommand($query)->execute();
    }

    //buscar por usuario
    private function searc_by_user($name, $messageHeaderId){
        $con = Yii::$app->db;
        $query = "select 	rpa.name, usuario
        from 	usuario usu
                inner join res_users rus on rus.login = usu.usuario
                inner join res_partner rpa on rpa.id = rus.partner_id
        where 	rpa.name ilike '%$name%' or usu.usuario ilike '$name%'
                and usu.usuario not in (select para_usuario from message_para 
                                        where message_id = $messageHeaderId 
                                            and para_usuario = usu.usuario);";
                                            
        $res = $con->createCommand($query)->queryAll();
        $html = '';

        $html .= $this->renderPartial('_ajax-users',[
            'users'    => $res,
            'messageId' => $messageHeaderId
        ]);
        
        return $html;
    }


    //para eliminar los usuarios del grupo para
    private function delete_para($messageHeaderId, $groupId){
        $con = Yii::$app->db;
        $query = "delete from message_para where message_id = $messageHeaderId and grupo_id = $groupId";
        $con->createCommand($query)->execute();
    }

    //para grabar el grupo
    private function save_group($messageId, $groupId){

        $con = Yii::$app->db;
        $query = "insert into message_para (message_id, para_usuario, estado, fecha_recepcion, grupo_id) 
                            select 	$messageId, usuario
                            ,false 
                            ,current_timestamp 
                            ,message_group_id
                    from 	message_group_user
                    where 	message_group_id = $groupId;";

        $con->createCommand($query)->execute();
    }


    // para llenar seleccion de grupos
    private function search_groups($periodoId, $messageId, $word){
        $con = Yii::$app->db;
        $query = "select 	gro.id, gro.nombre  
                            from 	message_group gro
                            where 	gro.scholaris_periodo_id = $periodoId
                                    and gro.nombre ilike '$word%'
                                    and gro.id not in (select 	par.grupo_id 
                                            from 	message_para par
                                                    inner join message_header mh on mh.id = par.message_id 
                                            where 	par.grupo_id = gro.id and mh.id = $messageId);";
                                            
        $res = $con->createCommand($query)->queryAll();
        $html = '';

        $html .= $this->renderPartial('_ajax-grupos',[
            'groups'    => $res,
            'messageId' => $messageId
        ]);
        
        return $html;

    } 




    private function search_students($periodoId, $messageId, $word){
        $con = Yii::$app->db;
        $query = "select 	concat(os.last_name,' ', os.first_name,' ', middle_name) as student, os.x_institutional_email  
        from 	op_student os 
        where 	last_name ilike 'apolo arev%'
                or first_name ilike 'apolo arev%'
                or middle_name ilike 'apolo arev%'
                or x_institutional_email ilike 'apolo arev%'
                and os.x_institutional_email not in (select 	par.para_usuario  
                from 	message_para par
                        inner join message_header mh on mh.id = par.message_id 
                where 	mh.id = $messageId);";
                                            
        $res = $con->createCommand($query)->queryAll();
        $html = '';

        $html .= $this->renderPartial('_ajax-students',[
            'students'    => $res,
            'messageId' => $messageId
        ]);
        
        return $html;

    } 


    /***
     * Accion update
     */
    public function actionUpdate($id){
        $model = MessageHeader::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->texto           = $_POST['texto'];
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }


    /**
     * Accion de eliminar mensaje
     */
    public function actionEliminar($id){
        $model = MessageHeader::findOne($id);        
        $detalle = $this->delete_for($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    private function delete_for($id){
        $con = Yii::$app->db;
        $query = "delete from message_para where message_id = $id";
        $con->createCommand($query)->execute();
    }


    
    /***
     * Acciones de mensajes recibidos
     */
    public function actionReceived(){
        $userLog = Yii::$app->user->identity->usuario;

        $searchModel = new MessageParaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $userLog);

        $listaAsunto = $this->get_message($userLog);

        return $this->render('received', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listaAsunto' => $listaAsunto
        ]);
    }


    private function get_message($userLog){
        $con = Yii::$app->db;
        $query = "select 	mh.id 
                            ,mh.asunto 
                    from 	message_para par
                            inner join message_header mh on mh.id = par.message_id 
                            inner join res_users ru on ru.login = mh.remite_usuario 
                            inner join res_partner rpa on rpa.id = ru.partner_id 
                    where 	par.para_usuario = '$userLog' 
                    and par.estado in ('enviado','recibido')
                    order by par.fecha_recepcion desc;";
        $res = $con->createCommand($query)->queryAll();

        $lista = ArrayHelper::map($res,'id', 'asunto');
        return $lista;
    }

}
