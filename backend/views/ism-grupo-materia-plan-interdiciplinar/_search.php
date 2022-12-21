<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmGrupoMateriaPlanInterdiciplinarSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-grupo-materia-plan-interdiciplinar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_grupo_plan_inter') ?>

    <?= $form->field($model, 'id_ism_area_materia') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
