<?php

namespace backend\controllers;

use backend\models\helpers\HelperGeneral;
use backend\models\Nee;
use backend\models\NeeDetalle;
use backend\models\NeeXClase;
use backend\models\NeeXOpcion; 
use backend\models\OpInstitute;
use backend\models\services\WebServicesUrls;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class NeeController extends Controller
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

    public function actionIndex()
    {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $estudiantes = $this->consulta_estudiantes($periodoId, $usuarioLog);
        $nees = $this->consulta_nee($periodoId, $usuarioLog);

        return $this->render('index', [
            'estudiantes' => $estudiantes,
            'nee' => $nees
        ]);
    }

    private function consulta_estudiantes($scholarisPeriodoId, $usuarioLog)
    {
        $con = Yii::$app->db;

        $query = "select  distinct c4.id,concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as student,
                    concat( c8.name,' ', c7.name ) curso 
                    ,c7.name as paralelo
                    from scholaris_clase c1 , scholaris_grupo_alumno_clase c2 ,
                        op_institute_authorities c3 ,op_student c4 ,op_student_inscription c5, 
                        scholaris_op_period_periodo_scholaris c6,op_course_paralelo c7, op_course c8
                    where c3.usuario  = '$usuarioLog' 
                            and c3.id = c1.dece_dhi_id 
                            and c1.id = c2.clase_id 
                            and c2.estudiante_id = c4.id 
                            and c4.id = c5.student_id 
                            and c5.period_id  = c6.op_id 
                            and c6.scholaris_id = $scholarisPeriodoId
                            and c7.id = c1.paralelo_id 
                            and c8.id = c7.course_id 
                    order by student;";
        
    //    echo $query;
    //    die();
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    private function consulta_nee($scholarisPeriodoId, $usuarioLog)
    {
        $con = Yii::$app->db;
        $query = "select  distinct nee.id,concat(c4.last_name, ' ',c4.first_name,' ',c4.middle_name) as student,
                            concat( c8.name,' ', c7.name ) curso,
                            c7.name as paralelo
                    from 	scholaris_clase c1 
                            ,scholaris_grupo_alumno_clase c2 
                            ,op_institute_authorities c3 
                            ,op_student c4 
                            ,op_student_inscription c5
                            ,scholaris_op_period_periodo_scholaris c6
                            ,op_course_paralelo c7
                            ,op_course c8
                            ,nee 
                    where 	c3.usuario  = '$usuarioLog' 
                            and c3.id = c1.dece_dhi_id 
                            and c1.id = c2.clase_id 
                            and c2.estudiante_id = c4.id 
                            and c4.id = c5.student_id 
                            and c5.period_id  = c6.op_id 
                            and c6.scholaris_id = $scholarisPeriodoId
                            and c7.id = c1.paralelo_id 
                            and c8.id = c7.course_id
                            and nee.student_id = c5.student_id
                    order by student;";
        // echo $query;
        // die();
        $res = $con->createCommand($query)->queryAll();
        
    //    echo $query;
    //    die();

        return $res;
    }

    public function actionCreate()
    {

        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $ahora = date('Y-m-d H:i:s');
        $studentId = $_POST['id'];

        $nee = Nee::find()->where(['student_id' => $studentId])->one();

        if (!$nee) {
            $model = new Nee();
            $model->student_id = $studentId;
            $model->scholaris_periodo_id = $periodoId;
            $model->created_at = $ahora;
            $model->created = $usuario;
            $model->save();
        }

        $modelNee =  Nee::find()->where(['student_id' => $studentId])->one();
        return $this->redirect(['ficha', 'nee_id' => $modelNee->id, 'pestana' => 'datos_estudiante']);
    }

    public function actionFicha()
    {

        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $periodoId = Yii::$app->user->identity->periodo_id;

        $neeId = $_GET['nee_id'];

        if(!isset($_GET['pestana'])){
            $pestana = 'datos_estudiante';
        }else{
            $pestana = $_GET['pestana'];
        }


        $model = Nee::findOne($neeId);
        // inicio info obtenida desde el web service
        $odooService = new WebServicesUrls('odoo');
        $dataJson = $odooService->consumir_servicio($odooService->url.'/nee/'.$model->student_id);
        $student = json_decode($dataJson);

        				
        //fin obtenida desde el web service

        //Obteniendo las edades
		$ageFamily = array();
        $helper = new HelperGeneral();
        $birthDate = $student->data_student[0]->birth_date;
        $edad = $helper->calcular_edad($birthDate);

        $ageStudent =  "{$edad->format('%Y')} años y {$edad->format('%m')} meses"; // Aplicamos un formato al objeto resultante de la funcion	
		$ageFamily['student'] = $ageStudent;
		
		if($student->data_parents[0]->x_state == 'madre'){
			$edadMama = $helper->calcular_edad($student->data_parents[0]->x_birth_date);
			$ageMom	 = "{$edadMama->format('%Y')} años y {$edadMama->format('%m')} meses"; 
			$ageFamily['madre'] =  $ageMom;
		}
		
		if($student->data_parents[0]->x_state == 'padre'){
			$edadPapa = $helper->calcular_edad($student->data_parents[0]->x_birth_date);
			$ageDad	 = "{$edadPapa->format('%Y')} años y {$edadPapa->format('%m')} meses"; 
			$ageFamily['padre'] =  $ageDad;
		}elseif(isset($student->data_parents[1]->x_state) == 'padre'){
			$edadPapa = $helper->calcular_edad($student->data_parents[1]->x_birth_date);
			$ageDad	 = "{$edadPapa->format('%Y')} años y {$edadPapa->format('%m')} meses"; 
			$ageFamily['padre'] =  $ageDad;
		}
		       
        //FInal de la edadedade

        $instituto      = OpInstitute::findOne($institutoId);
        $materiasSelect = $this->consulta_materias_estudiante($model->student_id, $periodoId);
        $materiasNee    = NeeXClase::find()->where(['nee_id' => $neeId])->all();
        
        $this->ingresa_opciones($neeId); //Ingresa las opciones en vacio
        // $opciones5 = $this->consulta_seccion_5($neeId);
        // $opciones6 = $this->consulta_seccion_6($neeId);

        $detalle = NeeDetalle::find()->where(['nee_id' => $neeId])->all();

        $historial = Nee::find()->where([
            '<>', 'scholaris_periodo_id', $periodoId
        ])->all();

         return $this->render('ficha', [
             'model' => $model,
             'student' => $student,
             'age_family' => $ageFamily,
             'instituto' => $instituto,
             'materiasSelect' => $materiasSelect,
             'materiasNee' => $materiasNee,
             'detalle' => $detalle,
            //  'opciones5' => $opciones5,
            //  'opciones6' => $opciones6,
             'historial' => $historial,
             'pestana' => $pestana             
         ]);
    }

    private function consulta_materias_estudiante($studentId, $periodoId){
        $con = Yii::$app->db;
        // $query = "select 	c.id as clase_id ,m.nombre as materia 
        //             from 	scholaris_grupo_alumno_clase g 
        //                     inner join scholaris_clase c on c.id = g.clase_id  
        //                     inner join ism_area_materia am on am.id = c.ism_area_materia_id 
        //                     inner join ism_materia m on m.id = am.materia_id  
        //                     inner join ism_malla_area ma on ma.id = am.malla_area_id 
        //                     inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id
        //             where 	g.estudiante_id = $studentId 
        //                     and pm.scholaris_periodo_id = $periodoId 
        //                     and c.id not in ( select clase_id 
		// 					from nee_x_clase x
		// 							inner join nee on nee.id = x.nee_id 
		// 					where clase_id = c.id 
		// 					and fecha_finaliza is null 
		// 					and nee.student_id = g.estudiante_id ) 
        //             order by m.nombre;";
        $query = "select 	c.id as clase_id ,m.nombre as materia 
                            ,(
                                select 	nee.grado 
                                from 	nee_x_clase nxc
                                        inner join nee on nee.id = nxc.nee_id 
                                where 	nee.student_id = $studentId
                                        and nee.scholaris_periodo_id = $periodoId
                                        and nxc.clase_id = g.clase_id 
                            )
                    from 	scholaris_grupo_alumno_clase g 
                            inner join scholaris_clase c on c.id = g.clase_id 
                            inner join ism_area_materia am on am.id = c.ism_area_materia_id 
                            inner join ism_materia m on m.id = am.materia_id 
                            inner join ism_malla_area ma on ma.id = am.malla_area_id 
                    inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                    where g.estudiante_id = $studentId 
                        and pm.scholaris_periodo_id = $periodoId 
                    order by m.nombre;";          
                    
            // echo $query;
            // die();

            $res = $con->createCommand($query)->queryAll();
            return $res;
    }

    private function ingresa_opciones($neeId) {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $con = Yii::$app->db;
        $query = "insert into nee_detalle (nee_id, opcion_codigo, categoria, contenido, es_seleccionado, created_at, created, updated_at, updated)
                    select 	$neeId
                            , op.codigo, op.categoria, op.nombre, false, current_timestamp, '$usuarioLog', current_timestamp, '$usuarioLog'
                    from 	nee_opciones op
                    where 	op.codigo not in (select 	opcion_codigo 
                                                from 	nee_detalle
                                                where 	nee_id = $neeId
                                                        and opcion_codigo = op.codigo) 
                            and op.estado = true
                    order by op.categoria, op.orden;";
        $con->createCommand($query)->execute();
    }


    public function consulta_seccion_5($neeId){
        $con = Yii::$app->db;
        $query = "select 	xo.id 
                        ,o.codigo 
                        ,o.seccion 
                        ,o.orden 
                        ,o.nombre 
                        ,o.estado 
                        ,xo.contenido 
                from 	nee_x_opcion xo
                        inner join nee_opciones o on o.id = xo.opcion_id 
                where 	xo.nee_id = $neeId
                        and o.seccion = 5
                order by o.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function consulta_seccion_6($neeId){
        $con = Yii::$app->db;
        $query = "select 	xo.id 
                            ,o.codigo 
                            ,o.seccion 
                            ,o.orden 
                            ,o.nombre 
                            ,o.estado 
                            ,xo.es_seleccionado 
                    from 	nee_x_opcion xo
                            inner join nee_opciones o on o.id = xo.opcion_id 
                    where 	xo.nee_id = $neeId
                            and o.seccion in (61,62)
                    order by o.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }





    public function actionInsertClass(){

        $neeId      = $_POST["nee_id"];
        $claseId    = $_POST["clase_id"];
        
        $nee = Nee::findOne($neeId);

        $grado      = 1;
        $diagnostico_inicia = 'Aquí su diagnóstico';
        $fechaHoy   = date("Y-m-d");        

        $model = new NeeXClase();
        $model->nee_id = $neeId;
        $model->clase_id = $claseId;
        $model->grado_nee = $nee->grado;
        $model->fecha_inicia = $nee->fecha_diagnostico;
        $model->diagnostico_inicia = $nee->diagnostico;
        $model->save();

        return $this->redirect(['ficha', 
            'nee_id' => $neeId,
            'pestana' => 'fecha_elab'
        ]);

    }

    public function actionUpdateClass(){
        $id         = $_POST['id'];
        $gradoNee   = $_POST['grado_nee'];
        $diagInicia = $_POST['diagnostico_inicia'];
        $fechaFinal = $_POST['fecha_finaliza'];
        $diagFinali = $_POST['diagnostico_finaliza'];
        $recomendacion = $_POST['recomendacion_clase'];
        
        $model = NeeXClase::findOne($id);
        $model->grado_nee = $gradoNee;
        $model->diagnostico_inicia = $diagInicia;
        $model->fecha_finaliza = $fechaFinal;
        $model->diagnostico_finaliza = $diagFinali;
        $model->recomendacion_clase = $recomendacion;
        $model->save();

        return $this->redirect(['ficha', 
            'nee_id' => $model->nee_id,
            'pestana' => 'fecha_elab'
        ]);
    }


    public function actionUpdateSections(){
        $id         = $_POST['id'];
        $contenido  = $_POST['contenido'];
        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy   = date("Y-m-d H:i:s");

        $model = NeeXOpcion::findOne($id);
        
        if($model->opcion->seccion == 5){
            $model->contenido = $contenido;
        }else{
            $model->es_seleccionado = $contenido;
        }

        $model->updated_at = $fechaHoy;
        $model->updated = $usuarioLog;
        $model->save();

        return $this->redirect(['ficha',
            'nee_id' => $model->nee_id,
            'pestana' => 'informe_psicopedagogico'
        ]);

    }

    public function actionUpdateSections6(){
                
        $id         = $_POST['id'];
        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy   = date("Y-m-d H:i:s");

        $model = NeeXOpcion::findOne($id);
        
        if($model->es_seleccionado == 0){
            $cambio = 1;
        }else{
            $cambio = 0;
        }

        $model->es_seleccionado = $cambio;
        $model->updated_at = $fechaHoy;
        $model->updated = $usuarioLog;
        
        $model->save();       
    }




    public function actionUpdateNee(){
        $campo = $_POST['campo'];
        $valor = $_POST['valor'];
        $neeId = $_POST['nee_id'];
        

        $model = Nee::findOne($neeId);
        $model->$campo = $valor;
        $model->save();


        $neeXClase = NeeXClase::find()->where(['nee_id' => $neeId])->all();

        foreach($neeXClase as $nxc){
            $nxc->grado_nee             = $model->grado;
            $nxc->fecha_inicia          = $model->fecha_diagnostico;
            $nxc->diagnostico_inicia    = $model->diagnostico;
            $nxc->diagnostico_finaliza  = $model->diagnostico;
            $nxc->fecha_finaliza        = $model->fecha_salida_nee;
            $nxc->diagnostico_finaliza  = $model->observacion_salida_nee;
            $nxc->recomendacion_clase   = $model->recomendaciones;
            $nxc->save();
        }
        


    }


    public function actionUpdatePermanente(){
        $neeId = $_POST['nee_id'];

        $model = Nee::findOne($neeId);
        if($model->es_permanente == 0 || $model->es_permanente == null){
            $model->es_permanente = true;
        }else{
            $model->es_permanente = false;
        }

        $model->save();
    }


    public function actionEliminarAsignaturaNee(){
        $id = $_GET['id'];
        $model = NeeXClase::findOne($id);
        $neeId = $model->nee_id;

        $model->delete();
        return $this->redirect(['ficha', 'nee_id' => $neeId, 'pestana' => 'fecha_elab']);
    }
}
