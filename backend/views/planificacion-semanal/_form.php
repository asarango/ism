<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionSemanal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="planificacion-semanal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'semana_id')->textInput() ?>

    <?= $form->field($model, 'clase_id')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'hora_id')->textInput() ?>

    <?= $form->field($model, 'orden_hora_semana')->textInput() ?>

    <?= $form->field($model, 'tema')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'actividades')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'diferenciacion_nee')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'recursos')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
