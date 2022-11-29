<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = $model->asunto;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="mensajes-view" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-7">
                    <h6><?= Html::encode($this->title) ?></h6>

                </div>
                <div class="col-lg-4 col-md-4" style="text-align: right;">
                    <!-- menu derecha -->
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="fa fa-briefcase" aria-hidden="true"></i> Volver a notificaciones</span>',
                        ['index'],
                        ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu derecha -->
            </div><!-- FIN DE CABECERA -->


            <!-- inicia cuerpo de card -->
            <div class="row" style="margin:10px">

                <!-- inicia datos del mensaje -->
                <div class="col-lg-6 col-md-6">
                    <div class="row" style="border: solid 1px #eee; margin-right: 5px;">

                        <div class="col-lg-12 col-md-12">

                        <?php
                            if(count($enviados)==0){
                                ?>
                                <div class="row" style="border: solid 1px #eee; margin: 10px; padding-top: 5px;">
                                    <p>
                                        <?= Html::a(
                                            '<i class="fas fa-edit"> Actualizar</i>',
                                            ['update', 'id' => $model->id],
                                            ['class' => '', 'style' => 'color: #0a1f8f']
                                        ) ?>
                                        |
                                        <?=
                                        Html::a('<i class="fas fa-trash-alt"> Eliminar</i>', ['eliminar', 'id' => $model->id], [
                                            'class' => '',
                                            'style' => 'color: #ab0a3d',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this item?',
                                                'method' => 'post',
                                            ],
                                        ])
                                        ?>
                                    </p>

                                </div>

                        <?php
                            }
                        ?>                            

                            <div class="row" style="margin: 10px;">
                                <?=
                                DetailView::widget([
                                    'model' => $model,
                                    'attributes' => [
                                        'id',
                                        'remite_usuario',
                                        'created_at',
                                        'updated_at',
                                        'asunto'
                                    ],
                                ])
                                ?>
                            </div>

                            <div class="row" style="background-color: #eee; margin: 10px; padding-top: 10px;">
                                <p style="color: #0a1f8f;"><b>Detalle de la notificación: </b></p>
                                <?= $model->texto ?>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- fin datos del mensaje -->


                <!-- inicia datos para y archivos adjuntos -->
                <div class="col-lg-6 col-md-6">
                    <div class="row" style="border: solid 1px #eee; margin-left: 5px;">
                        <div class="col-lg-12 col-md-12">
                            <?=
                                $this->render('_message-para',[
                                    'model' => $model,
                                    'to' => $to,
                                    'toUsers' => $toUsers,
                                    'para' => $para,
                                    'totalEnviado' => $totalEnviado,
                                    'enviados' => $enviados
                                ]);
                            ?>
                        </div>
                    </div>
                </div>
                <!-- fin datos para y archivos adjuntos -->

            </div>
            <!-- fin cuerpo de card -->

        </div>
    </div>

</div>













</div>