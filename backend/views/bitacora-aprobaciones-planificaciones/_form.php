<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BitacoraAprobacionesPlanificaciones */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bitacora-aprobaciones-planificaciones-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tipo_documento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link_pdf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'enviado_a')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'creado_por')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_creado')->textInput() ?>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
