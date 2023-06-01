<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '¡PARCIALES!';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="scholaris-bloque-semanas-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-11 col-md-11">
            <div class=" row " style="margin-top: 10px;">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style=""
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-7" style="margin-top: 20px;">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <!-- botones -->
                <div class="col-lg-4 col-md-4" style="text-align: right;">
                    <?= // Boton "Inicio"//
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i>  Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                    ?>
                    |
                    <?= //boton " Crear Bloque "//
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8">
                            <i class="fas fa-plus"></i> Crear bloque
                        </span>',
                            ['create'],
                            ['class' => 'link']
                        );
                    ?>
                    <!-- |
                    <?= //boton "Crear Notificación (eliminado)"//
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                            <i class="far fa-plus-square" aria-hidden="true"></i> Crear notificación
                        </span>',
                            ['create'],
                            ['class' => 'link']
                        );
                    ?> -->
                </div>
                <hr>
            </div><!-- FIN DE CABECERA -->

            <!-- inicia menu  -->
            <div class="row"></div>
            <!-- finaliza menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: -15px; margin-center: 60px">

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
                                'template' => '{update} {view}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                                        'title' => 'Actualizar',
                                        'data-toggle' => 'tooltip',
                                        'role' => 'modal-remote',
                                        'data-pjax' => "0",
                                        'class' => 'hand'
                                    ]);
                                },
                                    'view' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-street-view"></i>', $url, [
                                        'title' => 'Ver detalle',
                                        'data-toggle' => 'tooltip',
                                        'role' => 'modal-remote',
                                        'data-pjax' => "0",
                                        'class' => 'hand'
                                    ]);
                                }
                                ],
                                'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'update') {
                                    return \yii\helpers\Url::to(['update', 'id' => $key]);
                                } else if ($action === 'view') {
                                    return \yii\helpers\Url::to(['view', 'id' => $key]);
                                }
                            }
                            ],
                            /** FIN BOTONES DE ACCION * */
                            'id',
                            // 'tipo_uso',
                            [
                                'attribute' => 'tipo_uso',
                                'format' => 'raw',
                                'value' => function ($model) {
                                return $model->uso->nombre;
                            },
                                'filter' => $listaComparte,
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'prompt' => 'Seleccione tipo...'
                                ],
                            ],
                            'quimestre',
                            'tipo_bloque',
                            'orden',
                            'codigo',
                            'name',
                            'abreviatura',
                            'desde',
                            'hasta',
                            'bloque_inicia',
                            'bloque_finaliza',
                            // 'es_activo:boolean',
                        ],
                    ]);
                ?>

            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>