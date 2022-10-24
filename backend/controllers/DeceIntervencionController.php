<?php

namespace backend\controllers;

use Yii;
use backend\models\DeceCasos;
use backend\models\DeceIntervencionAreaCompromiso;
use backend\models\DeceAreasIntervenir;
use backend\models\DeceIntervencion;
use backend\models\DeceIntervencionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeceIntervencionController implements the CRUD actions for DeceIntervencion model.
 */
class DeceIntervencionController extends Controller
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
     * Lists all DeceIntervencion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeceIntervencionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeceIntervencion model.
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
     * Creates a new DeceIntervencion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeceIntervencion();
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');

        if($_GET)
        {
            $id_estudiante = $_GET['id_estudiante'];
            $id_caso= $_GET['id_caso'];
            $model->id_estudiante = $id_estudiante;
            $model->fecha_intervencion = $fechaActual;
            $model->razon = '-';
            $model->acciones_responsables = '-';
            $model->objetivo_general = '-';            
            $model->id_caso = $id_caso;
        } 

        if ($model->load(Yii::$app->request->post())) 
        {
            $ultimoNumIntervencion = $this->buscaUltimoNumIntervencion($_POST['DeceIntervencion']['id_caso'],$_POST['DeceIntervencion']['id_estudiante']);
            $numeroCaso = $this->buscaNumeroCaso($_POST['DeceIntervencion']['id_caso']);
            $fecha_intervencion = $_POST['fecha_intervencion'] ;
            $arrayAuxPost = $_POST;
            $model->fecha_intervencion = $fecha_intervencion .' ' .$hora;
            $model->numero_intervencion =  $ultimoNumIntervencion + 1;
            $model->numero_caso=  $numeroCaso;

            $model->save();
            foreach($arrayAuxPost as $aux)
            {  
                if (!is_array($aux))
                {
                    if(is_numeric(strpos($aux,"AI")))
                    {                       
                        $modelIntAreaInter = new DeceIntervencionAreaCompromiso();
                        $idAreaIntervenir = DeceAreasIntervenir::find()
                        ->where(['code'=>$aux])
                        ->one();
                        $modelIntAreaInter->id_dece_intervencion = $model->id;
                        $modelIntAreaInter->id_dece_areas_intervenir =  $idAreaIntervenir->id;
                        $modelIntAreaInter->save();
                    }
                }               
           } 
           return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function buscaUltimoNumIntervencion($idCaso,$idEstudiante)
    {
        //buscamos el ultimo numero de intervencion acorde al caso indicado
        $modelDeceIntervencion = DeceIntervencion::find()
        ->where(['id_caso'=>$idCaso])
        ->andWhere(['id_estudiante'=>$idEstudiante])
        ->max('numero_intervencion');

        if(!$modelDeceIntervencion){
            $modelDeceIntervencion =0;
        }
        return $modelDeceIntervencion;
    }
    public function buscaNumeroCaso($idCaso)
    {
        //buscamos el ultimo numero de Intervencion acorde al caso indicado
        $modelNumeroCaso = DeceCasos::find()
        ->where(['id'=>$idCaso])
        ->max('numero_caso');

        if(!$modelNumeroCaso){
            $modelNumeroCaso =0;
        }
        return $modelNumeroCaso;
    }

    /**
     * Updates an existing DeceIntervencion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $fechaActual = date('Y-m-d');
        $hora = date('H:i:s');         
        
        $arrayAreaIntervenirUpdate = $this->buscaAreaIntervenir($model->id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $model->save();
            $arrayAuxPost = $_POST; 

              //ELIMINAMOS TODOS LOS REGISTROS DE LAS AREAS A INTERVENIR PARA VOLVER AGREGAR NUEVAMENTE TODOS LOS SELECCIONADOS
              $x = Yii::$app->db->createCommand("
              DELETE FROM dece_intervencion_area_compromiso 
              WHERE id_dece_intervencion = '$model->id'                
          ")->execute();
          foreach($arrayAuxPost as $aux)
           {  
                if (!is_array($aux))//COMO EL $_POST, tiene el array nativo de yii2, lo excluimos
                {
                    if(is_numeric(strpos($aux,"IE")))
                    {   
                        $modelInterAreaComp = new DeceIntervencionAreaCompromiso();
                        $idAreaIntervenir = DeceAreasIntervenir::find()
                        ->where(['code'=>$aux])
                        ->one();
                        $modelInterAreaComp->id_dece_intervencion = $model->id;
                        $modelInterAreaComp->id_dece_areas_intervenir =  $idAreaIntervenir->id;
                        $modelInterAreaComp->save();
                    }
                }               
           }

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'arrayAreaIntervenirUpdate'=>$arrayAreaIntervenirUpdate,
        ]);
    }
    //busca la institucion externa por el ID, de la Area Intervencion
    public function buscaAreaIntervenir($idIntervencion)
    {
       $con =yii::$app->db;
        $query="select i.id,i.nombre,i.code,'si' as Seleccionado 
        from dece_intervencion d1 , dece_intervencion_area_compromiso  d2,
        dece_areas_intervenir  i
        where d1.id = d2.id_dece_intervencion  
        and d2.id_dece_areas_intervenir  = i.id 
        and d1.id = '$idIntervencion'
        union all
         select dd.id,dd.nombre,dd.code,'no' as Seleccionado
         from dece_areas_intervenir dd 
         where id not in
         ( select id_dece_areas_intervenir from dece_intervencion_area_compromiso dr 
         where id_dece_intervencion = '$idIntervencion') order by id;";

       $resp = $con->createCommand($query)->queryAll();
     
       return $resp;
    }

    /**
     * Deletes an existing DeceIntervencion model.
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
     * Finds the DeceIntervencion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeceIntervencion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeceIntervencion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
