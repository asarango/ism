<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\InspFechaPeriodoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insp-fecha-periodo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'periodo_id') ?>

    <?= $form->field($model, 'numero_dia') ?>

    <?= $form->field($model, 'hay_asitencia')->checkbox() ?>

    <?= $form->field($model, 'es_presencial')->checkbox() ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
