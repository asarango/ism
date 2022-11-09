<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmCriterioDescriptorArea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-criterio-descriptor-area-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_area')->textInput() ?>

    <?= $form->field($model, 'id_curso')->textInput() ?>

    <?= $form->field($model, 'id_criterio')->textInput() ?>

    <?= $form->field($model, 'id_literal_criterio')->textInput() ?>

    <?= $form->field($model, 'id_descriptor')->textInput() ?>

    <?= $form->field($model, 'id_literal_descriptor')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
