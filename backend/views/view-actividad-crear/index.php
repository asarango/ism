<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ViewActividadCrearSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Elija una actividad';
$this->params['breadcrumbs'][] = $this->title;


?>



<div class="view-actividad-crear-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10">
            <div class="row align-items-center p-2">
                <div class="col-lg-1">
                    <h3><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h3>
                </div>
                <div class="col-lg-8">

                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>

                </div>

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
                        </svg> Regresar
                            </span>',
                        ['scholaris-asistencia-profesor/insumos']
                    );
                    ?>
                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->

                <hr>
            </div>

            <div class="row" style="padding: 10px;margin-top: -25px;">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'id',
                        'plan_id',
                        'curso',
                        'paralelo',
                        'trimestre',
                        'nombre_semana',
                        'fecha',
                        'hora',
                        'materia',
                        // 'tema',
                        // 'login',
                        [
                            'attribute' => 'tema',
                            'format' => 'raw', // Usar 'raw' para no escapar HTML
                            'value' => function ($model) {
                                $temaSinEtiquetas = strip_tags($model->tema); // Elimina etiquetas HTML
                                return Html::encode($temaSinEtiquetas); // Escapar cualquier etiqueta HTML restante en "tema"
                            },
                        ],

                        [
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a(
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-apps-filled" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#597e8d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M9 3h-4a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2z" stroke-width="0" fill="currentColor" />
                                    <path d="M9 13h-4a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2z" stroke-width="0" fill="currentColor" />
                                    <path d="M19 13h-4a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2z" stroke-width="0" fill="currentColor" />
                                    <path d="M17 3a1 1 0 0 1 .993 .883l.007 .117v2h2a1 1 0 0 1 .117 1.993l-.117 .007h-2v2a1 1 0 0 1 -1.993 .117l-.007 -.117v-2h-2a1 1 0 0 1 -.117 -1.993l.117 -.007h2v-2a1 1 0 0 1 1 -1z" stroke-width="0" fill="currentColor" />
                                  </svg>',
                                    [
                                        'create', 'id' => $model->id,
                                        'plan_id' => $model->plan_id,
                                    ],
                                    [
                                        'title' => 'Crear Actividad',
                                    ]
                                );
                            },
                        ],

                        // [

                        //     'format' => 'raw',
                        //     'value' => function ($model) {
                        //         // echo "<pre>";
                        //         // print_r($model);
                        //         // die();
                        //         return Html::a(
                        //             '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tool" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#597e8d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        //             <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        //             <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" />
                        //           </svg>',
                        //             [
                        //                 'view-actividad-crear/view',
                        //                 'actividad_id' => $model->id,
                        //             ],

                        //             [
                        //                 'title' => 'Crear Actividad',
                        //             ]
                        //         );
                        //     },
                        // ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>



</div>