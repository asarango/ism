<?php

namespace backend\controllers;

use Yii;
use backend\models\KidsDestrezaTarea;
use app\models\KidsDestrezaTareaSearch;
use backend\models\KidsTareaArchivo;
use backend\models\PlanificacionOpciones;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KidsDestrezaTareaController implements the CRUD actions for KidsDestrezaTarea model.
 */
class KidsDestrezaTareaController extends Controller
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
     * Lists all KidsDestrezaTarea models.
     * @return mixed
     */
    public function actionCrearTarea()
    {
        $usuario    = Yii::$app->user->identity->usuario;
        $hoy        = date('Y-m-d H:i:s');

        $model = new KidsDestrezaTarea();
        $model->plan_destreza_id    = $_POST['plan_destreza_id'];
        $model->fecha_presentacion  =  $_POST['fecha_presentacion'];
        $model->titulo              =  $_POST['titulo'];
        $model->detalle_tarea       =  $_POST['detalle_tarea'];
        $model->materiales          =  $_POST['materiales'];
        $model->publicado_al_estudiante =  $_POST['publicado_al_estudiante'];
        $model->created_at          =  $hoy;
        $model->created             = $usuario;
        $model->updated_at          = $hoy;
        $model->updated             = $usuario;
        $model->save();

        if ($_FILES) {
            //            busca el directorio para guardar los archivos
            $directory = PlanificacionOpciones::find()->where([
                'categoria' => 'PATH_PROFE',
                'tipo' => 'SUBIDA_ARCHIVO'
            ])->one();

            //completa el path del archivo
            $path = $directory['opcion'] . 'kids/' . $model->id . '/';

            //            llama al método de subida de archivos
            $script = new \backend\models\helpers\Scripts();
            $script->upload_files($_FILES, $path);

            //insertando registro de los archivos

            foreach ($_FILES['archivo']['name'] as $f) {
                $arch = new \backend\models\KidsTareaArchivo();
                $arch->archivo = $f;
                $arch->tarea_id = $model->id;
                $arch->save();
            }
        }

        return $this->redirect([
            'update',
            'id' => $model->id,
        ]);
    }


    /**
     * Displays a single KidsDestrezaTarea model.
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
     * Creates a new KidsDestrezaTarea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KidsDestrezaTarea();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KidsDestrezaTarea model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $archivos = \backend\models\KidsTareaArchivo::find()->where([
            'tarea_id' => $model->id
        ])->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->fecha_presentacion = $model->fecha_presentacion . ' 23:59:59';
            
            $model->detalle_tarea = $_POST['detalle_tarea'];
            $model->materiales = $_POST['materiales'];            

            $model->save();          

            if ($_FILES) {
                //            busca el directorio para guardar los archivos
                $directory = PlanificacionOpciones::find()->where([
                    'categoria' => 'PATH_PROFE',
                    'tipo' => 'SUBIDA_ARCHIVO'
                ])->one();

                //completa el path del archivo
                $path = $directory['opcion'] . 'kids/' . $model->id . '/';

                //            llama al método de subida de archivos
                $script = new \backend\models\helpers\Scripts();
                $script->upload_files($_FILES, $path);

                //insertando registro de los archivos

                foreach ($_FILES['archivo']['name'] as $f) {
                    $arch = new \backend\models\KidsTareaArchivo();
                    $arch->archivo = $f;
                    $arch->tarea_id = $model->id;
                    $arch->save();
                }
            }

            return $this->redirect(['update', 'id' => $model->id]);
        }

        $directory = PlanificacionOpciones::find()->where([
            'categoria' => 'PATH_PROFE',
            'tipo' => 'VER_ARCHIVO'
        ])->one();

        //completa el path del archivo
        $path = $directory['opcion'] . 'kids/' . $model->id . '/';

        return $this->render('update', [
            'model' => $model,
            'archivos' => $archivos,
            'path' => $path
        ]);
    }


    public function actionEliminarArchivo(){
        $path = $_GET['path']; 
        $id = $_GET['archivo_id'];
        
        $model = KidsTareaArchivo::findOne($id);
        $tareaId = $model->tarea_id;

        unlink('/var/www/html/'.$path);
        $model->delete();
        

        return $this->redirect(['update', 'id' => $tareaId]);
    }

    /**
     * Deletes an existing KidsDestrezaTarea model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEliminar($id)
    {


        $model = KidsDestrezaTarea::findOne($id);
        $planSemanalId = $model->planDestreza->horaClase->plan_semanal_id;
        $claseId = $model->planDestreza->horaClase->clase_id;
        $detalleId = $model->planDestreza->horaClase->detalle_id;

        $this->eliminar_archivos($model->id);

        $model->delete();

        return $this->redirect([
            'kids-plan-semanal-hora-clase/index1',
            'plan_semanal_id' => $planSemanalId,
            'clase_id' => $claseId,
            'detalle_id' => $detalleId
        ]);
    }

    private function eliminar_archivos($tareaId)
    {
        $con = Yii::$app->db;
        $query = "delete from kids_tarea_archivo where tarea_id = $tareaId";
        $con->createCommand($query)->execute();
    }

    /**
     * Finds the KidsDestrezaTarea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KidsDestrezaTarea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KidsDestrezaTarea::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
