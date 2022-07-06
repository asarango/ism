<?php

namespace backend\controllers;

use Yii;
use backend\models\KidsDestrezaTarea;
use app\models\KidsDestrezaTareaSearch;
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
        // echo '<pre>';
        // print_r($_POST);
        // print_r($_FILES);
        // die();
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

        if($_FILES){
            $this->upload_files($_FILES, $model->id);
        }
        

        return $this->redirect(['update',
            'id' => $model->id
        ]);

    }

    private function upload_files($files, $tareaId){
        echo '<pre>';
        // print_r($files);

        $directory = PlanificacionOpciones::find()->where([
            'categoria' => 'PATH_PROFE',
            'tipo' => 'VER_ARCHIVO'
        ])->one();


        $path = $directory['opcion'].'kids/'.$tareaId.'/';

        
        foreach ($files["archivo"]['tmp_name'] as $key => $tmp_name) {
            //Validamos que el archivo exista
            if ($files["archivo"]["name"][$key]) {
                $filename = $files["archivo"]["name"][$key]; //Obtenemos el nombre original del archivo
                $source = $files["archivo"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo

                $directorio = $path; //Declaramos un  variable con la ruta donde guardaremos los archivos

                //Validamos si la ruta de destino existe, en caso de no existir la creamos
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");
                }

                $dir = opendir($directorio); //Abrimos el directorio de destino
                $target_path = $directorio . '/' . $filename; //Indicamos la ruta de destino, así como el nombre del archivo

                //Movemos y validamos que el archivo se haya cargado correctamente
                //El primer campo es el origen y el segundo el destino
                if (move_uploaded_file($source, $target_path)) {
                    echo "El archivo $filename se ha almacenado en forma exitosa.<br>";
                } else {
                    echo "Ha ocurrido un error, por favor inténtelo de nuevo.<br>";
                }
                closedir($dir); //Cerramos el directorio de destino
            }
        }


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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing KidsDestrezaTarea model.
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
