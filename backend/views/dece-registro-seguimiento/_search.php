<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroSeguimientoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dece-registro-seguimiento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_clase') ?>

    <?= $form->field($model, 'id_estudiante') ?>

    <?= $form->field($model, 'fecha_inicio') ?>

    <?= $form->field($model, 'fecha_fin') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'motivo') ?>

    <?php // echo $form->field($model, 'submotivo') ?>

    <?php // echo $form->field($model, 'submotivo2') ?>

    <?php // echo $form->field($model, 'persona_solicitante') ?>

    <?php // echo $form->field($model, 'atendido_por') ?>

    <?php // echo $form->field($model, 'atencion_para') ?>

    <?php // echo $form->field($model, 'responsable_seguimiento') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
