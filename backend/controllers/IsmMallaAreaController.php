<?php

namespace backend\controllers;

use Yii;
use backend\models\IsmMallaArea;
use backend\models\IsmMallaAreaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IsmMallaAreaController implements the CRUD actions for IsmMallaArea model.
 */
class IsmMallaAreaController extends Controller
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
     * Lists all IsmMallaArea models.
     * @return mixed
     */
    public function actionIndex1()    
    {
        if(!isset(\Yii::$app->user->identity->usuario)){
            return $this->redirect(['site/login']);
        }
        
        $periodoMallaId = $_GET['periodo_id'];
        $peridoMalla = \backend\models\IsmPeriodoMalla::findOne($periodoMallaId);
        
        $malla = $this->get_malla($periodoMallaId);

        return $this->render('index', [
           
            'peridoMalla' => $peridoMalla,
            'malla' => $malla
        ]);
    }
    
    private function get_malla($periodoMallaId){
        $malla = array();
        $areas = $this->get_malla_x_area($periodoMallaId);
        
        foreach ($areas as $a){
            
            $a['materias'] = $this->get_ism_area_materia($a['id']);
            array_push($malla, $a);
        }        
        
        return $malla;
    }
    
    private function get_ism_area_materia($areaMateriaId){
        $con = Yii::$app->db;
        $query = "select 	am.id 
                                    ,m.nombre 
                                    ,am.promedia, am.porcentaje, am.imprime_libreta
                                    ,am.es_cuantitativa, am.tipo, am.asignatura_curriculo_id
                                    ,am.curso_curriculo_id
                                    ,am.orden 
                                    ,am.total_horas_semana
                    from	ism_area_materia am 
                                    inner join ism_materia m on m.id = am.materia_id 
                    where 	am.malla_area_id = $areaMateriaId
                    order by am.orden;";
        $res = $con->createCommand($query)->queryAll();        
        return $res;
    }
    
    private function get_malla_x_area($periodoMallaId){
        $con = \Yii::$app->db;
        $query = "select 	ma.id 
                                    ,a.nombre
                                    ,ma.promedia, ma.imprime_libreta, ma.es_cuantitativa, ma.tipo, ma.porcentaje, ma.orden 
                    from	ism_malla_area ma
                                    inner join ism_area a on a.id = ma.area_id 
                    where 	ma.periodo_malla_id = $periodoMallaId
                    order by ma.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Displays a single IsmMallaArea model.
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
     * Creates a new IsmMallaArea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IsmMallaArea();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing IsmMallaArea model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'periodo_id' => $model->periodoMalla->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing IsmMallaArea model.
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
     * Finds the IsmMallaArea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IsmMallaArea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IsmMallaArea::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
