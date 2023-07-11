<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notificaciones enviadas';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($detalle);
//die();
?>
<div class="planificacion-aprobacion-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-7" >
                    <h4><?= Html::encode($this->title) ?></h4>

                </div>
                <div class="col-lg-4 col-md-4" style="text-align: right;">
                    <!-- botones derecha -->
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
                        '<span class="badge rounded-pill" style="background-color: #65b2e8">
                            <i class="far fa-envelope-open" aria-hidden="true"></i> Recibidos
                        </span>',
                        ['received'],
                        ['class' => 'link']
                    );
                    ?>

                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-plus-square" aria-hidden="true"></i> Crear notificación</span>',
                        ['create'],
                        ['class' => 'link']
                    );
                    ?>
                    <!-- fin botones derecha -->
                </div>
                <hr>
            </div><!-- FIN DE CABECERA -->



            <!-- inicia cuerpo de card -->
            <div class="row" style="margin: -1.2rem 1rem 1rem 1rem;">

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
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-street-view"></i>', $url, [
                                        'title' => 'Actualizar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'view') {
                                    return \yii\helpers\Url::to(['view', 'id' => $key]);
                                }
                                //                        else if ($action === 'update') {
                                //                            return \yii\helpers\Url::to(['update', 'id' => $key]);
                                //                        }
                            }
                        ],
                        /** FIN BOTONES DE ACCION * */
                        'id',
                        'asunto',
                        'created_at',
                        'updated_at',
                        'estado',
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

                        // 'es_activo:boolean',
                    ],
                ]);
                ?>

            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>