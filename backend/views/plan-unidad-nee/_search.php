<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanUnidadNeeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-unidad-nee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nee_x_unidad_id') ?>

    <?= $form->field($model, 'curriculo_bloque_unidad_id') ?>

    <?= $form->field($model, 'destrezas') ?>

    <?= $form->field($model, 'actividades') ?>

    <?php // echo $form->field($model, 'recursos') ?>

    <?php // echo $form->field($model, 'indicadores_evaluacion') ?>

    <?php // echo $form->field($model, 'tecnicas_instrumentos') ?>

    <?php // echo $form->field($model, 'detalle_pai_dip') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
