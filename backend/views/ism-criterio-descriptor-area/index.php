<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Configuración de criterios PAI';
?>

<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>


<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>

                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6"> |
                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fa fa-plus-circle" aria-hidden="true"></i> Inicio</span>',
                        ['site/index']
                    );
                    ?>
                    |
                </div>
                <!-- fin de primeros botones -->

                <!--botones derecha-->
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    |
                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> 
                            Nueva clase
                        </span>',
                        ['create']
                    );
                    ?>
                    |
                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>


            <!-- /****************************************************************************************************/  -->
            <!-- comienza cuerpo  -->
            <div class="row" style="margin-top: 15px; padding: 10px;">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        /** INICIO BOTONES DE ACCION * */
                        [
                            'class' => 'yii\grid\ActionColumn',
                            //                    'width' => '150px',
                            'template' => '{update}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-edit"></i>', $url, [
                                        'title' => 'Actualizar', 'data-toggle' => 'tooltip', 
                                        'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'update') {
                                    return \yii\helpers\Url::to(['update', 'id' => $key]);
                                }
                                //                        else if ($action === 'update') {
                                //                            return \yii\helpers\Url::to(['update', 'id' => $key]);
                                //                        }
                            }
                        ],
                        /** FIN BOTONES DE ACCION * */
                        'id',

                        [
                            'attribute' => 'id_area',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->area->nombre;
                            },
                            'filter' => $listaA,
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'prompt' => 'Seleccione area...'
                            ],
                        ],

                        [
                            'attribute' => 'id_curso',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->curso->name;
                            },
                            'filter' => $listaC,
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'prompt' => 'Seleccione curso...'
                            ],
                        ],

                        [
                            'attribute' => 'id_criterio',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->criterio->nombre;
                            },
                            'filter' => $listaCri,
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'prompt' => 'Seleccione criterio...'
                            ],
                        ],
                        
                        [
                            'attribute' => 'id_literal_criterio',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->literalCriterio->nombre_espanol;
                            },
                            // 'filter' => $listaCri,
                            // 'filterInputOptions' => [
                            //     'class' => 'form-control',
                            //     'prompt' => 'Seleccione criterio...'
                            // ],
                        ],
                        // 'id_descriptor',
                        
                        [
                            'attribute' => 'id_literal_descriptor',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->literalDescriptor->descripcion;
                            },
                            // 'filter' => $listaCri,
                            // 'filterInputOptions' => [
                            //     'class' => 'form-control',
                            //     'prompt' => 'Seleccione criterio...'
                            // ],
                        ],

                        //            ['class' => 'yii\grid\ActionColumn'],            
                    ],
                ]);
                ?>
            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>