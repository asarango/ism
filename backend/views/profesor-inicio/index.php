<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis asignaturas';
//$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($clases);
// die();

// $alumnos = obtenerAlumnos();
?>


<div class="portal-inicio-index animate__animated animate__fadeIn">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/aula.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-7">
                    <h2>
                        <?= Html::encode($this->title) ?>
                    </h2>
                </div>
                <div class="col-lg-4 col-md-3" style="text-align: right; margin-top: -10px;">
                    <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index', 'class' => 'link']); ?>
                    |
                    <!-- <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Agregar Estudiantes</span>', ['agregar-alumnos', 'clase_id' => $clases[0]['clase_id']]); ?> -->
                    <!-- | -->
                    <?= Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fas fa-clock"></i> Leccionario</span>', ['scholaris-asistencia-profesor/index'], ['class' => 'link']); ?>
                </div>
                <hr>
            </div>

            <!-- Fin encabezado -->

            <div class="row" style="padding: 0px 10px 10px 10px;">
                <div style="margin-bottom:20px;">
                    <div class="row" style="margin-bottom:20px; margin-left: 5px;">
                        <div class="col-lg-8 col-md-8">
                            <?= $this->render('menu', [
                                'clases' => $clases
                            ]) ?>
                            <div class="row">

                                <div class="table table-responsive table-bordered">
                                    <!-- <div style="margin: 1rem 0 1rem 0;">
                                        <?php
                                        // $alumnosCount = count($alumnos);
                                        // echo "Cantidad de alumnos disponibles: " . $alumnosCount;
                                        ?>

                                    </div>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                        </tr>
                                        <?php
                                        // $numeroEstudiante = 1;
                                        // foreach ($alumnos as $alumno) {
                                        //     echo '<tr>';
                                        //     echo '<td>' . $numeroEstudiante . '</td>';
                                        //     echo '<td>' . $alumno['estudiante'] . '</td>';
                                        //     echo '</tr>';
                                        //     $numeroEstudiante++;
                                        // }
                                        ?>
                                    </table> -->
                                    <!-- <table class="table table-condensed table-striped table-hover tamano10">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Estudiante</th>
                                                <th>Curso</th>
                                            </tr>
                                        </thead>

                                        <tbody> -->
                                            <?php
                                            // $i = 0;
                                            // foreach ($modelGrupo as $grupo) {
                                            //     $i++;
                                            //     echo '<tr>';
                                            //     echo '<td>' . $i . '</td>';
                                            //     echo '<td>' . $grupo['last_name'] . ' ' . $grupo['first_name'] . ' ' . $grupo['middle_name'] . '</td>';
                                            //     echo '<td>' . $grupo['curso'] . '</td>';
                                            //     echo '<td>' . $grupo['paralelo'] . '</td>';
                                            //     echo '<td>' . $grupo['inscription_state'] . '</td>';
                                            //     echo '<td>';
                                            //     echo Html::a('<p class="tamano10">Retirar</p>', ['scholaris-clase/retirar', 'grupoId' => $grupo['grupo_id']], ['class' => 'btn btn-link']);
                                            //     echo '</td>';
                                            //     echo '</tr>';
                                            // }
                                            ?>
                                    <!-- </tbody>
                                    </table> -->
                                </div>

                            </div>
                        </div>


                        <div class="col-lg-4 col-md-4" style="margin-top:5px;text-align: right">
                            <div id="div-detalle" style="display: none; margin-top: 0px;"></div>
                            <div id="div-semanas" style="display: none; padding: 15px;"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function muestra_detalle(claseId, accion) {
        $("#div-detalle").show();

        var url = '<?= Url::to(['docente-clases/detalle-clase']) ?>';
        var params = {
            clase_id: claseId,
            accion: accion
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () { },
            success: function (response) {
                $("#div-detalle").html(response);
                $("#div-info-bloque").hide();
                $("#div-actividades").hide();
            }
        });

    }


    function muestra_informacion_bloque(claseId, bloqueId, accion) {
        var url = '<?= Url::to(['docente-clases/detalle-bloque']) ?>';
        var params = {
            clase_id: claseId,
            accion: accion,
            bloque_id: bloqueId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () { },
            success: function (response) {
                if (accion == 'informacion') {
                    $("#div-info-bloque").show();
                    $("#div-actividades").hide();
                    $('#div-info-bloque').html(response);
                } else if (accion == 'calificadas') {
                    $("#div-actividades").show();
                    $("#div-actividades").html(response);
                } else if (accion == 'nocalificadas') {
                    $("#div-actividades").show();
                    $("#div-actividades").html(response);
                }
            }
        });


    }
</script>