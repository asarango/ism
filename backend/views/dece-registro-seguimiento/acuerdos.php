<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\DeceSeguimientoAcuerdos;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceSeguimientoAcuerdos */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="ddece-registro-seguimiento-acuerdos">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_reg_seguimiento')->textInput() ?>

    <?= $form->field($model, 'secuencial')->textInput() ?>

    <?= $form->field($model, 'acuerdo')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'responsable')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_max_cumplimiento')->textInput() ?>

    <?= $form->field($model, 'cumplio')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
