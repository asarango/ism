<?php

namespace backend\controllers;

use Yii;
use backend\models\InspFechaPeriodo;
use backend\models\InspFechaPeriodoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * InspFechaPeriodoController implements the CRUD actions for InspFechaPeriodo model.
 */
class InspFechaPeriodoController extends Controller
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
     * Lists all InspFechaPeriodo models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $this->actualiza_fechas_anio_lectivo($periodoId);              
        
        $searchModel = new InspFechaPeriodoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $periodoId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    
    public function actionAjaxLaborados(){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $laborados = $this->get_dias_trabajados($periodoId);
        
        $scripts = new \backend\models\helpers\Scripts();
        
        $labMes = array();
        $labTot = array();
        
        foreach ($laborados as $lab) {            
            $mes = $scripts->convertir_mes($lab['mes']);
            $tot = $lab['total'];            
            
            array_push($labMes, $mes);
            array_push($labTot, $tot);
        }
        
        $laborado = array(
            'meses' => $labMes,
            'totales' => $labTot
        );      
        
        return json_encode($laborado);        
    }
    
    public function actionAjaxNoJustificado(){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;        
        $comportamiento = $this->get_comportamiento_injustificado($periodoId, $institutoId);
        $arrayCursos = array();
        $arrayTotales = array();
        
        foreach ($comportamiento as $com){
            array_push($arrayCursos, $com['name']);
            array_push($arrayTotales, $com['total']);
        }
        
        $response = array(
            'cursos' => $arrayCursos,
            'totales' => $arrayTotales
        );
        
        return json_encode($response);
    }
    
    public function actionAjaxNoJustificadoDocentes(){
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        $noTimbrado = $this->get_notimbrado_docente($periodoId);
        
        $scripts = new \backend\models\helpers\Scripts();
        
        $labMes = array();
        $labTot = array();
        
        foreach ($noTimbrado as $lab) {            
            $mes = $scripts->convertir_mes($lab['mes']);
            $tot = $lab['sin_timbrar'];            
            
            array_push($labMes, $mes);
            array_push($labTot, $tot);
        }
        
        $respuesta = array(
            'meses' => $labMes,
            'totales' => $labTot
        );           
        
        return json_encode($respuesta); 
    }
    
    
    public function get_notimbrado_docente($periodoId){
        $con = Yii::$app->db;
        $query1 = "select 	count(cab.id) as total_horas_dia
                    from	scholaris_horariov2_cabecera cab
                                    inner join scholaris_horariov2_detalle det on det.cabecera_id = cab.id
                                    inner join scholaris_horariov2_hora h on h.id = det.hora_id 
                    where 	cab.periodo_id = $periodoId
                                    and cab.id in (21,26)
                                    and det.dia_id = 1
                                    and h.es_receso = false;";
        $res1 = $con->createCommand($query1)->queryOne();
        $totalHorasDia = $res1['total_horas_dia'];
        
        $query2 = "select extract(year from fecha) as anio, extract(month from fecha) as mes, sum(falta_timbrar) as sin_timbrar
                    from (
                                    select 	f.fecha
                                                    ,$totalHorasDia-(select count(id) from scholaris_asistencia_profesor where fecha = f.fecha) as falta_timbrar
                                    from 	insp_fecha_periodo f		
                                    where 	f.fecha >= '2021-09-01'
                                                    and f.periodo_id = $periodoId
                                                    and f.hay_asitencia = true
                                    order by f.fecha
                    ) as mes
                    group by anio,mes
                    order by anio,mes;";
        $res = $con->createCommand($query2)->queryAll();
        
        return $res;
    }
    
    
    private function get_dias_trabajados($periodoId){
        
        $con = Yii::$app->db;
        $query = "select 	count(EXTRACT(MONTH FROM fecha)) as total
                                    ,EXTRACT(MONTH FROM fecha) as mes
                                    ,EXTRACT(YEAR FROM fecha) as anio
                    from 	insp_fecha_periodo
                    where fecha > '2021-08-01'
                                    and hay_asitencia = true
                                    and periodo_id = $periodoId
                    group by EXTRACT(MONTH FROM fecha), EXTRACT(YEAR FROM fecha)
                    order by EXTRACT(YEAR FROM fecha), EXTRACT(MONTH FROM fecha);";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function get_comportamiento_injustificado($periodoId, $institutoId){
        $con = Yii::$app->db;
        $query = "select 	count(n.id) as total
                                    ,substring(cur.name from 1 for 4) as name
                    from 	scholaris_asistencia_alumnos_novedades n
                                    inner join scholaris_asistencia_profesor a on a.id = n.asistencia_profesor_id
                                    inner join scholaris_clase c on c.id = a.clase_id 
                                    inner join op_course_paralelo par on par.id = c.paralelo_id 
                                    inner join op_course cur on cur.id = par.course_id 
                                    inner join op_section s on s.id = cur.section
                                    inner join op_period op on op.id  = s.period_id 
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = op.id 		
                    where 	sop.scholaris_id = $periodoId
                                    and op.institute = $institutoId
                                    and n.es_justificado = false
                    group by cur.name order by cur.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
     /**
     * Metodo para actualizar las fechas del año lectivo hasta el dia de hoy
     */
    private function actualiza_fechas_anio_lectivo($periodoId){
        $con = Yii::$app->db;
        $query = "insert into insp_fecha_periodo (fecha, periodo_id, numero_dia)
                    SELECT 	date_trunc('day', dd)::date
                                    ,8
                                    ,extract (dow from date (date_trunc('day',dd)))				
                    FROM 	generate_series
                            ( (select 	b.bloque_inicia
                                            from 	scholaris_bloque_actividad b
                                                            inner join scholaris_periodo p on p.codigo = b.scholaris_periodo_codigo
                                            where	p.id = 8
                                            order by b.bloque_inicia 
                                            limit 1)::timestamp         
                            ,current_timestamp
                            , '1 day'::interval) dd
                    where extract (dow from date (date_trunc('day',dd))) not in (0,6)
                            and date_trunc('day', dd)::date not in (
                                    select fecha from insp_fecha_periodo where periodo_id = 8
                            )
                    order by 1 desc;";
        $con->createCommand($query)->execute();
    }

    /**
     * Displays a single InspFechaPeriodo model.
     * @param string $id
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
     * Creates a new InspFechaPeriodo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InspFechaPeriodo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->fecha]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing InspFechaPeriodo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($fecha)
    {
        $model = $this->findModel($fecha);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing InspFechaPeriodo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the InspFechaPeriodo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return InspFechaPeriodo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InspFechaPeriodo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
