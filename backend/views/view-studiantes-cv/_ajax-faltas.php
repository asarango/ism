<?php
// echo "<pre>";
// print_r($faltas);
?>

<style>
    .custom-table {
        border-collapse: collapse;
        width: 100%;
        /* border-radius: 10px; */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        color: black;
        font-weight: bold;
        font-size: 13px;
    }

    .custom-table th,
    .custom-table td {
        padding: 15px;
        text-align: center;
        /* border: 1px solid #333; */

    }

    .custom-table th {
        background-color: #1b325f;
        color: white;
    }

    .custom-table tr:nth-child(even) {
        background-color: #eee;
        color: black;
    }

    .custom-table th:first-child,
    .custom-table td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        text-align: left;
        color: black;
    }

    .custom-table th:last-child,
    .custom-table td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        text-align: right;
        color: black;
    }

    .falts {
        transition: transform 0.2s;
    }

    .falts:hover {
        transform: scale(1.05);
    }
</style>

<div class="row">
    <div class="col-lg-8 col-md-8" style="text-align: start;">
        <p class="card-title">Faltas (<?= count($faltas) ?>)</p>
    </div>
    <div class="col-lg-4 col-md-4" style="text-align: end;">
        <!-- Button trigger modal -->
        <a class="click" data-bs-toggle="modal" data-bs-target="#faltasModal">
            <span class="" style="background-color: #1b325f;color: white; padding:  7px;border-radius: 15px; cursor: pointer;font-size: 13px;">
                Ver
            </span>

        </a>
    </div>
</div>

<div class="row" style="color: white; margin-top: 10px;">
    <?php
    if (count($faltas) > 0) {
        foreach ($faltas as $key => $falt) {
            if ($key <= 2) {
    ?>
                <div class="col-lg-4 col-md-4" style="text-align: center" title="Motivo:<?= $falt["motivo_justificacion"] ?>">
                    <div class="card falts rounded shadow" style="height: 111px;background-color: #3a89c9;border: 1px solid #ccc;">
                        <div class="card-header" style="background-color: #e9f2f9;color: black;">
                            <p><b>
                                    <?= $falt["fecha"] ?>
                                </b></p>
                        </div>
                        <div class="card-footer" style="background-color: #3a89c9;">

                            <p class="click" data-bs-toggle="modal" data-bs-target="#faltasModal" style="cursor: pointer;">
                                <?php
                                $iconoJustificado = ($falt["es_justificado"] != "") ? '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check-filled" width="100" height="100" viewBox="0 0 24 24" stroke-width="2.5" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" stroke-width="0" fill="currentColor" />
                                  </svg>' : '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-x-filled" width="100" height="100" viewBox="0 0 24 24" stroke-width="2.5" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                  <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" stroke-width="0" fill="currentColor" />
                                </svg>';
                                ?>

                                <i class="fas fas-check"><?= $iconoJustificado ?></i>

                            </p>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
    } else {
        ?>
        <div class="col-lg-12 col-md-12">
            Ninguna novedad
        </div>
    <?php
    }
    ?>
</div>

<!-- Modal -->
<div class="modal fade" id="faltasModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Faltas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table table-responsive">
                    <table class="table table-stripped custom-table table-responsive table-hover">
                        <thead>
                            <tr>
                                <th style="border-bottom: none;color: white;">Fecha</th>
                                <th>Justificado</th>
                                <th>Motivo Justificación</th>
                                <th>Fecha Solicitud Justificación</th>
                                <th>Fecha Justificacion</th>
                                <th style="border-bottom: none;color: white;">Respuesta Justificacion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($faltas as $keyComp => $falta) {
                            ?>
                                <tr>
                                    <td>
                                        <?= $falta["fecha"] ?>
                                    </td>
                                    <td>
                                        <?= $falta["es_justificado"] ?>
                                    </td>
                                    <td>
                                        <?= $falta["motivo_justificacion"] ?>
                                    </td>
                                    <td>
                                        <?= $falta["fecha_solicitud_justificacion"] ?>
                                    </td>
                                    <td>
                                        <?= $falta["fecha_justificacion"] ?>
                                    </td>
                                    <td>
                                        <?= $falta["respuesta_justificacion"] ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>