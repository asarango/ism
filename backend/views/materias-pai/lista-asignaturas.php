<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aprobación de Planificaciónes de mapa de enfoques de PAI';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($materias);
?>
<div class="materias-pai-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/libros.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        (Esta pantalla muestra todas las materias pertenecientes al PAI - para JEFES DE ÁREA)
                    </small>
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
                        '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="fa fa-briefcase" aria-hidden="true"></i> Aprobaciones</span>',
                        ['planificacion-aprobacion/index'],
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

            <div class="table table-responsive">
                
                <table class="table table-hover table-bordered table-condensed table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">ASIGNATURA</th>
                            <th class="text-center">JEFE ÁREA</th>
                            <th class="text-center">ESTADO</th>
                            <th class="text-center">ACCIÓN</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            $i = 0;
                            foreach($details as $detail){
                                $i++;
                                ?>
                                <tr>
                                    <td class="text-center"><?= $i ?></td>
                                    <td><?= $detail['materia'] ?></td>
                                    <td><?= $detail['jefe_area'] ?></td>
                                    <td><?= $detail['estado'] ?></td>
                                    <td>
                                        <?php
                                            if($detail['estado'] == 'COORDINADOR'){
                                                echo Html::a('APROBAR', ['aprobar', 'id' => $detail['aprobacion_id'] ]);
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>

            </div>
            
            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>