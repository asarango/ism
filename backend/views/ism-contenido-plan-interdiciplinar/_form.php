<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmContenidoPlanInterdiciplinar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-contenido-plan-interdiciplinar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_seccion_interdiciplinar')->textInput() ?>

    <?= $form->field($model, 'nombre_campo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'activo')->checkbox() ?>

    <?= $form->field($model, 'heredado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
