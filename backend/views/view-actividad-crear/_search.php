<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ViewActividadCrearSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="view-actividad-crear-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'plan_id') ?>

    <?= $form->field($model, 'curso') ?>

    <?= $form->field($model, 'paralelo') ?>

    <?= $form->field($model, 'trimestre') ?>

    <?php // echo $form->field($model, 'nombre_semana') ?>

    <?php // echo $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'hora') ?>

    <?php // echo $form->field($model, 'materia') ?>

    <?php // echo $form->field($model, 'tema') ?>

    <?php // echo $form->field($model, 'login') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
