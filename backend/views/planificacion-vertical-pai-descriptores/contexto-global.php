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
                        // echo "<pre>";
                        // print_r($contextoGlobalDisponiblesCabeceras);
                        // die();
                        foreach ($contextoGlobalDisponiblesCabeceras as $disponibles) {
                            if ($idioma == 'es') {
                                $contenido = $disponibles['contenido_es'];
                            } else if ($idioma == 'en') {
                                $contenido = $disponibles['contenido_en'];
                            } else {
                                $contenido = $disponibles['contenido_fr'];
                            }
                        ?>
                            <tr style="background-color:#ff9e18;">
                                <td><strong style="violet"><?= $contenido ?> </strong></td>
                            </tr>
                            <?php
                            // echo '<pre>';
                            // print_r($contextoGlobalDisponibles);
                            // die();
                            
                            foreach ($contextoGlobalDisponibles as $disponibles1) {
                                if ($idioma == 'es') {
                                    $contenido1 = $disponibles1['contenido_es'];
                                    $subcontenido=$disponibles1['sub_contenido'];
                                    
                                } else if ($idioma == 'en') {
                                    $contenido1 = $disponibles1['contenido_en'];
                                    $subcontenido=$disponibles1['sub_contenido_en'];
                                } else {
                                    $contenido1 = $disponibles1['contenido_fr'];
                                    $subcontenido=$disponibles1['sub_contenido_fr'];
                                }
                                
                                // comparamos variable padre con  variable hijo para 
                                // realizar el listado correctro por cabecera del 
                                // contexto global                                 
                                if ($contenido == $contenido1) {
                                ?>
                                    <tr>
                                        <td style="width:30px; text-align:justify">
                                            <?=
                                            Html::a(
                                                '<span > * ' . $subcontenido . '</span>',
                                                [
                                                    'asignar-contenido', 'plan_unidad_id' => $bloqueUnidad->id,
                                                    'tipo' => $disponibles1['tipo'],
                                                    'tipo2' => 'CONTEXTO GOBAL',
                                                    'id_relacion' => $disponibles1['id'],
                                                    'contenido' => $contenido1,
                                                    'sub_contenido' => $subcontenido,
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

     <!-- INICIO DE BLOQUE SELECCIONADOS -->

    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 border border-1 border-warning">
        <h4 style="text-align:center">SELECCIONADOS</h4>
        <hr>
        <!-- Tabla que muestra conceptos seleccionados -->
        <div>
            <?php
            // echo "<pre>";
            // print_r($contextoGlobalDisponiblesCabeceras);
            // die();
            foreach ($contextoGlobalDisponiblesCabeceras as $disponibles) {
                if ($idioma == 'es') {
                    $contenido = $disponibles['contenido_es'];
                } else if ($idioma == 'en') {
                    $contenido = $disponibles['contenido_en'];
                } else {
                    $contenido = $disponibles['contenido_fr'];
                }
            ?>
                <p style="background-color:#ff9e18;color:black"><?= $contenido ?> </p>
                
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
                        $contador = 0;
                        // echo "<pre>";
                        // print_r($contextoGlobalSeleccionados);
                        // die();
                        foreach ($contextoGlobalSeleccionados as $seleccionado) {
                            $contador++;
                        ?>
                            <?php
                            if ($contenido == $seleccionado['contenido']) {
                            ?>
                                <tr>
                                    <td><?= $contador ?></td>
                                    <td style="text-align:left;"><?= $seleccionado['contenido'] . ' ' . $seleccionado['sub_contenido'] ?></td>
                                    <?php
                                    if ($condicion == false) {
                                    // DESDE EL TRUE NO HACE NADA
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
                            }
                        }

                        ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>