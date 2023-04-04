<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceSeguimientoAcuerdosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dece-seguimiento-acuerdos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_reg_seguimiento') ?>

    <?= $form->field($model, 'secuencial') ?>

    <?= $form->field($model, 'acuerdo') ?>

    <?= $form->field($model, 'responsable') ?>

    <?php // echo $form->field($model, 'fecha_max_cumplimiento') ?>

    <?php // echo $form->field($model, 'cumplio')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
