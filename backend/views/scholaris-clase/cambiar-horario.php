<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\bootstrap\Button;

$this->title = 'Cambiar Horario';

// echo "<pre>";
// print_r($horarioActual);
// die();

?>
<style>
    /* Estilo para el contenedor principal */
    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 20px;
        font: 10px;
        color: black;
        text-align: center;
    }


    .horario-select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background: #fff;
        color: #333;
    }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="scholaris-cambiar-horario">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10">
            <div class="row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h4>
                </div>

                <div class="col-lg-6 col-md-6">
                    <h4>
                        <?= Html::encode($this->title) ?><br>

                    </h4>
                    <small>
                        <?= ' Materia: ' . $clase->ismAreaMateria->materia->nombre . ' / ' .
                            ' Profesor: ' . ' ' . $clase->profesor->x_first_name . ' ' .
                            ' ' . $clase->profesor->last_name . ' / ' .
                            'Curso: ' . $clase->paralelo->course->name . ' / ' .
                            ' ' . '"' . $clase->paralelo->name . '"' . ' / ' . 'Clase#' . $clase->id ?>
                    </small>

                </div>

                <div class="col-lg-5" style="text-align: right;">
                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ff9e18">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up"
                             width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" 
                             fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                            <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                            </svg>Regresar</span>',
                        ['scholaris-clase/index'],
                        ['title' => 'Configurar clases']
                    );
                    ?>
                </div>

                <hr>

            </div>

            <!-- <h6>Horario Actual</h6> -->

            <!-- INICIO DE SELECT -->

            <div class="container">

                <?php
                foreach ($horarioActual as $horario) {
                    echo '<div class="">';
                    echo '<div class="">' . $horario['dia'] . ' - ' . $horario['hora'] . '</div>';
                    echo '<select id="HorarioSelect' . $horario['detalle_id'] . '" class="horario-select" name="horarioSeleccionado" onchange="cambiarHorario(' . $horario['detalle_id'] . ', this)">';
                    echo '<option value="">Cambiar horario</option>';
                    foreach ($listaHorario as $horarioOption) {
                        echo "<option value='{$horarioOption['detalle_id']}'>{$horarioOption['horario']}</option>";
                    }
                    echo "</select>";
                    echo '</div>';
                }
                ?>
            </div>
            <div class="row">

                <div class="col-lg-12" id="mostrar-cambios" style="margin: 20px"></div>
                <!-- <button type="submit" class="btn btn-primary">Guardar</button> -->
            </div>

            <!-- FIN DE SELECT -->
        </div>
    </div>
</div>

<script>
    // FUNCION PARA CAMBIAR HORARIO
    function cambiarHorario(detalleId, obj) {
        var url = "<?= Url::to(['mostrar-cambios-horario'])  ?>";
        console.log(obj.value);

        let = horaNueva = obj.value;

        params = {
            detalleId: detalleId,
            horaNueva: horaNueva,
            clase_id: '<?= $clase->id ?>',
            // horario: $horarioActual,

        };
        $.ajax({
            type: "POST",
            data: params,
            url: url,
            success: function(response) {
                // console.log(response);
                $('#mostrar-cambios').html(response);
            }
        })
    }

    // FUNCION PARA CAMBIAR SEMANA

    // function cambiaSemana() {
    //     var url = "<?= Url::to(['mostrar-cambios-semana'])  ?>";
    //     console.log(obj.value);

    //     let = horaNueva = obj.value;

    //     params = {
    //         detalleId: detalleId,
    //         horaNueva: horaNueva,
    //         clase_id: '<?= $clase->id ?>',
    //         // horario: $horarioActual,

    //     };
    //     $.ajax({
    //         type: "POST",
    //         data: params,
    //         url: url,
    //         success: function(response) {
    //             // console.log(response);
    //             $('#cambios-semana').html(response);
    //         }
    //     })
    // }
</script>