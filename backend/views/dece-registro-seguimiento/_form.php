<?php

use backend\models\DeceAsistente;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use backend\models\DeceMotivos;
use backend\models\DeceRegistroSeguimiento;
use backend\models\OpParent;
use backend\models\OpStudent;
use backend\models\ResPartner;
use backend\models\PlanificacionOpciones;
use backend\models\helpers\Scripts;
use backend\models\helpers\HelperGeneral;
use backend\models\DeceSeguimientoAcuerdos;
use backend\models\DeceSeguimientoFirmas;


/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroSeguimiento */
/* @var $form yii\widgets\ActiveForm */

$DateAndTime = date('m-d-Y h:i:s a', time());

//*** motivos */ 

$arrayMotivos = cargaArreglo("motivo");
$arrayEstado = cargaArreglo("estado_seg");
$arrayResponsableSeg = cargaArreglo("responsable_seg");
$arrayAtencionPara = cargaArreglo("atencion_para");
function cargaArreglo($campo)
{
    $consulta = DeceMotivos::find()
        ->select([$campo,])
        ->distinct()
        ->where(['not', [$campo => null]])
        ->asArray()
        ->all();

    $array = array();
    //recorremos arreglo
    foreach ($consulta as $dato) {
        $array[$dato[$campo]] = $dato[$campo];
    }
    return $array;
}
$modelPathArchivo = PlanificacionOpciones::find()
    ->where(['tipo' => 'VER_ARCHIVO'])
    ->andWhere(['categoria' => 'PATH_DECE_SEG'])
    ->one();
//llamo a grupo para buscar id alumno e id clase, $id_grupo es parametro de entrada
$modelEstudiante = OpStudent::findOne($model->id_estudiante);
$representante = OpParent::findOne($modelEstudiante->x_representante);
$modelRepresentante = ResPartner::findOne($representante->name);

//buscamos el numero de seguimientos que tiene el alumno
$modelRegSeguimiento = DeceRegistroSeguimiento::find()
    ->where(['id_caso' => $model->id_caso])
    ->orderBy(['estado' => SORT_DESC, 'fecha_inicio' => SORT_ASC])
    ->all();

//extrae usuarios del sistema, para mosrtrar en atendido por 
$objScript = new Scripts();
$usuarios = $objScript->mostrarUsuarioParaDece();
$arrayUsuario = array();
//recorremos arreglo
foreach ($usuarios as $usu) {
    $arrayUsuario[$usu['usuario']] = $usu['usuario'];
}


//datos acuerdos
$listAcuerdos = DeceSeguimientoAcuerdos::find()
    ->where(['id_reg_seguimiento' => $model->id])
    ->orderBy(['secuencial' => SORT_ASC])
    ->all();

//datos firmas
$listFirmas = DeceSeguimientoFirmas::find()
    ->where(['id_reg_seguimiento' => $model->id])
    ->orderBy(['id' => SORT_ASC])
    ->all();

?>
<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>

