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
        color: blue;
        cursor: pointer;
    }

    .table-bordered {
        border: 1px #afacab;
    }

    .table bordered td,
    .table bordered th {
        border: 1px #afacab;
    }


    .menuizquierda {
        font-size: 12px;
        font-weight: bold;

    }

    table {
        width: 100%;
        border-collapse: collapse;

    }

    td {
        border: 1px #afacab;
        padding: 10px;
        vertical-align: middle;
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


    body {
        position: relative;
        min-height: 100vh;
    }

    .content {
        padding-bottom: 60px;
        /* Altura de la firma */
    }

    .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 60px;
        /* Altura de la firma */
        background-color: lightgray;
        text-align: center;
        padding-top: 20px;
    }


    .boton {
        background-color: #ab0a3d;
    }

    .agg_unidad {
        color: blue;
        cursor: pointer;
    }

    .plan-uni {
        font-size: 15px;
        font-weight: bold;
    }

    .pointer {
        cursor: pointer;
    }
</style>

<link href="ruta/al/archivo/fullcalendar.min.css" rel="stylesheet" />
<script src="ruta/al/archivo/moment.min.js"></script>
<script src="ruta/al/archivo/fullcalendar.min.js"></script>

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
            <div class="row">
                <div class="col-lg-2 col-md-2" style="height: 60vh; background-color: #eee; 
                    font-size: 10px;margin-top:-24px;">

                    <div class=" row align-middle ancho-boton zoom"
                        style="border-bottom:solid 1px #ccc;margin-top:5px;">
                        <h6><a href="#datos" class="menuizquierda" style="height: 30px;margin-top:10px;">
                                DATOS INFORMATIVOS</a></h6>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <h6><a href="#plan" class=" menuizquierda">PLAN DE UNIDAD</a></h6>
                    </div>

                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <h6><a href="#firma" class="menuizquierda">FIRMAS</a></h6>
                    </div>
                </div>

                <div class="col-lg-10 col-md-10" style="overflow-y: scroll; height: 60vh;margin-top:-24px;">
                    <!-- Inicia tabla de datos informativos -->
                    <div class="row" id="datos">
                        <?php echo $this->render('_datosinformativos', ['vertical' => $vertical]); ?>
                    </div>
                    <!-- FIN DE TABLA DE DATOS INF -->

                    <!-- Inicio de Unidades -->
                    <div id="plan" class="row">
                        <?php echo $this->render('_unidades', ['unidades' => $unidades]); ?>
                    </div>
                    <!-- FIN DE UNIDADES      -->

                    <!-- INICIO FIRMAS -->
                    <div class="segunda_tabla cursor: pointer" id="firma" style=" margin-top:10px;margin-bottom:20px;">
                        <?php echo ('Elaborado por:') ?>
                        <?php buscar_respuesta($vertical, 'PROFESORES'); ?>
                    </div>
                    <div class="segunda_tabla cursor: pointer;" style="margin-left: 480px; margin-top:-44px;">
                        <?php echo ('Aprovado por:') ?>
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


<?php
function buscar_respuesta($modelVertical, $campo)
{
    foreach ($modelVertical as $plan) {
        if ($plan->opcion_descripcion == $campo) {
            ?>
            <!-- Button trigger modal -->
            <a data-bs-toggle="modal" data-bs-target="#modal<?= $plan->id ?>">
                <?php
                echo $plan->contenido;
                ?>
            </a>

            <!-- Modal -->
            <div class="modal fade" id="modal<?= $plan->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modificando Campo</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?= Html::beginForm(['update-field'], 'post') ?>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $plan->id ?>">
                            <div class="form-group">
                                <label class="form-label"><b>Contenido</b></label>
                                <textarea name="contenido" class="form-control"><?= $plan->contenido ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                </div>
            </div>
            <?php

        }
    }
}
?>