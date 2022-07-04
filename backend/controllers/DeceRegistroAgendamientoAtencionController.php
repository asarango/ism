<?php

namespace backend\controllers;

use Yii;
use backend\models\DeceRegistroAgendamientoAtencion;
use backend\models\DeceRegistroAgendamientoAtencionSearch;
USE backend\models\PlanificacionOpciones;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DeceRegistroAgendamientoAtencionController implements the CRUD actions for DeceRegistroAgendamientoAtencion model.
 */
class DeceRegistroAgendamientoAtencionController extends Controller
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
     * Lists all DeceRegistroAgendamientoAtencion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeceRegistroAgendamientoAtencionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeceRegistroAgendamientoAtencion model.
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
     * Creates a new DeceRegistroAgendamientoAtencion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idSeguimiento)
    {
        $model = new DeceRegistroAgendamientoAtencion();
        /** Extrae path donde se almacena los archivos */
        $path_archivo_dece_atencion = PlanificacionOpciones::find()->where([
            'tipo'=>'SUBIDA_ARCHIVO',
            'categoria'=>'PATH_DECE_SEG'
        ])->one();
        $pathArchivos =$path_archivo_dece_atencion->opcion.$idSeguimiento.'/';       
        //creamos la carpeta
        
        if (!file_exists($pathArchivos)) {            
            mkdir($pathArchivos, 0777);     
            chmod($pathArchivos, 0777) ;      
        } 
        if ($model->load(Yii::$app->request->post())) {
            $imagenSubida = UploadedFile::getInstance($model,'path_archivo');         
            if(!empty($imagenSubida))
            {  
                $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);
                $path = $pathArchivos.$imagenSubida->name;
                                
                if ($imagenSubida->saveAs($path))
                {
                    $model->save();
                    $model->path_archivo = $model->id.'##'.$imagenSubida->name; 
                    $model->save();
                }
            }
            else
            { 
                $model->save();
            }
                       
            return $this->redirect(['create', 'idSeguimiento' => $model->id_reg_seguimiento]);
        }

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['create', 'idSeguimiento' => $model->id_reg_seguimiento]);
        // }

        return $this->render('create', [
            'model' => $model,
            'idSeguimiento'=>$idSeguimiento,
        ]);
    }

    /**
     * Updates an existing DeceRegistroAgendamientoAtencion model.
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
            'idSeguimiento'=>$model->id_reg_seguimiento,
        ]);
    }

    /**
     * Deletes an existing DeceRegistroAgendamientoAtencion model.
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
     * Finds the DeceRegistroAgendamientoAtencion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceRegistroAgendamientoAtencion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceRegistroAgendamientoAtencion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
