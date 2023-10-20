<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ViewStudiantesCvSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="view-studiantes-cv-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'estudiante_id') ?>

    <?= $form->field($model, 'inscription_id') ?>

    <?= $form->field($model, 'seccion') ?>

    <?= $form->field($model, 'curso') ?>

    <?= $form->field($model, 'paralelo') ?>

    <?php // echo $form->field($model, 'estudiante') ?>

    <?php // echo $form->field($model, 'inscription_state') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
