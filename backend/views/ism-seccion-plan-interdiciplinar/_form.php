<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmSeccionPlanInterdiciplinar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-seccion-plan-interdiciplinar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'num_seccion')->textInput() ?>

    <?= $form->field($model, 'nombre_seccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'activo')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
