<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TocPlanUnidadDetalleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toc-plan-unidad-detalle-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'toc_plan_unidad_id') ?>

    <?= $form->field($model, 'evaluacion_pd') ?>

    <?= $form->field($model, 'descripcion_unidad') ?>

    <?= $form->field($model, 'preguntas_conocimiento') ?>

    <?php // echo $form->field($model, 'conocimientos_esenciales') ?>

    <?php // echo $form->field($model, 'actividades_principales') ?>

    <?php // echo $form->field($model, 'enfoques_aprendizaje') ?>

    <?php // echo $form->field($model, 'funciono_bien') ?>

    <?php // echo $form->field($model, 'no_funciono_bien') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
