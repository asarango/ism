<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aprobaciondes de planificaciones de unidad';
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->identity->usuario;
$hoy = date('Y-m-d H:i:s');

// echo "<pre>";
// print_r($seccionCode);
// die();

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
                <div class="col-lg-6">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                    <small>
                        <?= $dataMateria['curso'] . ' | ' . $dataMateria['materia'] . ' | ' . $dataMateria['last_name'] ?>
                    </small>
                </div>

                <div class="col-lg-5 col-md-5" style="text-align: right;">
                    <img src="../imagenes/iso/iso.png" class="img-thumbnail" width="50px" /> <b>
                        <?= $docIso ?>
                    </b>
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
                    <?php
                    if ($seccionCode == 'DIPL') {
                    ?>
                        <iframe width="100%" height="600" src="<?= Url::toRoute(['pud-dip/pdf-pud-dip', 'planificacion_unidad_bloque_id' => $dataMateria['id']]) ?>">
                        </iframe>
                    <?php
                    } elseif ($seccionCode == 'PAI') {
                    ?>
                        <iframe width="100%" height="600" src="<?= Url::toRoute(['pud-pai/genera-pdf', 'planificacion_unidad_bloque_id' => $dataMateria['id']]) ?>">
                        </iframe>
                    <?php
                    } elseif ($seccionCode == 'BAS') {
                    ?>
                        <iframe width="100%" height="600" src="<?= Url::toRoute(['pud-pep/genera-pdf', 'planificacion_unidad_bloque_id' => $dataMateria['id']]) ?>">
                        </iframe>
                    <?php
                    }
                    ?>

                </div>


                <div class="col-lg-4 col-md-4">

                    <div class="row">
                        <p>
                            El avance de la planificación es del
                            <b style="border:solid 1px #ccc; border-radius: 50%; padding: 10px">
                                <?= $dataMateria['avance_porcentaje'] ?>%
                            </b>
                        </p>
                    </div>

                    <div class="row">
                        <?php
                        if ($modelBitacora) {
                            if ($modelBitacora['estado_jefe_coordinador'] == 'ENVIADO') {
                                echo '<div class="alert alert-primary" role="alert" style="text-align:center">';
                                echo '<i class="fas fa-envelope"></i> ' . $modelBitacora['estado_jefe_coordinador'] . '<hr>';
                                echo $modelBitacora['notificacion'];
                                echo '</div>';
                            } elseif ($modelBitacora['estado_jefe_coordinador'] == 'APROBADO') {
                                echo '<div class="alert alert-success" role="alert" style="text-align:center">';
                                echo '<i class="fas fa-envelope"></i> ' . $modelBitacora['estado_jefe_coordinador'] . '<hr>';
                                echo $modelBitacora['notificacion'];
                                echo '</div>';
                            } elseif ($modelBitacora['estado_jefe_coordinador'] == 'DEVUELTO') {
                                echo '<div class="alert alert-warning" role="alert" style="text-align:center">';
                                echo '<i class="fas fa-envelope"></i> ' . $modelBitacora['estado_jefe_coordinador'] . '<hr>';
                                echo $modelBitacora['respuesta'];
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger" role="alert" style="text-align:center">';
                            echo '<i class="fas fa-envelope"></i> No presenta<hr>';
                            echo 'El grupo de docentes de esta asignatura todavía no envía la planificación';
                            echo '</div>';
                        }
                        ?>
                    </div>

                    <?php if ($modelBitacora) { ?>
                        <?php if ($modelBitacora['estado_jefe_coordinador'] == 'ENVIADO') { ?>
                            <?php
                            $form = ActiveForm::begin([
                                'action' => Url::to(['devuelve-aprueba', 'bitacora_id' => $modelBitacora['id']]),
                                'method' => 'post'
                            ]);
                            ?>

                            <!--CKEDITOR-->
                            <!--EDITOR DE TEXTO KARTIK-->
                            <textarea name="devolucion" id="editor">

                                    </textarea>
                            <script>
                                CKEDITOR.replace('editor', {
                                    customConfig: '/ckeditor_settings/config.js'
                                })
                            </script>

                            <div style="margin-top: 10px;">

                                <input type="radio" id="huey" name="estado" value="DEVUELTO" checked>
                                <label for="huey">DEVOLVER</label>

                                <input type="radio" id="dewey" name="estado" value="APROBADO">
                                <label for="dewey">APROBAR</label>

                            </div>

                            <input type="hidden" name="id" value="<?= $modelBitacora['id'] ?>">
                            <br />
                            <br />

                            <?= Html::submitButton('Registrar', ['class' => 'btn btn-outline-primary btn-block']) ?>

                </div>

                <?php ActiveForm::end(); ?>
            <?php } ?>
        <?php } ?>

            </div>
            <!--fin de retroalimentacion-->

            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8 text-center">
                    <b><u>BITÁCORA</u></b>
                    <div class="table table-responsive">
                        <table class="table table-condensed">

                            <thead>
                                <tr>
                                    <th>NOTIFICADO</th>
                                    <th>DOCENTE</th>
                                    <th>DOCENTE-NOTA</th>
                                    <th>RESPONDIDO</th>
                                    <th>JEFE/COORDINADOR</th>
                                    <th>NOTA</th>
                                    <th>ESTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($bitacora as $bita) {
                                    echo '<tr>';
                                    echo '<td>' . $bita->fecha_notifica . '</td>';
                                    echo '<td>' . $bita->usuario_notifica . '</td>';
                                    echo '<td>' . $bita->notificacion . '</td>';

                                    echo '<td>' . $bita->fecha_responde . '</td>';
                                    echo '<td>' . $bita->usuario_responde . '</td>';
                                    echo '<td>' . $bita->respuesta . '</td>';
                                    echo '<td>' . $bita->estado_jefe_coordinador . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2"></div>
            </div>
        </div>


        <!-- fin cuerpo de card -->
    </div>
</div>