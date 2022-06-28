<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsPlanSemanalHoraClaseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kids-plan-semanal-hora-clase-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'plan_semanal_id') ?>

    <?= $form->field($model, 'clase_id') ?>

    <?= $form->field($model, 'detalle_id') ?>

    <?= $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
