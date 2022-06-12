<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmAreaMateriaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-area-materia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'malla_area_id') ?>

    <?= $form->field($model, 'materia_id') ?>

    <?= $form->field($model, 'promedia')->checkbox() ?>

    <?= $form->field($model, 'porcentaje') ?>

    <?php // echo $form->field($model, 'imprime_libreta')->checkbox() ?>

    <?php // echo $form->field($model, 'es_cuantitativa')->checkbox() ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'asignatura_curriculo_id') ?>

    <?php // echo $form->field($model, 'curso_curriculo_id') ?>

    <?php // echo $form->field($model, 'orden') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
