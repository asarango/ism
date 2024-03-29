<?php

use yii\helpers\Html;
use yii\helpers\Url;

// echo '<pre>';
// print_r($planesSemanalese);
// die();
// print_r($paralelos);
//      
// echo '<pre>';
// print_r($planUnidadId);
// die();

// echo "<pre>";
// print_r($planUnidad);
// die();
// echo '<pre>';
// print_r($planUnidadId);
// die();
?>

<style>
    .row-title {
        background-color: #eee;
        color: rgba(0, 0, 0, 0.7);
        font-family: 'Times New Roman', Times, serif;
        font-size: 10px;
    }

    .row-body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 10px;
    }
</style>

<!-- <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script> -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<div style="overflow-y: scroll; height: 500px;">

    <div class="row" style="background-color: rgba(0, 0, 0, 0.1);">
        <h4><b>5.0.- SEMANAS</b></h4>
    </div>

    <div style="margin-top: 15px;">
        <?php
        foreach ($planesSemanales as $semana) {

            ?>
            <!-- inicia de card -->
            <div class="card" style="margin-bottom: 15px;">
                <div class="card-header" style="background-color: #65b2e8; color: white">
                    <div class="col-lg-2 col-md-2">
                        <?= $semana['nombre_semana'] ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 text-center row-title">PERÍODO SEMANA</div>
                        <div class="col-lg-6 col-md-6 text-center row-title">PROCESO DE APRENDIZAJE (ACTIVIDADES):</div>
                        <div class="col-lg-4 col-md-4 text-center row-title">TAREAS:</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-md-2 text-center row-body align-items-center"
                            style="margin-top: 25px; border-right: solid 1px #eee;">
                            <b>DESDE:</b>
                            <?= $semana['fecha_inicio'] ?><br>
                            <b>HASTA:</b>
                            <?= $semana['fecha_finaliza'] ?>
                        </div>

                        <!-- inicia para actividades -->
                        <div class="col-lg-6 col-md-6 text-center row-body" style="border-right: solid 1px #eee;">
                            <div class="table table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">HORA</th>
                                            <th class="text-center">INICIO</th>
                                            <th class="text-center">DESARROLLO</th>
                                            <th class="text-center">CIERRE</th>
                                            <th class="text-center" colspan="2">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        foreach ($semana['actividades'] as $actividad) {

                                            $inicio = $actividad['dip_inicio'];
                                            $desarrollo = $actividad['dip_desarrollo'];
                                            $cierre = $actividad['dip_cierre'];
                                            $lmsId = $actividad['id'];

                                            $checkInicio = strlen($actividad['dip_inicio']) > 10 ? '<i class="fas fa-check-circle" style="color: #0a1f8f"></i>' : '<i class="fas fa-ban" style="color: #ab0a3d"></i>';
                                            $checkDesarrollo = strlen($actividad['dip_desarrollo']) > 10 ? '<i class="fas fa-check-circle" style="color: #0a1f8f"></i>' : '<i class="fas fa-ban" style="color: #ab0a3d"></i>';
                                            $checkCierre = strlen($actividad['dip_cierre']) > 10 ? '<i class="fas fa-check-circle" style="color: #0a1f8f"></i>' : '<i class="fas fa-ban" style="color: #ab0a3d"></i>';

                                            $url = Url::to(['form-actividad', 'lms_id' => $lmsId]);
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $actividad['hora_numero'] ?>
                                                </td>
                                                <td>
                                                    <?= $checkInicio ?>
                                                </td>
                                                <td>
                                                    <?= $checkDesarrollo ?>
                                                </td>
                                                <td>
                                                    <?= $checkCierre ?>
                                                </td>
                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop"                                                     
                                                    onclick="abrirVentana('<?= $url ?>');">
                                                    <i class="fas fa-eye"></i>
                                                </a>-->

                                                    <!-- Button trigger modal -->
                                                    <a type="button" class="" data-bs-toggle="modal" title="Inicio_Desarrollo_Cierre"
                                                        data-bs-target="#staticBackdrop<?= $actividad['id'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="staticBackdrop<?= $actividad['id'] ?>"
                                                        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="staticBackdropLabel">
                                                                        Configurando actividades de la semana</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>

                                                                <?= Html::beginForm(['form-actividad'], 'get', ['enctype' => 'multipart/form-data']) ?>
                                                                <div class="modal-body">


                                                                    <input type="hidden" name="lms_id"
                                                                        value="<?= $actividad['id'] ?>">
                                                                    <input type="hidden" name="plan_bloque_unidad_id"
                                                                        value="<?= $planUnidadId ?>">
                                                                    <div class="form-group">

                                                                        <label class="form-label"
                                                                            for="inicio"><b>INICIO:</b></label>
                                                                        <textarea name="inicio"
                                                                            id="dipinicio<?= $actividad['id'] ?>" cols="30"
                                                                            rows="10"
                                                                            class="form-control"><?= $actividad['dip_inicio'] ?></textarea>
                                                                    </div>

                                                                    <div class="form-group" style="margin-top: 20px;">
                                                                        <label class="form-label"
                                                                            for="desarrollo"><b>DESARROLLO:</b></label>
                                                                        <textarea name="desarrollo"
                                                                            id="dipdesarrollo<?= $actividad['id'] ?>" cols="30"
                                                                            rows="10"
                                                                            class="form-control"><?= $actividad['dip_desarrollo'] ?></textarea>
                                                                    </div>


                                                                    <div class="form-group" style="margin-top: 20px;">
                                                                        <label class="form-label"
                                                                            for="cierre"><b>CIERRE:</b></label>
                                                                        <textarea name="cierre"
                                                                            id="dipcierre<?= $actividad['id'] ?>" cols="30"
                                                                            rows="10"
                                                                            class="form-control"><?= $actividad['dip_cierre'] ?></textarea>
                                                                    </div>



                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Cerrar</button>
                                                                    <?= Html::submitButton('Grabar', ['class' => 'btn btn-outline-primary']) ?>
                                                                </div>
                                                                <?= Html::endForm() ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        CKEDITOR.replace("dipinicio<?= $actividad['id'] ?>", {
                                                            customConfig: "/ckeditor_settings/config.js"
                                                        })
                                                        CKEDITOR.replace("dipdesarrollo<?= $actividad['id'] ?>", {
                                                            customConfig: "/ckeditor_settings/config.js"
                                                        })
                                                        CKEDITOR.replace("dipcierre<?= $actividad['id'] ?>", {
                                                            customConfig: "/ckeditor_settings/config.js"
                                                        })
                                                    </script>
                                                    <!-- fin de modal -->


                                                </td>

                                                <td style="padding: 0%; text-align: center; ;">
                                                    <button type="button" class="btn btn-outline " data-bs-toggle="modal"
                                                        data-bs-target="#paralelosModal">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-list-check" width="12"
                                                            height="12" viewBox="0 0 24 24" stroke-width="3" stroke="#0d6efd"
                                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M3.5 5.5l1.5 1.5l2.5 -2.5" />
                                                            <path d="M3.5 11.5l1.5 1.5l2.5 -2.5" />
                                                            <path d="M3.5 17.5l1.5 1.5l2.5 -2.5" />
                                                            <path d="M11 6l9 0" />
                                                            <path d="M11 12l9 0" />
                                                            <path d="M11 18l9 0" />
                                                        </svg>
                                                    </button>
                                                    <?= ""
                                                        // Html::a(
                                                        //     '<i class="fas fa-tasks"></i>',
                                                        //     [
                                                        //         'planificacion-semanal/mis-clases',
                                                        //         'lms_id' => $actividad['id'],
                                                        //         'plan_bloque_unidad_id' => $planUnidadId,
                                                        //         'action-back' => 'pud-dip/index1'
                                                        //     ]
                                            
                                                        // );
                                                        ?>


                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal" tabindex="-1" id="paralelosModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-certificate" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="#00264a" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                    <path d="M13 17.5v4.5l2 -1.5l2 1.5v-4.5" />
                                                    <path
                                                        d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -1 1.73" />
                                                    <path d="M6 9l12 0" />
                                                    <path d="M6 12l3 0" />
                                                    <path d="M6 15l2 0" />
                                                </svg>
                                                Escoja un paralelo
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                <?php

                                                foreach ($paralelos as $paralelo) {


                                                    echo Html::a('<lu style="padding-left: 5px; text-align: center"><h4><b>- ' . $paralelo['paralelo'] . ' -<lu><h4><b>', [
                                                        'redirect-ps',
                                                        'lms_id' => $actividad['id'],
                                                        'clase_id' => $paralelo['clase_id'],
                                                        'pud_origen' => "normal",
                                                        'plan_bloque_unidad_id' => $planUnidadId,
                                            
                                                        // 'boton_retorno' => "pud-dip/index1?plan_bloque_unidad_id=" . $planUnidadId,
                                                    ],
                                                    ['target' => '_blank']
                                                );
                                                }

                                                ?>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <!-- <button type="button" class="btn btn-secondary">Salir</button> -->
                                            <button type="button" class="btn btn-primary"
                                                data-bs-dismiss="modal">Volver</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- finaliza para actividades -->

                        <!-- inicio de tareas -->
                        <div class="col-lg-4 col-md-4 row-body">
                            <ul>
                                <?php
                                foreach ($semana['tareas'] as $tarea) {
                                    echo '<li><b>* </b>' . $tarea['titulo'] . '</li>';
                                }
                                ;
                                ?>
                            </ul>
                        </div>
                        <!-- fin de tareas -->

                    </div>
                </div>
            </div>
            <!-- fin de card -->

            <?php
        }

        ?>
    </div>
</div>


<script>
    // function abrirVentana(url) {

    //     window.open(url, "MiVentana", "width=800,height=600");
    // }
</script>