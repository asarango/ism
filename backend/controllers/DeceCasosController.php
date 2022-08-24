<?php

namespace backend\controllers;

use Yii;
use backend\models\DeceCasos;
use backend\models\DeceCasosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeceCasosController implements the CRUD actions for DeceCasos model.
 */
class DeceCasosController extends Controller
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
     * Lists all DeceCasos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $estudiantes = $this->consulta_estudiantes($periodoId, $usuarioLog);
        //$casos = $this->mostrar_casos_por_usuario($usuarioLog);   
        $casos = $this->mostrar_casos_y_estadistica($usuarioLog);

        $searchModel = new DeceCasosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'estudiantes'=>$estudiantes,
            'casos'=>$casos
        ]);
    }
    private function mostrar_casos_y_estadistica($user)
    {
        $con = yii::$app->db;
        $periodoId = Yii::$app->user->identity->periodo_id;
        //extrae tdos los estudiantes asociados a un usuario de la tabla dece_casos
        $query ="select distinct id_estudiante from dece_casos dc where id_usuario  = '$user';";
        $usuariosCasos = $con->createCommand($query)->queryAll();
        
        $arrayCasos = array();
        foreach($usuariosCasos as $usuario)
        {
            $id_estudiante = $usuario['id_estudiante'];          
            
            
            $query2 = "select  concat(os.last_name,' ',os.middle_name,' ',os.first_name) nombre,dc.id_estudiante , 
                    (select count(*) from dece_casos dc2 where id_estudiante  = dc.id_estudiante ) casos,
                    (select count(*) from dece_registro_seguimiento drs where id_estudiante  = dc.id_estudiante ) seguimiento
                    from dece_casos dc, op_student os  
                    where id_estudiante =  $id_estudiante
                    and id_usuario = '$user'
                    and os.id = dc.id_estudiante 
                    group by os.last_name,os.middle_name,os.first_name,dc.id_estudiante ;                   
                    ";
                   
            $arrayCasos[]= $con->createCommand($query2)->queryOne();
        }
        return $arrayCasos;


    }
    private function mostrar_casos_por_usuario($usuario)
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelCasos = DeceCasos::find()       
        ->where(['id_usuario'=>$usuarioLog])
        ->andWhere(['id_periodo'=>$periodoId])
        ->all();
        return $modelCasos;
    }
    private function consulta_estudiantes($scholarisPeriodoId, $usuarioLog)
    {
        $con = Yii::$app->db;
        $query = "select 	s.id 
		,concat(s.last_name, ' ',s.first_name,' ',s.middle_name) as student
        from 	res_users u 
		inner join op_faculty f on f.partner_id = u.partner_id 
		inner join scholaris_clase c on c.idprofesor = f.id 
		--inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id 
		inner join op_student_inscription i on i.student_id = g.estudiante_id 
		inner join op_student s on s.id = i.student_id 
		inner join op_course_paralelo par on par.id = c.paralelo_id 
		inner join op_course cur on cur.id = par.course_id 
		inner join op_section sec on sec.id = cur.section
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id 
        where u.login = '$usuarioLog' 
		and sop.scholaris_id = $scholarisPeriodoId 
		order by s.last_name, s.first_name, s.middle_name ;";
     
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    /**
     * Displays a single DeceCasos model.
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
     * Creates a new DeceCasos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeceCasos();
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $ahora = date('Y-m-d H:i:s');
        $modelDeceCasos = new DeceCasos();      

        if ($model->load(Yii::$app->request->post()) && $model->save() ) 
        {
            if($model->id_clase>0)
            {
                return $this->redirect(['update','id'=>$model->id]);
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [           
            'model' => $modelDeceCasos
        ]);
    }   

    public function actionHistorico()
    {
       
        $model = new DeceCasos();
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $ahora = date('Y-m-d H:i:s');
        $modelDeceCasos = new DeceCasos();

        if(isset($_GET['id']))// POR get envia desde el leccionario, por tanto lleva id;s clase y estudiante
        {
            $id_estudiante = $_GET['id'];    
            $modelDeceCasos->numero_caso = $this->mostrar_numero_maximo_caso( $periodoId) + 1;
            $modelDeceCasos->id_estudiante =   $id_estudiante ;
            $modelDeceCasos->id_clase =   0 ;
            $modelDeceCasos->id_periodo =   $periodoId;
            $modelDeceCasos->estado = 'PENDIENTE';
            $modelDeceCasos->fecha_inicio = $ahora;
            $modelDeceCasos->motivo = "";
            $modelDeceCasos->detalle = "";
            $modelDeceCasos->id_usuario = $usuario; 
            
            $modelDeceCasos->save();
        }  
        return $this->render('historicos', [           
            'model' => $modelDeceCasos
        ]);           
    }
    private function mostrar_numero_maximo_caso($id_periodo)
    {
        $resp = DeceCasos::find()
        ->where(['id_periodo'=>$id_periodo])
        ->max('numero_caso');
        
        return $resp;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DeceCasos model.
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
     * Finds the DeceCasos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceCasos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceCasos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
