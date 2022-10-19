<?php

namespace backend\controllers;

use Yii;
use backend\models\DeceDeteccion;
use backend\models\DeceDeteccionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeceDeteccionController implements the CRUD actions for DeceDeteccion model.
 */
class DeceDeteccionController extends Controller
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
     * Lists all DeceDeteccion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeceDeteccionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeceDeteccion model.
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
     * Creates a new DeceDeteccion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeceDeteccion();
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');        

        if($_GET)
        {
            $id_estudiante = $_GET['id_estudiante'];
            $id_caso= $_GET['id_caso'];
            $ultimoNumDeteccion = $this->buscaUltimoNumDeteccion($id_caso);
            $model->numero_deteccion = $ultimoNumDeteccion + 1;            
            $model->id_estudiante = $id_estudiante;
            $model->fecha_reporte = $fechaActual;
            $model->id_caso = $id_caso;
            $model->numero_caso = $model->caso->numero_caso;
            $model->descripcion_del_hecho = ' - ';
            $model->acciones_realizadas = ' - ';
            $model->lista_evidencias = ' - ';
            $model->path_archivos = ' - ';

        }  

        if ($model->load(Yii::$app->request->post())) 
        {
            
            $ultimoNumDeteccion = $this->buscaUltimoNumDeteccion($model->id_caso);
            $model->numero_deteccion = $ultimoNumDeteccion + 1;
            $model->fecha_reporte = $model->fecha_reporte.' '.$hora;
            // echo '<pre>';
            // print_r($model);
            // die();
            $model->save();
            
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function buscaUltimoNumDeteccion($idCaso)
    {
        //buscamos el ultimo numero de derivacion acorde al caso indicado
        $modelDeceDeteccion= DeceDeteccion::find()
        ->where(['id_caso'=>$idCaso])        
        ->max('numero_deteccion');

        if(!$modelDeceDeteccion){
            $modelDeceDeteccion =0;
        }
        return $modelDeceDeteccion;
    }

    /**
     * Updates an existing DeceDeteccion model.
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
     * Deletes an existing DeceDeteccion model.
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
     * Finds the DeceDeteccion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceDeteccion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceDeteccion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
