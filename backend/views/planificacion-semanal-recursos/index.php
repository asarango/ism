<?php

use yii\helpers\Html;
use yii\grid\GridView;

// echo "<pre>";
// print_r($searchModel);
// die();

$this->title = 'Crear Recursos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planificacion-semanal-recursos-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-11 col-md-11 col-sm-11">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/actividad-fisica.png" width="64px" style=""
                            class="img-thumbnail"></h4>
                    <p>

                    </p>
                </div>
                <div class="col-lg-7">
                    <h2>
                        <?= Html::encode($this->title) ?>
                    </h2>
                </div>

                <!-- BOTONES -->
                <div class="col-lg-4 col-md-4" style="text-align: right">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up" 
                            width="12" height="12" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                            <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                          </svg> Regresar</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                    ?>
                    |
                    <?= Html::a(

                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-apps" width="12" height="12" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        <path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        <path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        <path d="M14 7l6 0" />
                        <path d="M17 4l0 6" />
                      </svg>  Nuevo Recurso</span>',
                        ['create', 'planificacion_semanal_id' => $planificacionSemanal->id]
                    ) ?>
                    |
                    <?= Html::a(

                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                      </svg>  Vista previa</span>',
                        [''],
                        ['class' => '', 'title' => 'Crear Recurso']
                    ) ?>

                </div>
                <!-- FIN BOTONES -->
            </div>
            <hr>

            <!-- COMIENZO CUERPO -->
            <div class="row">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        // ['attribute' => 'id',
                        //     'visible' => false,
                        // ],
                        // ['atribute' => 'plan_semanal_id',
                        //     'visible' => false,],
                        'tema',
                        'tipo_recurso',
                        'url_recurso:url',
                        //'estado:boolean',
                
                        /** INICIO BOTONES DE ACCION * */
                        [
                            'class' => 'yii\grid\ActionColumn',
                            //                    'width' => '150px',
                            'template' => '{update}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, [
                                    'title' => 'Actualizar',
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
                            }
                            //                        else if ($action === 'update') {
//                            return \yii\helpers\Url::to(['update', 'id' => $key]);
//                        }
                        }
                        ],
                        /** FIN BOTONES DE ACCION * */
                    ],
                ]); ?>
            </div>
            <!-- FIN DE CUERPO -->


        </div>
    </div>
</div>

