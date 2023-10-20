<?php //echo "<pre>"; print_r($dece); 
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
</style>

<div class="row">

    <div class="col-lg-12 col-md-12">
        <div class="table table-responsive">
            <?php
            foreach ($dece as $keyDece => $dc) {
            ?>
                <div class="card shadow" style="margin-bottom: 15px; padding: 10px; ">
                    <b><?= strtoupper($keyDece) ?></b>

                    <table class="table table-stripped custom-table table-hover table-responsive">
                        <thead class="">
                            <tr>
                                <?php
                                if ($keyDece == 'seguimiento') {
                                ?>
                                    <th style="border-top: none;color: white;">Motivo</th>
                                    <th style="border-top: none;">Pronunciamiento</th>
                                    <th style="border-top: none;">Fecha Inicio</th>
                                    <th style="border-top: none;color: white;">Fecha Fin</th>
                                <?php
                                } else if ($keyDece == 'deteccion') {
                                ?>
                                    <th style="border-top: none;color: white;">Quien reporta</th>
                                    <th>Descripción</th>
                                    <th>Acciones realizadas</th>
                                    <th>Hora Aprox.</th>
                                    <th style="border-top: none;color: white;">Fecha Reporte</th>
                                <?php
                                } else if ($keyDece == 'intervencion') {
                                ?>
                                    <th style="border-top: none;color: white;">Razón</th>
                                    <th>Acciones responsables</th>
                                    <th>Objetivo general</th>
                                    <th style="border-top: none;color: white;">Fecha Intervención</th>
                                <?php
                                } else if ($keyDece == 'derivacion') {
                                ?>
                                    <th style="border-top: none;color: white;">Quien deriva</th>
                                    <th>Derivación</th>
                                    <th>Motivo referencia</th>
                                    <th>Acción realizada</th>
                                    <th style="border-top: none;color: white;">Fecha derivación</th>
                                <?php
                                }
                                ?>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                            foreach ($dece[$keyDece] as $keyData => $data) {
                            ?>
                                <tr>
                                    <?php
                                    if ($keyDece == 'seguimiento') {
                                    ?>
                                        <td style="border-bottom: none;"> <?= $data["motivo"] ?> </td>
                                        <td style="border: none;"> <?= $data["pronunciamiento"] ?> </td>
                                        <td style="border-bottom: none;"> <?= $data["fecha_inicio"] ?> </td>
                                        <td style="border-bottom: none;"> <?= $data["fecha_fin"] ?> </td>
                                    <?php
                                    } else if ($keyDece == 'deteccion') {
                                    ?>
                                        <td> <?= $data["nombre_quien_reporta"] ?> </td>
                                        <td> <?= $data["descripcion_del_hecho"] ?> </td>
                                        <td><?= $data["acciones_realizadas"] ?></td>
                                        <td> <?= $data["hora_aproximada"] ?> </td>
                                        <td> <?= $data["fecha_reporte"] ?> </td>
                                    <?php
                                    } else if ($keyDece == 'intervencion') {
                                    ?>
                                        <td style="border-bottom:none"> <?= $data["razon"] ?> </td>
                                        <td style="border-bottom:none"> <?= $data["acciones_responsables"] ?> </td>
                                        <td style="border-bottom:none"> <?= $data["objetivo_general"] ?> </td>
                                        <td style="border-bottom:none"> <?= $data["fecha_intervencion"] ?> </td>
                                    <?php
                                    } else if ($keyDece == 'derivacion') {
                                    ?>
                                        <td> <?= $data["nombre_quien_deriva"] ?> </td>
                                        <td> <?= $data["tipo_derivacion"] ?> </td>
                                        <td> <?= $data["motivo_referencia"] ?> </td>
                                        <td> <?= $data["accion_desarrollada"] ?> </td>
                                        <td> <?= $data["fecha_derivacion"] ?> </td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                </div>


            <?php
            }
            ?>
        </div>
    </div>

</div>