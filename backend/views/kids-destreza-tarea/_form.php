<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsDestrezaTarea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kids-destreza-tarea-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'plan_destreza_id')->textInput() ?>

    <?= $form->field($model, 'fecha_presentacion')->textInput() ?>

    <?= $form->field($model, 'detalle_tarea')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'materiales')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'publicado_al_estudiante')->checkbox() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'upated')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
