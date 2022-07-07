<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceCasosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dece-casos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'numero_caso') ?>

    <?= $form->field($model, 'id_estudiante') ?>

    <?= $form->field($model, 'id_periodo') ?>

    <?= $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'fecha_inicio') ?>

    <?php // echo $form->field($model, 'fecha_fin') ?>

    <?php // echo $form->field($model, 'detalle') ?>

    <?php // echo $form->field($model, 'id_usuario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
