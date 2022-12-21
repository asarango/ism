<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmGrupoPlanInterdiciplinar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-grupo-plan-interdiciplinar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_bloque')->textInput() ?>

    <?= $form->field($model, 'id_op_course')->textInput() ?>

    <?= $form->field($model, 'nombre_grupo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_periodo')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>