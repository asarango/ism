<?php

namespace backend\controllers;

use Yii;
use backend\models\IsmAreaMateria;
use backend\models\IsmAreaMateriaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IsmAreaMateriaController implements the CRUD actions for IsmAreaMateria model.
 */
class IsmAreaMateriaController extends Controller
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
     * Lists all IsmAreaMateria models.
     * @return mixed
     */
    public function actionIndex1()
    {                
        $searchModel = new IsmAreaMateriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single IsmAreaMateria model.
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
     * Creates a new IsmAreaMateria model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IsmAreaMateria();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing IsmAreaMateria model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $docentes = $this->get_usuarios_docente();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['ism-malla-area/index1', 'periodo_id' => $model->mallaArea->periodoMalla->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'docentes' => $docentes
        ]);
    }

    private function get_usuarios_docente(){
        $con = Yii::$app->db;
        $query = "select 	usu.usuario 
            from	scholaris_clase cla 
                    inner join op_faculty fac on fac.id = cla.idprofesor 
                    inner join res_users rus on rus.partner_id = fac.partner_id 
                    inner join usuario usu on usu.usuario = rus.login
            group by usu.usuario
            order by usu.usuario;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Deletes an existing IsmAreaMateria model.
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
     * Finds the IsmAreaMateria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IsmAreaMateria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IsmAreaMateria::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
