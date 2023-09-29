<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FirmarDocumentos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firmar-documentos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tabla_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'documento_id')->textInput() ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cedula')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_firma')->textInput() ?>

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
