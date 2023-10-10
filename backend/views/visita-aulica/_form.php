<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VisitaAulica */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visita-aulica-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'clase_id')->textInput() ?>

    <?= $form->field($model, 'estudiantes_asistidos')->textInput() ?>

    <?= $form->field($model, 'aplica_grupal')->checkbox() ?>

    <?= $form->field($model, 'psicologo_usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'hora_inicio')->textInput() ?>

    <?= $form->field($model, 'hora_finalizacion')->textInput() ?>

    <?= $form->field($model, 'observaciones_al_docente')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fecha_firma_dece')->textInput() ?>

    <?= $form->field($model, 'fecha_firma_docente')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
