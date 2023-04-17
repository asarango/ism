<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\LmsActividadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lms-actividad-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lms_id') ?>

    <?= $form->field($model, 'tipo_actividad_id') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?php // echo $form->field($model, 'tarea') ?>

    <?php // echo $form->field($model, 'material_apoyo') ?>

    <?php // echo $form->field($model, 'es_calificado')->checkbox() ?>

    <?php // echo $form->field($model, 'es_publicado')->checkbox() ?>

    <?php // echo $form->field($model, 'es_aprobado')->checkbox() ?>

    <?php // echo $form->field($model, 'retroalimentacion') ?>

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
