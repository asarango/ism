<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionSemanalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="planificacion-semanal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'semana_id') ?>

    <?= $form->field($model, 'clase_id') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'hora_id') ?>

    <?php // echo $form->field($model, 'orden_hora_semana') ?>

    <?php // echo $form->field($model, 'tema') ?>

    <?php // echo $form->field($model, 'actividades') ?>

    <?php // echo $form->field($model, 'diferenciacion_nee') ?>

    <?php // echo $form->field($model, 'recursos') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
