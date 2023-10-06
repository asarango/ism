<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Novedades de comportamiento ';
//$this->params['breadcrumbs'][] = $this->title;
// echo '<pre>';
// print_r($modelCompDetalle);
// die();



?>

<style>
    @keyframes vibrate {
        0% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-3px);
        }

        50% {
            transform: translateX(3px);
        }

        75% {
            transform: translateX(-3px);
        }

        100% {
            transform: translateX(3px);
        }
    }

    .vibrate {
        animation: vibrate 0.8s;
    }

    .enlarge-on-hover:hover {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }
</style>

<div class="comportamiento-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-9 col-md-9">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h4>
                </div>
                <div class="col-lg-7" style="margin-bottom: -30px;">
                    <h5>
                        <?= Html::encode($this->title) ?>
                    </h5>
                    <b>
                        <?= ' (' . $modelGrupo->alumno->last_name . ' ' . $modelGrupo->alumno->first_name. ' ' . $modelGrupo->alumno->middle_name . ')'; ?>
                    </b>
                    <p>
                        <font size=1>
                            <?=
                                $modelAsistencia->clase->ismAreaMateria->materia->nombre . ' / ' .
                                $modelAsistencia->clase->paralelo->course->name . ' "' .
                                $modelAsistencia->clase->paralelo->name . '" / ' .
                                $modelAsistencia->clase->profesor->last_name . " " . $modelAsistencia->clase->profesor->x_first_name . ' / ' .
                                $modelAsistencia->fecha . " / " .
                                $modelAsistencia->hora->sigla . " Hora"
                                ?>
                        </font>
                    </p>
                </div>
                <!-- Inicio botones -->
                <div class="col-lg-4" style="text-align: right;margin-top:-15px">
                    <p>
                        <?= Html::a('<span class="badge rounded-pill enlarge-on-hover" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>
                        |
                        <?=
                            Html::a(
                                '<span class="badge rounded-pill enlarge-on-hover" style="background-color: #ff9e18"><i class="far fa-file"></i> Listado</span>',
                                ['index', 'id' => $modelAsistencia->id],
                                ['class' => 'link']
                            );
                        ?>
                        |
                        <?= Html::a(
                            '<span class="badge rounded-pill enlarge-on-hover" style="background-color: #0a1f8f">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exclamation-mark vibrate" 
                            width="14" height="14" viewBox="0 0 24 24" stroke-width="3" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 19v.01" />
                            <path d="M12 15v-10" />
                            </svg>Alertas</span>',
                            ['dece-casos/crear-deteccion', 'id' => $modelGrupo->estudiante_id, 'id_clase' => $modelGrupo->clase_id],
                            ['class' => 'link']
                        ); ?>


                        <!--?=
                Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Mis asignaturas</span>',
                        ['profesor-inicio/index'], ['class' => 'link']);
                ?-->

                    </p>
                </div>
                <!-- fin de botones -->
            </div>
            <hr>
            <!-- comienzo cuerpo -->
            <div class="row" style="margin-bottom: 40px">

                <div class="col-lg-4 col-md-4 my-text-small">

                    <div class="mimenu">
                        <ul class="uno">

                            <?php
                            foreach ($modelComportamientos as $comp) {
                                ?>
                                <br>
                                <li><a href="#" style="color:#ab0a3d">
                                        <?= $comp->nombre ?>
                                    </a>
                                    <ul class="dos">
                                        <?php
                                        foreach ($modelCompDetalle as $det) {
                                            //<li><a href="#">Historia</a></li>
                                            if ($comp->id == $det->comportamiento_id) {
                                                echo '<li>';
                                                /*echo Html::a($det->codigo . ' - ' . $det->nombre, [
                                                    'asignar',
                                                    "asistenciaId" => $modelAsistencia->id,
                                                    "detalleId" => $det->id,
                                                    "grupoId" => $modelGrupo->id,
                                                ], ['class' => '']);*/
                                                ?>
                                                <a href="#" style="font-size:15px;"
                                                    onclick="asignaComportamiento(<?php echo $det->id ?>,'<?php echo ($det->nombre) ?>')"><?php echo ($det->codigo . ' - ' . $det->nombre) ?></a>
                                                <?php
                                                echo '</li>';
                                            }
                                        }
                                        ?>

                                    </ul>
                                </li>

                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-8 col-md-8">
                    <div class="row" style="margin-bottom: 50px">
                        <table clase="table table-condensed table-striped table-hover my-text-small">
                            <tr>
                                <td><b>Comportamiento Seleccionado:</b><label id="txt_seleccion_comp">N/A</label></td>
                            </tr>
                        </table>
                        <?php $form = ActiveForm::begin(); ?>

                        <?= $form->field($model, 'asistencia_profesor_id')->hiddenInput(['value' => $modelAsistencia->id])->label(false) ?>
                        <?= $form->field($model, 'comportamiento_detalle_id')->hiddenInput()->label(false) ?>
                        <?= $form->field($model, 'observacion')->textarea(array('rows' => 3, 'cols' => 4))->label('Ingrese Observación') ?>
                        <?= $form->field($model, 'grupo_id')->hiddenInput(['value' => $modelGrupo->id])->label(FALSE) ?>

                        <div class="form-group m-2">
                            <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div><!-- fin de formulario -->
                    <div class="row" style="margin-bottom: 30px;">
                        <div class="table table-responsive shadow" style="height: 190px; overflow-y: scroll">
                            <table class="table table-responsive table-bordered table-striped">
                                <thead class="table-success">
                                    <tr style=" text-align: center;color: #ab0a3d;">
                                        <th style="color:#ab0a3d">Código</th>
                                        <th style="color:#ab0a3d">Detalle</th>
                                        <th style="color:#ab0a3d">Observación</th>
                                        <th style="color:#ab0a3d">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($modelNovedades as $novedad) {

                                        echo '<tr style="font-size:15px;">';
                                        echo '<td>' . $novedad->comportamientoDetalle->codigo . '</td>';
                                        echo '<td>' . $novedad->comportamientoDetalle->nombre . '</td>';
                                        echo '<td>' . $novedad->observacion . '</td>';

                                        echo '<td class="text-center">';
                                        echo Html::a('<i class="fas fa-ban"></i>', ['quitar', "novedadId" => $novedad->id], ['class' => 'link', 'title' => 'Eliminar']);
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function asignaComportamiento(id, descri) {
        //alert(id);
        $('#scholarisasistenciaalumnosnovedades-comportamiento_detalle_id').val(id);
        $('#txt_seleccion_comp').html('<h5 style="background-color: white;color:#ab0a3d">' + descri + '</h5>');
    }

</script>