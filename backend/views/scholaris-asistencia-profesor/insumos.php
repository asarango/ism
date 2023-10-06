<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = '¡Mis insumos!';

// echo '<pre>';
// print_r($dataProvider);
// die();
?>

<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>


<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h3><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h3>
                </div>
                <div class="col-lg-8">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>

                </div>

                <!--botones derecha-->
                <div class="col-lg-3 col-md-3" style="text-align: right; margin-top: -5px;">
                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-share" width="15" height="15" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.247 0 .484 .045 .702 .127" />
                        <path d="M19 12h2l-9 -9l-9 9h2v7a2 2 0 0 0 2 2h5" />
                        <path d="M16 22l5 -5" />
                        <path d="M21 21.5v-4.5h-4.5" />
                        </svg> Inicio
                            </span>',
                        ['site/index']
                    );
                    ?>
                    |
                    <?php

                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Crear Insumo
                        </span>',
                        [
                            'view-actividad-crear/index1',
                            // 'clase_id' => $claseId,
                        ]
                    );

                    ?>

                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
                <hr>
            </div>

            <!-- /****************************************************************************************************/  -->
            <!-- comienza cuerpo  -->
            <div class="row" style="padding: 10px;margin-top: -25px;">
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
                            'template' => '{activity}',
                            'buttons' => [
                                'activity' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-edit"></i>', $url, [
                                        'title' => 'Ir a actividad',
                                        'data-toggle' => 'tooltip',
                                        'role' => 'modal-remote',
                                        'data-pjax' => "0",
                                        'class' => 'hand'
                                    ]);
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'activity') {



                                    return \yii\helpers\Url::to(['scholaris-actividad/actividad', 'actividad' => $model->actividad_id]);
                                }
                                //     else if ($action === 'update') {
                                //          return \yii\helpers\Url::to(['update', 'id' => $key]);
                                //      }
                            }
                        ],
                        /** FIN BOTONES DE ACCION * */
                        'clase_id',
                        'bloque',
                        'semana_numero',
                        'curso',
                        'paralelo',
                        'nombre',
                        'nombre_nacional',
                        'actividad_id',
                        'inicio',
                        'title',
                        // 'tipo_actividad_id', agregar el tipo actividad !
                        'total_calificados',
                        'total_estudiantes'
                        // [
                        //     'attribute' => 'ism_area_materia_id',
                        //     'format' => 'raw',
                        //     'value' => function ($model) {
                        //         return $model->ismAreaMateria->materia->nombre;
                        //     },
                        //     'filter' => $listaM,
                        //     'filterInputOptions' => [
                        //         'class' => 'form-control',
                        //         'prompt' => 'Seleccione asignatura...'
                        //     ],
                        // ],

                        //            ['class' => 'yii\grid\ActionColumn'],            
                    ],
                ]);
                ?>
            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>