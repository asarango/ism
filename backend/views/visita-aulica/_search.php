<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VisitaAulicaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visita-aulica-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'clase_id') ?>

    <?= $form->field($model, 'estudiantes_asistidos') ?>

    <?= $form->field($model, 'aplica_grupal')->checkbox() ?>

    <?= $form->field($model, 'psicologo_usuario') ?>

    <?php // echo $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'hora_inicio') ?>

    <?php // echo $form->field($model, 'hora_finalizacion') ?>

    <?php // echo $form->field($model, 'observaciones_al_docente') ?>

    <?php // echo $form->field($model, 'fecha_firma_dece') ?>

    <?php // echo $form->field($model, 'fecha_firma_docente') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
