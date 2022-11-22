<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MessageGroup */

$this->title = 'Grupo: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Message Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="message-group-view">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8 col-md-8">
                    <h6><?= Html::encode($this->title) ?></h6>
                    <small>
                        | <b>Tipo: </b><?= $model->tipo ?>
                        | <b>Recurso: </b><?= $model->source_table ?>
                        | <b>Código Ref: </b><?= $model->source_id ?> |
                    </small>
                </div>

                <div class="col-lg-3 col-md-3" style="text-align: right;">
                    | <?php
                        echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fa fa-plus-circle" aria-hidden="true"></i> Inicio</span>',
                            ['site/index']
                        );
                        ?>
                    |
                </div>
            </div>
            <hr>
            <!-- /****************************************************************************************************/  -->
            <!-- comienza cuerpo  -->
            <div class="row" style="margin: 10px; padding: 5px;">

                <!-- INICIA VIEW -->
                <div class="col-lg-4 col-md-4" style="border: solid 1px #ccc; padding: 10px;">
                    <p>
                        <?= Html::a('<i class="fas fa-edit" style="color: #0a1f8f"> Actualizar</i>', ['update', 'id' => $model->id], ['class' => 'zoom']) ?>
                        <?= Html::a('<i class="fas fa-trash-alt"> Eliminar</i>', ['delete', 'id' => $model->id], [
                            'class' => 'zoom',
                            'style' => 'color: #ab0a3d',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'scholaris_periodo_id',
                            'source_id',
                            'source_table',
                            'nombre',
                            'tipo',
                            'estado:boolean',
                        ],
                    ]) ?>
                </div>
                <!-- FIN DE VIEW -->

                <div class="col-lg-1 col-md-1"></div>

                <!-- INICIA LISTA DEL GRUPO -->
                <div class="col-lg-7 col-md-7" style="border: solid 1px #ccc; padding: 10px;">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'id',
                        'usuario',
                        'usuario0.resUser.partner.name',
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
                                    return \yii\helpers\Url::to(['update', 'id' => $key]);
                                } else if ($action === 'view') {
                                    return \yii\helpers\Url::to(['view', 'id' => $key]);
                                }else if($action === 'mapa') {
                                    return \yii\helpers\Url::to(['materias-pai/mapa-enfoques', 'materia_id' => $key]);
                                }
                            }
                        ],
                    /** FIN BOTONES DE ACCION * */
                    ],
                ]);
                ?>
                </div>
                <!-- FIN LISTA DEL GRUPO -->

            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>