<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencionCompromisoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dece-intervencion-compromiso-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_dece_intervencion') ?>

    <?= $form->field($model, 'comp_estudiante') ?>

    <?= $form->field($model, 'comp_representante') ?>

    <?= $form->field($model, 'comp_docente') ?>

    <?php // echo $form->field($model, 'comp_dece') ?>

    <?php // echo $form->field($model, 'fecha_max_cumplimiento') ?>

    <?php // echo $form->field($model, 'revision_compromiso') ?>

    <?php // echo $form->field($model, 'esaprobado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