<div class="comportamiento-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">


        <div class="row p-4 ">
            <div class="card col-lg-5 col-ms-5">
                <!-- RENDERIZA A LA VISTA datos_estudiante.php -->
                <h3 style="color:blueviolet"><b>Datos Estudiante</b></h3>
                <table class="table table-responsive">
                    <tr>
                        <td><b>No. Caso: </b></td>
                        <td><?= $model->caso->numero_caso ?></td>
                    </tr>
                    <tr>
                        <td><b>Alumno: </b></td>
                        <td><?= $modelEstudiante->last_name . ' ' . $modelEstudiante->first_name . ' ' . $modelEstudiante->middle_name  ?></td>
                    </tr>
                    <tr>
                        <?php
                        //calcual la edad
                        $objHelperGeneral = new HelperGeneral();
                        $edad =  $objHelperGeneral->obtener_edad_segun_fecha($modelEstudiante->birth_date);
                        ?>
                        <td><b>Fecha Nacimiento: </b></td>
                        <td><?= $modelEstudiante->birth_date . ' (' . $edad . ' años)' ?></td>
                    </tr>
                    <tr>
                        <td><b>Representante: </b></td>
                        <td><?= $modelRepresentante->name ?></td>
                    </tr>
                    <tr>
                        <td><b>Email Representante: </b></td>
                        <td><?= $modelRepresentante->email ?></td>
                    </tr>
                    <tr>
                        <td><b>Telèfono: </b></td>
                        <td><?= $modelRepresentante->phone . ' - ' . $modelRepresentante->mobile . ' - ' . $modelRepresentante->x_work_phone ?></td>
                    </tr>
                </table>
                <h3 style="color:red">Histórico Acompañamiento</h3>
                <div style="overflow-x:scroll;overflow-y:scroll;">
                    <table class="table table-success table-striped table-bordered my-text-small">
                        <tr class="table-primary">
                            <td>No.</td>
                            <td>Fecha Creación</td>
                            <td>Última Modificación</td>
                            <td>Estado</td>
                            <td>Motivo</td>
                            <td>Editar</td>
                            <td>Ver</td>
                        </tr>
                        <?php if ($modelRegSeguimiento) {
                            foreach ($modelRegSeguimiento as $modelReg) {
                        ?>
                                <tr>
                                    <td><?= $modelReg->numero_seguimiento ?></td>
                                    <td><?= substr($modelReg->fecha_inicio, 0, 10) ?></td>
                                    <td><?= substr($modelReg->fecha_fin, 0, 10) ?></td>
                                    <td><?= $modelReg->estado ?></td>
                                    <td><?= $modelReg->motivo ?></td>
                                    <td>
                                        <?=
                                        Html::a(
                                            '<i class="fa fa-edit" aria-hidden="true"></i>',
                                            ['dece-registro-seguimiento/update', 'id' => $modelReg->id],
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
                                                        <h5 class="modal-title" id="staticBackdropLabel"><b>Acompañamiento No: <?= $modelReg->numero_seguimiento ?></b></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped table-hover" style="font-size:13px">
                                                            <tr>
                                                                <td><b>Fecha Creación: </b></td>
                                                                <td><?= substr($modelReg->fecha_inicio, 0, 10) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Última Modificación: </b></td>
                                                                <td><?= substr($modelReg->fecha_fin, 0, 10)  ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Estado: </b></td>
                                                                <td><?= $modelReg->estado ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Motivo: </b></td>
                                                                <td><?= $modelReg->motivo ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Pronunciamiento: </b></td>
                                                                <td><?= $modelReg->pronunciamiento ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Hora Inicio: </b></td>
                                                                <td><?= $modelReg->hora_inicio ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Hora Fin: </b></td>
                                                                <td><?= $modelReg->hora_cierre ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Nombre Quien Lidera: </b></td>
                                                                <td><?= $modelReg->nombre_quien_lidera ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Departamento:</b></td>
                                                                <td><?= $modelReg->departamento?></td>
                                                            </tr>
                                                            <tr>
                                                                <?php $arrayArchivo = array("", "");
                                                                if (strlen($modelReg->path_archivo) > 0) {
                                                                    $arrayArchivo = explode("##", $modelReg->path_archivo);
                                                                }
                                                                ?>
                                                                <td><b>Archivo: </b></td>
                                                                <td><a target="_blank" href="<?= $modelPathArchivo->opcion . $arrayArchivo[0] . '/' . $arrayArchivo[1] ?>">
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
                        <?php
                            } //fin for
                        } //fin if
                        ?>
                    </table>
                </div>
            </div>
            <div class="card col-lg-7 col-ms-7">
                <?php if ($model->isNewRecord) { ?>
                    <h3 style="color:blueviolet"><b>Acompañamiento</b></h3>
                <?php } else { ?>
                    <h3 style="color:blueviolet"><b>Acompañamiento No. <?= $model->numero_seguimiento ?></b></h3>
                    <h6 style="color:blueviolet"><b>Fecha Creación. <?= $model->fecha_inicio ?></b></h6>
                <?php } ?>


                <div class="dece-registro-seguimiento-form">

                    <?php $form = ActiveForm::begin();

                    ?>

                    <?= $form->field($model, 'numero_seguimiento')->hiddenInput(['value' => $model->numero_seguimiento])->label(false); ?>

                    <?= $form->field($model, 'id_estudiante')->hiddenInput(['value' => $model->id_estudiante])->label(false); ?>

                    <?= $form->field($model, 'id_clase')->hiddenInput(['value' => $model->id_clase])->label(false); ?>

                    <?= $form->field($model, 'id_caso')->hiddenInput(['value' => $model->id_caso])->label(false); ?>


                    <?php if ($model->isNewRecord) { ?>

                        <label for="fecha" class="form-label">Fecha Creación</label>
                        <input type="date" id="fecha_inicio" class="form-control" name="fecha_inicio" require="true" value="<?= $model->fecha_inicio; ?>">


                        <?= $form->field($model, 'fecha_fin')->hiddenInput()->label(false) ?>
                    <?php } else { ?>

                        <label for="fechaActualizacion" class="form-label">Fecha Actualización</label>
                        <input type="date" id="fecha_fin" class="form-control" name="fecha_fin" require="true" value="<?= $model->fecha_fin; ?>">

                        <?= $form->field($model, 'fecha_inicio')->hiddenInput()->label(false) ?>
                    <?php } ?>

                    <div class="row">
                        <div class="col">

                            <?= $form->field($model, 'nombre_quien_lidera')->textInput(['value' => $resUser->partner->name]) ?>
                        </div>
                        <div class="col">

                            <?= $form->field($model, 'departamento')->dropDownList($arrayResponsableSeg, ['prompt' => 'Seleccione Opción']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <?= $form->field($model, 'hora_inicio')->textInput() ?>
                        </div>
                        <div class="col">

                            <?= $form->field($model, 'hora_cierre')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <?= $form->field($model, 'estado')->dropDownList($arrayEstado, ['prompt' => 'Seleccione Estado']) ?>
                        </div>
                        <div class="col">

                            <?= $form->field($model, 'motivo')->dropDownList($arrayMotivos, ['prompt' => 'Seleccione Motivo']) ?>
                        </div>
                    </div>

                    <!-- <?= $form->field($model, 'atendido_por')->dropDownList($arrayUsuario, ['prompt' => 'Seleccione Opción']) ?>  -->

                    <!-- <?= $form->field($model, 'atencion_para')->dropDownList($arrayAtencionPara, ['prompt' => 'Seleccione Opción']) ?> -->

                    <!-- <?= $form->field($model, 'responsable_seguimiento')->dropDownList($arrayResponsableSeg, ['prompt' => 'Seleccione Opción']) ?> -->

                    <?= $form->field($model, 'pronunciamiento')->textarea(['rows' => 3]) ?>
                    <br>



                    <!-- //DETALLE DE ACUERDOS -->

                    <?php
                    if (!($model->isNewRecord)) {
                    ?>

                        <div class="card">
                            <!-- <?php
                                    // $modelDeceSegAcuerdo = new DeceSeguimientoAcuerdos();
                                    // echo $this->render('/dece-seguimiento-acuerdos/create', [
                                    //     'model' => $modelDeceSegAcuerdo,
                                    //     'id_seguimiento' => $model->id,
                                    // ]);
                                    ?> -->
                            <br>
                            <h5>Detalle de Acuerdos</h5>
                            <div class="form-control" id="div_crea_acuerdo">
                                <div class="card">
                                    <div class="card-header" style="background-color:lightblue">
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <textarea class="form-control" type="text" id="acuerdo_acuerdo" placeholder="Acuerdo"></textarea>
                                            </div>
                                            <div class="col-lg-3">
                                                <input class="form-control" type="text" id="responsable_acuerdo" placeholder="Responsable" />
                                            </div>
                                            <div class="col-lg-3">
                                                <input class="form-control" type="date" id="fecha_cumplimiento_acuerdo" placeholder="Fecha max cumplimiento" />

                                            </div>
                                            <div class="col-lg-1">
                                                <button type="button" class="btn btn-primary" id="icono_acuerdo" onclick="guardar_acuerdo()" title="Guardar Acuerdos"><i class="fas fa-save"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body" id="div_muestra_acuerdo">
                                        <table class="table table-striped table-success">
                                            <thead>
                                                <td><b> Ítem </b></td>
                                                <td><b> Acuerdo </b></td>
                                                <td><b> Responsable </b></td>
                                                <td><b> Fecha Cumplimiento </b></td>
                                                <td><b> Cumplió </b></td>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($listAcuerdos as $acuerdo) {
                                                ?>
                                                    <tr>
                                                        <td> <?= $acuerdo->secuencial ?> </td>
                                                        <td> <?= $acuerdo->acuerdo ?> </td>
                                                        <td> <?= $acuerdo->responsable ?> </td>
                                                        <td> <?= substr($acuerdo->fecha_max_cumplimiento, 0, 10) ?> </td>
                                                        <?php
                                                        if ($acuerdo->cumplio) {
                                                        ?>
                                                            <td> <input type="checkbox" id="cumplio_acuerdo" onclick="guardar_acuerdo_cumplido(<?= $acuerdo->id ?>,0)" checked /></td>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <td> <input type="checkbox" id="cumplio_acuerdo" onclick="guardar_acuerdo_cumplido(<?= $acuerdo->id ?>,1)" /></td>
                                                        <?php
                                                        }
                                                        ?>
                                                        <td>
                                                            <button type="button" class="btn btn-primary" id="icono_acuerdo" onclick="eliminar_acuerdo(<?= $acuerdo->id ?>)" title="Eliminar Acuerdo">
                                                                <i class="fas fa-trash-alt" style="color:white;"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        <?php
                    }
                        ?>
                        <!-- FIN DETALLE DE ACUERDOS -->


                        <!-- //FIRMAS -->

                        <?php
                        if (!($model->isNewRecord)) {
                        ?>

                            <div class="card">

                                <br>
                                <h5>Firmas</h5>
                                <div class="form-control" id="div_crea_acuerdo">
                                    <div class="card">
                                        <div class="card-header" style="background-color:lightblue">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <input class="form-control" type="text" id="firmas_nombre" placeholder="Nombre" />
                                                </div>
                                                <div class="col-lg-3">
                                                    <input class="form-control" type="number" id="firmas_cedula" placeholder="Cédula" />
                                                </div>
                                                <div class="col-lg-3">
                                                    <input class="form-control" type="text" id="firmas_parentesco" placeholder="Parentesco" />
                                                </div>
                                                <div class="col-lg-2">
                                                    <input class="form-control" type="text" id="firmas_cargo" placeholder="Cargo" />
                                                </div>
                                                <div class="col-lg-1">
                                                    <button type="button" class="btn btn-primary" id="icono_acuerdo" onclick="guardar_firmas()" title="Guardar Firmas"><i class="fas fa-save"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body" id="div_muestra_firmas">
                                            <table class="table table-striped table-success">
                                                <thead>
                                                    <td><b> Nombre </b></td>
                                                    <td><b> Cédula </b></td>
                                                    <td><b> Parentesco </b></td>
                                                    <td><b> Cargo </b></td>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($listFirmas as $firma) {
                                                    ?>
                                                        <tr>
                                                            <td> <?= $firma->nombre ?> </td>
                                                            <td> <?= $firma->cedula ?> </td>
                                                            <td> <?= $firma->parentesco ?> </td>
                                                            <td> <?= $firma->cargo ?> </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary" id="icono_firmas" onclick="eliminar_firma(<?= $firma->id ?>)" title="Eliminar Firma">
                                                                    <i class="fas fa-trash-alt" style="color:white;"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            <?php
                        }
                            ?>
                            <!-- FIN FIRMAS-->


                            <!-- <script>
                                    CKEDITOR.replace("deceregistroseguimiento-pronunciamiento");
                            </script> -->
                            <!-- <?= $form->field($model, 'acuerdo_y_compromiso')->textarea(['rows' => 4]) ?> -->
                            <!-- <script>
                                CKEDITOR.replace("deceregistroseguimiento-acuerdo_y_compromiso");
                            </script> -->
                            <!-- <?= $form->field($model, 'eviencia')->textarea(['rows' => 4]) ?> -->
                            <!-- <script>
                                CKEDITOR.replace("deceregistroseguimiento-eviencia");
                            </script> -->
                            <br>
                            <table class="table table-striped table-hover table-responsive">
                                <tr>
                                    <td>
                                        <?= $form->field($model, 'path_archivo')->fileInput(['maxlength' => true]) ?>
                                    </td>
                                </tr>
                            </table>
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                            </div>
                        </div>

                </div>
                <!--div row -->
            </div>
        </div>

        <script>
            //******   Acuerdos    ******** ///
            function guardar_acuerdo() {
                var url = '<?= Url::to(['guardar-acuerdos']) ?>';

                var secuencial = $('#secuencial_acuerdo').val();
                var acuerdo = $('#acuerdo_acuerdo').val();
                var responsable = $('#responsable_acuerdo').val();
                var cumplio = 0;
                var fecha_max_cumplimiento = $('#fecha_cumplimiento_acuerdo').val();
                var id_seguimiento = '<?= $model->id ?>';

                var params = {
                    secuencial: secuencial,
                    acuerdo: acuerdo,
                    responsable: responsable,
                    cumplio: cumplio,
                    fecha_max_cumplimiento: fecha_max_cumplimiento,
                    id_seguimiento: id_seguimiento,
                }

                $.ajax({
                    data: params,
                    url: url,
                    type: 'POST',
                    beforeSend: function(response) {},
                    success: function(response) {
                        $('#div_muestra_acuerdo').html(response);
                    }

                });
            }

            function guardar_acuerdo_cumplido(id_seg_acuerdo, cumplio) {
                var url = '<?= Url::to(['guardar-acuerdos-cumplido']) ?>';

                var params = {
                    id_seg_acuerdo: id_seg_acuerdo,
                    cumplio: cumplio,
                }

                $.ajax({
                    data: params,
                    url: url,
                    type: 'POST',
                    beforeSend: function(response) {},
                    success: function(response) {
                        $('#div_muestra_acuerdo').html(response);
                    }

                });
            }

            function eliminar_acuerdo(id_seg_acuerdo) {
                var url = '<?= Url::to(['eliminar-acuerdo']) ?>';

                var params = {
                    id_seg_acuerdo: id_seg_acuerdo,
                }

                $.ajax({
                    data: params,
                    url: url,
                    type: 'POST',
                    beforeSend: function(response) {},
                    success: function(response) {
                        $('#div_muestra_acuerdo').html(response);
                    }
                });
            }
            //******  fin  Acuerdos    ******** ///

            //******   firmas    ******** ///
            function guardar_firmas() {
                var url = '<?= Url::to(['guardar-firmas']) ?>';

                var nombre = $('#firmas_nombre').val();
                var cedula = $('#firmas_cedula').val();
                var parentesco = $('#firmas_parentesco').val();
                var cargo = $('#firmas_cargo').val();

                var id_seguimiento = '<?= $model->id ?>';

                var params = {
                    nombre: nombre,
                    cedula: cedula,
                    parentesco: parentesco,
                    cargo: cargo,
                    id_seguimiento: id_seguimiento,
                }

                $.ajax({
                    data: params,
                    url: url,
                    type: 'POST',
                    beforeSend: function(response) {},
                    success: function(response) {
                        $('#div_muestra_firmas').html(response);
                    }

                });
            }

            function eliminar_firma(id_seg_firmas) {
                var url = '<?= Url::to(['eliminar-firmas']) ?>';

                var params = {
                    id_seg_firmas: id_seg_firmas,
                }

                $.ajax({
                    data: params,
                    url: url,
                    type: 'POST',
                    beforeSend: function(response) {},
                    success: function(response) {
                        $('#div_muestra_firmas').html(response);
                    }
                });
            }
            //******   fin firmas    ******** ///
        </script>