<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmMallaArea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-malla-area-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'area_id')->textInput() ?>

    <?= $form->field($model, 'periodo_malla_id')->textInput() ?>

    <?= $form->field($model, 'promedia')->checkbox() ?>

    <?= $form->field($model, 'imprime_libreta')->checkbox() ?>

    <?= $form->field($model, 'es_cuantitativa')->checkbox() ?>

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'porcentaje')->textInput() ?>

    <?= $form->field($model, 'orden')->textInput() ?>

    <hr>
    <div class="form-group">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
