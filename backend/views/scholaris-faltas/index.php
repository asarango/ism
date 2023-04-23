<?php

use backend\models\OpStudent;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Justificación de faltas del día';


?>
<!--Scripts para que funcionen AJAX'S-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>


<div class="scholaris-faltas-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <h6>
                           
                        </h6>
                    </small>
                </div>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- inicia menu cabecera -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu cabecera izquierda -->
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5">
                            <i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    );
                    ?>
                    |
                </div> <!-- fin de menu cabecera izquierda -->

                <!-- inicio de menu cabecera derecha -->
                <div class="col-lg-6 col-md-6" style="text-align: right;">

                </div>
                <!-- fin de menu cabecera derecha -->
            <!-- finaliza menu cabecera  -->

            <!-- inicia cuerpo de card -->
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    //'scholaris_perido_id',
                    'student',
                    'fecha_falta',
                    'solicita_justificacion',
                    'fecha_solicitud_justificacion',
                    //'motivo_justificacion:ntext',
                    'es_justificado:boolean',
                    'fecha_justificacion',
                    //'respuesta_justificacion:ntext',
                    //'usuario_justifica',
                    //'created',
                    //'created_at',
                    //'updated',
                    //'updated_at',

                    /** INICIO BOTONES DE ACCION * */
                    [
                        'class' => 'yii\grid\ActionColumn',
//                    'width' => '150px',
                        'template' => '{justificar}',
                        'buttons' => [
                            'justificar' => function ($url, $model) {
                                return Html::a('<i class="fas fa-balance-scale-right"></i>', $url, [
                                    'title' => 'Justificar falta', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                ]);
                            }
                        ],
                        'urlCreator' => function ($action, $model, $key) {
                            if ($action === 'justificar') {
                                return \yii\helpers\Url::to(['justificar', 'id' => $model->id]);
                            }
//                        else if ($action === 'update') {
//                            return \yii\helpers\Url::to(['update', 'id' => $key]);
//                        }
                        }
                    ],
                    /** FIN BOTONES DE ACCION * */
                ],
            ]); ?>
            <!-- fin cuerpo de card -->
        </div>
    </div>