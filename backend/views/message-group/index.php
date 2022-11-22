<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MessageGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grupos para mensajería';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-group-index">

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
                        NUEVO GRUPO POR:
                    |                    
                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Curso</span>',
                        ['create','tipo' => 'curso']
                    );
                    ?>
                    |
                                  
                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Paralelo</span>',
                        ['create','tipo' => 'paralelo']
                    );
                    ?>
                    |
                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>


            <!-- /****************************************************************************************************/  -->
            <!-- comienza cuerpo  -->
            <div style="margin-top: 10px;">
                <?= GridView::widget([
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
                                    return Html::a('<i class="fas fa-edit"></i>', $url, [
                                        'title' => 'Actualizar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 
                                                    'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                },

                                'view' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-street-view"></i>', $url, [
                                        'title' => 'Integrantes', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 
                                                    'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                },

                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'update') {
                                    return \yii\helpers\Url::to(['update', 'id' => $key]);
                                }
                                else if ($action === 'view') {
                                    return \yii\helpers\Url::to(['view', 'id' => $key]);
                                }
                            }
                        ],
                        /** FIN BOTONES DE ACCION * */

                        'id',
                        'scholaris_periodo_id',
                        'source_id',
                        'source_table',
                        'nombre',
                        'tipo',
                        'estado:boolean'
                    ],
                ]); ?>
            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>