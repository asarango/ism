<?php

namespace backend\controllers;

use backend\models\DeceMotivos;
use Yii;
use backend\models\DeceRegistroSeguimiento;
use backend\models\DeceRegistroSeguimientoSearch;
use backend\models\ScholarisGrupoAlumnoClase;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\helpers\Scripts;
use yii\web\UploadedFile;
USE backend\models\PlanificacionOpciones;

/**
 * DeceRegistroSeguimientoController implements the CRUD actions for DeceRegistroSeguimiento model.
 */
class DeceRegistroSeguimientoController extends Controller
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
     * Lists all DeceRegistroSeguimiento models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeceRegistroSeguimientoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeceRegistroSeguimiento model.
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
     * Creates a new DeceRegistroSeguimiento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    //recibe el id de scholarisgrupoalumnoclase
    public function actionCreate()
    {
        $model = new DeceRegistroSeguimiento();
    //     echo '<pre>';
    //    print_r($_GET);
    //    die();
        if($_GET)
        {
            $id_clase = $_GET['id_clase'];
            $id_estudiante = $_GET['id_estudiante'];
            $id_caso= $_GET['id_caso'];
            $model->id_estudiante = $id_estudiante;
            $model->id_clase = $id_clase;
            $model->id_caso = $id_caso;
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
     * Updates an existing DeceRegistroSeguimiento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
         $model = $this->findModel($id);
      
         /** Extrae path donde se almacena los archivos */
        $path_archivo_dece_atencion = PlanificacionOpciones::find()->where([
            'tipo'=>'SUBIDA_ARCHIVO',
            'categoria'=>'PATH_DECE_SEG'
        ])->one();
        $pathArchivoModel = $model->path_archivo;


        if ($model->load(Yii::$app->request->post()))
        {
            //$imagenSubida = UploadedFile::getInstance($model,'path_archivo');

            if(!empty($model->path_archivo))
            {
                $imagenSubida = UploadedFile::getInstance($model,'path_archivo');
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
            else{
                $model->path_archivo = $pathArchivoModel;
                $model->save();
            }
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DeceRegistroSeguimiento model.
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
     * Finds the DeceRegistroSeguimiento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceRegistroSeguimiento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceRegistroSeguimiento::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
