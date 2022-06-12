<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmPeriodoMalla */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-periodo-malla-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'malla_id')->textInput() ?>

    <?= $form->field($model, 'scholaris_periodo_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
