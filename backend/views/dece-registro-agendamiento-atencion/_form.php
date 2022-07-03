<?php

use backend\models\DeceRegistroAgendamientoAtencion;
use backend\models\DeceRegistroSeguimiento;
use backend\models\DeceRegistroSeguimientoSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\OpStudent;
use backend\models\OpParent;
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
$modelRegSeguimiento=DeceRegistroSeguimiento::findOne($idRegSeguimiento);
$modelEstudiante = OpStudent::findOne($modelRegSeguimiento->id_estudiante);
$representante = OpParent::findOne($modelEstudiante->x_representante);
$modelRepresentante = ResPartner::findOne($representante->name);
$modelAtenciones = DeceRegistroAgendamientoAtencion::find()
->where(['id_reg_seguimiento'=>$modelRegSeguimiento->id])
->all();
?>
<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
<div class="comportamiento-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-9 col-md-9">
            <div class="row p-4 ">
                <div class="card col-lg-4 col-ms-4">
                    <!-- RENDERIZA A LA VISTA datos_estudiante.php -->
                    <h3 style="color:blueviolet"><b>Datos Estudiante</b></h3>
                    <table class="table table-responsive">
                        <tr>
                            <td><b>Alumno: </b></td>
                            <td><?= $modelEstudiante->first_name . ' ' . $modelEstudiante->middle_name . ' ' . $modelEstudiante->last_name ?></td>
                        </tr>
                        <tr>
                            <td><b>Fecha Nacimiento: </b></td>
                            <td><?= $modelEstudiante->birth_date ?></td>
                        </tr>
                        <tr>
                            <td><b>Cédula: </b></td>
                            <td><?= $modelEstudiante->birth_date ?></td>
                        </tr>
                        <tr>
                            <td><b>Representante: </b></td>
                            <td><?= $modelRepresentante->name ?></td>
                        </tr>
                        <tr>
                            <td><b>Carnét Discapacidad: </b></td>
                            <td><?= $modelEstudiante->blood_group ?></td>
                        </tr>
                        <tr>
                            <td><b>Grupo Sanguineo: </b></td>
                            <td><?= $modelEstudiante->blood_group ?></td>
                        </tr>
                    </table>
                    <hr>
                    <h3 style="color:blue"><b>Datos Seguimiento</b></h3>
                    <table class="table-responsive">
                         <tr>
                            <td><b>Número Registro: </b></td>
                            <td><?= $modelRegSeguimiento->id ?></td>
                        </tr>
                        <tr>
                            <td><b>Fecha: </b></td>
                            <td><?= $modelRegSeguimiento->fecha_inicio ?></td>
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
                            <td><b>Persona Solicitante: </b></td>
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
                     if($modelAtenciones){                                                
                    ?>                   
                    <table class="table">
                        <tr>
                            <td><b>Num. Atención</b></td>
                            <td><b>Fecha</b></td>
                            <td><b>Estado</b></td>
                            <td><b>Ver</b></td>
                        </tr>
                        <?php  foreach($modelAtenciones as $atencion) { ?>
                        <tr>
                            <td><?= $atencion->id ?></td>
                            <td><?= $atencion->fecha_inicio ?></td>
                            <td><?= $atencion->estado ?></td>
                            <td><i class="fas ok">click aqui</i></td>
                        </tr>
                        <?php }//fin foreach?>
                    </table>

                    <?php                        
                      }//if                         
                    ?>
                    
                </div>
                <div class="card col-lg-8 col-ms-8">
                     <h3 style="color:blueviolet"><b>Agendamiento de Atención</b></h3>
                    <div class="dece-registro-agendamiento-atencion-form">

                        <?php $form = ActiveForm::begin(); ?>

                        <?= $form->field($model, 'id_reg_seguimiento')->hiddenInput(['value' => $idRegSeguimiento])->label(false) ?>

                        <?= $form->field($model, 'fecha_inicio')->textInput(['type' => 'date']) ?>

                        <!-- <?= $form->field($model, 'fecha_fin')->textInput() ?> -->

                        <?= $form->field($model, 'estado')->dropDownList($arrayEstado, ['prompt' => 'Seleccione Opción']) ?>


                        <div >
                            <?= $form->field($model, 'pronunciamiento')->textarea(['rows' => 3]) ?>
                            <script>
                                CKEDITOR.replace("deceregistroagendamientoatencion-pronunciamiento", {
                                    //toolbar: [ 'bold', 'italic', 'link', 'undo', 'redo', 'numberedList', 'bulletedList' ]
                                    customConfig: "/ckeditor_settings/config.js"
                                });
                            </script>
                        </div>
                        <div>
                            <?= $form->field($model, 'acuerdo_y_compromiso')->textarea(['rows' => 3]) ?>
                            <script>
                                CKEDITOR.replace("deceregistroagendamientoatencion-acuerdo_y_compromiso", {
                                    //toolbar: [ 'bold', 'italic', 'link', 'undo', 'redo', 'numberedList', 'bulletedList' ]
                                    customConfig: "/ckeditor_settings/config.js"
                                });
                            </script>
                        </div>
                        <div>
                            <?= $form->field($model, 'evidencia')->textarea(['rows' => 3]) ?>
                            <script>
                                CKEDITOR.replace("deceregistroagendamientoatencion-evidencia", {
                                    //toolbar: [ 'bold', 'italic', 'link', 'undo', 'redo', 'numberedList', 'bulletedList' ]
                                    customConfig: "/ckeditor_settings/config.js"
                                });
                            </script>
                        </div>

                        <?= $form->field($model, 'path_archivo')->textInput(['maxlength' => true]) ?>
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