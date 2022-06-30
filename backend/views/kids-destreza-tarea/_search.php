<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KidsDestrezaTareaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kids-destreza-tarea-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'plan_destreza_id') ?>

    <?= $form->field($model, 'fecha_presentacion') ?>

    <?= $form->field($model, 'detalle_tarea') ?>

    <?= $form->field($model, 'materiales') ?>

    <?php // echo $form->field($model, 'publicado_al_estudiante')->checkbox() ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'upated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
