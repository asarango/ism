<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LmsActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insumos';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="lms-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?= $week['nombre_semana'].' - <b>desde:</b> '. $week['fecha_inicio'] .' <b>hasta:</b> '. $week['fecha_finaliza'] ?>
                    </small>
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
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    );
                    ?>
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ff9e18">
                                <i class="fa fa-briefcase" aria-hidden="true"></i> Planificación de unidad
                        </span>',
                        ['pud-dip/index1',
                        'plan_bloque_unidad_id' => $planBloqueUnidadId],
                        ['class' => 'link']
                    );
                    ?>
                    |



                </div> <!-- fin de menu cabecera izquierda -->

                <!-- inicio de menu cabecera derecha -->
                <div class="col-lg-6 col-md-6" style="text-align: right;">                                       
                    <?= Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Crear insumo</span>', 
                        ['create', 
                            'lms' => $lms->id,
                            'planBloqueUnidadId' => $planBloqueUnidadId,
                            'actionBack' => 'pud-dip/index1'
                        ]) ?>
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
                        'lms_id',
                        'tipo_actividad_id',
                        'titulo',
                        'descripcion:ntext',
                        //'tarea:ntext',
                        //'material_apoyo:ntext',
                        //'es_calificado:boolean',
                        //'es_publicado:boolean',
                        //'es_aprobado:boolean',
                        //'retroalimentacion:ntext',
                        //'created',
                        //'created_at',
                        //'updated',
                        //'updated_at',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>

                <!-- fin cuerpo de card -->
            </div>
        </div>