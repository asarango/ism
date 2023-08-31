<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Insumos';
$this->params['breadcrumbs'][] = ['label' => 'Semanas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;





// echo "<pre>";
// print_r($ods);
// die();

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

    .radio-container {
        margin-top: 10px;
        display: flex;
        align-items: center;
    }

    .radio-button {
        margin-right: 10px;
    }

    #optionText {
        display: none;
        margin-top: 10px;
    }

    .info-icon {
        font-size: 20px;
        margin-left: 5px;
        cursor: pointer;
    }

    .info-icon:hover::before {
        content: attr(title);
        position: absolute;
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 5px;
        border-radius: 5px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
    }

    .info-icon:hover::before {
        opacity: 1;
        visibility: visible;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        10%,
        30%,
        50%,
        70%,
        90% {
            transform: translateX(-3px);
        }

        20%,
        40%,
        60%,
        80% {
            transform: translateX(3px);
        }
    }

    .shake {
        animation: shake 2s ease-in-out 1s infinite;
    }

    #ODS {
        display: none;
    }
</style>

<div class="Tareas-form">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8 ">
            <div class="row align-items-center p-2">

                <div class="col-lg-1 col-md-1 col-sm-1">
                    <h4><img src="../ISM/main/images/submenu/plan.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                    <p>

                        <?= '<b><small>' . $semana->clase->paralelo->course->name . ' ' . '"' . $semana->clase->paralelo->name .
                            '' . '"' . '/' . ' ' . $semana->clase->profesor->last_name . ' ' . $semana->clase->profesor->x_first_name . ' ' . '/' . ' ' . 'Hora de clase:' .
                            ' ' . $semana->hora->nombre . ' ' . ' ' . '/' . 'Materia: ' . ' ' . $semana->clase->ismAreaMateria->materia->nombre .
                            '</small></b>' ?>

                    </p>
                </div>
                <!-- BOTONES -->
                <div class="col-lg-3 col-md-3" style="text-align: right;">
                    <?= Html::button(
                        '<span class="badge rounded-pill" style="background-color: #ff9e18"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up"
                        width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" 
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                       <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                       <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                       <path d="M19 22v-6" />
                       <path d="M22 19l-3 -3l-3 3" />
                       </svg>Regresar</span>',
                        ['class' => 'btn btn-default', 'onclick' => 'history.go(-1); return false;']
                    ) ?>
                </div>
                <hr>
            </div>
            <div>
                <div class="card" style="padding: 1rem; justify-content: center; margin-bottom: 1rem;">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'title')->textInput() ?>
                    <?= $form->field($model, 'plan_semanal_id')->hiddenInput(['value' => $semana->id])->label(false) ?>
                    <?= $form->field($model, 'tipo_actividad_id')->dropDownList($tipoActividades, ['prompt' => 'Seleccionar tipo de actividad']) ?>

                    <div method="post" class="form-group field-model-fecha_inicio">
                        <label class="control-label" for="model-fecha_inicio">Fecha de inicio</label>
                        <input type="date" id="model-fecha_inicio" class="form-control" name="inicio">
                    </div>

                    <div method="post" class="form-group field-model-fecha_fin">
                        <label class="control-label" for="model-fecha_fin">Fecha de fin</label>
                        <input type="date" id="model-fecha_fin" class="form-control" name="fin">
                    </div>

                    <div class="" style="margin-top: 10px;">
                        <h6>
                            ¿Quieres calificar competencias ODS?
                            <input type="checkbox" id="mostrar-ods">
                            <span class="info-icon" title="Este campo es opcional"><svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-info-hexagon shake" width="20" height="20"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#9e9e9e" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
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
    </div>
</div>

<script>
    const checkbox = document.getElementById('mostrar-ods');
    const additionalInfo = document.getElementById('ODS');

    checkbox.addEventListener('change', function () {
        additionalInfo.style.display = this.checked ? 'block' : 'none';
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quitarSeleccionButton = document.getElementById('quitar-seleccion-button');

        quitarSeleccionButton.addEventListener('click', function () {
            const radioButtons = document.querySelectorAll('input[type="radio"]');

            radioButtons.forEach(function (radioButton) {
                radioButton.checked = false;
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radioButtons = document.querySelectorAll('input[type="radio"]');

        radioButtons.forEach(function (radioButton) {
            radioButton.addEventListener('change', function () {
                if (this.checked) {
                    const confirmMessage = "Estás marcando una competencia ODS. ¿Estás seguro que deseas continuar? En caso de no necesitarlo puedes eliminar la selección con el boton 'Quitar selección'."; ;
                    const userConfirmed = window.confirm(confirmMessage);

                    if (!userConfirmed) {
                        this.checked = false; // Desmarca el radio button si el usuario cancela
                    }
                }
            });
        });

        const quitarSeleccionButton = document.getElementById('quitar-seleccion-button');

        quitarSeleccionButton.addEventListener('click', function () {
            radioButtons.forEach(function (radioButton) {
                radioButton.checked = false;
            });
        });
    });
</script>


<!-- Da un mensaje de confirmación en caso de haberse marcado un radio button y enviar el formulario 

 <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form'); // Cambia esto para seleccionar el formulario adecuado

        form.addEventListener('submit', function (event) {
            const radioButtons = document.querySelectorAll('input[type="radio"]');
            let radioButtonChecked = false;

            radioButtons.forEach(function (radioButton) {
                if (radioButton.checked) {
                    radioButtonChecked = true;
                }
            });

            if (radioButtonChecked) {
                const confirmMessage = "Has seleccionado un radio button. ¿Estás seguro que deseas continuar?";
                const userConfirmed = window.confirm(confirmMessage);

                if (!userConfirmed) {
                    event.preventDefault(); // Cancela el envío del formulario si el usuario cancela
                }
            }
        });

        const quitarSeleccionButton = document.getElementById('quitar-seleccion-button');

        quitarSeleccionButton.addEventListener('click', function () {
            const radioButtons = document.querySelectorAll('input[type="radio"]');

            radioButtons.forEach(function (radioButton) {
                radioButton.checked = false;
            });
        });
    });
</script> -->