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
            'dataProvider' => $dataProvider,
        ]);
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
