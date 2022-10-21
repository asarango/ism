<?php

namespace backend\controllers;

use Yii;
use backend\models\DeceCasos;
use backend\models\DeceCasosSearch;
use Exception;
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
        $conteoEjesDeAccion = $this->consulta_conteo_por_eje($usuarioLog);
        //$casos = $this->mostrar_casos_por_usuario($usuarioLog);   
        $casos = $this->mostrar_casos_y_estadistica($usuarioLog);

        $searchModel = new DeceCasosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'estudiantes'=>$estudiantes,
            'casos'=>$casos,
            'conteoEjesDeAccion'=> $conteoEjesDeAccion
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
                    (select count(*) from dece_registro_seguimiento drs where id_estudiante  = dc.id_estudiante ) seguimiento,
                    (select count(*) from dece_derivacion d where id_estudiante  = dc.id_estudiante ) derivacion,
                    (select count(*) from dece_deteccion d where id_estudiante  = dc.id_estudiante ) deteccion
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
        $query = 
        "select  c4.id,concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as student,
        concat( c8.name,' ', c7.name ) curso
        from scholaris_clase c1 , scholaris_grupo_alumno_clase c2 ,
         op_institute_authorities c3 ,op_student c4 ,op_student_inscription c5, 
         scholaris_op_period_periodo_scholaris c6,op_course_paralelo c7, op_course c8
        where c3.usuario  = '$usuarioLog' 
        and c3.id = c1.dece_dhi_id 
        and c1.id = c2.clase_id 
        and c2.estudiante_id = c4.id 
        and c4.id = c5.student_id 
        and c5.period_id  = c6.op_id 
        and c6.scholaris_id = '$scholarisPeriodoId'
        and c7.id = c1.paralelo_id 
        and c8.id = c7.course_id 
        order by student;";

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }
    private function consulta_conteo_por_eje($usuarioLog)
    {
        $con = Yii::$app->db;
        $query = 
        "select count(*) as conteo1
        from dece_casos d1
        where d1.id_usuario = '$usuarioLog'
        union all
        select count(*) as conteo2
        from dece_casos d1, dece_registro_seguimiento   r1 
        where d1.id_usuario = '$usuarioLog'
        and r1.id_caso = d1.id 
        union all
        select count(*) as conteo3
        from dece_casos d1, dece_derivacion r1 
        where d1.id_usuario = '$usuarioLog'
        and r1.id_casos = d1.id 
        union all
        select count(*) as conteo4
        from dece_casos d1, dece_deteccion r1 
        where d1.id_usuario = '$usuarioLog'
        and r1.id_caso = d1.id 
        ;";        

        $res = $con->createCommand($query)->queryColumn();
        
      

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
    public function actionCreate($idEstudiante)
    {
        $model = new DeceCasos();
        $hora = date('H:i:s');
        //la fecha de ingreso viene vacio cuando es un nuevo, por eso guarda solo cuando hay fecha de ingreso
        //quiere decir que vino desde la pantalla de de CREACION
        if ($model->load(Yii::$app->request->post()) && isset($_POST['fecha_inicio']))
        {
            $model->fecha_inicio = $_POST['fecha_inicio']. ' '.$hora;
            $model->save();            
            return $this->redirect(['historico','id'=>$model->id_estudiante]);
        }
 
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $ahora = date('Y-m-d H:i:s');
        $ahora = date('Y-m-d');

        $modelDeceCasos = new DeceCasos(); 
        $modelDeceCasos->id_usuario = $usuario;
        $modelDeceCasos->detalle = '-';
        if($idEstudiante==0)//si es igual a cero, es porque biene de INDEX, CREAR CASOS
        {
            $modelDeceCasos->id_estudiante = $_POST['idAlumno'];
        }else
        {
            $modelDeceCasos->id_estudiante = $idEstudiante;
        }        
        $modelDeceCasos->id_periodo = $periodoId;
        $modelDeceCasos->id_clase= 0;
        $modelDeceCasos->fecha_inicio= $ahora;
        $modelDeceCasos->numero_caso = $this->mostrar_numero_maximo_caso($periodoId,$idEstudiante) + 1;
       

        return $this->render('create', [           
            'model' => $modelDeceCasos
        ]);
    }   

    public function actionHistorico()
    {
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $ahora = date('Y-m-d H:i:s');
        $modelDeceCasos = new DeceCasos();

        if(isset($_GET['id']))
        {
            $id_estudiante = $_GET['id'];    
            $modelDeceCasos->numero_caso = $this->mostrar_numero_maximo_caso($periodoId,$id_estudiante) + 1;
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
    private function mostrar_numero_maximo_caso($id_periodo,$idEstudiante)
    {
        $resp = DeceCasos::find()
        ->where(['id_periodo'=>$id_periodo])
        ->andWhere(['id_estudiante'=>$idEstudiante])
        ->max('numero_caso');
        
        return $resp;
    }

    public function actionUpdate($id)
    {
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');
        $model = $this->findModel($id);           

        if ($model->load(Yii::$app->request->post()) &&  isset($_POST['fecha_fin']))
        { 
            $model->fecha_fin = $_POST['fecha_fin'].' '.$hora;  
            $model->save();
            return $this->redirect(['historico','id'=>$model->id_estudiante]);
        }

         //se asigna la fecha de creacion del seguimiento con la fecha de modificacion, para cargar en pantalla
         if($model)
         {
             $model->fecha_fin = $fechaActual;
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
