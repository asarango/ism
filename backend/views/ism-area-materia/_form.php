<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmAreaMateria */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ism-area-materia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'malla_area_id')->textInput() ?>

    <?= $form->field($model, 'materia_id')->textInput() ?>

    <?= $form->field($model, 'promedia')->checkbox() ?>

    <?= $form->field($model, 'porcentaje')->textInput() ?>

    <?= $form->field($model, 'imprime_libreta')->checkbox() ?>

    <?= $form->field($model, 'es_cuantitativa')->checkbox() ?>

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'es_bi')->radioList([0 => 'NO', 1=>'SI']) ?>

    <?= $form->field($model, 'asignatura_curriculo_id')->textInput() ?>

    <?= $form->field($model, 'curso_curriculo_id')->textInput() ?>

    <?= $form->field($model, 'orden')->textInput() ?>
    
    <?= $form->field($model, 'total_horas_semana')->textInput() ?>

    <?php 
        $lista = ArrayHelper::map($docentes, 'usuario', 'usuario');
        echo $form->field($model, 'responsable_planificacion')->dropDownlist(
            $lista, ['prompt' => 'Seleccione docente...']
        );
    ?>

    <?= $form->field($model, 'idioma')->dropDownList([
        'es' => 'Español',
        'en' => 'Inglés',
        'fr' => 'Francés'
    ]) ?>
    
    <hr>
    
    <div class="form-group">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
