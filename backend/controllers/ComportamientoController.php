<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\ScholarisClase;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisAsistenciaProfesor;
use backend\models\ScholarisAsistenciaComportamiento;
use backend\models\ScholarisAsistenciaComportamientoDetalle;
use backend\models\PlanificacionOpciones;
use backend\models\ScholarisAsistenciaAlumnosNovedades;
use backend\models\ScholarisActividad;
use backend\models\ScholarisAsistenciaClaseTema;
use backend\models\helpers\Scripts;
use backend\models\ScholarisAsistenciaComportamientoSearch;

/**
 * ScholarisAsistenciaProfesorController implements the CRUD actions for ScholarisAsistenciaProfesor model.
 */
class ComportamientoController extends Controller {

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

   
    public function actionIndex($id) {

        $con = Yii::$app->db;
        $fechaDesde = date("Y-m-d").' 00:00:00';
        $fechaHasta = date("Y-m-d").' 23:59:59';       
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        
        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $id])
                ->one();
        //las novedades especificas son las que del codigo 1a,1b,1c , las que se muestran en pantalla
        $listaNovedadesEspecificas = $this->consulta_novedades_especifica($modelAsistencia->id);
        $listaNovedadesTodas= $this->consulta_novedades_todas($modelAsistencia->id);

        $modelClase = ScholarisClase::find()->where(['id' => $modelAsistencia->clase_id])->one();
               
        $query = "select sgac.id,sgac.clase_id,sgac.estudiante_id,ltrim(rtrim(os.last_name)) as last_name,
        ltrim(rtrim(os.first_name)) as first_name ,ltrim(rtrim(os.middle_name )) as middle_name
                         ,i.student_state
                         ,i.transfer_from_id  
                         ,os.x_origin_institute
                from	op_student os
                                inner join scholaris_grupo_alumno_clase sgac on os.id = sgac.estudiante_id
                                inner join op_student_inscription i on i.student_id = sgac.estudiante_id
                                inner join op_course_paralelo par on par.id = i.parallel_id
                                inner join op_course cur on cur.id = par.course_id
                                inner join op_section sec on sec.id = cur.section
                                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id 
                where 	sgac.clase_id = $modelAsistencia->clase_id
                                and sop.scholaris_id = $periodoId order by last_name ;;";
    

        $modelGrupo = $con->createCommand($query)->queryAll();        

        $modelActividades = ScholarisActividad::find()
                ->where([
                          'paralelo_id' => $modelClase->id,
                          //'inicio' => date("Y-m-d")
                        ])
                ->andWhere(['between', 'inicio', $fechaDesde, $fechaHasta])
                ->all();
             
        //buscamos alumnos con NEE, de la clase
        $objScript = new Scripts();
        $modelNeeXClase = $objScript->mostrarAlumnosNeeClase($modelClase->id);
        
        $modelTemas = ScholarisAsistenciaClaseTema::find()
                ->where(['asistencia_profesor_id' => $id])
                ->all();        

        return $this->render('index', [
                    'modelClase' => $modelClase,
                    'modelGrupo' => $modelGrupo,
                    'modelAsistencia' => $modelAsistencia,
                    'modelActividades' => $modelActividades,
                    'modelTemas' => $modelTemas,
                    'modelNeeXClase'=>$modelNeeXClase,
                    'listaNovedadesEspecificas'=>$listaNovedadesEspecificas, 
                    'listaNovedadesTodas'=>$listaNovedadesTodas,
        ]);
    }

    public function actionDetalle($alumnoId, $asistenciaId) 
    {       


        $model = new ScholarisAsistenciaAlumnosNovedades();
        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $asistenciaId])
                ->one();
        
        $modelGrupo = ScholarisGrupoAlumnoClase::find()
                ->where([
                    'clase_id' => $modelAsistencia->clase_id,
                    'estudiante_id' => $alumnoId
                ])
                ->one();

        $modelComportamientos = \backend\models\ScholarisAsistenciaComportamiento::find()->orderBy("id")->all();
        $modelCompDetalle = \backend\models\ScholarisAsistenciaComportamientoDetalle::find()
                ->where(['activo' => true])
                ->orderBy("id")->all();

        $modelNovedades = ScholarisAsistenciaAlumnosNovedades::find()
                ->where([
                    'asistencia_profesor_id' => $asistenciaId,
                    'grupo_id' => $modelGrupo->id
                ])
                ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['detalle', 'alumnoId' => $modelGrupo->estudiante_id, 'asistenciaId' => $asistenciaId]);
        }
                       

        return $this->render('detalle', [
                    'model' => $model,
                    'modelNovedades' => $modelNovedades,
                    'modelAsistencia' => $modelAsistencia,
                    'modelComportamientos' => $modelComportamientos,
                    'modelCompDetalle' => $modelCompDetalle,
                    'modelGrupo' => $modelGrupo,
        ]);
    }

    public function actionAsignar($asistenciaId, $detalleId, $grupoId) {
        $model = new ScholarisAsistenciaAlumnosNovedades();
        
        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $asistenciaId])
                ->one();
        
        $modelGrupo = ScholarisGrupoAlumnoClase::find()
                ->where(['id' => $grupoId])
                ->one();
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['detalle', 'alumnoId' => $modelGrupo->estudiante_id, 'asistenciaId' => $asistenciaId]);
        }

        return $this->render('asignar', [
                    'model' => $model,
                    'modelAsistencia' => $modelAsistencia,
                    'modelGrupo' => $modelGrupo,
                    'detalleId' => $detalleId,
        ]);
    }
    
    
    public function actionNuevotema($asistenciaId){
        $model = new ScholarisAsistenciaClaseTema();
        
        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $asistenciaId])
                ->one();
        
        
        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index', 'id' => $asistenciaId]);
        }else{
            return $this->render('nuevotema',[
                'model' => $model,
                'modelAsistencia' => $modelAsistencia
            ]);
        }
        
    }

    
    /**
     * Elimina la novedad del estudiante con respecto a las novedades cometidas
     */
    public function actionQuitar($novedadId){
        
        $model = ScholarisAsistenciaAlumnosNovedades::find()->where(['id' => $novedadId])->one();        
        $alumnoId = $model->grupo->estudiante_id;
        $model->delete();
        return $this->redirect([
            'detalle',
            'alumnoId' => $alumnoId,
            'asistenciaId' => $model->asistencia_profesor_id
        ]);
    }    

    /**
     * Elimina el tema tratado en clase
     */
    public function actionQuitartema($id){
        $model = ScholarisAsistenciaClaseTema::find()->where(['id' => $id])->one();
        $asistenciaId = $model->asistencia_profesor_id;
        
        $model->delete();

        return $this->redirect([
            'index',
            'id' => $asistenciaId
        ]);        
    }

    /**
     * Genera falta automatica, con codigo 1CH, Falta injustificada hora clase 
     */
    public function consulta_novedades_especifica($asistenciaId)
    {
        $con = yii::$app->db;
        $query = "select a1.id,a1.asistencia_profesor_id,a1.comportamiento_detalle_id,a1.observacion ,a1.grupo_id  
        from scholaris_asistencia_alumnos_novedades a1,
        scholaris_asistencia_comportamiento_detalle a2
        where a1.comportamiento_detalle_id = a2.id 
        and a2.codigo  in ('1a','1b','1c','1d')
        and a1.observacion ilike 'AUTO:%'
        and a1.asistencia_profesor_id = $asistenciaId;";
        

        $listaNovedades = $con->createCommand($query)->queryAll();
        return $listaNovedades;
    }
    public function consulta_novedades_todas($asistenciaId)
    {
        $con = yii::$app->db;
        $query = "select a1.id,a1.asistencia_profesor_id,a1.comportamiento_detalle_id,a1.observacion ,a1.grupo_id  
        from scholaris_asistencia_alumnos_novedades a1,
        scholaris_asistencia_comportamiento_detalle a2
        where a1.comportamiento_detalle_id = a2.id     
        and a1.asistencia_profesor_id = $asistenciaId;";
        $listaNovedades = $con->createCommand($query)->queryAll();
        return $listaNovedades;
    }
    public function delete_novedades($idNovedad)
    {
        $con = yii::$app->db;
        $query = "delete from scholaris_asistencia_alumnos_novedades where id =$idNovedad ";
        $con->createCommand($query)->queryAll();
    }
    public function delete_novedades_especificas($asistenciaId,$alumnoId)
    {
        //extraccion id de grupo 
       $modelAsistencia = ScholarisAsistenciaProfesor::find()
       ->where(['id' => $asistenciaId])
       ->one();     

        $modelGrupo = ScholarisGrupoAlumnoClase::find()
            ->where([
                'estudiante_id' => $alumnoId,
                'clase_id'=>$modelAsistencia->clase_id
                ])
            ->one(); 

        $con = yii::$app->db;
        $query = "delete from scholaris_asistencia_alumnos_novedades 
        where comportamiento_detalle_id  in ( select id from scholaris_asistencia_comportamiento_detalle where codigo in ('1a','1b','1c','1d') )
        and grupo_id = '$modelGrupo->id' and asistencia_profesor_id = '$asistenciaId';";

      
        $con->createCommand($query)->queryAll();
    }
   
    public function actionFaltaAutoEstudiante()
    {   
        /*
        Creado Por: Santiago / Fecha Creacion: 
        Modificado Por: Santiago	/ Fecha Modificación: 2013-03-15
        Detalle:  Asigna un codigo de los especificados en pantalla, y elimna los demas, de la lista de codigos especificos 1a,1b,1c,1d
        */

       $asistenciaId = $_GET['idClase'];
       $alumnoId = $_GET['idAlumno'];
       $codigoNovedad = $_GET['codigoNovedad'];      
       $obsFalta ='';  
       
       //1.- buscamos si existe algun ingreso automatico con los codigos 1a,1b,1c,1d 
       $listaNovedades = $this->consulta_novedades_especifica($asistenciaId);         

        
        //1.2 extraemos el detalle de codigo que se ingreso en pantalla
        $modelCodNovedad = ScholarisAsistenciaComportamientoDetalle::find()
        ->where(['codigo'=>$codigoNovedad])
        ->one();

        //extraccion id de grupo 
       $modelAsistencia = ScholarisAsistenciaProfesor::find()
       ->where(['id' => $asistenciaId])
       ->one();     

        $modelGrupo = ScholarisGrupoAlumnoClase::find()
            ->where([
                'estudiante_id' => $alumnoId,
                'clase_id'=>$modelAsistencia->clase_id
                ])
            ->one(); 
        
        //1.1.- eliminamos los codigos si existen con 1a,1b,1c,1d, que pertenezcan al grupo del niño
        foreach($listaNovedades as $novedad)
        {
            if($novedad['grupo_id']==$modelGrupo->id)
            {
                $idNovedad = $novedad['id'];
                $this->delete_novedades($idNovedad);   
            }                    
        }

       //2.- ingresamos el nuevo codigo enviado       
       $modelNovedad = new ScholarisAsistenciaAlumnosNovedades();
       $modelNovedad->asistencia_profesor_id=$asistenciaId;
       $modelNovedad->comportamiento_detalle_id=$modelCodNovedad->id;
       $modelNovedad->observacion = 'AUTO: '.$modelCodNovedad->nombre;
       $modelNovedad->grupo_id = $modelGrupo->id;
       $modelNovedad->save();  
      
    }

    public function actionBorrarFaltaAutoEstudiante()
    {      
        /*
        Creado Por: Santiago / Fecha Creacion: 
        Modificado Por: Santiago	/ Fecha Modificación: 2023-03-15
        Detalle: Elimina todas las novedades de un estudiante  de los codigos especiales en la pantalla, codigos 1a,1b,1c,1d
        */ 
        $asistenciaId = $_GET['idClase'];
        $alumnoId = $_GET['idAlumno'];
        $codigoNovedad = $_GET['codigoNovedad'];    

        // echo '<pre>';
        // print_r($_GET);
        // die();

       //1. eliminamos los codigos si existen con 1a,1b,1c,1d
        $this->delete_novedades_especificas($asistenciaId,$alumnoId);  
       
     }





}
