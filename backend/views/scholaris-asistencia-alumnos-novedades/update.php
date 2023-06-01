<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

//echo'<pre>';
//print_r($areas);

$this->title = 'Justificar Falta: ';
?>

<div class="scholaris-asistencia-alumno-novedades-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/areas.png" width="64px" style="" class="img-thumbnail">
                    </h4>
                </div>
                <div class="col-lg-8">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>
                    <font size=3>
                        <?= $model->grupo->alumno->first_name . ' ' . $model->grupo->alumno->middle_name . ' ' . $model->grupo->alumno->last_name ?>
                    </font>
                    <!-- fin de menu izquierda -->
                </div>
                <div class="col-lg-3 col-md-3" style="text-align: right; margin-top: -40px;">
                    <!-- menu izquierda -->
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fas fa-clipboard-list"></i> Menú Principal</span>',
                            ['insp-fecha-periodo/index'],
                            ['class' => 'link']
                        );
                    ?>
                </div>
                <hr>
            </div><!-- FIN DE CABECERA -->
            <!-- #################### inicia cuerpo de card ##########################-->
            <div class="row p-5" style="margin-top: -60px;margin-left:20px; margin-bottom: -50px;">
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 bg-segundo" style="border: solid 1px #eee">FECHA:</div>
                        <div class="col-lg-9 col-md-9 bg-sexto" style="border: solid 1px #eee">
                            <?= $model->asistenciaProfesor->fecha ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 bg-segundo" style="border: solid 1px #eee">MOTIVO:</div>
                        <div class="col-lg-9 col-md-9 bg-sexto" style="border: solid 1px #eee">
                            <?= $model->comportamientoDetalle->nombre ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 bg-segundo" style="border: solid 1px #eee">OBSERVACIÓN:</div>
                        <div class="col-lg-9 col-md-9 bg-sexto" style="border: solid 1px #eee">
                            <?= $model->observacion ?>
                        </div>
                    </div>

                </div>
            </div>
            <hr>
            <div class="row p-5" style="margin-top: -60px;margin-left:20px;">
                <div class="row" style="margin-top: 10px">

                    <div class="col-lg-6 col-md-6">
                        <div class="card mb-3" style="max-width: 540px;">
                            <div class="row g-0">
                                <div class="col-md-6"
                                    style="padding: 20px; background-color: #ff9e18; color: white;text-align:center;">
                                    <?= $docente->login ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <h5 class="card-title">Solicitud de justificación</h5>
                                        <p class="card-text">
                                            <?= $model->solicitud_representante_motivo ?>
                                        </p>
                                        <p class="card-text"><small class="text-muted">
                                                <?= $model->solicitud_representante_fecha ?>
                                            </small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <?=
                            $this->render('_form', [
                                'model' => $model,
                            ])
                            ?>
                    </div>

                </div>
            </div>
        </div><!-- fin de card principal -->
    </div>
</div>