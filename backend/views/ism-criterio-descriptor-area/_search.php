<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmCriterioDescriptorAreaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-criterio-descriptor-area-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_area') ?>

    <?= $form->field($model, 'id_curso') ?>

    <?= $form->field($model, 'id_criterio') ?>

    <?= $form->field($model, 'id_literal_criterio') ?>

    <?php // echo $form->field($model, 'id_descriptor') ?>

    <?php // echo $form->field($model, 'id_literal_descriptor') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
