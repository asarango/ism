
<style>
    .contenedor-servicios{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    .servicio{
        width: 25%;
        padding: 50px 38px 50px 38px;
        border-radius: 10px;
        margin-bottom: 40px;
        /*color: white;*/
    }

    .violeta{
        background-color: #5055df;
    }

    .celeste{
        background: #40a8fa;
    }

    h3{
        text-align: center;
        font-size: 40px;
        font-weight: normal;
    }
</style>

<?php
$helper = new \backend\models\helpers\HelperGeneral();
$semanaDetalle = 'del ' . $semana->fecha_inicio . ' al ' . $semana->fecha_finaliza;

if ($actividades) {
    ?>   

    <div class="row" style="padding: 0px 20px 20px 20px;">
        <div class="col-lg-12 col-md-12 card" style="border: solid 1px #0a1f8f">
            <h4 style="color: #0a1f8f"><?= $docente->x_first_name . ' ' . $docente->last_name ?></h4>        
            <h5 style="color: #0a1f8f"><?= $semanaDetalle ?></h5>        
        </div>
    </div>

    <div class="row" style="padding: 0px 20px 20px 20px; height: 300px">
        <div class="col-lg-12 col-md-12 card" style="border: solid 1px #0a1f8f">
            <div class="table table-responsive" style="overflow-y: scroll">

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

    <section class="">
        <div class="col-lg-12 col-md-12 card" style="border: solid 1px #0a1f8f">
            <h3>Detalle de planificación semanal</h3>


            <div class="row"> 

                <div class="col-lg-6 col-md-6">
                    <div class="row">
                        <div class="">
                            <b>Diseñar experiencias de aprendizaje interesantes:</b>
                            <?= $planSemanal->experiencias_aprendizaje ?>
                        </div>
                    </div>

                    <div class="row">
                        <b>Evaluación continua:</b>
                        <div class="">
                            <?= $planSemanal->evaluacion_continua ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6"> 
                    <div class="">
                        <h4>Aprobación</h4>
                        <?= presenta_aprobacion($planSemanal) ?>

                    </div>

                    <div class="" style="margin-top: 30px;">

                        <h4>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Retroalimentar
                            </button>
                        </h4>

                        <?= $planSemanal->retroalimentacion ?>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Actualizar retroalimentación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <textarea name="retroalimentacion" id="retro" class="form-control"><?= $planSemanal->retroalimentacion ?></textarea>                  
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary" onclick="grabar(<?= $planSemanal->id ?>)" data-bs-dismiss="modal">Actualizar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--fin de Modal-->

                    </div>
                </div>

            </div>
        </div>

    </section>

    <?php
} else {
    ?>
    <div class="row" style="padding: 0px 20px 20px 20px;">
        <div class="col-lg-12 col-md-12 card" style="border: solid 1px #ab0a3d">
            <h4 style="color: #ab0a3d"><?= $docente->x_first_name . ' ' . $docente->last_name ?></h4>
            <h5 style="color: #ab0a3d">No tiene realizada la planificación semanal</h5>
            <h1><i class="fas fa-sad-tear" style="color: #ab0a3d"></i></h1>
        </div>
    </div>

    <?php
}
?>

<?php

function presenta_aprobacion($planSemanal) {

    if ($planSemanal->es_aprobado == true) {
        ?>
        <p style="color: green">
            <i class="far fa-laugh-beam"></i>
            <b>PLANIFICACIÓN APROBADA POR: </b><?= $planSemanal->quien_aprueba ?>
            <br>
            <b>EL: </b><?= $planSemanal->fecha_aprobacion ?>
        </p>
        <?php
    } else {
        ?>
        <p style="color: #ab0a3d; ">
            <i class="far fa-sad-tear"></i>
            <b>No se encuentra aprobada, si deseas aprobar la planificación, realiza clic</b>
        </p>

        <a href="#" onclick="aprobar(<?= $planSemanal->id ?>)"><i class="fas fa-mouse fa-spin" style="color: #ab0a3d" onclick="aprobar()"> </i> AQUí</a>
        <?php
    }
}

//fin de dunction presenta_aprobacion()
?>


<script>
    function grabar(planSemanalId) {
        let retroalimentacion = $("#retro").val();
        let url = '<?= yii\helpers\Url::to(['update-retro']) ?>';
        let facId = '<?= $docente->id ?>';

        params = {
            retroalimentacion: retroalimentacion,
            plan_semanal_id: planSemanalId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (resp) {
                showDetail(facId);
            }
        });
    }


    function aprobar(planSemanalId) {
        let url = '<?= yii\helpers\Url::to(['aprobar']) ?>';
        let facId = '<?= $docente->id ?>';

        params = {
            plan_semanal_id: planSemanalId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (resp) {
                showDetail(facId);
            }
        });
    }

</script>