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
    public function actionCreate($id)
    {       
        $model = new DeceRegistroSeguimiento(); 
        
        if ($model->load(Yii::$app->request->post())) 
        {                       
            $model->save();
            return $this->redirect(['update', 'id' => $model->id]);         
        }
        return $this->render('create', [
            'model' => $model,
            'id_grupo' => $id //grupo contiene id estudinte e id clase 
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
        //busca el id del grupo, por clase y estudiante
        $modelGrupo = ScholarisGrupoAlumnoClase::find()
        ->where(['clase_id' =>$model->id_clase])
        ->andWhere(['estudiante_id' =>$model->id_estudiante])
        ->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'id_grupo' => $modelGrupo->id
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
