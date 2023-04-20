<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\DeceCasos;
use backend\models\DeceCasosSearch;
use backend\models\DeceDeteccion;
use backend\models\OpInstituteAuthorities;
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
        //buscamos si el usuario es coordinador
        $modelCoordinadorDece = OpInstituteAuthorities::find()
        ->where(['ilike','cargo_descripcion','dececoor'])
        ->andWhere(['usuario'=>$user])
        ->one();        

        $con = yii::$app->db;
        $periodoId = Yii::$app->user->identity->periodo_id;
        //extrae tdos los estudiantes asociados a un usuario de la tabla dece_casos
        if($modelCoordinadorDece)
        {
            $query ="select distinct id_estudiante from dece_casos dc where id_usuario_super_dece  = '$user';";
        }else
        {
            $query ="select distinct id_estudiante from dece_casos dc where id_usuario_dece  = '$user';";
        }
       
        $usuariosCasos = $con->createCommand($query)->queryAll();
        
        $arrayCasos = array();
        foreach($usuariosCasos as $usuario)
        {
            $id_estudiante = $usuario['id_estudiante'];     

            $query2 = "select  concat(os.last_name,' ',os.middle_name,' ',os.first_name) nombre,dc.id_estudiante , 
                        (select count(*) from dece_casos dc2 where id_estudiante  = dc.id_estudiante ) casos,
                        (select count(*) from dece_registro_seguimiento drs where id_estudiante  = dc.id_estudiante ) seguimiento,
                        (select count(distinct id_caso) from dece_registro_seguimiento drs where id_estudiante = dc.id_estudiante) casos_seguimiento,
                        (select count(*) from dece_derivacion d where id_estudiante  = dc.id_estudiante ) derivacion,
                        (select count(distinct id_casos) from dece_derivacion drs where id_estudiante = dc.id_estudiante) casos_derivacion,
                        (select count(*) from dece_deteccion d where id_estudiante  = dc.id_estudiante ) deteccion,
                        (select count(distinct id_caso) from dece_deteccion drs where id_estudiante = dc.id_estudiante) casos_deteccion,
                        (select count(*) from dece_intervencion d where id_estudiante  = dc.id_estudiante ) intervencion,
                        (select count(distinct id_caso) from dece_intervencion drs where id_estudiante = dc.id_estudiante) casos_intervencion
                        from dece_casos dc, op_student os  
                        where id_estudiante =  $id_estudiante";
                        if($modelCoordinadorDece )
                            {
                                $query2 .= " and id_usuario_super_dece = '$user'";
                            }else{
                                $query2 .= " and id_usuario_dece = '$user'";
                            }
                        
                            $query2 .= " and os.id = dc.id_estudiante 
                        group by os.last_name,os.middle_name,os.first_name,dc.id_estudiante ;";

        // echo '<pre>';
        // print_r($query2);
        // die();
                   
            $arrayCasos[]= $con->createCommand($query2)->queryOne();
        }

        // echo '<pre>';
        // print_r($arrayCasos);
        // die();
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
        "select  distinct c4.id,concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as student,
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

        // echo '<pre>';
        // print_r($query);
        // die();


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
        union all
        select count(*) as conteo5
        from dece_casos d1, dece_intervencion r1 
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
        //la fecha de ingreso viene vacio cuando es un nuevo registro, por eso guarda solo cuando hay fecha de ingreso
        //quiere decir que vino desde la pantalla de de CREACION
        if ($model->load(Yii::$app->request->post()) && isset($_POST['fecha_inicio']))
        {
            $decesPorAlumno = $this->mostrar_dece_y_super_dece_por_alumno($model->id_estudiante);

            // echo '<pre>';
            // print_r($decesPorAlumno);
            // die();

            $model->id_usuario_dece = $decesPorAlumno['hdi_dece'];
            $model->id_usuario_super_dece = $decesPorAlumno['super_dece'];
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
    private function mostrar_dece_y_super_dece_por_alumno($id_estudiante)
    {
        $con = Yii::$app->db;
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $query = "select  distinct c4.id,concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as student,
                    concat( c8.name,' ', c7.name ) curso, 
                    (select usuario from op_institute_authorities a where a.id=c1.coordinador_dece_id) super_dece,
                    (select usuario from op_institute_authorities a where a.id=c1.dece_dhi_id) hdi_dece
                    from scholaris_clase c1 , scholaris_grupo_alumno_clase c2 ,
                    op_institute_authorities c3 ,op_student c4 ,op_student_inscription c5, 
                    scholaris_op_period_periodo_scholaris c6,op_course_paralelo c7, op_course c8
                    where c3.usuario  = '$usuario' 
                    and c3.id = c1.dece_dhi_id 
                    and c1.id = c2.clase_id 
                    and c2.estudiante_id = c4.id 
                    and c4.id = c5.student_id 
                    and c5.period_id  = c6.op_id 
                    and c6.scholaris_id = '1'
                    and c7.id = c1.paralelo_id 
                    and c8.id = c7.course_id 
                    and c4.id = '$id_estudiante'
                    order by student;";
        $resp = $con->createCommand($query)->queryOne();
        return $resp;

    }
    
    public function actionCrearDeteccion()
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $fecha = date('Y-m-d H:i:s');

        $idEstudiante = $_GET['id'];  
        $idClase = $_GET['id_clase'];

        /*** PROCESO 1*/
        //Buscar si tiene un caso
        $modelDeceCasos = DeceCasos::find()
        ->where(['id_estudiante'=>$idEstudiante])
        ->max('numero_caso');

        $consecutivoCaso = 1;

        if($modelDeceCasos)
        {
            // si tiene caso, conseguir el consecutivo mas uno
            $consecutivoCaso = $modelDeceCasos + 1;
        }
        // Crear caso, mas un consecutivo
            //PROCESO 1.-
            $modelDeceCasos = new DeceCasos();
            $modelDeceCasos->numero_caso = $consecutivoCaso;
            $modelDeceCasos->id_estudiante = $idEstudiante;
            $modelDeceCasos->id_periodo = $periodoId;
            $modelDeceCasos->estado = 'PENDIENTE';
            $modelDeceCasos->fecha_inicio =  $fecha;
            $modelDeceCasos->motivo =  'DISCIPLINARIO';
            $modelDeceCasos->detalle =  '-';
            $modelDeceCasos->id_usuario =  $usuarioLog;
            $modelDeceCasos->id_clase = $idClase;
            $modelDeceCasos->save();
      

            return $this->redirect(['/dece-deteccion/create',
                'id_estudiante'=>$modelDeceCasos->id_estudiante,
                'id_caso'=>$modelDeceCasos->id,
                'es_lexionario'=>true]);            

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
