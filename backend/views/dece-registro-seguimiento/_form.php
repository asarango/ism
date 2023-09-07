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

$arrayMotivos = cargaArreglo("motivo", true);
$arrayEstado = cargaArreglo("estado_seg", true);
$arrayResponsableSeg = cargaArreglo("responsable_seg", true);
$arrayAtencionPara = cargaArreglo("atencion_para", true);

$cargoList = cargaArreglo("responsable_seg", false);
$parentescoList = cargaArreglo("atencion_para", false);
// echo '<pre>';
// print_r($cargoList);
// echo '<pre>';
// print_r($arrayMotivos);
// die();
function cargaArreglo($campo, $esArray)
{
    $consulta = DeceMotivos::find()
        ->select([$campo,])
        ->distinct()
        ->where(['not', [$campo => null]])
        //->asArray()
        ->all();

    if ($esArray) {
        $array = array();
        //recorremos arreglo
        foreach ($consulta as $dato) {
            $array[$dato[$campo]] = $dato[$campo];
        }
        return $array;
    }
    return $consulta;
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

<style>
    .card {
        border-radius: 10px
    }

    /* .title {
        transition: transform 0.5s ease;
        text-align: center;
    }

    .title:hover {
        transform: scale(1.2);
       
    } */

    .historial {
        background-color: #ab0a3d;
    }

    .detalle {
        margin-left: 10px;
        text-align: center;
        margin: 5px;
        color: black;


    }

    .btn-acuerdo {

        transition: transform 0.5s ease;
    }

    .btn-acuerdo:hover {
        transform: scale(1.4);

    }
</style>



<div class="comportamiento-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="row p-5 " style="margin-top: -35px;">
            <div class="card col-lg-5 col-ms-5" style="padding: 20px;">
                <!-- RENDERIZA A LA VISTA datos_estudiante.php -->
                <h5 style="color: #0a1f8f;"><b>Datos Estudiante</b></h5>
                <table class=" table table-responsive table-striped table-hover" style="border: 1px solid black">
                    <tr>
                        <td style="background-color: #ab0a3d;color: white;"><b>No. Caso: </b></td>
                        <td class="title">
                            <?= $model->caso->numero_caso ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ab0a3d;color: white;"><b>Alumno: </b></td>
                        <td>
                            <?= $modelEstudiante->last_name . ' ' . $modelEstudiante->first_name . ' ' . $modelEstudiante->middle_name ?>
                        </td>
                    </tr>
                    <tr>
                        <?php

                        // echo "<pre>";
                        // print_r($padres1);
                        // die();

                        //calcual la edad
                        $objHelperGeneral = new HelperGeneral();
                        $edad = $objHelperGeneral->obtener_edad_segun_fecha($modelEstudiante->birth_date);
                        ?>
                        <td style="background-color: #ab0a3d;color: white;"><b>Fecha Nacimiento: </b></td>
                        <td>
                            <?= $modelEstudiante->birth_date . ' (' . $edad . ' años)' ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ab0a3d;color: white;"><b>Representante: </b></td>
                        <td>
                            <?= $modelRepresentante->name ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ab0a3d;color: white;"><b>Email Representante: </b></td>
                        <td>
                            <?= $modelRepresentante->email ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ab0a3d;color: white;"><b>Telèfono: </b></td>
                        <td>
                            <?= $modelRepresentante->phone . ' - ' . $modelRepresentante->mobile . ' - ' . $modelRepresentante->x_work_phone ?>
                        </td>
                    </tr>
                </table>

                <h5 style="color: #0a1f8f;font-weight: bold">Histórico Acompañamiento </h5>



                <div style="overflow-x:scroll;overflow-y:scroll;">
                    <table class="table table-striped table-bordered table-hover my-text-small"
                        style="border: 1px solid black;">
                        <tr class="table table-bordered table-striped historial"
                            style="color: white; font-weight: bold;">
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
                                <tr style="font-weight: bold;text-align: center;">
                                    <td>
                                        <?= $modelReg->numero_seguimiento ?>
                                    </td>
                                    <td>
                                        <?= substr($modelReg->fecha_inicio, 0, 10) ?>
                                    </td>
                                    <td>
                                        <?= substr($modelReg->fecha_fin, 0, 10) ?>
                                    </td>
                                    <td>
                                        <?= $modelReg->estado ?>
                                    </td>
                                    <td>
                                        <?= $modelReg->motivo ?>
                                    </td>
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
                                        <button type="button" class="rounded-pill" data-bs-toggle="modal"
                                            data-bs-target="<?php echo "#staticBackdrop$modelReg->id"; ?>">
                                            <i class="fas fa-glasses" style="color:blueviolet;"></i>
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="<?php echo "staticBackdrop$modelReg->id"; ?>"
                                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel"><b>Acompañamiento No:
                                                                <?= $modelReg->numero_seguimiento ?>
                                                            </b></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" style="padding: 20px;">
                                                        <table class="table table-striped table-hover" style="font-size:13px">
                                                            <tr>
                                                                <td><b>Fecha Creación: </b></td>
                                                                <td>
                                                                    <?= substr($modelReg->fecha_inicio, 0, 10) ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Última Modificación: </b></td>
                                                                <td>
                                                                    <?= substr($modelReg->fecha_fin, 0, 10) ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Estado: </b></td>
                                                                <td>
                                                                    <?= $modelReg->estado ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Motivo: </b></td>
                                                                <td>
                                                                    <?= $modelReg->motivo ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Pronunciamiento: </b></td>
                                                                <td>
                                                                    <?= $modelReg->pronunciamiento ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Hora Inicio: </b></td>
                                                                <td>
                                                                    <?= $modelReg->hora_inicio ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Hora Fin: </b></td>
                                                                <td>
                                                                    <?= $modelReg->hora_cierre ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Nombre Quien Lidera: </b></td>
                                                                <td>
                                                                    <?= $modelReg->nombre_quien_lidera ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Departamento:</b></td>
                                                                <td>
                                                                    <?= $modelReg->departamento ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <?php $arrayArchivo = array("", "");
                                                                if (strlen($modelReg->path_archivo) > 0) {
                                                                    $arrayArchivo = explode("##", $modelReg->path_archivo);
                                                                }
                                                                ?>
                                                                <td><b>Archivo: </b></td>
                                                                <td><a target="_blank"
                                                                        href="<?= $modelPathArchivo->opcion . $arrayArchivo[0] . '/' . $arrayArchivo[1] ?>">
                                                                        <?= $arrayArchivo[1] ?>
                                                                    </a></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>

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
                    <!-- <button type="button" class="btn btn-primary"
                        style="background-color: #ab0a3d;border:none;margin-bottom: 10px; text-align: center;" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Ver Historico
                    </button> -->
                </div>
            </div>
            <div class="card col-lg-7 col-ms-7" style="padding: 20px">
                <?php if ($model->isNewRecord) { ?>
                    <h5 style="color: #0a1f8f"><b>Acompañamiento</b></h5>
                <?php } else { ?>
                    <h5 style="color: #0a1f8f"><b>Acompañamiento No.
                            <?= $model->numero_seguimiento ?>
                        </b></h5>
                    <small style="color: #0a1f8f"><b>Fecha Creación.
                            <?= $model->fecha_inicio ?>
                        </b></small><br>
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
                        <input type="date" id="fecha_inicio" class="form-control" name="fecha_inicio" require="true"
                            value="<?= $model->fecha_inicio; ?>">


                        <?= $form->field($model, 'fecha_fin')->hiddenInput()->label(false) ?>
                    <?php } else { ?>

                        <label for="fechaActualizacion" class="form-label">Fecha Actualización</label>
                        <input type="date" id="fecha_fin" class="form-control" name="fecha_fin" require="true"
                            value="<?= $model->fecha_fin; ?>">

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

                            <?= $form->field($model, 'estado')->dropDownList(
                                $arrayEstado,
                                ['options' => ['PENDIENTE' => ['selected' => true]]]
                            ) ?>

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
                            <h5 class="detalle" style="font-weight: bold;"> Detalle de Acuerdos</h5>
                            <hr>
                            <div class="form-control" id="div_crea_acuerdo"
                                style="padding: 1rem;border: none; margin-top: -15px">
                                <div class="">
                                    <div class="card-header" style="background-color: #ab0a3d; border: 1px solid black;">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <textarea class="form-control" type="text" id="acuerdo_acuerdo" rows="4"
                                                    placeholder="Acuerdo"></textarea>
                                            </div>

                                            <div class="col-lg-8">
                                                <div class="row" style="margin: 5px;">
                                                    <div class="col-lg-6">
                                                        <input class="form-control" type="text" id="responsable_acuerdo"
                                                            placeholder="Responsable" />
                                                    </div>
                                                    <div class="col-lg-6">

                                                        <input class="form-control" type="date"
                                                            id="fecha_cumplimiento_acuerdo"
                                                            placeholder="Fecha max cumplimiento" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin: 5px;">
                                                    <div class="col-lg-6">
                                                        <select class="form-select" aria-label="Default select example"
                                                            id="parentesco">
                                                            <option value="">Parentesco</option>
                                                            <?php
                                                            foreach ($parentescoList as $item) {
                                                                ?>
                                                                <option value="<?= $item->atencion_para ?>"><?= $item->atencion_para ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <select class="form-select" aria-label="Default select example"
                                                            id="cargo" aria-placeholder="Parentesco">
                                                            <option value=""> Cargo</option>
                                                            <?php
                                                            foreach ($cargoList as $item) {
                                                                ?>
                                                                <option value="<?= $item->responsable_seg ?>"><?= $item->responsable_seg ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="row" style="margin-top: 5px;">
                                                        <div class="col-lg-6">
                                                            <input class="form-control" type="number" id="cedula"
                                                                style="width: 150px;" placeholder="Cédula" />
                                                        </div>
                                                        <div class="col-lg-6 btn-acuerdo"
                                                            style="text-align: center;padding: 3px;">
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                style="margin-left: 30px;background-color: #ff9e18;border: none;"
                                                                id="icono_acuerdo" onclick="guardar_acuerdo()"
                                                                title="Guardar Acuerdos"><i
                                                                    class="fas fa-save"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-body" id="div_muestra_acuerdo"
                                        style="background-color: #eee;border: 1px solid black;margin-top: 10px;">
                                        <table class="table table-striped table-bordered my-text-small" style="background-color: #ab0a3d; color: white;
                                            border: 1px solid black;text-align: center;">
                                            <thead style="font-weight: bold;">
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
                                                    <tr style="color: white;font-weight: bold;font-size: 0.9rem">
                                                        <td>
                                                            <?= $acuerdo->secuencial ?>
                                                        </td>
                                                        <td>
                                                            <?= $acuerdo->acuerdo ?>
                                                        </td>
                                                        <td>
                                                            <?= $acuerdo->responsable ?>
                                                        </td>
                                                        <td>
                                                            <?= substr($acuerdo->fecha_max_cumplimiento, 0, 10) ?>
                                                        </td>
                                                        <?php
                                                        if ($acuerdo->cumplio) {
                                                            ?>
                                                            <td> <input type="checkbox" id="cumplio_acuerdo"
                                                                    onclick="guardar_acuerdo_cumplido(<?= $acuerdo->id ?>,0)"
                                                                    checked /></td>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <td> <input type="checkbox" id="cumplio_acuerdo"
                                                                    onclick="guardar_acuerdo_cumplido(<?= $acuerdo->id ?>,1)" />
                                                            </td>
                                                            <?php
                                                        }
                                                        ?>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                id="icono_acuerdo"
                                                                onclick="eliminar_acuerdo(<?= $acuerdo->id ?>)"
                                                                title="Eliminar Acuerdo">
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


                                <h5 class="detalle" style="font-weight: bold;"> Firmas</h5>
                                <div class="form-control" id="div_crea_acuerdo">
                                    <div>
                                        <div class="card-header"
                                            style="background-color: #ab0a3d; text-align: center;border: 1px solid black;">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <input class="form-control" type="text" id="firmas_nombre"
                                                        placeholder="Nombre" />
                                                </div>
                                                <div class="col-lg-3">
                                                    <input class="form-control" type="number" id="firmas_cedula"
                                                        placeholder="Cédula" />
                                                </div>
                                                <div class="col-lg-3">
                                                    <input class="form-control" type="text" id="firmas_parentesco"
                                                        placeholder="Parentesco" />
                                                </div>
                                                <div class="col-lg-2">
                                                    <input class="form-control" type="text" id="firmas_cargo"
                                                        placeholder="Cargo" />
                                                </div>
                                                <div class="col-lg-1 btn-acuerdo">
                                                    <button type="button" class="btn btn-primary btn-sm " id="icono_acuerdo"
                                                        onclick="guardar_firmas()"
                                                        style="background-color: #ff9e18;border: none;"
                                                        title="Guardar Firmas"><i class="fas fa-save"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body" id="div_muestra_firmas"
                                            style="border: 1px solid black;margin-top: 10px;background-color: #eee;">
                                            <table class="table table-success table-striped table-bordered my-text-small"
                                                style="color: white; text-align: center;border: 1px solid black;font-weight: bold;">
                                                <thead>
                                                    <td style="background-color: #ab0a3d;"><b> Nombre </b></td>
                                                    <td style="background-color: #ab0a3d;"><b> Cédula </b></td>
                                                    <td style="background-color: #ab0a3d;"><b> Parentesco </b></td>
                                                    <td style="background-color: #ab0a3d;"><b> Cargo </b></td>
                                                </thead>

                                                <div class="row" style="text-align: center;">
                                                    <div class="col-lg-4">
                                                        <p>Familia directa del estudiante:</p>
                                                        <ul>
                                                            <li><b>Padre</b><input type="checkbox">

                                                            </li>
                                                            <li>
                                                                <b>Madre</b><input type="checkbox">

                                                            </li>
                                                            <li>
                                                                <b>Estudiante</b><input type="checkbox">
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <!-- /* se agregan los profesores respomsables */ -->
                                                        <p>Docente:</p>
                                                        <!-- para prueba -->
                                                        
                                                        <ul>
                                                            <li><b>Docentes</b><input type="checkbox"></li>
                                                            <li><b>Docentes</b><input type="checkbox"></li>
                                                            <li><b>Docentes</b><input type="checkbox"></li>
                                                            <li><b>Docentes</b><input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <!-- /* se agregan los profesores respomsables */ -->
                                                        <p>Autoridades:</p>
                                                        <!-- para prueba -->
                                                        
                                                        <ul>
                                                            <li><b>Autoridades</b><input type="checkbox"></li>
                                                            <li><b>Autoridades</b><input type="checkbox"></li>
                                                            <li><b>Autoridades</b><input type="checkbox"></li>
                                                            <li><b>Autoridades</b><input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <tbody>
                                                    <?php
                                                    foreach ($listFirmas as $firma) {
                                                        ?>
                                                        <tr style="color: black;">
                                                            <td>
                                                                <?= $firma->nombre ?>
                                                            </td>
                                                            <td>
                                                                <?= $firma->cedula ?>
                                                            </td>
                                                            <td>
                                                                <?= $firma->parentesco ?>
                                                            </td>
                                                            <td>
                                                                <?= $firma->cargo ?>
                                                            </td>
                                                            <td>
                                                                <button type="
                                                                    button" class="btn btn-primary btn-sm"
                                                                    id="icono_firmas"
                                                                    onclick="eliminar_firma(<?= $firma->id ?>)"
                                                                    title="Eliminar Firma">
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
                            <div class="row" style="margin: 10px;">
                                <div class="form-group">
                                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success ', 'style' => 'background-color: #ff9e18;border: none;']) ?>
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
            var parentesco = $("#parentesco").val();
            var cargo = $("#cargo").val();
            var cedula = $("#cedula").val();

            // alert(cedula)

            var params = {
                secuencial: secuencial,
                acuerdo: acuerdo,
                responsable: responsable,
                cumplio: cumplio,
                fecha_max_cumplimiento: fecha_max_cumplimiento,
                id_seguimiento: id_seguimiento,
                parentesco: parentesco,
                cargo: cargo,
                cedula: cedula,
            }

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function (response) { },
                success: function (response) {
                    //$('#div_muestra_acuerdo').html(response);
                    var data = $.parseJSON(response);
                    $('#div_muestra_acuerdo').html(data.acuerdos);
                    $('#div_muestra_firmas').html(data.firmas);
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
                beforeSend: function (response) { },
                success: function (response) {
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
                beforeSend: function (response) { },
                success: function (response) {
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
                beforeSend: function (response) { },
                success: function (response) {
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
                beforeSend: function (response) { },
                success: function (response) {
                    $('#div_muestra_firmas').html(response);
                }
            });
        }
            //******   fin firmas    ******** ///
    </script>