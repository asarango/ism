<?php

namespace backend\controllers;

use backend\models\IsmAreaMateria;
use Yii;
use backend\models\KidsPca;
use backend\models\KidsPcaBitacora;
use backend\models\KidsUnidadMicro;
use backend\models\KidsPcaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KidsPcaController implements the CRUD actions for KidsPca model.
 */
class KidsPcaController extends Controller
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
     * Lists all KidsPca models.
     * @return mixed
     */
    public function actionIndex1()
    {
        $userLog = Yii::$app->user->identity->usuario;
        $today = date('Y-m-d H:i:s');        
        
        $pcaId = $_GET['pca_id'];
        //$ismAreaMallaId = $_GET['ism_area_materia_id'];        
        
        $modelPca = KidsPca::findOne($pcaId);       
        
        $modelMicro = new KidsUnidadMicro();

        if ($modelPca->load(Yii::$app->request->post())) {            
            $modelPca->save();
            return $this->redirect(['index1', 'pca_id' => $pcaId]);
        }

        if ($modelMicro->load(Yii::$app->request->post())) {            
            $modelMicro->save();
            return $this->redirect(['index1', 'pca_id' => $pcaId]);
        }


        $microcurriculares = KidsUnidadMicro::find()
        ->where(['pca_id' => $pcaId])
        ->orderBy('orden')
        ->all();



        return $this->render('index',[
            'modelPca' => $modelPca,            
            'userLog' => $userLog,
            'today' => $today,
            'modelMicro' => $modelMicro,
            'microcurriculares' => $microcurriculares
        ]);
    }

    /**
     * Displays a single KidsPca model.
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
     * Creates a new KidsPca model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KidsPca();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KidsPca model.
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
     * Deletes an existing KidsPca model.
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
     * Finds the KidsPca model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KidsPca the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KidsPca::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionPdf(){
        $id = $_GET['id'];
        $pdf = new \backend\models\kids\PcaPdf($id);
        
    }
}
