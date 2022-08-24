<?php

namespace backend\controllers;

use backend\models\ScholarisClase;
use backend\models\helpers\Scripts;
use backend\models\Nee;
use backend\models\NeeXClase;
use Yii;
use backend\models\PlanUnidadNee;
use backend\models\PlanUnidadNeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PlanUnidadNeeController implements the CRUD actions for PlanUnidadNee model.
 */
class PlanUnidadNeeController extends Controller
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
     * Lists all PlanUnidadNee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlanUnidadNeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $nombreProfesor='N/A';
        $objScript = new Scripts();
        $modelCursos = $objScript->sql_mostrar_todas_las_clases_x_profesor(); 
      
        //buscamos nombre del profesor, a travez del id de una de las clases
        if(isset($modelCursos[0]['clase_id'])){
            
            $modelClase = ScholarisClase::findOne($modelCursos[0]['clase_id']); 
            $nombreProfesor = $modelClase->profesor->last_name. ' '.$modelClase->profesor->middle_name.' '.$modelClase->profesor->x_first_name;    
            
        }
        
        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelCursos'=> $modelCursos,
            'nombreProfesor'=>$nombreProfesor,           
        ]);
    }
  
    

    /**
     * Displays a single PlanUnidadNee model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // return $this->render('view', [
        //     'model' => $this->findModel($id),
        // ]);
        return $this->redirect(['index']);
    }   
    public function actionLlamarForm($idNeeXCase,$idBloque,$seccion)
    {
       
        //buscar en plan unidad nee, si tiene id de neexclase
         $modelPlanUnidNee = PlanUnidadNee::find()
         ->where(['nee_x_unidad_id'=>$idNeeXCase,'curriculo_bloque_unidad_id'=>$idBloque])      
         ->one();  
       
         if($modelPlanUnidNee)
         {
            return $this->redirect(['update', 'id' => $modelPlanUnidNee->id,'seccion'=>$seccion]);
         }
         else
         {            
            $model = new PlanUnidadNee();    
                  
            $model->nee_x_unidad_id = $idNeeXCase;
            $model->curriculo_bloque_unidad_id = $idBloque;            
            $model->save();               
            
            return $this->redirect(['update', 'id' =>$model->id,'seccion'=>$seccion]);
         }        
    }

    /**
     * Creates a new PlanUnidadNee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {        
        $model = new PlanUnidadNee();      

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PlanUnidadNee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$seccion)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'seccion'=>$seccion
        ]);
    }

    /**
     * Deletes an existing PlanUnidadNee model.
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
     * Finds the PlanUnidadNee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlanUnidadNee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlanUnidadNee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
