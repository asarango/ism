<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionSemanalRecursosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="planificacion-semanal-recursos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'plan_semanal_id') ?>

    <?= $form->field($model, 'tema') ?>

    <?= $form->field($model, 'tipo_recurso') ?>

    <?= $form->field($model, 'url_recurso') ?>

    <?php // echo $form->field($model, 'estado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
