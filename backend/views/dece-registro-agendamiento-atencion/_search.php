<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroAgendamientoAtencionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dece-registro-agendamiento-atencion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_reg_seguimiento') ?>

    <?= $form->field($model, 'fecha_inicio') ?>

    <?= $form->field($model, 'fecha_fin') ?>

    <?= $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'pronunciamiento') ?>

    <?php // echo $form->field($model, 'acuerdo_y_compromiso') ?>

    <?php // echo $form->field($model, 'evidencia') ?>

    <?php // echo $form->field($model, 'path_archivo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
