<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aprobaciondes de planificaciones de unidad';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>';
//print_r($cabecera);
//die();
?>
<!-- Jquery AJAX -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>



<div class="pca-index1">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8 col-sm-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-10">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>Asignaturas para: <?= $curso->name ?></small>
                </div>
            </div><!-- FIN DE CABECERA -->


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

                    |

                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Aprobaciones</span>',
                            ['planificacion-aprobacion/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                   
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            
            <div class="row" style="margin-top: 20px">
                <div class="table table-responsive">
                    <table class="table table-hover table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">ID</th>
                                <th class="text-center">ASIGNATURA</th>
                                <?php
                                    foreach ($bloques as $bloque){                                                                             
                                        echo '<th class="text-center">'.$bloque['shot_name'].'</th>';
                                    }
                                ?>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                            $i = 0;
                            
                            foreach ($detalle as $det){
                                $i++;
                                echo '<tr>';
                                    echo '<td class="text-center">'.$i.'</td>';
                                    echo '<td class="text-center">'.$det['id'].'</td>';
                                    echo '<td>'.$det['nombre'].'</td>';
                                    
                                    foreach ($bloques as $b){     
                                        echo '<td class="text-center">';
                                        if(count($det['porcentajes'])>0){
                                            foreach ($det['porcentajes'] as $por){
                                                if($por['id'] == $b->id){
                                                    
                                                    echo Html::a($por['avance_porcentaje'].' %',['detalle', 
                                                            'bloque_id'         => $b->id,
                                                            'ism_area_materia_id'  => $det['id']
                                                        ]);
                                                }
                                            }
                                        }else{
                                            echo '--';
                                        }
                                        echo '</td>';                                        
                                        
                                    }
                                    
                                echo '</tr>';
                            }                            
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>            
            
            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>



