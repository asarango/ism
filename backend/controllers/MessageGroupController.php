<?php

namespace backend\controllers;

use Yii;
use backend\models\MessageGroup;
use backend\models\MessageGroupSearch;
use backend\models\MessageGroupUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessageGroupController implements the CRUD actions for MessageGroup model.
 */
class MessageGroupController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all MessageGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new  MessageGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MessageGroup model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $searchModel    = new MessageGroupUserSearch();
        $dataProvider   = $searchModel->search(Yii::$app->request->queryParams, $id);

        $model = $this->findModel($id);

        $this->inserta_integrantes($model);

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    private function inserta_integrantes($model){
        
        $sourceId       = $model->source_id;
        $sourceTable    = $model->source_table;
        $tipo           = $model->tipo;

        if($tipo == 'PADRES'){
            $tipoRol = 'Padre';
        }else{
            $tipoRol = 'Profesor';
        }    

        if($sourceTable == 'CURSO' && $tipoRol == 'Padre'){
            $this->script_insercion_curso_padres($sourceId, $tipoRol, $model->id);
        }else if($sourceTable == 'CURSO' && $tipoRol == 'Profesor'){            
            $this->script_insercion_curso_docentes($sourceId, $model->id);
        }    

    }

    public function script_insercion_curso_padres($sourceId, $tipoRol, $groupId){
        $con = Yii::$app->db;
        $query = "insert into message_group_user (message_group_id, usuario)
        select 	$groupId, usu.usuario 	
        from 	op_student_inscription ins
                inner join op_course_paralelo par on par.id = ins.parallel_id 
                inner join op_parent_op_student_rel rel on rel.op_student_id = ins.student_id 
                inner join op_parent op on op.id = rel.op_parent_id 
                inner join res_users ru on ru.partner_id = op.name
                inner join usuario usu on usu.usuario = ru.login 
                inner join rol on rol.id = usu.rol_id 
        where 	par.course_id = $sourceId
                and ins.inscription_state = 'M'
                and rol.rol = '$tipoRol'
                and usu.usuario not in (select 	mu.usuario  
                                from 	message_group_user mu
                                        inner join message_group mg on mg.id = mu.message_group_id
                                where 	mg.id = $groupId); ";

        $con->createCommand($query)->execute();
    }


    private function script_insercion_curso_docentes($sourceId, $groupId){
        $con = Yii::$app->db;
        $query = "insert into message_group_user (message_group_id, usuario)
                            select 	$groupId, usu.usuario 
                    from	scholaris_clase cla
                            inner join op_course_paralelo par on par.id = cla.paralelo_id 
                            inner join op_faculty fac on fac.id = cla.idprofesor 
                            inner join res_users ru on ru.partner_id = fac.partner_id 
                            inner join usuario usu on usu.usuario = ru.login 
                    where	par.course_id = $sourceId
                            and usu.usuario not in (select 	mu.usuario  
                                            from 	message_group_user mu
                                                    inner join message_group mg on mg.id = mu.message_group_id
                                            where 	mg.id = $groupId) 
                    group by usu.usuario;";

        $con->createCommand($query)->execute();
    }

    /**
     * Creates a new MessageGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $tipo = $_GET['tipo'];
        
        $periodoId      = Yii::$app->user->identity->periodo_id;
        $institutoId    = Yii::$app->user->identity->instituto_defecto;
        $model          = new MessageGroup();


        if ($model->load(Yii::$app->request->post())) {

            $sourceId = $_POST['source_id'];
            $model->source_id = $sourceId;
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $cursos = $this->get_cursos($periodoId, $institutoId);


        return $this->render('create', [
            'model'     => $model,
            'periodoId' => $periodoId,
            'tipo'      => $tipo,
            'cursos'    => $cursos            
        ]);
    }


    private function get_cursos($periodoId, $institutoId){
        $con    = Yii::$app->db;
        $query  = "select 	oc.id 
                            ,oc.name as nombre
                    from 	op_course oc 
                            inner join op_section sec on sec.id = oc.section 
                            inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id
                    where 	sop.scholaris_id = $periodoId
                            and oc.x_institute = $institutoId
                    order by oc.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Updates an existing MessageGroup model.
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
     * Deletes an existing MessageGroup model.
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
     * Finds the MessageGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MessageGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MessageGroup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
