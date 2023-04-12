<?php

use yii\helpers\Html;
use yii\helpers\Url;

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
                    <div class="col-lg-2 col-md-2"><?= $semana['nombre_semana'] ?></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 text-center row-title">PERÍODO SEMANA</div>
                        <div class="col-lg-6 col-md-6 text-center row-title">PROCESO DE APRENDIZAJE (ACTIVIDADES):</div>
                        <div class="col-lg-4 col-md-4 text-center row-title">TAREAS:</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-md-2 text-center row-body align-items-center" style="margin-top: 25px; border-right: solid 1px #eee;">
                            <b>DESDE:</b> <?= $semana['fecha_inicio'] ?><br>
                            <b>HASTA:</b> <?= $semana['fecha_finaliza'] ?>
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

                                            $inicio     = $actividad['dip_inicio'];
                                            $desarrollo = $actividad['dip_desarrollo'];
                                            $cierre     = $actividad['dip_cierre'];
                                            $lmsId      = $actividad['id'];

                                            $checkInicio = strlen($actividad['dip_inicio']) > 10 ? '<i class="fas fa-check-circle" style="color: #0a1f8f"></i>' : '<i class="fas fa-ban" style="color: #ab0a3d"></i>';
                                            $checkDesarrollo = strlen($actividad['dip_desarrollo']) > 10 ? '<i class="fas fa-check-circle" style="color: #0a1f8f"></i>' : '<i class="fas fa-ban" style="color: #ab0a3d"></i>';
                                            $checkCierre = strlen($actividad['dip_cierre']) > 10 ? '<i class="fas fa-check-circle" style="color: #0a1f8f"></i>' : '<i class="fas fa-ban" style="color: #ab0a3d"></i>';

                                            $url = Url::to(['form-actividad', 'lms_id' => $lmsId]);
                                        ?>
                                            <tr>
                                                <td><?= $actividad['hora_numero'] ?></td>
                                                <td><?= $checkInicio ?></td>
                                                <td><?= $checkDesarrollo ?></td>
                                                <td><?= $checkCierre ?></td>
                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop"                                                     
                                                    onclick="abrirVentana('<?= $url ?>');">
                                                    <i class="fas fa-eye"></i>
                                                </a>-->

                                                    <!-- Button trigger modal -->
                                                    <a type="button" class="" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $actividad['id'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="staticBackdrop<?= $actividad['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="staticBackdropLabel">Configurando actividades de la semana</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>

                                                                <?= Html::beginForm(['form-actividad'], 'get', ['enctype' => 'multipart/form-data']) ?>
                                                                <div class="modal-body">
                                                                    

                                                                        <input type="text" name="lms_id" value="<?= $actividad['id'] ?>">
                                                                        <input type="text" name="plan_bloque_unidad_id" value="<?= $planUnidadId ?>">
                                                                        <div class="form-group">
                                                                        
                                                                        <label class="form-label" for="inicio"><b>INICIO:</b></label>
                                                                            <textarea name="inicio" id="dipinicio<?= $actividad['id'] ?>" cols="30" rows="10" class="form-control"><?= $actividad['dip_inicio'] ?></textarea>
                                                                        </div>

                                                                        <div class="form-group" style="margin-top: 20px;">
                                                                            <label class="form-label" for="desarrollo"><b>DESARROLLO:</b></label>
                                                                            <textarea name="desarrollo" id="dipdesarrollo<?= $actividad['id'] ?>" cols="30" rows="10" class="form-control"><?= $actividad['dip_desarrollo'] ?></textarea>
                                                                        </div>


                                                                        <div class="form-group" style="margin-top: 20px;">
                                                                            <label class="form-label" for="cierre"><b>CIERRE:</b></label>
                                                                            <textarea name="cierre" id="dipcierre<?= $actividad['id'] ?>" cols="30" rows="10" class="form-control"><?= $actividad['dip_cierre'] ?></textarea>
                                                                        </div>
                                                                    

                                                                    
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                    <?= Html::submitButton('Grabar', ['class' => 'btn btn-outline-primary']) ?>
                                                                </div>
                                                                <?= Html::endForm() ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        CKEDITOR.replace("dipinicio<?= $actividad['id'] ?>", { customConfig: "/ckeditor_settings/config.js" })
                                                        CKEDITOR.replace("dipdesarrollo<?= $actividad['id'] ?>", { customConfig: "/ckeditor_settings/config.js" })
                                                        CKEDITOR.replace("dipcierre<?= $actividad['id'] ?>", { customConfig: "/ckeditor_settings/config.js" })
                                                    </script>
                                                    <!-- fin de modal -->


                                                </td>

                                                <td>
                                                    <?=
                                                        Html::a('<i class="fas fa-tasks"></i>',['lms-actividad/index1',
                                                            'lms_id' => $actividad['id'],
                                                            'plan_bloque_unidad_id' => $planUnidadId,
                                                            'action-back' => 'pud-dip/index1'
                                                        ]);
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- finaliza para actividades -->

                        <!-- inicio de tareas -->
                        <div class="col-lg-4 col-md-4 row-body">
                            <ul>
                                <?php
                                foreach ($semana['tareas'] as $tarea) {
                                    echo '<li><b>* </b>' . $tarea['titulo'] . '</li>';
                                };
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