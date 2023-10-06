<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ScholarisQuimestre;
use backend\models\ResUsers;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueActividad */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$periodo = Yii::$app->user->identity->periodo_id;
$modelUser = ResUsers::find()->where(['login' => $usuario])->one();

$modelPerido = \backend\models\ScholarisPeriodo::findOne($periodo);

$fecha = date("Y-m-d H:i:s");
?>

<div class="scholaris-bloque-actividad-form col-lg-10" style="margin-left: 65px;">
    

</div>