<?php
// echo "<pre>";
// print_r($detalle->tareas);
// print_r($detalle->promediosInsumos);
// print_r($detalle->promediosTrimestres);


$atareasPromedios = array();
$aPromedioTrimestre = array();

foreach ($detalle->promediosTrimestres as $key => $value) {
    $aPromedioTrimestre[$value["trimestre_id"]] = $value["promedio_general"];
}

//armo array para pintar en la table de tareas
foreach ($detalle->promediosInsumos as $keyPromIns => $promIns) {
    $atareasPromedios[$promIns['grupo_calificacion']]['promedio'] = $promIns["nota"];
}

$aDistrib = array();   //<- aqui tengo los trimestres o quimestre que se manejen en el anio lectivo

foreach ($detalle->tareas as $key => $det) {
    $aDistrib[$det["trimestre_id"]] = $det['trimestre'];

    $atareasPromedios[$det["orden"]]['tareas'][$key]["nombre_nacional"] = $det["nombre_nacional"];
    $atareasPromedios[$det["orden"]]['tareas'][$key]["tipo_aporte"] = $det["tipo_aporte"];
    $atareasPromedios[$det["orden"]]['tareas'][$key]["categoria"] = $det["categoria"];
    $atareasPromedios[$det["orden"]]['tareas'][$key]["titulo"] = $det["titulo"];
    $atareasPromedios[$det["orden"]]['tareas'][$key]["calificacion"] = $det["calificacion"];
}


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
            foreach ($aDistrib as $keyTri => $trimestre) {
            ?>
                <table class="table custom-table table-small table-stripped table-responsive">
                    <tbody>

                        <tr>
                            <td style="background-color: #1b325f;color: white;">Promedio <?= $trimestre ?>:</td>
                            <td style="background-color: #1b325f;color: white;"><?= $aPromedioTrimestre[$keyTri] ?></td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <table class="table table-stripped table-small table-hover">
                                    <thead>
                                        <tr>
                                            <th style="color: white;">INSUMO</th>
                                            <th>APORTE</th>
                                            <th>TÍTULO</th>
                                            <th style="color: white;">CALIFICACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach ($atareasPromedios as $keyPromedio => $promedio) {

                                            if (isset($promedio["tareas"])) {
                                                foreach ($promedio["tareas"] as $keyTarea => $tarea) {
                                        ?>
                                                    <tr>
                                                        <td style="font-weight: normal;"><?= $tarea["nombre_nacional"] ?></td>
                                                        <td style="font-weight: normal;"><?= $tarea["tipo_aporte"] ?></td>
                                                        <td style="font-weight: normal;"><?= $tarea["titulo"] ?></td>
                                                        <td><?= $tarea["calificacion"] ?></td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td style="background-color: #1b325f;color: white;">Promedio:</td>
                                                <td style="background-color: #1b325f;"></td>
                                                <td style="background-color: #1b325f;"></td>
                                                <td style="background-color: #1b325f;color: white;">
                                                    <?php 
                                                        if(isset($promedio["promedio"])){
                                                            echo $promedio["promedio"];
                                                        }else{
                                                            echo "0";
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </td>

                        </tr>
                    </tbody>
                </table>
            <?php
            }
            ?>
        </div>
    </div>
</div>