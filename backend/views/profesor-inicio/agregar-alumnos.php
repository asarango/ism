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
// print_r($alumnos);
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
                </div>
                <div class="col-lg-2">
                    <!-- botones -->
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


                <div class="col-lg-6">

                    <!-- <button id="mostrarDiv" class="btn btn-warning" style="color: white;">Seleccionar Alumnos</button>
                    <div id="miDiv" > -->

                    <div style="margin: 1rem 0 1rem 0; text-align: center;"><b style="font-size: 20px;">Seleccionar Alumnos</b><br>
                        <?php
                        $alumnosCount = count($alumnos);
                        echo "Cantidad de alumnos disponibles: " . $alumnosCount;
                        ?>

                    </div>

                    <?php echo Html::beginForm(['registrar-alumno-clase', 'post']); ?>
                    <select class="form-control select2 select2-hidden-accessible" 
                    style="width: 99%;" tabindex="-1" aria-hidden="true" name="estudiante_id" required>
                    
                        <option value="" >Escoje un alumno</option>
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