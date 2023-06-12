<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Planificación TOC ';
$this->params['breadcrumbs'][] = $this->title;
// echo "<pre>";
// print_r($classes);
// die();
?>

<div class="planificacion-toc-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-7">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <!-- INICIO BOTONES DERECHA -->
                <div class="col-lg-4 col-md-4" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i 
                            class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                    ?>
                </div>
                <!-- FIN BOTONES DERECHA -->
                <hr>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- inicia cuerpo de card -->
            <div>
                <div class="card text-center; col-lg-4" style="margin-bottom: 480px;margin-left:20px; margin-top: 5px;">
                    <div class="card-header">
                        <h4>Plan Vertical</h4>
                    </div>
                    <div class="card-body">
                        <ul>
                            <?php
                            foreach ($classes as $clase) {
                                // echo '<li>' . $clase['curso'] . ' ' . '" B "' . '</li>';
                                echo Html:: a ('<li>' . $clase['curso'] . ' ' . '" B "' . '</li>', ['toc-plan-vertical/index1','clase_id'=>$clase['clase_id']]);
                            }
                            ?>
                        </ul>

                    </div>
                    <div class="card-footer text-muted">
                        Duración: 42 Semanas
                    </div>
                </div>
            </div>
            <div class="card text-center; col-lg-4" style="margin-bottom:20px;margin-left:400px;margin-top:-644px;">
                <div class="card-header">
                    <h4>Plan de Unidad</h4>
                </div>
                <div class="card-body">
                    <ul>
                        <?php
                        foreach ($classes as $clase) {
                            echo Html:: a ('<li>' . $clase['curso'] . ' ' . '" B "' . '</li>', ['toc-plan-vertical/index1','clase_id'=>$clase['clase_id']]);
                        }
                        ?>
                    </ul>
                </div>
                <div class="card-footer text-muted">
                    Cantidad de Unidades: 4
                </div>
            </div>
            <div class="card text-center; col-lg-3" style="margin-bottom: 30px;margin-left:780px;margin-top:-184px;">
                <div class="card-header">
                    <h4>Plan Semanal</h4>
                </div>
                <div class="card-body">
                <ul>
                            <?php
                            foreach ($classes as $clase) {
                                // echo '<li>' . $clase['curso'] . ' ' . '" B "' . '</li>';
                                echo Html:: a ('<li>' . $clase['curso'] . ' ' . '" B "' . '</li>', ['toc-plan-vertical/index1','clase_id'=>$clase['clase_id']]);
                            }
                            ?>
                        </ul>
                </div>
                <div class="card-footer text-muted">
                    Duración: XX Horas
                </div>
            </div>

            <!-- fin cuerpo de card -->
        </div>
        <!-- Termina shadow principal -->
    </div>
</div>