<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Planificación Vertical TOC';
$this->params['breadcrumbs'][] = $this->title;

// echo "<pre>";
// print_r($unidades);
// die();
?>

<style>
    .fondo-campos {
        background-color: #ccc !important;
    }

    .estilo-link-modal {
        color: blue !important;
        cursor: pointer !important;
        background-color: #eee !important;
        font-weight: bold !important;
        font-size: 12px;
    }

    .table-bordered {
        border: 1px #afacab;
    }

    .menuizquierda {
        font-size: 13px;
        font-weight: bold;
        color: blue;
    }

    table {
        width: 100%;
        border-collapse: collapse;

    }

    .segunda_tabla {
        font-size: 12px;
        font-weight: bold;

    }

    .links {
        font-size: 12px;
    }

    .lin_tabla {
        border: 1px solid black;
        padding: 10px;
        background-color: #ccc !important;
    }

    .firmas {
        margin: 1 rem;
    }

    .bg-color {
        background-color: #eee !important;
    }

    .search-bar {
        position: relative;
        transition: top 0.5s ease;
        /* Propiedad de transición */
    }
</style>
<link href="ruta/al/archivo/fullcalendar.min.css" rel="stylesheet" />
<script src="ruta/al/archivo/moment.min.js"></script>
<script src="ruta/al/archivo/fullcalendar.min.js"></script>

<div class="planificacion-toc-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10 col-sm-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1 col-sm-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>
                    <p><b>2DO. DE BACHILLERATO "
                            <?php echo $unidades[0]->clase->paralelo->name; ?> "
                        </b></p>
                </div>
                <!-- INICIO BOTONES DERECHA -->
                <div class="col-lg-4 col-md-4 col-sm-4" style="text-align: right;">
                <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                            <i class="fa fa-briefcase" aria-hidden="true"></i> PDF</span>',
                            ['pdf', 'clase_id' =>  $unidades[0]->clase_id],
                            ['class' => '', 'title' => 'PDF']
                        );
                    ?>
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5">
                            <i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => '', 'title' => 'Inicio']
                        );
                    ?>
                </div>
                <!-- FIN BOTONES DERECHA -->
                <hr>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- inicia cuerpo de card -->
            <div class="row d-flex">
                <div class="col-lg-2 col-md-2 col-sm-2" style="height: 60vh; background-color: #eee; 
                    font-size: 10px;margin-top:-24px;">

                    <div class=" align-middle ancho-boton zoom"
                        style="border-bottom:solid 1px #ccc;margin-top:5px;">
                        <h6><a href="#datos" class="menuizquierda" style="height: 30px;margin-top:10px;">
                                DATOS INFORMATIVOS</a></h6>
                    </div>
                    <div class="ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <h6><a href="#plan" class=" menuizquierda">PLAN DE UNIDAD</a></h6>
                    </div>

                    <div class="ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <h6><a href="#firma" class="menuizquierda">FIRMAS</a></h6>
                    </div>
                    <div style="text-align: center;margin-top:10px;" class=" d-flex">
                   
                        <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #65b2e8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler i con-tabler-copy" width="28" height="28" viewBox="0 0 24 24" 
                                stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                                <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                              </svg>COPIAR PLANIFICACIÓN </span>',
                              ['copy', 'clase_id' =>  $unidades[0]->clase_id],
                              ['class' => '', 'title' => 'COPIAR PLANIFICACIÓN']
                            );
                        ?>
                    </div>

                </div>
                <div class=" col-lg-10 col-md-10 col-sm-10" style="overflow-y: scroll; height: 60vh;margin-top:-24px;">
                    <!-- Inicia tabla de datos informativos -->
                    <div class=" cursor: pointer" id="datos">
                        <?php echo $this->render('_datosinformativos', ['vertical' => $vertical]); ?>
                    </div>
                    <!-- FIN DE TABLA DE DATOS INF -->

                    <!-- Inicio PLan de Unidades -->
                    <div id="plan" class="">
                        <?php echo $this->render('_unidades', ['unidades' => $unidades, 'claseId' => $claseId ]); ?>
                    </div>
                    <!-- FIN DE PLAN DE UNIDADES      -->

                    <!-- INICIO FIRMAS -->
                    <div class="segunda_tabla cursor: pointer " id="firma" style=" margin-top:10px;margin-bottom:20px;">
                        <?php echo ('Elaborado por:') ?>
                        <?php buscar_respuesta($vertical, 'PROFESORES'); ?>
                    </div>
                    <div class="segunda_tabla cursor: pointer; " style="margin-left: 480px; margin-top:-2.3rem;">
                        <?php echo ('Aprobado por:') ?>
                        <!-- <?php buscar_respuesta($vertical, ''); ?>  Aprovar por supervisor -->
                    </div>
                    <!-- FIN FIRMAS -->
                </div>
            </div>

        </div>
        <!-- FIN DE CUERPO -->
    </div>
</div>
</div>