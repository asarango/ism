<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ViewActividadCrear */
/* @var $form yii\widgets\ActiveForm */


// echo "<pre>";
// print_r($listaActividades);
// die();
// $listaActividades = array(
//     'Lecciones de revisión' => 'Lecciones de revisión ( APORTES / INDIVIDUAL )',
//     'Pruebas de base estructuradas' => 'Pruebas de base estructuradas ( APORTES / INDIVIDUAL )',
//     'Tareas en clase' =>  'Tareas en clase ( APORTES / INDIVIDUAL )',
//     'Proyectos y/o investigaciones i' => 'Proyectos y/o investigaciones i ( APORTES / INDIVIDUAL )',
//     'Proyectos y/o investigaciones' => 'Proyectos y/o investigaciones ( APORTES / GRUPAL )',
//     'Exposiciones foros' => 'Exposiciones foros ( APORTES / GRUPAL )',
//     'Talleres' => 'Talleres ( APORTES / GRUPAL )',
//     'Desarrollo de productos' => 'Desarrollo de productos ( APORTES / GRUPAL )',
//     'Se aplica metodología ABP' => 'Se aplica metodología ABP ( PROYECTO INTERDISCIPLINARIO / PROYECTO )',
//     'Evaluación de base estructurada' => 'Evaluación de base estructurada ( EVALUACIÓN DE PERIODO / EVALUACION )'
// );



?>

<style>
    .card-ods {
        font-weight: bold;
        font-size: 0.8rem;
    }

    .ods h5 {
        font-weight: bold;
        color: black;
    }

    #ODS {
        display: none;
    }
</style>

<div class="view-actividad-crear-form">

    <div>
        <div class="card" style="padding: 1rem; justify-content: center; margin-bottom: 1rem;">

            <?php $form = ActiveForm::begin(); ?>

            <!-- faltan 2 campos titlo y tipo de actividad -->

            <?= $form->field($model, 'title')->textInput() ?>
            <?= $form->field($model, 'plan_semanal_id')->hiddenInput(['value' => $planSemanalId])->label(false) ?>
            <?= $form->field($model, 'tipo_actividad_id')->dropDownList($listaActividades, ['prompt' => 'Seleccionar tipo de actividad']) ?>

            <div class="row">
                <div class="col-md-6" method="post" class="form-group field-model-fecha_inicio">
                    <label class="control-label" for="model-fecha_inicio">Fecha de inicio</label>
                    <input type="date" id="model-fecha_inicio" class="form-control" name="inicio">
                </div>

                <div class="col-md-6" method="post" class="form-group field-model-fecha_fin">
                    <label class="control-label" for="model-fecha_fin">Fecha de fin</label>
                    <input type="date" id="model-fecha_fin" class="form-control" name="fin">
                </div>
            </div>

            <div class="" style="margin-top: 10px;">
                <h6>
                    ¿Quieres calificar competencias ODS?
                    <input type="checkbox" id="mostrar-ods">
                    <span class="info-icon" title="Este campo es opcional"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-hexagon shake" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                            <path d="M12 9h.01" />
                            <path d="M11 12h1v4h1" />
                        </svg></span>
                </h6>
            </div>
            <div class="row ods" style="margin-top: 1rem;justify-content: center;" id="ODS">
                <!-- <div style="display: flex; align-items: center;">

                            <h5 style="text-align: center;">Competencias ODS elegidas:</h5>

                        </div> -->
                <ul>
                    <?php
                    $lista = ArrayHelper::map($ods, 'ods_pud_dip_id', 'opcion_texto');

                    echo $form->field($model, 'ods_pud_dip_id')->radioList($lista, [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return '<li><label>' . Html::radio($name, $checked, ['value' => $value]) . $label . '</label></li>';
                        },
                    ])->label(false);
                    ?>
                </ul>

                <button id="quitar-seleccion-button" type="button" class="btn btn-danger">Quitar
                    selección</button>
            </div>

            <div style="margin-top: 1rem">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>


<script>
    const checkbox = document.getElementById('mostrar-ods');
    const additionalInfo = document.getElementById('ODS');

    checkbox.addEventListener('change', function() {
        additionalInfo.style.display = this.checked ? 'block' : 'none';
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quitarSeleccionButton = document.getElementById('quitar-seleccion-button');

        quitarSeleccionButton.addEventListener('click', function() {
            const radioButtons = document.querySelectorAll('input[type="radio"]');

            radioButtons.forEach(function(radioButton) {
                radioButton.checked = false;
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioButtons = document.querySelectorAll('input[type="radio"]');

        radioButtons.forEach(function(radioButton) {
            radioButton.addEventListener('change', function() {
                if (this.checked) {
                    const confirmMessage = "Estás marcando una competencia ODS. ¿Estás seguro que deseas continuar? En caso de no necesitarlo puedes eliminar la selección con el boton 'Quitar selección'.";;
                    const userConfirmed = window.confirm(confirmMessage);

                    if (!userConfirmed) {
                        this.checked = false; // Desmarca el radio button si el usuario cancela
                    }
                }
            });
        });

        const quitarSeleccionButton = document.getElementById('quitar-seleccion-button');

        quitarSeleccionButton.addEventListener('click', function() {
            radioButtons.forEach(function(radioButton) {
                radioButton.checked = false;
            });
        });
    });
</script>