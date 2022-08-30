<?php

namespace backend\controllers;

use Yii;
use backend\models\PlanificacionOpciones;
use backend\models\DeceDerivacion;
use backend\models\DeceDerivacionSearch;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeceDerivacionController implements the CRUD actions for DeceDerivacion model.
 */
class DeceDerivacionController extends Controller
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
     * Lists all DeceDerivacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeceDerivacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeceDerivacion model.
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
     * Creates a new DeceDerivacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeceDerivacion();
        $fechaActual = date('Y-m-d');
        if($_GET)
        {
            $id_estudiante = $_GET['id_estudiante'];
            $id_caso= $_GET['id_caso'];
            $model->id_estudiante = $id_estudiante;
            $model->fecha_derivacion = $fechaActual;
            $model->id_casos = $id_caso;
        }
        /** Extrae path donde se almacena los archivos */
        $path_archivo_dece_atencion = PlanificacionOpciones::find()->where([
            'tipo'=>'SUBIDA_ARCHIVO',
            'categoria'=>'PATH_DECE_SEG'
        ])->one();

        if ($model->load(Yii::$app->request->post()))
        {
            $imagenSubida = UploadedFile::getInstance($model,'path_archivo');
            $model->save();

            if(!empty($imagenSubida))
            {
              $pathArchivos =$path_archivo_dece_atencion->opcion.$model->id_estudiante.'/'.$model->id.'/';

               //creamos la carpeta
                if (!file_exists($pathArchivos)) {
                    mkdir($pathArchivos, 0777,true);
                    chmod($pathArchivos, 0777);
                }
                $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);
                $path = $pathArchivos.$imagenSubida->name;
                if ($imagenSubida->saveAs($path))
                {
                    $model->path_archivo = $model->id_estudiante.'/'.$model->id.'##'.$imagenSubida->name;
                    $model->save();
                }
            }
            return $this->redirect(['update', 'id' => $model->id]);
        }      

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DeceDerivacion model.
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
     * Deletes an existing DeceDerivacion model.
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
     * Finds the DeceDerivacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceDerivacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceDerivacion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
