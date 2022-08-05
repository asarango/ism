<?php

use backend\models\PlanificacionVerticalDiplomaHabilidades;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use yii\helpers\Html;

//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación Semanal';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($seccion);
// die();
?>

<div class="planificacion-vertical-pai-criterios-index">
    <!-- CABECERA -->
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"  class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small><b>BLOQUE Nº: </b> 

                    </small>
                </div>
            </div>
            <!-- FIN DE CABECERA -->


            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>


                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->


                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->


            <hr>

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 10px; margin-left:1px;margin-right:1px; margin-bottom:5px">

                <div class="table table-responsive">
                    <table class="table table-condensed table-hover table-bordered">
                        <thead>
                            <tr>
                                <?php
                                foreach ($calendario as $calen) {
                                    echo '<th class="text-center" style="background: #ccc">';
                                    echo $calen['nombre'].'<br>';
                                    echo $calen['fecha'].'<br>';
                                    echo '</th>';
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <?php
                            
                                foreach ($calendario as $cal){
                                    $fecha = $cal['fecha'];
                                    echo '<td class="text-center"> Total actividades: ';
                                    foreach ($actividades as $act){
                                        if($fecha == $act['inicio']){
                                            echo '<b>'.$act['total_actividades'].'</b>';
                                        }
                                    }
                                    echo '</td>';
                                }
                            
                            ?>
                            </tr>
                            <tr>
                                <?php
                                foreach ($calendario as $calen) {
                                    echo '<td class="text-center">';
                                    ?>
                                    <a href="#" onclick="ver_detalle('<?= $calen['fecha'] ?>')">VER DETALLE</a>
                                <?php
                                    echo '</td>';
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>                                               
             
            </div>
            
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8" id="div-detalle">
                    
                </div>
                <div class="col-lg-2 col-md-2"></div>
            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>
</div>

<script>
    function ver_detalle(fecha){
        var url = '<?= yii\helpers\Url::to(['detalle']) ?>';
        
        params = {
            fecha : fecha
        };
        
        $.ajax({
            data : params,
            url  : url,
            type: 'GET',
            beforeSend: function () {},
            success: function (resp) {
                        $('#div-detalle').html(resp);
                    }
        });
        
    }
</script>