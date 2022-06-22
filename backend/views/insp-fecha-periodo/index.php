<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

//echo'<pre>';
//print_r($areas);

$this->title = 'Fechas del año lectivo';
?>

<div class="insp-fecha-periodo-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fas fa-clipboard-list"></i> Areas</span>',
                            ['ism-area/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fas fa-folder-plus"></i> Nueva Asignatura</span>',
                            ['create'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            
             <!-- #################### inicia cuerpo de card ##########################-->
            <div class="row p-5">
               

                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,                    
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'fecha',
                        //'periodo_id',
                        //'numero_dia',
                        'hay_asitencia:boolean',
                        'es_presencial:boolean',
                        'observacion',
                        /** INICIO BOTONES DE ACCION * */
                        [
                            'class' => 'yii\grid\ActionColumn',
                            //                    'width' => '150px',
                            'template' => '{update} {view} {mapa}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-edit"></i>', $url, [
                                        'title' => 'Actualizar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                },
                                'view' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-eye"></i>', $url, [
                                        'title' => 'VIsualizar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                },
                                'mapa' => function ($url, $model) {
                                    return Html::a('<i class="fab fa-accusoft"></i>', $url, [
                                        'title' => 'VIsualizar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                },
                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'update') {
                                    return \yii\helpers\Url::to(['update', 'fecha' => $key]);
                                } else if ($action === 'view') {
                                    return \yii\helpers\Url::to(['view', 'fecha' => $key]);
                                }
//                                else if($action === 'mapa') {
//                                    return \yii\helpers\Url::to(['materias-pai/mapa-enfoques', 'materia_id' => $key]);
//                                }
                            }
                        ],
                    /** FIN BOTONES DE ACCION * */
                    ],
                ]);
                ?>

            </div><!-- ######################## fin cuerpo de card #######################-->


        </div><!-- fin de card principal -->
    </div>
</div>
