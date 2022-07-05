<?php

use backend\models\PlanificacionVerticalDiplomaHabilidades;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use yii\helpers\Html;
//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opciones del plan Vertical Diploma';

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
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small><b>BLOQUE Nº: </b> 
                        <?= $modelPlanVertical->planificacionBloqueUnidad->curriculoBloque->last_name ?><b> | 
                        <?= $modelPlanVertical->planificacionBloqueUnidad->unit_title ?></b> | 
                        <?= $modelPlanVertical->planificacionBloqueUnidad->planCabecera->ismAreaMateria->materia->nombre ?></b>
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

                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificación Vertical</span>',
                            ['planificacion-vertical-diploma/index1', 'unidad_id' => $modelPlanVertical->planificacion_bloque_unidad_id],
                            ['class' => 'link']
                    );
                    ?>
                    |

                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    
                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

                      

            <!-- inicia cuerpo de card -->
            
            <!-- fin cuerpo de card -->
        </div>
    </div>
</div>


