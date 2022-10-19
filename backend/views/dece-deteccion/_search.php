<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceDeteccionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dece-deteccion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'numero_deteccion') ?>

    <?= $form->field($model, 'id_estudiante') ?>

    <?= $form->field($model, 'id_caso') ?>

    <?= $form->field($model, 'numero_caso') ?>

    <?php // echo $form->field($model, 'nombre_estudiante') ?>

    <?php // echo $form->field($model, 'anio') ?>

    <?php // echo $form->field($model, 'paralelo') ?>

    <?php // echo $form->field($model, 'nombre_quien_reporta') ?>

    <?php // echo $form->field($model, 'cargo') ?>

    <?php // echo $form->field($model, 'cedula') ?>

    <?php // echo $form->field($model, 'fecha_reporte') ?>

    <?php // echo $form->field($model, 'descripcion_del_hecho') ?>

    <?php // echo $form->field($model, 'hora_aproximada') ?>

    <?php // echo $form->field($model, 'acciones_realizadas') ?>

    <?php // echo $form->field($model, 'lista_evidencias') ?>

    <?php // echo $form->field($model, 'path_archivos') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
