<?php

namespace backend\models\messages;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class Messages extends \yii\db\ActiveRecord {

//    Atributos
    private $user;

    public function __construct() {        
        $this->user = Yii::$app->user->identity->usuario;


        echo $this->user;
    }
        

}
