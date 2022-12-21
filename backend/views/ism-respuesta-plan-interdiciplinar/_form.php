<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmRespuestaPlanInterdiciplinar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-respuesta-plan-interdiciplinar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_grupo_plan_inter')->textInput() ?>

    <?= $form->field($model, 'id_contenido_plan_inter')->textInput() ?>

    <?= $form->field($model, 'respuesta')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
