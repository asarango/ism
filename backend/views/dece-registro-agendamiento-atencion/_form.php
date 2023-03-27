<?php

use backend\models\DeceRegistroAgendamientoAtencion;
use backend\models\DeceRegistroSeguimiento;
use backend\models\DeceRegistroSeguimientoSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\OpStudent;
use backend\models\OpParent;
use backend\models\PlanificacionOpciones;
use backend\models\ResPartner;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroAgendamientoAtencion */
/* @var $form yii\widgets\ActiveForm */

//estado 
$arrayEstado = array(
    'PENDIENTE' => 'PENDIENTE',
    'FINALIZADO' => 'FINALIZADO',
    'NO ASISTIO' => 'NO ASISTIO'
);
$modelRegSeguimiento = DeceRegistroSeguimiento::findOne($idRegSeguimiento);
$modelEstudiante = OpStudent::findOne($modelRegSeguimiento->id_estudiante);
$representante = OpParent::findOne($modelEstudiante->x_representante);
$modelRepresentante = ResPartner::findOne($representante->name);
$modelAtenciones = DeceRegistroAgendamientoAtencion::find()
    ->where(['id_reg_seguimiento' => $modelRegSeguimiento->id])
    ->all();

$modelPathArchivo = PlanificacionOpciones::find()
    ->where(['tipo' => 'VER_ARCHIVO'])
    ->andWhere(['categoria' => 'PATH_DECE_SEG'])
    ->one();

    echo '<pre>';
    print_r($modelEstudiante);
    die();
?>


<!-- JS y CSS Ckeditor -->
<!-- <script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script> -->
<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>

