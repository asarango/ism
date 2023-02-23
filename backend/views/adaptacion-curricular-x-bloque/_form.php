<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdaptacionCurricularXBloque */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="adaptacion-curricular-xbloque-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_nee_x_clase')->textInput() ?>

    <?= $form->field($model, 'id_scholaris_bloque')->textInput() ?>

    <?= $form->field($model, 'adaptacion_curricular')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'creado_por')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_creacion')->textInput() ?>

    <?= $form->field($model, 'actualizado_por')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_actualizacion')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
