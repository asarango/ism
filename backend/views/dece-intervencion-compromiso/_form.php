<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencionCompromiso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dece-intervencion-compromiso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_dece_intervencion')->textInput() ?>

    <?= $form->field($model, 'comp_estudiante')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comp_representante')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comp_docente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comp_dece')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_max_cumplimiento')->textInput() ?>

    <?= $form->field($model, 'revision_compromiso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'esaprobado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
