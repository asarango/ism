<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueActividad */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Parciales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-bloque-actividad-view">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
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
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #65b2e8">
                            <i class="fas fa-home"></i> Bloques
                        </span>',
                        ['index'],
                        ['class' => 'link']
                    );
                    ?>

                    |
                    <!-- <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                            <i class="far fa-plus-square" aria-hidden="true"></i> Crear notificación
                        </span>',
                                ['create'],
                                ['class' => 'link']
                            );
                            ?>

                    | -->
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 10px; margin-left: 60px">

                <div class="col-lg-6 col-md-6" style="background-color: #eee;padding: 10px; margin-bottom: 10px;">
                    <p>
                        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
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
                            'name',
                            'create_uid',
                            'create_date',
                            'write_uid',
                            'write_date',
                            'quimestre',
                            'tipo',
                            'desde',
                            'hasta',
                            'orden',
                            'scholaris_periodo_codigo',
                            'tipo_bloque',
                            'dias_laborados',
                            'estado',
                            'abreviatura',
                            'tipo_uso',
                            'bloque_inicia',
                            'bloque_finaliza',
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-6 col-md-6">
                    <p><b>Deatalle de semanas</b></p>

                    <div class="table table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Semana</th>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($modelSemanas as $semana) {
                                ?>
                                    <tr>
                                        <td><?= $semana->id ?></td>
                                        <td><?= $semana->semana_numero ?></td>
                                        <td><?= $semana->fecha_inicio ?></td>
                                        <td><?= $semana->fecha_finaliza ?></td>
                                        <td>Prox.</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <?= Html::a('<i class="fas fa-cogs"></i> Procesar semanas',['process-weeks',
                                'bloque_id' => $model->id
                            ],
                            ['class' => 'btn btn-outline-primary']) 
                    ?>
                </div>

            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>
</div>