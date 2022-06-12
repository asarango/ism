<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

//echo'<pre>';
//print_r($areas);

$this->title = 'Mallas';
?>

<div class="ism-area-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/areas.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div><!-- FIN DE CABECERA -->

            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                   
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <div class="row">
                <!-- #################### inicia cuerpo de card ##########################-->

                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'id',
//                        'malla_id',
                        [
                            'attribute' => 'malla_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->malla->nombre;
                            },
                            'filter' => $listaMalla,
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'prompt' => 'Seleccione asignatura...'
                           ],
                        ],
                        'scholaris_periodo_id',
                        /** INICIO BOTONES DE ACCION * */
                        [
                            'class' => 'yii\grid\ActionColumn',
                            //                    'width' => '150px',
                            'template' => '{detalle}',
                            'buttons' => [
                                'detalle' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-calendar-day"></i>', $url, [
                                        'title' => 'Actualizar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'detalle') {
                                    return \yii\helpers\Url::to(['/ism-malla-area/index1', 'periodo_id' => $key]);
                                }
                            }
                        ],
                    /** FIN BOTONES DE ACCION * */
                    ],
                ]);
                ?>


            </div><!-- ######################## fin cuerpo de card #######################-->


        </div><!-- fin de card principal -->
    </div>
</div>

