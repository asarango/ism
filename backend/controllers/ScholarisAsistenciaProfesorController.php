<?php

namespace backend\controllers;

use Yii;
use app\models\ScholarisAsistenciaProfesor;
use app\models\ScholarisAsistenciaProfesorSearch;
use backend\models\kids\ScriptsKids;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\SentenciasSql;
use backend\models\ScholarisPeriodo;
use backend\models\ResUsers;
use backend\models\ViewInsumosSearch;

/**
 * ScholarisAsistenciaProfesorController implements the CRUD actions for ScholarisAsistenciaProfesor model.
 */
class ScholarisAsistenciaProfesorController extends Controller {

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

    /**
     * Lists all ScholarisAsistenciaProfesor models.
     * @return mixed
     */
    public function actionIndex() 
    {
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $scripts = new \backend\models\helpers\Scripts();
        $model = $scripts->sql_mostrar_clases_x_profesor();
        
        $scriptsKids = new ScriptsKids();
        
        $clases = $scriptsKids->get_class_teacher();      

        $i = 0;
        $j = 0;
        
        foreach($clases as $mo){
            if($mo['code'] == 'PRES'){
                $i++;
            }else{
                $j++;
            }
        }

        $i>0 ? $tienePrescolar = true : $tienePrescolar = false;
        $j>0 ? $tieneOtras = true : $tieneOtras = false;

        return $this->render('index', [
                    'model' => $model,
                    'tienePrescolar' => $tienePrescolar,
                    'tieneOtras' => $tieneOtras
        ]);
    }

    /**
     * Displays a single ScholarisAsistenciaProfesor model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisAsistenciaProfesor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ScholarisAsistenciaProfesor();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisAsistenciaProfesor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisAsistenciaProfesor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisAsistenciaProfesor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisAsistenciaProfesor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisAsistenciaProfesor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionRegistrar($clase, $hora) {

        $fecha = date("Y-m-d");

        $model = \backend\models\ScholarisAsistenciaProfesor::find()->where([
                    'clase_id' => $clase,
                    'hora_id' => $hora,
                    'fecha' => $fecha
                ])->one();                               

//        if (!$modelExiste) {            
        if (!$model) {            
            $login = Yii::$app->user->identity->usuario;

            $modelUsuario = ResUsers::find()->where(['login' => $login])->one();

            $usuario = Yii::$app->user->identity->usuario;
            $periodoId = Yii::$app->user->identity->periodo_id;

            $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

//            $sentencias = new SentenciasSql();
            $scripts = new \backend\models\helpers\Scripts();

            $horaActual = \backend\models\ScholarisHorariov2Hora::findOne($hora);

            $modelSiguiente = $scripts->sql_mostrar_hora_siguiente($clase);
                        
            $horaSiguiente = $modelSiguiente['numero'];


            $diferencia = $modelSiguiente['numero'] - $horaActual->numero;


            $hoy = date("Y-m-d");
            $horaHoy = date('H:i:s');


            $model = new \backend\models\ScholarisAsistenciaProfesor();

            $model->clase_id = $clase;
            $model->hora_id = $hora;
            $model->hora_ingresa = $horaHoy;
            $model->fecha = $hoy;
            $model->user_id = $modelUsuario->id;
            $model->creado = $hoy;
            $model->modificado = $hoy;
            $model->estado = 1;
            $model->save();                                  

            if ($diferencia == 1) {

                $modelSiguienteR = $model = new \backend\models\ScholarisAsistenciaProfesor();

                $modelSiguienteR->clase_id = $clase;
                $modelSiguienteR->hora_id = $modelSiguiente['hora_id'];
                $modelSiguienteR->hora_ingresa = $modelSiguiente['desde'];
                $modelSiguienteR->fecha = $hoy;
                $modelSiguienteR->user_id = $modelUsuario->id;
                $modelSiguienteR->creado = $hoy;
                $modelSiguienteR->modificado = $hoy;
                $modelSiguienteR->estado = 1;
                $modelSiguienteR->save();
            }
        }


        return $this->redirect(['comportamiento/index', 'id' => $model->id ]);
    }
    
    public function actionNovedades(){
        $usuario = \Yii::$app->user->identity->email;
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
        
        $this->elimina_novedades(trim($usuario));
        $this->ingresa_novedades_a_tabla(trim($usuario), $modelPeriodo->codigo);
        
        return $this->redirect(['/scholaris-reporte-novedades-comportamiento/index1']);
        
        
    }
    
    private function elimina_novedades($usuario){
        $con = \Yii::$app->db;
        $query = "delete from scholaris_reporte_novedades_comportamiento where 	usuario = '$usuario';";

        $con->createCommand($query)->execute();
    }
    
    
    private function ingresa_novedades_a_tabla($usuario, $periodoCodigo){
        $con = \Yii::$app->db;
        $query = "insert into scholaris_reporte_novedades_comportamiento
                    select 	n.id 
                                    ,b.name as bloque
                                    ,sem.nombre_semana 
                                    ,p.fecha 
                                    ,h.nombre as hora
                                    ,m.name as materia
                                    ,concat(s.last_name,' ',s.first_name, ' ',s.middle_name ) as estudiante
                                    ,cur.name as curso
                                    ,pa.name as paralelo
                                    ,d.codigo 
                                    ,d.nombre as falta
                                    ,n.observacion 
                                    ,(select motivo_justificacion from scholaris_asistencia_justificacion_alumno where novedad_id = n.id)
                                    ,u.login 
                    from 	scholaris_asistencia_alumnos_novedades n
                                    inner join scholaris_asistencia_profesor p on p.id = n.asistencia_profesor_id 
                                    inner join scholaris_clase c on c.id = p.clase_id 
                                    inner join op_faculty f on f.id = c.idprofesor 
                                    inner join res_users u on u.partner_id = f.partner_id
                                    inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id 
                                    inner join op_student s on s.id = g.estudiante_id 
                                    inner join scholaris_asistencia_comportamiento_detalle d on d.id = n.comportamiento_detalle_id
                                    inner join op_course cur on cur.id = c.idcurso 
                                    inner join op_course_paralelo pa on pa.id = c.paralelo_id 
                                    inner join scholaris_bloque_actividad b on p.fecha between b.bloque_inicia and b.bloque_finaliza 
                                                                    and b.tipo_uso = c.tipo_usu_bloque 
                                    inner join scholaris_bloque_semanas sem on sem.bloque_id = b.id 
                                                                    and p.fecha between sem.fecha_inicio and sem.fecha_finaliza 
                                    inner join scholaris_horariov2_hora h on h.id = p.hora_id 
                                    inner join scholaris_materia m on m.id = c.idmateria
                    where 	c.periodo_scholaris = '$periodoCodigo'
                                    and u.login = '$usuario'
                    order by 2;";
        $con->createCommand($query)->execute();
    }

    public function actionReporte()
    {
        $usuario = rtrim(\Yii::$app->user->identity->email);
        $periodoId = \Yii::$app->user->identity->periodo_id; 
        return $this->render('reporte',
            [
                'usuario'=>$usuario,
                'periodoId'=>$periodoId,

            ]
        );
    }
    
    
    
    public function actionDocentes(){ 
        
        
        return $this->render('docentes');
    }


    /**
     * Método para tomar todos los insumos calificados del docente
     */
    public function actionInsumos(){
        $user = Yii::$app->user->identity->usuario;
        $periodId = Yii::$app->user->identity->periodo_id;
        $searchModel = new ViewInsumosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user, $periodId);

