<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Configuración de clases';

// echo "<pre>";
// print_r($clase);
// die();
?>

<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>


<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
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
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nueva clase</span>',
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
                            'template' => '{update} {cambiar}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-edit"></i>', $url, [
                                        'title' => 'Actualizar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                },
                                'cambiar' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-exchange-alt"></i>', $url, [
                                        'title' => 'Cambiar horario', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                },
                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'update') {
                                    return \yii\helpers\Url::to(['scholaris-clase-aux/update', 'id' => $key]);
                                } else if ($action === 'cambiar') {
                                    return \yii\helpers\Url::to(['cambiar-horario', 'id' => $key]);
                                }
                            }
                        ],
                        /** FIN BOTONES DE ACCION * */
                        'id',
                        //'idmateria',
                        //            'ism_area_materia_id',
                        [
                            'attribute' => 'ism_area_materia_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->ismAreaMateria->materia->nombre;
                            },
                            'filter' => $listaM,
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'prompt' => 'Seleccione asignatura...'
                            ],
                        ],
                        //            'idprofesor',
                        [
                            'attribute' => 'idprofesor',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->profesor->x_first_name . ' ' . $model->profesor->last_name;
                            },
                            'filter' => $listaT,
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'prompt' => 'Seleccione docente...'
                            ],
                        ],
                        //'idcurso',
                        [
                            'attribute' => 'paralelo_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->paralelo->course->name . ' - | ' . $model->paralelo->name . ' |';
                            },
                            'filter' => $listaC,
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'prompt' => 'Seleccione curso...'
                            ],
                        ],
                        //'paralelo_id',
                        //'peso',
                        //'periodo_scholaris',
                        //'promedia',
                        'asignado_horario',
                        [
                            'attribute' => 'asignado_horario',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $tipo = backend\models\ScholarisHorariov2Cabecera::findOne($model->asignado_horario);
                                return $tipo->descripcion;
                            },
                            //                            'filter' => $listaC,
                            //                            'filterInputOptions' => [
                            //                                'class' => 'form-control',
                            //                                'prompt' => 'Seleccione curso...'
                            //                            ],
                        ],
                        'tipo_usu_bloque',
                        'todos_alumnos',
                        //'malla_materia',
                        //'materia_curriculo_codigo',
                        //'codigo_curso_curriculo',
                        //'fecha_cierre',
                        //'fecha_activacion',
                        //'estado_cierre:boolean',
                        //'rector_id',
                        //'coordinador_dece_id',
                        //'secretaria_id',
                        //'coordinador_academico_id',
                        //'inspector_id',
                        //'dece_dhi_id',
                        //'tutor_id',            
                        'es_activo:boolean',
                        //            ['class' => 'yii\grid\ActionColumn'],            
                    ],
                ]);
                ?>
            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>