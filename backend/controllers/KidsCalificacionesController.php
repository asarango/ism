<?php

namespace backend\controllers;

use backend\models\ViewKidsTareasSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


class KidsCalificacionesController extends Controller{
    
    
    public function actionIndex1(){

        $usuario = Yii::$app->user->identity->usuario;

        $searchModel = new ViewKidsTareasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $usuario);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    } 



}