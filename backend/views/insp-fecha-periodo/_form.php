<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\InspFechaPeriodo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insp-fecha-periodo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'periodo_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'numero_dia')->textInput() ?>

    <?= $form->field($model, 'hay_asitencia')->checkbox() ?>

    <?= $form->field($model, 'es_presencial')->checkbox() ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <hr />
    <div class="form-group">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
