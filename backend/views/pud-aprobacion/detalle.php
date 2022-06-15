<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aprobaciondes de planificaciones de unidad';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Jquery AJAX -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>

<div class="pud-aprobacion-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12 col-sm-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-10">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small><?= $dataMateria['curso'] . ' | ' . $dataMateria['materia'] . ' | ' . $dataMateria['last_name'] ?></small>
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

                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Aprobaciones</span>',
                            ['planificacion-aprobacion/index'],
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

            <!-- inicia cuerpo de card -->

            <div class="row" style="margin-top: 20px">
                <div class="col-lg-8 col-md-8">                    
                    <iframe width="100%"
                            height="600"
                            src="<?= Url::toRoute(['pud-dip/pdf-pud-dip', 'planificacion_unidad_bloque_id' => $dataMateria['id']]) ?>">
                    </iframe>
                </div>


                <div class="col-lg-4 col-md-4">

                    <?php                    
                    $form = ActiveForm::begin([
                                'action' => Url::to(['detalle', 'unidad_id' => $dataMateria['id']]),
                                'method' => 'post'
                    ]);
                    ?>

                    <!--CKEDITOR-->
                    <!--EDITOR DE TEXTO KARTIK-->
                    <textarea name="" id="editor">
                        <?= $cabecera->revision_coordinacion_observaciones ?>
                    </textarea>
                    <script>
                        CKEDITOR.replace('editor', {
                            customConfig: '/ckeditor_settings/config.js'
                        })
                    </script>


                    <?php
                    if ($cabecera->estado == 'APROBADO') {
                        ?>
                        <div class="alert alert-success" role="alert" style="text-align:center" >
                            ¡Usted aprobó Planificaciones <i class="fas fa-thumbs-up"></i>! 
                        </div>
                        <?php
                    } elseif ($cabecera->estado == 'EN_COORDINACION') {
                        ?>
                        <br>
                        <div class="row" style="text-align: center; padding-left: 30px;padding-right: 30px;">
                            <?=
                            Html::submitButton('Devolver Planificación',
                                    [
                                        'class' => 'btn btn-danger my-text-medium'
                                    ])
                            ?>
                            <hr>
                            <i class="far fa-hand-point-down" style="font-size: 20px;color: #0a1f8f"></i> 
                            <?=
                            Html::a(
                                    '<i class="fas fa-check-circle"> Aprobar Planificación</i>',
                                    ['aprobacion', 'cabecera_id' => $cabecera->id],
                                    ['class' => 'btn btn-success my-text-medium']
                            );
                            ?> 
                        </div>
                        <?php
                    } elseif ($cabecera->estado == 'DEVUELTO') {
                        ?>
                        <div class="alert alert-warning" role="alert" style="text-align:center" >
                            ¡Se ha enviado a modificar al profesor!
                        </div>
                        <?php
                    } elseif ($cabecera->estado == 'INICIANDO') {
                        ?>
                        <div class="alert alert-info" role="alert" style="text-align:center" >
                            ¡El profesor está iniciando su planificación!
                        </div>
                        <?php
                    }
                    ?>

                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>


        <!-- fin cuerpo de card -->
    </div>
</div>

</div>



