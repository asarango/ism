<?php

$helper = new \backend\models\helpers\HelperGeneral();
$semanaDetalle = 'del '.$semana->fecha_inicio.' al '.$semana->fecha_finaliza;

if ($actividades) {
    ?>   

<div class="row" style="padding: 0px 20px 20px 20px;">
    <div class="col-lg-12 col-md-12 card" style="border: solid 1px #0a1f8f">
        <h4 style="color: #0a1f8f"><?= $docente->x_first_name.' '.$docente->last_name ?></h4>        
        <h5 style="color: #0a1f8f"><?= $semanaDetalle ?></h5>        
    </div>
</div>

    <div class="row" style="padding: 0px 20px 20px 20px;">
        <div class="col-lg-12 col-md-12 card" style="border: solid 1px #0a1f8f">
            <div class="table table-responsive">

                <table class="table table-bordered">
                    <thead>
                        <tr style="background-color: #0a1f8f; color: white">
                            <th class="text-center">FECHA</th>
                            <th class="text-center">HORA</th>
                            <th class="text-center">ASIGNATURA</th>                            
                            <th class="text-center">TÍTULO</th>                            
                            <th class="text-center">ENSEÑANZA</th>
                            <th class="text-center">TAREAS</th>
                            <th class="text-center">INSUMO</th>
                            <th class="text-center">ES_CAL</th>
                            <th class="text-center">TIPO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($actividades as $actividad) {
                            
                            $dia = $helper->get_dia_fecha($actividad['inicio']);
                            
                            ?>
                            <tr>
                                <td class="text-center"><?= $dia ?></td>
                                <td class="text-center"><?= $actividad['sigla'] ?></td>
                                <td><?= $actividad['materia'] ?></td>
                                <td><?= $actividad['title'] ?></td>
                                <td><?= $actividad['descripcion'] ?></td>
                                <td><?= $actividad['tareas'] ?></td>                                
                                <td><?= $actividad['nombre_nacional'] ?></td>
                                <td class="text-center"><?= $actividad['calificado'] ?></td>
                                <td class="text-center"><?= $actividad['tipo_calificacion'] ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
} else {
    ?>
<div class="row" style="padding: 0px 20px 20px 20px;">
    <div class="col-lg-12 col-md-12 card" style="border: solid 1px #ab0a3d">
        <h4 style="color: #ab0a3d"><?= $docente->x_first_name.' '.$docente->last_name ?></h4>
        <h5 style="color: #ab0a3d">No tiene realizada la planificación semanal</h5>
        <h1><i class="fas fa-sad-tear" style="color: #ab0a3d"></i></h1>
    </div>
</div>

<?php
}
?>


