<?php

use backend\models\OpStudent;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Justificar: ' . $model->student->last_name . ' ' . $model->student->first_name;


?>
<!--Scripts para que funcionen AJAX'S-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>


<div class="scholaris-faltas-justificar">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-4 col-md-4">
                    <b><?= Html::encode($this->title) ?></b>
                    <small>
                        <?=
                        '<b>Fecha de falta: </b>' . $model->fecha
                        ?>
                    </small>
                </div>

                <div class="col-lg-7 col-md-7" style="text-align: right;">
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5">
                            <i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    );
                    ?>
                    |

                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ff9e18">
                            <i class="fas fa-user-times"></i> Faltas</span>',
                        ['index'],
                        ['class' => 'link']
                    );
                    ?>
                    |
                </div>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- inicia cuerpo de card -->

            <?php

            $diferencia_en_segundos = strtotime(date('Y-m-d')) - strtotime($model->fecha);
            $diferencia_en_dias = round($diferencia_en_segundos / 86400);

            if ($diferencia_en_dias <= $dias) {
            ?>
                <div class="alert alert-success" role="alert">
                    ¡Es buen tiempo para justificar la falta! <?= "$diferencia_en_dias días." ?>
                </div>
            <?php
            } else {
            ?>
                <div class="alert alert-danger" role="alert">
                    ¡La justificación esta fuera de tiempo! <?= "$diferencia_en_dias días." ?>;
                </div>
            <?php
            }

            ?>

            <div class="row" style="margin-bottom: 10px;">
                <div class="col-lg-6 col-md-6 text-center">

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card" style="margin-bottom: 10px">
                                <div class="card-header"><b>Solicitud de justificación</b></div>

                                <div class="card-body">
                                    <p>
                                        <b>Fecha de solicitud de justificación:</b>
                                        <?= $model->fecha_solicitud_justificacion ?>
                                    </p>

                                    <p>
                                        <b>Solicitud de justificación:</b>
                                        <br>
                                        <?= $model->motivo_justificacion ?>
                                        
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 text-center">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-header"><b>Justificación ISM</b></div>
                                <div class="card-body">
                                    <?= Html::beginForm(['justificar'], 'get', ['enctype' => 'multipart/form-data']) ?>
                                        <input type="hidden" value="<?= $model->id ?>" name="id">

                                        <b>Fecha de justificación: </b><?= $model->fecha_justificacion ?>
                                        
                                        <textarea name="justificacion" id="editor"><?= $model->respuesta_justificacion ?></textarea>

                                        <br>

                                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-outline-success']) ?>
                                    <?= Html::endForm() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>

    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>