<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmRespuestaPlanInterdiciplinarSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-respuesta-plan-interdiciplinar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_grupo_plan_inter') ?>

    <?= $form->field($model, 'id_contenido_plan_inter') ?>

    <?= $form->field($model, 'respuesta') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