        return $this->render('insumos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    


    /**
     * ACCIÓN PARA ENTREGAR HORARIO DE CLASES DE DOCENTE
     * CREADO POR: Arturo Sarango - 2023-03-28
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-28
     */

     public function actionHorarioDocente(){
        $user = Yii::$app->user->identity->usuario;
        $periodId = Yii::$app->user->identity->periodo_id;

        $horario = $this->get_horario($periodId, $user);

        return $this->render('horario-docente',[
            'horario' => $horario
        ]);

     }

     private function get_horario($periodId, $user){
        $con = Yii::$app->db;
        $query = "select 	dia.id 
                        ,dia.nombre as dia
                        ,hor.nombre as hora
                        ,mat.nombre as materia
                        ,fac.last_name 
                        ,cur.name as curso
		                ,par.name as paralelo
                from	scholaris_horariov2_horario hh
                        inner join scholaris_clase cla on cla.id = hh.clase_id
                        inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                        inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                        inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                        inner join op_faculty fac on fac.id = cla.idprofesor 
                        inner join res_users rus on rus.partner_id = fac.partner_id 
                        inner join scholaris_horariov2_detalle det on det.id = hh.detalle_id 
                        inner join scholaris_horariov2_dia dia on dia.id = det.dia_id 
                        inner join scholaris_horariov2_hora hor on hor.id = det.hora_id 
                        inner join ism_materia mat on mat.id = iam.materia_id 
                        inner join op_course_paralelo par on par.id = cla.paralelo_id 
		                inner join op_course cur on cur.id = par.course_id
                where 	ipm.scholaris_periodo_id = $periodId
                        and rus.login = '$user'
                order by dia.numero, hor.numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
     }

}
