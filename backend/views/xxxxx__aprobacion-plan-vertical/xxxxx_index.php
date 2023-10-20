<?php

use backend\models\PlanificacionVerticalDiplomaHabilidades;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Aprobaciones de Vertical ';
$this->params['breadcrumbs'][] = $this->title;
// echo "<pre>";
// print_r($curso);
// die();
?>


<div class="aprobacion-plan-semanal">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8 col-sm-8">
            <!-- INICIO DE CABECERA  -->
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style=""
                            class="img-thumbnail">
                </div>
                <div class="col-lg-8">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                </div>
                <!-- BOTONES -->
                <div class="col-lg-3">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                    ?>
                    |
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Aprobaciones</span>',
                            ['planificacion-aprobacion/index'],
                            ['class' => 'link']
                        );
                    ?>
                </div>
                <!-- BOTONES -->
                <hr>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- INICIO DE TABLA  -->

            <section class="row">

                <div class="table table-responsive">

                    <table class="table table-hover table-condensed table-striped table-bordered">
                        <thead class="" style="text-align: center">
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>ASIGNATURA</th>
                                <th>Estado</th>
                            </tr>

                            <?php
                            // foreach ($var1 as $trimestre){
                            //     echo '<th class="text-center">'.$trimestre['id'].'</th>'
                            // }
                            ?>

                        </thead>
                        <tbody>
                            <?php
                            $i = 0;

                            // foreach
                            

                            foreach ($materias as $mat) {
                                $i++;
                                echo '<tr>';
                                    echo '<td class="text-center">' . $i . '</td>';
                                    echo '<td class="text-center">' . $mat['id'] . '</td>';
                                    echo '<td class="text-center">' . $mat['nombre'] . '</td>';
                                echo '<tr>';
                            }


                            ?>
                        </tbody>
                    </table>

                </div>
            </section>

            <!-- FIN DE TABLA  -->

        </div>
    </div>

</div>