<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceDerivacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dece-derivacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tipo_derivacion') ?>

    <?= $form->field($model, 'id_estudiante') ?>

    <?= $form->field($model, 'nombre_quien_deriva') ?>

    <?= $form->field($model, 'fecha_derivacion') ?>

    <?php // echo $form->field($model, 'motivo_referencia') ?>

    <?php // echo $form->field($model, 'historia_situacion_actual') ?>

    <?php // echo $form->field($model, 'accion_desarrollada') ?>

    <?php // echo $form->field($model, 'tipo_ayuda') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
