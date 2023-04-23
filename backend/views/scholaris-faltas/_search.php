<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFaltasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-faltas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'scholaris_perido_id') ?>

    <?= $form->field($model, 'student_id') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'fecha_solicitud_justificacion') ?>

    <?php // echo $form->field($model, 'motivo_justificacion') ?>

    <?php // echo $form->field($model, 'es_justificado')->checkbox() ?>

    <?php // echo $form->field($model, 'fecha_justificacion') ?>

    <?php // echo $form->field($model, 'respuesta_justificacion') ?>

    <?php // echo $form->field($model, 'usuario_justifica') ?>

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
