<?php //echo "<pre>"; print_r($comportamiento); 
?>
<?php // echo "<pre>"; echo count($comportamiento); 
?>

<style>
    .nov {
        transition: transform 0.2s;
    }

    .nov:hover {
        transform: scale(1.05);
    }

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
</style>

<div class="row">
    <div class="col-lg-8 col-md-8" style="text-align: start;">
        <p class="card-title">Novedades de comportamiento (<?= count($comportamiento) ?>)</p>
    </div>
    <div class="col-lg-4 col-md-4" style="text-align: end;">
        <!-- Button trigger modal -->
        <a class="click" data-bs-toggle="modal" data-bs-target="#comportamientoModal">
            <span class="" style="background-color: #1b325f;color: white; padding:  7px;border-radius: 15px; cursor: pointer;font-size: 13px;">
                 Ver
            </span>
            
        </a>
    </div>
</div>

<div class="row" style="color: white; margin-top: 10px;">
    <?php
    if (count($comportamiento) > 0) {
        foreach ($comportamiento as $key => $compo) {
            if ($key <= 2) {
    ?>
                <div class="col-lg-6 col-md-6" style="text-align: center">
                    <div title="Tipo: <?= $compo["comportamiento"] ?>" class="card rounded shadow nov" style="overflow: y; max-height: 200px;background-color:  #e25431;">
                        <div class="card-header" style="background-color: #f26c4f;color: white;font-weight: normal;text-align: left;height: 90px">
                            <div class="row">
                                <div class="col-lg-9 col-md-9">
                                    <p style="font-size: 15px;font-weight: bold;"><?= $compo["fecha"] ?></p>
                                    <p><b>
                                            <?= $compo["detalle_comportamiento"] ?>
                                        </b></p>
                                </div>
                                <div class="col-lg-3 col-md-3" style="text-align: center;margin-top: 25px">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell-exclamation" width="100" height="100" viewBox="0 0 24 24" stroke-width="2.5" stroke="#d84a30" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M15 17h-11a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6a2 2 0 1 1 4 0a7 7 0 0 1 4 6v1.5" />
                                        <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                        <path d="M19 16v3" />
                                        <path d="M19 22v.01" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card-footer" style="background-color: #f26c4f;">

                        </div> -->
                        <div class="card-body" style="background-color: #e25431; height: 35px;">
                            <div class="col-lg-12 col-md-12">
                                <!-- Button trigger modal -->
                                <p style="margin-top: -5px">
                                    <?= $compo["materia"] ?> <a style="cursor: pointer;" class="click" data-bs-toggle="modal" data-bs-target="#comportamientoModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="34" height="34" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                            <path d="M12 9h.01" />
                                            <path d="M11 12h1v4h1" />
                                        </svg>
                                    </a>
                                </p>
                            </div>
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
<div class="modal fade" id="comportamientoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Novedades y comportamiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table table-responsive">
                    <table class="table custom-table table-hover table-stripped">
                        <thead>
                            <tr>
                                <th style="border-bottom: none;color: white;">Tipo</th>
                                <th>Materia</th>
                                <th>Docente</th>
                                <th>Fecha</th>
                                <th>Detalle Comp.</th>
                                <th>Es Justificado</th>
                                <th>Justificado Motivo</th>
                                <th style="border-bottom: none;color: white;">Justificado Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($comportamiento as $keyComp => $compor) {
                            ?>
                                <tr>
                                    <td>
                                        <?= $compor["comportamiento"] ?>
                                    </td>
                                    <td>
                                        <?= $compor["materia"] ?>
                                    </td>
                                    <td>
                                        <?= $compor["docente"] ?>
                                    </td>
                                    <td>
                                        <?= $compor["fecha"] ?>
                                    </td>
                                    <td>
                                        <?= $compor["detalle_comportamiento"] ?>
                                    </td>
                                    <td>
                                        <?= $compor["es_justificado"] ?>
                                    </td>
                                    <td>
                                        <?= $compor["solicitud_representante_motivo"] ?>
                                    </td>
                                    <td>
                                        <?= $compor["justificacion_fecha"] ?>
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