<div class="comportamiento-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-11 col-md-11">
            <div class="row p-4 ">
                <div class="card col-lg-5 col-ms-5">
                    <!-- RENDERIZA A LA VISTA datos_estudiante.php -->
                    <h3 style="color:blueviolet"><b>Datos Estudiante</b></h3>
                    <table class="table table-responsive">
                        <tr>
                            <td><b>Alumno: </b></td>
                            <td><?= $modelEstudiante->first_name . ' ' . $modelEstudiante->middle_name . ' ' . $modelEstudiante->last_name ?></td>
                        </tr>
                        <tr>
                            <td><b>Fecha Nacimiento: </b></td>
                            <td><?= substr($modelEstudiante->birth_date, 0, 10) ?></td>
                        </tr>
                        <tr>
                            <td><b>Cédula: </b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>Representante: </b></td>
                            <td><?= $modelRepresentante->name ?></td>
                        </tr>
                        <tr>
                            <td><b>Carnét Discapacidad: </b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>Grupo Sanguineo: </b></td>
                            <td><?= $modelEstudiante->blood_group ?></td>
                        </tr>
                    </table>
                    <hr>
                    <h5 style="color:red"><b>Ficha Seguimiento</b></h5>
                    <table class="table table-success table-hover">
                        <tr classe="table-primary">
                            <td><b>Número: </b></td>
                            <td><?= $modelRegSeguimiento->id ?></td>
                        </tr>
                        <tr>
                            <td><b>Fecha: </b></td>
                            <td><?= substr($modelRegSeguimiento->fecha_inicio, 0, 10) ?></td>
                        </tr>
                        <tr>
                            <td><b>Estado: </b></td>
                            <td><?= $modelRegSeguimiento->estado ?></td>
                        </tr>
                        <tr>
                            <td><b>Motivo: </b></td>
                            <td><?= $modelRegSeguimiento->motivo ?></td>
                        </tr>
                        <tr>
                            <td><b>Submotivo: </b></td>
                            <td><?= $modelRegSeguimiento->submotivo ?></td>
                        </tr>
                        <tr>
                            <td><b>Solicitante: </b></td>
                            <td><?= $modelRegSeguimiento->persona_solicitante ?></td>
                        </tr>
                        <tr>
                            <td><b>Atendido Por: </b></td>
                            <td><?= $modelRegSeguimiento->atendido_por ?></td>
                        </tr>
                        <tr>
                            <td><b>Atención Para: </b></td>
                            <td><?= $modelRegSeguimiento->atencion_para ?></td>
                        </tr>
                        <tr>
                            <td><b>Responsable Seguimiento: </b></td>
                            <td><?= $modelRegSeguimiento->responsable_seguimiento ?></td>
                        </tr>
                    </table>
                    <hr>
                    <h5 style="color:red"><b>Atenciones Registradas</b></h5>
                    <?php
                    if ($modelAtenciones) {
                    ?>
                        <table class="table table-success table-striped">
                            <tr class="table-primary">
                                <td><b>ID</b></td>
                                <td><b>Fecha</b></td>
                                <td><b>Estado</b></td>
                                <td><b>Ver</b></td>
                            </tr>
                            <?php foreach ($modelAtenciones as $atencion) {

                            ?>
                                <tr>
                                    <td><?= $atencion->id ?></td>
                                    <td><?= substr($atencion->fecha_inicio, 0, 10) ?></td>
                                    <td><?= $atencion->estado ?></td>
                                    <td>
                                        <!-- boton llama modal -->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="<?php echo "#staticBackdrop$atencion->id"; ?>">
                                            <i class="fas fa-glasses"></i>
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="<?php echo "staticBackdrop$atencion->id"; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel"><b>Registro Atención: <?= $atencion->id ?></b></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped table-hover">
                                                            <tr>
                                                                <td><b>Fecha: </b></td>
                                                                <td><?= substr($atencion->fecha_inicio, 0, 10) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Estado: </b></td>
                                                                <td style="color:red"><b><?= $atencion->estado ?></b></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Pronunciamiento: </b></td>
                                                                <td><?= $atencion->pronunciamiento ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Acuerdo y Compromiso: </b></td>
                                                                <td><?= $atencion->acuerdo_y_compromiso ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Evidencia: </b></td>
                                                                <td><?= $atencion->evidencia ?></td>
                                                            </tr>
                                                            <tr>
                                                                <?php $arrayArchivo = array("", "");
                                                                if (strlen($atencion->path_archivo) > 0) {
                                                                    $arrayArchivo = explode("##", $atencion->path_archivo);
                                                                }

                                                                ?>
                                                                <td><b>Archivo: </b></td>
                                                                <td><a target="_blank" href="<?= $modelPathArchivo->opcion . $atencion->id_reg_seguimiento . '/' . $arrayArchivo[1] ?>">
                                                                        <?= $arrayArchivo[1] ?>
                                                                    </a></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } //fin foreach
                            ?>
                        </table>

                    <?php
                    } //if                         
                    ?>

                </div>
                <div class="card col-lg-7 col-ms-7">
                    <h3 style="color:blueviolet"><b>Agendamiento de Atención</b></h3>
                    <div class="dece-registro-agendamiento-atencion-form">

                        <?php $form = ActiveForm::begin(); ?>

                        <?= $form->field($model, 'id_reg_seguimiento')->hiddenInput(['value' => $idRegSeguimiento])->label(false) ?>

                        <?= $form->field($model, 'fecha_inicio')->textInput(['type' => 'date']) ?>

                        <!-- <?= $form->field($model, 'fecha_fin')->textInput() ?> -->

                        <?= $form->field($model, 'estado')->dropDownList($arrayEstado, ['prompt' => 'Seleccione Opción']) ?>


                        <div>
                            <?= $form->field($model, 'pronunciamiento')->textarea(['rows' => 3]) ?>
                            <script>
                                CKEDITOR.replace('deceregistroagendamientoatencion-pronunciamiento');
                            </script>
                        </div>
                        <div>
                            <?= $form->field($model, 'acuerdo_y_compromiso')->textarea(['rows' => 3]) ?>
                            <script>
                                CKEDITOR.replace("deceregistroagendamientoatencion-acuerdo_y_compromiso");
                            </script>
                        </div>
                        <div>
                            <?= $form->field($model, 'evidencia')->textarea(['rows' => 3]) ?>
                            <script>
                                CKEDITOR.replace("deceregistroagendamientoatencion-evidencia");
                            </script>
                        </div>
                        <table class="table table-striped table-hover table-responsive">
                            <tr>
                                <td>
                                    <?= $form->field($model, 'path_archivo')->fileInput(['maxlength' => true]) ?>
                                </td>
                            </tr>
                        </table>
                        <br>


                        <div class="form-group">
                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
                <!--div col -->
            </div>
            <!--div row -->
        </div>
    </div>
</div>