<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

//echo'<pre>';
//print_r($areas);

$this->title = 'Justificar falta de: ';
?>

<div class="scholaris-asistencia-alumno-novedades-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/areas.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small><?= $model->grupo->alumno->first_name . ' ' . $model->grupo->alumno->middle_name . ' ' . $model->grupo->alumno->last_name ?></small>
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fas fa-clipboard-list"></i> Menù Principal</span>',
                            ['insp-fecha-periodo/index'],
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


            <!-- #################### inicia cuerpo de card ##########################-->
            <div class="row p-5">          
                <div class="col-lg-12 col-md-12">           
                    <div class="row">
                        <div class="col-lg-3 col-md-3 bg-segundo" style="border: solid 1px #eee">FECHA:</div>
                        <div class="col-lg-9 col-md-9 bg-sexto" style="border: solid 1px #eee">
                            <?= $model->asistenciaProfesor->fecha?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 bg-segundo" style="border: solid 1px #eee">MOTIVO:</div>
                        <div class="col-lg-9 col-md-9 bg-sexto">
                            <?= $model->comportamientoDetalle->nombre?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 bg-segundo" style="border: solid 1px #eee">OBSERVACIÓN:</div>
                        <div class="col-lg-9 col-md-9 bg-sexto" style="border: solid 1px #eee">
                            <?= $model->observacion?>
                        </div>
                    </div>
                    <hr />
                    <div class="row" style="margin-top: 20px">
                        <?=
                        $this->render('_form', [
                            'model' => $model,
                        ])
                        ?>
                    </div>
                </div>                
            </div><!-- ######################## fin cuerpo de card #######################-->


        </div><!-- fin de card principal -->
    </div>
</div>
