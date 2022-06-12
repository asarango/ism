<?php

namespace backend\controllers;

use Yii;
use backend\models\IsmPeriodoMalla;
use backend\models\IsmPeriodoMallaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IsmPeriodoMallaController implements the CRUD actions for IsmPeriodoMalla model.
 */
class IsmPeriodoMallaController extends Controller
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
     * Lists all IsmPeriodoMalla models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        if(!isset(\Yii::$app->user->identity->usuario)){
            return $this->redirect(['site/login']);
        }
        
        $searchModel = new IsmPeriodoMallaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $this->ingresa_malla_automatico();
        $malla = $this->get_malla_x_periodo();
        $listaMalla = \yii\helpers\ArrayHelper::map($malla, 'id', 'nombre');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listaMalla' => $listaMalla
        ]);
    }
    
    public function get_malla_x_periodo(){
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $con = Yii::$app->db;
        $query = "select 	pm.id 
                                ,m.nombre 
                from 	ism_periodo_malla pm
                                inner join ism_malla m on m.id = pm.malla_id 
                where 	pm.scholaris_periodo_id = $periodoId
                order by m.nombre; ";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function ingresa_malla_automatico(){
        if(isset(\Yii::$app->user->identity->periodo_id)){
            $periodoId = \Yii::$app->user->identity->periodo_id;
        }else{
            return $this->redirect(['site/login']);
        }
        
        $con = \Yii::$app->db;
        $query = "insert into ism_periodo_malla
                    select 	m.id, 1 
                    from 	ism_malla m
                    where 	m.id not in (
                                            select 	 malla_id
                                    from 	ism_periodo_malla
                                    where 	scholaris_periodo_id = $periodoId
                    );";
        $con->createCommand($query)->execute();
    }
    

    /**
     * Displays a single IsmPeriodoMalla model.
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
     * Creates a new IsmPeriodoMalla model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IsmPeriodoMalla();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing IsmPeriodoMalla model.
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
     * Deletes an existing IsmPeriodoMalla model.
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
     * Finds the IsmPeriodoMalla model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IsmPeriodoMalla the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IsmPeriodoMalla::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
