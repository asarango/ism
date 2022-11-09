<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * IsmCriterioDescriptorAreaController implements the CRUD actions for IsmCriterioDescriptorArea model.
 */
class MigrarCriteriosController extends Controller{
    public function actionIndex(){
        echo 'oli';
    }
}