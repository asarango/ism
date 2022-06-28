<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsPlanSemanalHoraClase */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kids-plan-semanal-hora-clase-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'plan_semanal_id')->textInput() ?>

    <?= $form->field($model, 'clase_id')->textInput() ?>

    <?= $form->field($model, 'detalle_id')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
