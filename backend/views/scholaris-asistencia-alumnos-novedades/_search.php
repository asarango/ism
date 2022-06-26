<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaAlumnosNovedadesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-asistencia-alumnos-novedades-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'asistencia_profesor_id') ?>

    <?= $form->field($model, 'comportamiento_detalle_id') ?>

    <?= $form->field($model, 'observacion') ?>

    <?= $form->field($model, 'grupo_id') ?>

    <?php // echo $form->field($model, 'es_justificado')->checkbox() ?>

    <?php // echo $form->field($model, 'codigo_justificacion') ?>

    <?php // echo $form->field($model, 'acuerdo_justificacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
