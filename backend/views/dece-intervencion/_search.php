<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dece-intervencion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_estudiante') ?>

    <?= $form->field($model, 'fecha_intervencion') ?>

    <?= $form->field($model, 'razon') ?>

    <?= $form->field($model, 'id_area') ?>

    <?php // echo $form->field($model, 'otra_area') ?>

    <?php // echo $form->field($model, 'acciones_responsables') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
