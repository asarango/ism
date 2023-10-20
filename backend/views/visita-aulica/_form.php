<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VisitaAulica */
/* @var $form yii\widgets\ActiveForm */

// echo "<pre>";
// print_r($clase);
// die();
//obtiene la hora actual
$hora_actual = date('H:i');
//le agrega a la hora actual 30min
$hora_nueva = date('H:i', strtotime($hora_actual . ' +30 minutes'));

?>

<div class="visita-aulica-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'clase_id')->hiddenInput(['value' => $clase->id])->label(false) ?>

    <?= $form->field($model, 'bloque_id')->hiddenInput(['value' => $trimestre->id])->label(false) ?>

    <!-- si es nuevo debe ser 0 X-->

    <?= $form->field($model, 'estudiantes_asistidos')->hiddenInput(['value' => $model->isNewRecord ? 0 : $model->estudiantes_asistidos])->label(false) ?>

    <!-- capturar desde variable X-->

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'psicologo_usuario')->textInput([
                'maxlength' => true,
                'value' => $clase->paralelo->dece_nombre,
                'readonly' => true,
            ]) ?>

        </div>
        <!-- poner fecha actual por defecto X-->

        <div class="col-lg-6">
            <?= $form->field($model, 'fecha')->textInput(['value' => date('Y-m-d')]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <!-- poner fecha actual por defecto -->

            <?= $form->field($model, 'hora_inicio')->textInput(['value' => date('H:i')]) ?>
        </div>
        <div class="col-lg-6 col-md-6">
            <!-- se agrega media hora mas de la actual por defecto -->

            <?= $form->field($model, 'hora_finalizacion')->textInput(['value' => $hora_nueva]) ?>
        </div>
    </div>

    <!-- se deja momentáneamente -->

    <?= $form->field($model, 'observaciones_al_docente')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fecha_firma_dece')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'fecha_firma_docente')->hiddenInput()->label(false) ?>

    <div style="margin-top: 5px; text-align: center;">
        <?= $form->field($model, 'aplica_grupal')->checkbox() ?>
    </div>

    <div class="form-group" style="margin-top: 20px;margin-bottom: 5px; text-align: center;">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>