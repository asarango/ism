<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MessageGroupUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-group-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'message_group_id')->textInput() ?>

    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
