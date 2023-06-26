<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TocPlanUnidadDetalle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toc-plan-unidad-detalle-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'toc_plan_unidad_id')->textInput() ?>

    <?= $form->field($model, 'evaluacion_pd')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'descripcion_unidad')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'preguntas_conocimiento')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'conocimientos_esenciales')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'actividades_principales')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'enfoques_aprendizaje')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'funciono_bien')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'no_funciono_bien')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
