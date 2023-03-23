<?php

use yii\helpers\Html;
use yii\grid\GridView;

// echo '<pre>';
//    print_r($conceptosClaveDisponibles);
//  print_r($contextoGlobalSeleccionados);
//  die();
// print_r($contextoGlobalDisponibles);
$condicionClass = new backend\models\helpers\Condiciones;
$estado = $bloqueUnidad->planCabecera->estado;
$isOpen = $bloqueUnidad->is_open;
$condicion = $condicionClass->aprobacion_planificacion($estado, $isOpen, $bloqueUnidad->settings_status);
?>


<div class="row text-center" style="margin-top:15px;">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style=" background-color: #eee">
        <h4 style="text-align:center">DISPONIBLES</h4>
        <hr>
        <?php
        if ($condicion == false) {
        ?>
            <h6>La planificación está <?= $estado ?></h6>
        <?php
        } else {
        ?>
            <!-- Aqui se muestran los conceptos -->
            <div id="global">
                <table class="table table-hover my-text-medium">
                    <thead>
                        <tr>
                            <th scope="col" style="width:10px">CONTENIDO</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        foreach ($contextoGlobalDisponiblesCabeceras as $disponibles) {
                        ?>
                            <tr style="background-color:#ff9e18;">
                                <td><strong style="violet"><?= $disponibles['contenido_es'] ?> </strong></td>
                            </tr>
                            <?php
                            foreach ($contextoGlobalDisponibles as $disponibles1) {
                            ?>

                                <?php
                                if ($disponibles['contenido_es'] == $disponibles1['contenido_es']) {
                                ?>
                                    <tr>
                                        <td style="width:30px; text-align:justify">
                                            <?=
                                            Html::a(
                                                '<span > * ' . $disponibles1['sub_contenido'] . '</span>',
                                                [
                                                    'asignar-contenido', 'plan_unidad_id' => $bloqueUnidad->id,
                                                    'tipo' => $disponibles1['tipo'],
                                                    'tipo2' => 'CONTEXTO GOBAL',
                                                    'id_relacion' => $disponibles1['id'],
                                                    'contenido' => $disponibles1['contenido_es'],
                                                    'sub_contenido' => $disponibles1['sub_contenido'],
                                                    'pestana' => 'contexto_global'
                                                ],
                                                ['class' => 'link']
                                            );
                                            ?>
                                        </td>
                                    </tr>
                        <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        }
        ?>

    </div>

    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 border border-1 border-warning">
        <h4 style="text-align:center">SELECCIONADOS</h4>
        <hr>
        <!-- Tabla que muestra conceptos seleccionados -->
        <div>
            <?php
            foreach ($contextoGlobalDisponiblesCabeceras as $disponibles) {
            ?>
                <p style="background-color:#ff9e18;color:black"><?= $disponibles['contenido_es'] ?> </p>
                
                <table class="table table-hover my-text-medium">
                    <thead>
                        <tr>
                            <th scope="col" width="100px"></th>
                            <th scope="col" style="text-align:center" width="500px"></th>
                            <?php
                            if ($condicion == false) {
                            ?>

                            <?php
                            } else {
                            ?>
                                <th scope="col" width="100px"></th>
                            <?php
                            }
                            ?>

                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $contador = 1;
                        foreach ($contextoGlobalSeleccionados as $seleccionado) {
                        ?>
                            <?php
                            if ($disponibles['contenido_es'] == $seleccionado['contenido']) {
                            ?>
                                <tr>
                                    <td><?= $contador ?></td>
                                    <td style="text-align:left;"><?= $seleccionado['contenido'] . ' ' . $seleccionado['sub_contenido'] ?></td>
                                    <?php
                                    if ($condicion == false) {
                                    ?>

                                    <?php
                                    } else {
                                    ?>
                                        <td>
                                            <?=
                                            Html::a(
                                                '<i class="far fa-trash-alt" style="color:red"></i>',
                                                [
                                                    'quitar-contenido', 'id' => $seleccionado['id'],
                                                    'pestana' => 'contexto_global'
                                                ],
                                                ['class' => 'link']
                                            );
                                            ?>
                                        </td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                        <?php
                                $contador = $contador + 1;
                            }
                        }

                        ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>