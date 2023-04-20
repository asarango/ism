<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

//echo'<pre>';
//print_r($areas);

$this->title = 'Mis novedades en la clase';
?>

<div class="scholaris-asistencia-alumno-novedades-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/areas.png" width="64px" style="" class="img-thumbnail"></h4>
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

                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    
                    
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->


            <!-- #################### inicia cuerpo de card ##########################-->
            <div class="row p-5">
                
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        
                    //    'id',
                       'fecha',
                       'nombre',
                       'docente',
                       'curso',
                       'paralelo',
                       'materia',
                       'estudiante',
                       'codigo',
                       'observacion:ntext',
                       'es_justificado',
                       'solicitud_representante_fecha',
                        //'acuerdo_justificacion:ntext',
                        
                        /** INICIO BOTONES DE ACCION * */
                        [
                            'class' => 'yii\grid\ActionColumn',
                            //                    'width' => '150px',
                            'template' => '{justificar}',
                            'buttons' => [
                                'justificar' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-hands-helping" style="color: green"></i>', $url, [
                                        'title' => 'Justificar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                },
                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'justificar') {
                                    return \yii\helpers\Url::to(['justificar', 'id' => $model->id]);
                                } 
//                                else if($action === 'mapa') {
//                                    return \yii\helpers\Url::to(['materias-pai/mapa-enfoques', 'materia_id' => $key]);
//                                }
                            }
                        ],
                    /** FIN BOTONES DE ACCION * */
                    ],
                ]);
                ?>
                    </div>
                </div>                

            </div><!-- ######################## fin cuerpo de card #######################-->


        </div><!-- fin de card principal -->
    </div>
</div>

