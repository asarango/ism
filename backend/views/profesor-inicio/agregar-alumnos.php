<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\ScholarisActividad;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agregar Alumnos';

// echo "<pre>";
// print_r($cabecera);
// die();
// $alumnos = obtenerAlumnos();
?>

<style>
    .alumno-list {
        list-style: none;
        padding: 0;
    }

    .alumno-item {
        margin-bottom: 8px;
        font-size: 14px;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />


<div class="agregar-alumnos">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class="row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-9">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                    <small>
                        (
                        <?=
                        $cabecera[0]['materia'] . ' - ' .
                            $cabecera[0]['curso'] . ' ' . $cabecera[0]['paralelo'];
                        ?>
                        )
                    </small>
                </div>
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <?= Html::button(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                        </svg>Volver</span>',
                        ['class' => 'btn btn-default', 'onclick' => 'history.go(-1); return false;']
                    ) ?>
                </div>
                <hr>
            </div>

            <div class="row justify-content-center">

                <div class="col-lg-6">
                    <div style="text-align: center;">
                        <h5>Listado de Alumnos</h5>
                        <div style="margin: 1rem 0 1rem 0;">
                            <?php
                            $alumnosCount = count($alumnos);
                            echo "Cantidad de alumnos: " . $alumnosCount;
                            ?>

                        </div>
                        <table class="table table-bordered">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                            </tr>
                            <?php
                            $numeroEstudiante = 1;
                            foreach ($alumnos as $alumno) {
                                echo '<tr>';
                                echo '<td>' . $numeroEstudiante . '</td>';
                                echo '<td>' . $alumno['estudiante'] . '</td>';
                                echo '</tr>';
                                $numeroEstudiante++;
                            }
                            ?>
                        </table>
                    </div>
                </div>


                <div id="alumnosDiv" class="col-lg-6">

                    <!-- <button id="mostrarDiv" class="btn btn-warning" style="color: white;">Seleccionar Alumnos</button>
                    <div id="miDiv" > -->

                    <div style="margin: 1rem 0 1rem 0; text-align: center;"><b style="font-size: 20px;">Seleccionar Alumnos</b><br>
                        <?php
                        $alumnosSelec = count($alumnoSeleccionado);
                        echo "Total alumnos Seleccionados: " . $alumnosSelec;
                        ?>

                    </div>

                    <?php echo Html::beginForm(['registrar-alumno-clase', 'post']); ?>
                    <select class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true" name="estudiante_id" required>

                        <option value="">Escoje un alumno</option>
                        <?php
                        foreach ($alumnos as $alumno) {
                            echo '<option value="' . $alumno['student_id'] . '">' . $alumno['estudiante'] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="hidden" name="clase_id" value="<?= $clase->id ?>">
                    <div style="margin: 1rem 0 1rem 0;">
                        <?= Html::submitButton('Agregar',  ['class' => 'btn btn-success']) ?>
                    </div>

                    <table class="table table-bordered">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                        </tr>
                        <?php
                        $numeroEstudiante = 1;
                        foreach ($alumnoSeleccionado as $alumnos) {
                            echo '<tr>';
                            echo '<td>' . $numeroEstudiante . '</td>';
                            echo '<td>' . $alumnos['estudiante'] . '</td>';
                            echo '</tr>';
                            $numeroEstudiante++;
                        }
                        ?>
                    </table>

                </div>
            </div>
            <?php echo Html::endForm(); ?>
        </div>
    </div>
</div>
</div>


<script>
    $(document).ready(function() {
        $('#mostrarDiv').click(function() {
            $('#miDiv').show();
            buscador();
        });
    });
</script>

<script>
    buscador();

    function buscador() {
        $('.select2').select2({
            closeOnSelect: true
        });
    }
</script>

<script>
    // Obtener el valor de la variable PHP
    var todosAlumnos = <?= $clase->todos_alumnos ?>;

    // Función para mostrar u ocultar el div según el valor de todosAlumnos
    function mostrarOcultarDiv() {
        var div = document.getElementById("alumnosDiv");
        if (todosAlumnos === 0) {
            div.style.display = "block"; // Mostrar el div
        } else {
            div.style.display = "none"; // Ocultar el div
        }
    }

    //Llamar a la función al cargar la página para configurar el estado inicial
    window.onload = mostrarOcultarDiv;
</script>