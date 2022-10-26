<?php

namespace backend\controllers;

use Yii;
use backend\models\DeceIntervencionCompromiso;
use backend\models\DeceIntervencionCompromisoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeceIntervencionCompromisoController implements the CRUD actions for DeceIntervencionCompromiso model.
 */
class DeceIntervencionCompromisoController extends Controller
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
     * Lists all DeceIntervencionCompromiso models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeceIntervencionCompromisoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeceIntervencionCompromiso model.
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
     * Creates a new DeceIntervencionCompromiso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeceIntervencionCompromiso();       
       
        $arrayPost = $_POST;
       
        if(count($arrayPost)>0)
        {     
            $id_intervencion = $arrayPost['id_intervencion'];
            $bloque = $arrayPost['bloque'];
            $tipo_compromiso = $arrayPost['tipo_compromiso'];
            $detalle = $arrayPost['detalle'];
            $fecha_compromiso = $arrayPost['fecha_compromiso'];
        
            $model->bloque = $bloque;
            $model->id_dece_intervencion =$id_intervencion;
            $model->fecha_max_cumplimiento = $fecha_compromiso;
            $model->revision_compromiso = '';
            $model->esaprobado = false;
            switch($tipo_compromiso)
            {
                case 'ESTUDIANTE': 
                    $model->comp_estudiante = $detalle;
                    break;
                case 'DOCENTE': 
                    $model->comp_docente = $detalle;
                    break;
                case 'REPRESENTANTE': 
                    $model->comp_representante = $detalle;
                    break;
                case 'DECE': 
                    $model->comp_dece = $detalle;
                    break;
            }
            $model->save();
           
        }
        

    }
    public function actionMostrarTabla()
    {
        $id_intervencion = $_POST['id_intervencion'];        

        $modelCompromisos= DeceIntervencionCompromiso::find()       
        ->where(['id_dece_intervencion'=> $id_intervencion])
        ->all();

        return $this->renderPartial('_tabla_compromiso', [
            'modelCompromisos' =>$modelCompromisos,
        ]);
    }

    /**
     * Updates an existing DeceIntervencionCompromiso model.
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
     * Deletes an existing DeceIntervencionCompromiso model.
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
     * Finds the DeceIntervencionCompromiso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceIntervencionCompromiso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceIntervencionCompromiso::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
