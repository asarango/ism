<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaAlumnosNovedades */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-asistencia-alumnos-novedades-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'asistencia_profesor_id')->textInput() ?>

    <?= $form->field($model, 'comportamiento_detalle_id')->textInput() ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'grupo_id')->textInput() ?>

    <?= $form->field($model, 'es_justificado')->checkbox() ?>

    <?= $form->field($model, 'codigo_justificacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acuerdo_justificacion')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
