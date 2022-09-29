<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

$this->title = 'Detalle de Malla ' . $peridoMalla->malla->nombre;
?>


<div class="ism-area-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/areas.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fas fa-list"></i> Listado de Mallas</span>',
                            ['ism-periodo-malla/index'],
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

            <div class="row" style="margin-top: 20px; margin-bottom: 20px">
                <!-- #################### inicia cuerpo de card ##########################-->


                <!--inicio de arbol-->
                <div class="col-lg-12 col-md-12">

                    <div class="card p-4 table table-responsive">

                        <table class="table table-hover table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">AREA / ASIGNATURA</th>
                                    <th class="text-center">PROM</th>
                                    <th class="text-center">IMPR</th>
                                    <th class="text-center">CUANT</th>
                                    <th class="text-center">TIPO</th>
                                    <th class="text-center">%</th>
                                    <th class="text-center">ORDEN</th>
                                    <th class="text-center">H.SEM</th>
                                    <th class="text-center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //echo '<pre>';
                                //print_r($malla);
                                foreach ($malla as $m) {
                                    ?>
                                    <tr>
                                        <td class="" style="background-color: #898b8d; color: white">
                                            <i class="fas fa-tree" style="color: greenyellow"></i> 
                                                <?= '('.$m['id'].') ' .$m['nombre']?>
                                        </td>
                                        <td class="text-center" style="background-color: #0a1f8f; color: white"><?= $m['promedia']?></td>
                                        <td class="text-center" style="background-color: #0a1f8f; color: white"><?= $m['imprime_libreta']?></td>
                                        <td class="text-center" style="background-color: #0a1f8f; color: white"><?= $m['es_cuantitativa']?></td>
                                        <td class="text-center" style="background-color: #0a1f8f; color: white"><?= $m['tipo']?></td>
                                        <td class="text-center" style="background-color: #0a1f8f; color: white"><?= $m['porcentaje']?></td>
                                        <td class="text-center" style="background-color: #0a1f8f; color: white"><?= $m['orden']?></td>                                        
                                        <td class="text-center" style="background-color: #0a1f8f; color: white">-</td>                                        
                                        <td class="text-center" style="background-color: #0a1f8f; color: white">
                                            <?= 
                                                Html::a('<i class="fas fa-edit zoom" style="color: #fff"></i>', ['ism-malla-area/update', 
                                                        'id' => $m['id']
                                                    ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                        foreach ($m['materias'] as $mat){
                                            ?>
                                    <tr>
                                        <td style="padding-left: 5%"><i class="fas fa-file-signature" style="color: #ab0a3d"></i> 
                                            <?= '('.$mat['id'].') ' . $mat['nombre'] ?>
                                        </td>
                                        <td class="text-center"><?= $mat['promedia'] ?></td>
                                        <td class="text-center"><?= $mat['imprime_libreta'] ?></td>
                                        <td class="text-center"><?= $mat['es_cuantitativa'] ?></td>
                                        <td class="text-center"><?= $mat['tipo'] ?></td>
                                        <td class="text-center"><?= $mat['porcentaje'] ?></td>
                                        <td class="text-center"><?= $mat['orden'] ?></td>
                                        <td class="text-center"><?= $mat['total_horas_semana'] ?></td>
                                        <td class="zoom">
                                            <?= 
                                                Html::a('<i class="fas fa-edit" style="color: #ab0a3d"></i>', ['ism-area-materia/update', 
                                                        'id' => $mat['id']
                                                    ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>                    

                </div> 
                <!--fin de arbol-->             

            </div><!-- ######################## fin cuerpo de card #######################-->


        </div><!-- fin de card principal -->
    </div>
</div>
