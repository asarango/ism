<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdaptacionCurricularXBloqueSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="adaptacion-curricular-xbloque-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_nee_x_clase') ?>

    <?= $form->field($model, 'id_scholaris_bloque') ?>

    <?= $form->field($model, 'adaptacion_curricular') ?>

    <?= $form->field($model, 'creado_por') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'actualizado_por') ?>

    <?php // echo $form->field($model, 'fecha_actualizacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
