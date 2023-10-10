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

// print_r($atareasPromedios);
// die();
?>


<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="table table-responsive">
            <?php
            foreach ($aDistrib as $keyTri => $trimestre) {
            ?>
                <table class="table table-hover table-small table-stripped table-bordered">
                    <tbody>
                        <tr>
                            <td><?= $trimestre ?></td>
                            <td>Promedio: <?= $aPromedioTrimestre[$keyTri] ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" >
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>INSUMO</th>
                                            <th>APORTE</th>
                                            <th>TÍTULO</th>
                                            <th>CALIFICACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($atareasPromedios as $keyPromedio => $promedio) {
                                            foreach ($promedio["tareas"] as $keyTarea => $tarea) {
                                        ?>
                                                <tr>
                                                    <td><?= $tarea["nombre_nacional"] ?></td>
                                                    <td><?= $tarea["tipo_aporte"] ?></td>
                                                    <td><?= $tarea["titulo"] ?></td>
                                                    <td><?= $tarea["calificacion"] ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>Promedio:</td>
                                                <td><?= $promedio["promedio"]  ?></td>
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