<?php

use backend\models\DeceDeteccion;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\helpers\HelperGeneral;
use backend\models\OpParent;
use backend\models\OpStudent;
use backend\models\ResPartner;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceDeteccion */
/* @var $form yii\widgets\ActiveForm */

// echo '<pre>';
//        print_r($array_datos_estudiante);
//        die();

//Si el arreglo con datos del estudiante, viene vacio
if ($model->isNewRecord && count($array_datos_estudiante) == 0) {
    $array_datos_estudiante['student'] = '';
    $array_datos_estudiante['curso'] = '';
    $array_datos_estudiante['paralelo'] = '';
}
//llamo a grupo para buscar id alumno e id clase, $id_grupo es parametro de entrada
$modelEstudiante = OpStudent::findOne($model->id_estudiante);
$representante = OpParent::findOne($modelEstudiante->x_representante);
$modelRepresentante = ResPartner::findOne($representante->name);

$modelRegDeteccion = DeceDeteccion::find()
    ->where(['id_caso' => $model->id_caso])
    ->orderBy(['numero_deteccion' => SORT_ASC])
    ->all();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>



<div class="dece-deteccion-form">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="row p-4 ">

            <div class="card col-lg-4 col-ms-4">
                <!-- RENDERIZA A LA VISTA datos_estudiante.php -->
                <h5 style="color:blueviolet"><b>Datos Estudiante</b></h5>
                <table class="table table-responsive">
                    <tr>
                        <td><b>No. Caso: <?php echo '<pre>'; ?></b></td>
                        <td><?= $model->caso->numero_caso ?></td>
                    </tr>
                    <tr>
                        <td><b>Alumno: </b></td>
                        <td><?= $modelEstudiante->first_name . ' ' . $modelEstudiante->middle_name . ' ' . $modelEstudiante->last_name ?></td>
                    </tr>
                    <?php
                    //calcual la edad
                    $objHelperGeneral = new HelperGeneral();
                    $edad =  $objHelperGeneral->obtener_edad_segun_fecha($modelEstudiante->birth_date);
                    ?>
                    <tr>
                        <td><b>Fecha Nacimiento: </b></td>
                        <td><?= $modelEstudiante->birth_date . ' (' . $edad . ' años)'  ?></td>
                    </tr>
                    <tr>
                        <td><b>Representante: </b></td>
                        <td><?= $modelRepresentante->name ?></td>
                    </tr>
                </table>
                <h3 style="color:red">Histórico Detección</h3>
                <div style="overflow-x:scroll;overflow-y:scroll;">
                    <table class="table table-success table-striped table-bordered my-text-small">
                        <tr class="table-primary">
                            <td>No.</td>
                            <td>Fecha Creación</td>
                            <td>Editar</td>
                            <td>Ver</td>
                        </tr>
                        <?php if ($modelRegDeteccion) {
                            foreach ($modelRegDeteccion as $modelReg) {
                        ?>
                                <tr>
                                    <td><?= $modelReg->numero_deteccion ?></td>
                                    <td><?= $modelReg->fecha_reporte ?></td>

                                    <td>
                                        <?=
                                        Html::a(
                                            '<i class="fa fa-edit" aria-hidden="true"></i>',
                                            ['dece-deteccion/update', 'id' => $modelReg->id],
                                            ['class' => 'link']
                                        );
                                        ?>
                                    </td>
                                    <td>
                                        <!--boton VER  boton llama modal -->
                                        <button type="button" class="rounded-pill" data-bs-toggle="modal" data-bs-target="<?php echo "#staticBackdrop$modelReg->id"; ?>">
                                            <i class="fas fa-glasses" style="color:blueviolet;"></i>
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="<?php echo "staticBackdrop$modelReg->id"; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel"><b>Derivación No: <?= $modelReg->id ?></b></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped table-hover">
                                                            <tr>
                                                                <td><b>Fecha Creación: </b></td>
                                                                <td><?= $modelReg->fecha_reporte ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Nombre Quien Reporta: </b></td>
                                                                <td><?= $modelReg->nombre_quien_reporta ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Descripción del Hecho: </b></td>
                                                                <td><?= $modelReg->descripcion_del_hecho ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Acciones Realizadas </b></td>
                                                                <td><?= $modelReg->acciones_realizadas ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Listar Evidencias </b></td>
                                                                <td><?= $modelReg->lista_evidencias ?></td>
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
                        <?php
                            } //fin for
                        } //fin if
                        ?>
                    </table>
                </div>
            </div>
            <div class="card col-lg-8 col-ms-8">


                <?php $form = ActiveForm::begin(); ?>


                <!-- <?= $form->field($model, 'numero_deteccion')->textInput() ?> -->

                <!-- <?= $form->field($model, 'id_estudiante')->textInput() ?> -->

                <!-- <?= $form->field($model, 'id_caso')->textInput() ?> -->

                <!-- <?= $form->field($model, 'numero_caso')->textInput() ?> -->



                <div class="row ">
                    <h5 style="color:blueviolet;"><b>DATOS INFORMATIVOS GENERALES</b></h5>
                    <?php
                    if ($model->isNewRecord) {
                    ?>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'nombre_estudiante')->textInput(['maxlength' => true, 'disabled' => true, 'value' => $array_datos_estudiante['student']]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'anio')->textInput(['maxlength' => true, 'disabled' => true, 'value' => $array_datos_estudiante['curso']]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'paralelo')->textInput(['maxlength' => true, 'disabled' => true, 'value' => $array_datos_estudiante['paralelo']]) ?>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'nombre_estudiante')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'anio')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'paralelo')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>

                <br>
                <div class="row">
                    <h5 style="color:blueviolet;">PERSONA QUE REPORTA</h5>
                    <div class="row">
                        <div class="col-lg-5">
                            <label for="exampleInputEmail1" class="form-label">Fecha</label>
                            <input type="date" id="fecha_reporte" class="form-control" name="fecha_reporte" require="true" value="<?= substr($model->fecha_reporte, 0, 10); ?>">

                        </div>
                    </div>
                    <div class="row">
                        <?php
                        if ($model->isNewRecord) {
                        ?>
                            <div class="col-lg-5">
                                <?= $form->field($model, 'nombre_quien_reporta')->textInput(['maxlength' => true, 'disabled' => true, 'value' => $resUser->partner->name]) ?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'cedula')->textInput(['maxlength' => true, 'disabled' => true, 'value' => $resUser->partner->numero_identificacion]) ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'cargo')->textInput(['maxlength' => true, 'value' => 'Psicologo']) ?>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="col-lg-5">
                                <?= $form->field($model, 'nombre_quien_reporta')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'cedula')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>
                            </div>

                        <?php
                        }
                        ?>
                    </div>



                </div>
                <br>
                <div class="row">
                    <h5 style="color:blueviolet;">DESCRIPCIÓN DEL HECHO (qué paso, quienes se involucran, dónde, cuándo)</h5>
                    <div class="row">
                        <?= $form->field($model, 'hora_aproximada')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'descripcion_del_hecho')->textarea(['rows' => 6]) ?>
                        <!-- <script>
                            CKEDITOR.replace("decedeteccion-descripcion_del_hecho");
                        </script> -->
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'acciones_realizadas')->textarea(['rows' => 6])  ?>
                        <!-- <script>
                            CKEDITOR.replace("decedeteccion-acciones_realizadas");
                        </script> -->
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5 style="color:blueviolet;">ENLISTE LAS EVIDENCIAS</h5>
                    <div class="row">
                        <?= $form->field($model, 'lista_evidencias')->textarea(['rows' => 6]) ?>
                        <script>
                            CKEDITOR.replace("decedeteccion-lista_evidencias");
                        </script>
                    </div>
                </div>


                <!-- <?= $form->field($model, 'path_archivos')->textInput(['maxlength' => true]) ?> -->

                <div class="form-group">
                    <?= Html::submitButton('GUARDAR', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>


            </div>
        </div>
    </div>
    </div>