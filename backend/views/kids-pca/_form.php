<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsPca */
/* @var $form yii\widgets\ActiveForm */

// echo '<pre>';
// print_r($modelIsmAreaMateria);
?>

<div class="kids-pca-form" style="margin-bottom:5px; padding:10px; padding-left:50px; padding-right:50px; font-size:12px ">

    <?php $form = ActiveForm::begin([
        'options' => ['style' => 'font-size: 10px']
    ]); ?>

    <?= $form->field($modelPca, 'op_course_id')->hiddenInput(['value' => $modelPca->op_course_id])->label(false) ?>
    <div class="row">
        <div class="col-md-4 col-sm-4">
            <?= $form->field($modelPca, 'carga_horaria_semanal')->textInput([
                'type' => 'number',
                'style' => 'font-size: 10px'              
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-4">
            <?= $form->field($modelPca, 'numero_semanas_trabajo')->textInput([
                'type' => 'number',
                'style' => 'font-size: 10px'  
            ])->label('Nro. Semanas de trabajo') ?>
        </div>
        <div class="col-md-4 col-sm-4">
            <?= $form->field($modelPca, 'imprevistos')->textInput([
                'type' => 'number',
                'style' => 'font-size: 10px'  
            ])->label('Nro. Imprevistos') ?>
        </div>
    </div>


    <?= $form->field($modelPca, 'objetivos')->textarea(['rows' => 2, 'style' => 'font-size: 10px'  ]) ?>

    <?= $form->field($modelPca, 'observaciones')->textarea(['rows' => 2, 'style' => 'font-size: 10px'  ]) ?>

    <?= $form->field($modelPca, 'bibliografia')->textarea(['rows' => 2, 'style' => 'font-size: 10px'  ]) ?>

    <?php
    if ($modelPca->isNewRecord) {
        echo $form->field($modelPca, 'estado')->hiddenInput(['maxlength' => true, 'value' => 'INICIANDO'])->label(false);
    } else {
        echo $form->field($modelPca, 'estado')->hiddenInput(['maxlength' => true])->label(false);
    }
    ?>

    <?= $form->field($modelPca, 'created_at')->hiddenInput(['value' => $today])->label(false) ?>

    <?= $form->field($modelPca, 'created')->hiddenInput(['value' => $userLog])->label(false) ?>

    <?= $form->field($modelPca, 'updated_at')->hiddenInput(['value' => $today])->label(false) ?>

    <?= $form->field($modelPca, 'updated')->hiddenInput(['value' => $userLog])->label(false) ?>

    <div class="form-group" style="margin-top:10px">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>


