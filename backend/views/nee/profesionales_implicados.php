<?php

use yii\helpers\Html;
use yii\grid\GridView;

// echo "<pre>";
// print_r ($materiasNee);
// die();
// echo "<pre>";
// print_r($materiasSelect);
// die();

?>


<div class="row">
  

    <h5 style="text-align: start; margin-top: 10px;color:#0a1f8f">4.-PROFESIONALES IMPLICADOS</h5>
    <div class="col-lg-12 col-md-12" style="padding:10px">
        <div class="table responsive">
            <table class="table table-hover table-bordered table-striped my-text-medium" style="text-align: center">
                <thead style="background-color: #ab0a3d; color: white;">
                    <tr>
                        <th width="100px">NOMBRE</th>
                        <th width="100px">MATERIA</th>
                        <th width="100px">FUNCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($materiasNee as $seleccionada) {
                    ?>
                        <tr>
                            <td><strong> <?= $seleccionada->clase->profesor->last_name . ' ' . $seleccionada->clase->profesor->x_first_name ?> </strong></td>
                            <td><strong><?= $seleccionada->clase->ismAreaMateria->materia->nombre ?></strong></td>
                            <td><strong>DOCENTE</strong></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>