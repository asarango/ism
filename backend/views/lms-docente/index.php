<?php

use backend\models\helpers\HelperGeneral;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan Semanal - ' . $modelClase->ismAreaMateria->materia->nombre;


$helper = new HelperGeneral();

?>
<!--<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>-->
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

<link rel="stylesheet" href="estilo.css" />


<div class="lms-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/aula.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?=
                        $modelClase->paralelo->course->name .
                            ' ' . $modelClase->paralelo->name .
                            ' | ' . '<b>Semana:</b>' . $detalle[0]['semana_numero']
                        ?>
                    </small>
                </div>
            </div>
            <hr>

            <!--incia cuerpo-->
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <?php
                    foreach ($detalle as $det) {
                        $dia = $helper->get_dia_fecha($det['fecha']);
                        $seRealizo = $det['se_realizo'] ? 'SI' : 'NO';

                    ?>
                        <div class="card" style="margin-bottom: 30px;">
                            <div class="card-header" style="background: linear-gradient(#898b8d, #ab0a3d); color: white;">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <div class="col-lg-11 col-md-11">
                                        <p>
                                            <b><?= $dia . ' - ' . $det['fecha'] ?></b>
                                            <br>
                                            <?= $det['hora'] . ' Hora | ' . $det['titulo'] ?>
                                        </p>
                                    </div>
                                </div>
                                <!--fin de row de fecha y titulo-->
                            </div>
                            <!--FIN DE CLASS-HEADER-->

                            <!-- <div class="card-body my-scroll" style="height: 50vh;"> -->
                            <div class="card-body my-scroll" style="">

                                <div class="row" style="background-color: #eee;">
                                    <div class="col-lg-9 col-md-9" style="font-size: 10px;">
                                        <?php
                                        if ($det['es_aprobado']) {
                                        ?>
                                            <i class="fas fa-thumbs-up" style="color: green;"> ES APROBADO</i>
                                        <?php
                                        } else {
                                        ?>
                                            <i class="fas fa-thumbs-down" style="color: red;"> NO APROBADO</i>
                                        <?php
                                        }
                                        ?>
                                        |
                                        <?php
                                        if ($det['se_realizo']) {
                                        ?>
                                            <i class="fas fa-check" style="color: #0a1f8f;"> SE REALIZÓ</i>
                                        <?php
                                        } else {
                                        ?>
                                            <i class="fas fa-ban" style="color: red;"> NO SE REALIZÓ</i>
                                        <?php
                                        }
                                        ?>

                                        |

                                        <?=
                                        Html::a(
                                            '<i class="fas fa-tasks" style="color: #9e28b5;"> TOTAL INSUMOS: </i>' . $det['total_insumos'],
                                            [
                                                'scholaris-actividad/lista',
                                                'clase_id' => $clase_id,
                                                'semana_numero' => $semana_numero,
                                                'detalle_horario_id' => $det['detalle_id'],
                                                'lms_id' => $det['lms_id']
                                            ]
                                        )
                                        ?>

                                        |

                                        <a data-bs-toggle="collapse" href="#collapseExample<?= $det['lms_doc_id'] ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Ver más ...
                                        </a>

                                        |

                                        <?php
                                        if (count($nees) > 0) {
                                            echo Html::a(
                                                '<i class="fas fa-users-class" style="color: #ff9e18;"> ADAPTACIONES CURRICULARES: </i> ' . count($nees),
                                                [
                                                    'nee',
                                                    'clase_id' => $clase_id,
                                                    'semana_numero' => $semana_numero,
                                                    'nombre_semana' => $nombre_semana,
                                                    'lsm_docente_id' => $det['lms_doc_id'],
                                                    'lms_id' => $det['lms_id']
                                                ]
                                            );
                                        }
                                        ?>

                                        |


                                    </div> <!-- FIN DE COL DE ICONOS IZQUIERDA -->

                                    <div class="col-lg-3 col-md-3" style="font-size: 10px; text-align: right;">
                                        <!-- Button trigger modal motivo de no realizó la actividad de la hora-->
                                        <a class="zoom" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $det['lms_doc_id'] ?>">
                                            <i class="fas fa-ellipsis-h zoom" style="color: #65b2e8; font-size: 12px;"> </i>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="staticBackdrop<?= $det['lms_doc_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">¿Se realizó la actividad?</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" style="text-align: left;">
                                                        <?= Html::beginForm(['update'], 'post', ['enctype' => 'multipart/form-data']) ?>

                                                        <input type="hidden" name="lms_docente_id" value="<?= $det['lms_doc_id'] ?>">
                                                        <input type="hidden" name="nombre_semana" value="<?= $nombre_semana ?>">
                                                        <input type="hidden" name="semana_numero" value="<?= $semana_numero ?>">
                                                        <input type="hidden" name="clase_id" value="<?= $clase_id ?>">



                                                        <div class="form-group">
                                                            <label for="serealizo" class="form-label">Se realizó</label>
                                                            <select name="se_realizo" class="form-control">
                                                                <option value="<?= $det['se_realizo'] ?>"><?= $seRealizo ?></option>
                                                                <option value="1">SI</option>
                                                                <option value="0">NO</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group" style="margin-top: 15px;">
                                                            <label for="motivo" class="form-label">¿Por qué no se realizó?</label>
                                                            <textarea name="motivo_no_realizado" class="form-control"><?= $det['motivo_no_realizado'] ?></textarea>
                                                        </div>

                                                        <div class="form-group" style="margin-top: 15px;">
                                                            <label for="justificativo" class="form-label">¿Cómo recuperará?</label>
                                                            <textarea name="justificativo" class="form-control"><?= $det['justificativo'] ?></textarea>
                                                        </div>                                                        

                                                        <hr>
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                                                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-outline-primary']) ?>
                                                        <?= Html::endForm() ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- para observaciones -->
                                <div class="row" style="background-color: #eee;">
                                <div class="col-lg-9 col-md-9" style="font-size: 10px; text-align: left;">
                                        <!-- Button trigger modal motivo de no realizó la actividad de la hora-->
                                        <a class="zoom" data-bs-toggle="modal" data-bs-target="#observations<?= $det['lms_doc_id'] ?>">
                                            <i class="fas fa-binoculars zoom" style="color: #0a1f8f; font-size: 12px;"> OBSERVACIONES</i>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="observations<?= $det['lms_doc_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Observaciones:</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" style="text-align: left;">
                                                        <?= Html::beginForm(['update'], 'post', ['enctype' => 'multipart/form-data']) ?>

                                                        <input type="hidden" name="lms_docente_id" value="<?= $det['lms_doc_id'] ?>">
                                                        <input type="hidden" name="nombre_semana" value="<?= $nombre_semana ?>">
                                                        <input type="hidden" name="semana_numero" value="<?= $semana_numero ?>">
                                                        <input type="hidden" name="clase_id" value="<?= $clase_id ?>">
                                                        <input type="hidden" name="se_realizo" value="<?= $det['se_realizo'] ?>">
                                                        <input type="hidden" name="motivo_no_realizado" value="<?= $det['motivo_no_realizado'] ?>">
                                                        <input type="hidden" name="justificativo" value="<?= $det['justificativo'] ?>">



                                                        <!-- <div class="form-group">
                                                            <label for="serealizo" class="form-label">Se realizó</label>
                                                            <select name="se_realizo" class="form-control">
                                                                <option value="<?= $det['se_realizo'] ?>"><?= $seRealizo ?></option>
                                                                <option value="1">SI</option>
                                                                <option value="0">NO</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group" style="margin-top: 15px;">
                                                            <label for="motivo" class="form-label">¿Por qué no se realizó?</label>
                                                            <textarea name="motivo_no_realizado" class="form-control"><?= $det['motivo_no_realizado'] ?></textarea>
                                                        </div>

                                                        <div class="form-group" style="margin-top: 15px;">
                                                            <label for="justificativo" class="form-label">¿Cómo recuperará?</label>
                                                            <textarea name="justificativo" class="form-control"><?= $det['justificativo'] ?></textarea>
                                                        </div>        -->
                                                        
                                                        <div class="form-group" style="margin-top: 15px;">
                                                            <label for="observaciones" class="form-label">Observaciones:</label>
                                                            <textarea name="observaciones" class="form-control"><?= $det['observaciones'] ?></textarea>
                                                        </div>

                                                        <hr>
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                                                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-outline-primary']) ?>
                                                        <?= Html::endForm() ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--fin de row de iconos-->
                                <hr>

                                <div class="collapse" id="collapseExample<?= $det['lms_doc_id'] ?>">
                                    <div class="card card-body">

                                        <div class="row" style="padding-left: 10%;">
                                            <div class="col-lg-12 col-md-12">
                                                <?= $det['conceptos'] ?>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row" style="padding-left: 10%;">
                                            <b>ESTRATEGIA METODOLÓGICA:</b>
                                            <div class="col-lg-12 col-md-12">
                                                <?= $det['indicaciones'] ?>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row" style="padding-left: 10%;">
                                            <b>DESCRIPCIÓN DE ACTIVIDADES:</b>
                                            <div class="col-lg-12 col-md-12">
                                                <?= $det['descripcion_actividades'] ?>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row" style="padding-left: 10%;">
                                            <b>TAREA:</b>
                                            <div class="col-lg-12 col-md-12">
                                                <?= $det['tarea'] ?>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row" style="padding-left: 10%;">
                                            <b>RECURSOS:</b>
                                            <div class="col-lg-12 col-md-12">
                                                <?= $det['recursos'] ?>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer" style="background: linear-gradient(white, #898b8d); color: black;">

                            </div>
                        </div>
                        <hr>
                    <?php
                    }
                    ?>

                </div>
            </div>
            <!--fin de cuerpo-->

        </div>
    </div>
</div>