<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFaltas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-faltas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'scholaris_perido_id')->textInput() ?>

    <?= $form->field($model, 'student_id')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'fecha_solicitud_justificacion')->textInput() ?>

    <?= $form->field($model, 'motivo_justificacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'es_justificado')->checkbox() ?>

    <?= $form->field($model, 'fecha_justificacion')->textInput() ?>

    <?= $form->field($model, 'respuesta_justificacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'usuario_justifica')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
