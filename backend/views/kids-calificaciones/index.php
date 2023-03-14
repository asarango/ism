<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KidsDestrezaTareaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kids Destreza Tareas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kids-destreza-tarea-index">
    <div class="" style="padding-left: 40px; padding-right: 40px">
        <div class="m-0 vh-50 row justify-content-center align-items-center">
            <div class="card shadow col-lg-10 col-md-10">

                <!-- comienza encabezado -->
                <div class="row" style="background-color: #ccc; font-size: 12px">
                    <div class="col-md-12 col-sm-12">
                        <p style="color:white">
                            |                                
                            <?=
                            Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                    ['site/index'], ['class' => 'link']);
                            ?>                
                            |
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificaciones</span>',
                                    [
                                        'kids-menu/index1'
                                    ]
                            );
                            ?>    
                            |
                        
                        </p>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <h5 style="color:white"><?= Html::encode($this->title) ?></h5>
                    </div>
                </div>
                    <!-- Fin de encabezado -->

                    <!-- Comienza cuerpo -->
                    <div class="row" style="margin-top:10px">
                        <div class="col-md-12 col-sm-12">
                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'tarea_id',
                                'curso',
                                'paralelo',
                                'materia',
                                'fecha_presentacion',
                                'titulo',

                                /** INICIO BOTONES DE ACCION * */
                        [
                            'class' => 'yii\grid\ActionColumn',
//                    'width' => '150px',
                            'template' => '{calificar}',
                            'buttons' => [
                                'calificar' => function ($url, $model) {
                                    return Html::a('<img src="../ISM/main/images/kids/calificaciones.png">', $url, [
                                        'title' => 'Calificar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'calificar') {
                                    return \yii\helpers\Url::to(['calificar', 'tarea_id' => $model->tarea_id]);
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
                    </div>
                    <!-- Fin cuerpo -->
            </div>
        </div>
    </div>


    
</div>
