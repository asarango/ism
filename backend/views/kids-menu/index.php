<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menu Kids';

// echo '<pre>';
// print_r($class);
?>

<div class="scholaris-asistencia-profesor-index" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8 " style="background-color: #ccc;">

            <!--comienza cuerpo de documento-->
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <h4 style="color:white">Kids Menu</h4>
                    <hr>
                    <p>
                        |                                
                        <?=
                        Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                ['site/index'], ['class' => 'link']);
                        ?>                
                        |    
                    </p>
                </div>
            </div>

            <div class="row" style="background-color: #fff; margin-top:10px">

                <div class="col-md-4">
                    <div class="zoom" style="text-align:center; margin:15px">
                        <?=
                        Html::a(
                                '<img src="ISM/main/images/kids/planificador.png" width="20%" title="Planificaciones">  
                                    <br>Planificaciones',
                                [
                                    'kids-menu/index1'
                                ],
                                [
                                    'class' => 'link my-text-small'
                                ]
                        );
                        ?>
                    </div>
                </div>

                <div class="col-md-8">
                    <div style="padding-top:20px; padding-left:40px; padding-right:40px " >
                        <p>Aqui realizas planificación curricular anual (PCA) y planificación semanal</p>
                    </div>
                </div>

            </div>


            <div class="row" style="background-color: #fff; margin-top: 20px;">

                <div class="col-md-12 col-sm-12">
                    <div class="table table-responsive">
                        <table class="table table-hover table-stripped my-text-medium">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>CURSO</th>
                                    <th>Estado</th>
                                    <th colspan="2" style="text-align: center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($plans as $plan) {
                                    ?>
                                    <tr>
                                        <td><?= $plan['id'] ?></td>                                        
                                        <td><?= $plan['curso'] ?></td>
                                        <td><?= $plan['estado'] ?></td>
                                        <td style="width: 50px">
                                            <?=
                                            Html::a(
                                                    '<span class="badge" style="background-color:#0a1f8f">
                                                    <i class="fas fa-list" title="PCA" style="color:white"> 1.PCA</i>
                                                 </span>',
                                                    ['kids-pca/index1',
                                                        'pca_id' => $plan['id']
                                                    ]
                                            );
                                            ?>
                                        </td>
                                        <td style="width: 50px">
                                            <?=
                                            Html::a(
                                                    '<span class="badge" style="background-color:#ff9e18">
                                                    <i class="fas fa-list" title="Plan Semanal" style="color:white"> 2.PLAN SEMANAL</i>
                                                 </span>',
                                                    ['kids-plan-semanal/index',
                                                        'pca_id' => $plan['id']
                                                    ]
                                            );
                                            ?>
                                        </td>
                                        
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--finaliza cuerpo de documento-->


        </div>
    </div>

</div>