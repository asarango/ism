<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmContenidoPlanInterdiciplinarSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-contenido-plan-interdiciplinar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_seccion_interdiciplinar') ?>

    <?= $form->field($model, 'nombre_campo') ?>

    <?= $form->field($model, 'activo')->checkbox() ?>

    <?= $form->field($model, 'heredado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
