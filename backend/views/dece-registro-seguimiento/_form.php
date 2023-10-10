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



// echo "<pre>";
// print_r($modelEstudiante);
// die();
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>



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
        transform: scale(1.05);

    }

    .checkbox-label {
        display: flex;
        align-items: center;
        font-weight: bold;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<div class="comportamiento-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="row" style="margin-top: -25px;">
        
            <div class="card col-lg-5 col-ms-5 mb-3" style="padding: 20px;">

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
                                                                <?php

                                                                        $institutoId = Yii::$app->user->identity->instituto_defecto;
                                                                        // echo $modelPathArchivo->opcion . $arrayArchivo[0] . $arrayArchivo[1];
                                                                        // echo "<pre>";
                                                                        // print_r($institutoId);
                                                                        // die();

                                                                        if ($institutoId == 1) {
                                                                            $path = 'http://100.50.40.52/' . $modelPathArchivo->opcion;
                                                                        } else {
                                                                            $path = $modelPathArchivo->opcion;
                                                                        }
                                                                        ?>
                                                                <td><a target="_blank"
                                                                        href="<?= $path. $arrayArchivo[0] . '/' . $arrayArchivo[1] ?>">
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
            <div class="card col-lg-7 col-ms-7 mb-3" style="padding: 20px; ">
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
                    <script>
                        CKEDITOR.replace("deceregistroseguimiento-pronunciamiento");
                    </script>

                    <br>

                    <!-- //DETALLE DE ACUERDOS -->

                    <?php

                    // echo "<pre>";
                    // print_r($modelEstudiante);
                    // die();
                    

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
                                            <div class="col-lg-12">
                                                <textarea class="form-control" type="text" id="acuerdo_acuerdo" rows="4"
                                                    placeholder="Acuerdo"></textarea>

                                                <input style="margin-top: 5px;" class="form-control" type="date"
                                                    id="fecha_cumplimiento_acuerdo" placeholder="Fecha max cumplimiento" />
                                            </div>


                                            <div class="row" style="text-align: center;">
                                                <div class="row" style="margin: 5px;">
                                                    <div class="col-lg-12">
                                                        <input class="form-control" style="display: none;" type="text"
                                                            id="responsable_acuerdo" placeholder="Responsable" />

                                                    </div>

                                                </div>
                                                <div class="row" style="margin: 5px;">
                                                    <div>
                                                        <select style="display:none;" class="form-select"
                                                            aria-label="Default select example" id="parentesco">
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
                                                    <div>
                                                        <select style="display:none;" class="form-select"
                                                            aria-label="Default select example" id="cargo"
                                                            aria-placeholder="Parentesco">
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
                                                    <div class="" style="margin-top: 5px;">
                                                        <div>
                                                            <input style="display:none;" class="form-control" type="text"
                                                                id="cedula" style="width: 150px;" placeholder="Cédula" />
                                                        </div>
                                                        <div class="col-lg-12 btn-acuerdo"
                                                            style="text-align: center;padding: 3px;">
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                style="background-color: #ff9e18;border: none;"
                                                                id="icono_acuerdo" onclick="guardar_acuerdo()"
                                                                title="Guardar Acuerdos"><b>Guardar Acuerdo </b><i
                                                                    class="fas fa-save"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-body" id="div_muestra_acuerdo"
                                        style="background-color: #eee;border: 1px solid black;margin-top: 10px;">
                                        <table name="acuerdos" class="table table-striped table-bordered my-text-small"
                                            style="background-color: #ab0a3d; color: white;
                                            border: 1px solid black;text-align: center;font-weight: bold; font-size: 0.8rem;">
                                            <thead style="font-weight: bold;">
                                                <td><b> Ítem </b></td>
                                                <td><b> Acuerdo </b></td>
                                                <!-- <td><b> Responsable </b></td> -->
                                                <td><b> Fecha Cumplimiento </b></td>
                                                <td><b> Cumplió </b></td>
                                            </thead>
                                            <tbody style="font-weight: bold; color: white; font-size: 1rem;">
                                                <?php
                                                foreach ($listAcuerdos as $acuerdo) {
                                                    ?>
                                                    <tr style="color: black;background-color: #ccc;">
                                                        <td>
                                                            <?= $acuerdo->secuencial ?>
                                                        </td>
                                                        <td>
                                                            <?= $acuerdo->acuerdo ?>
                                                        </td>
                                                        <!-- <td>
                                                            < $acuerdo->responsable ?>
                                                        </td> -->
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

                                <?php
                                // echo "<pre>";
                                // print_r($listadoActores);
                                // die ();
                                ?>

                                <div class="row col-md-11" style="color: black; margin: 0 auto; margin-bottom: 1rem;">
                                    <div class="row" style="padding: 1rem;">
                                        <label for=""><b>Seleccione responsable:</b></label>

                                        <select style="background-color: white;" name="familiar" id="familiarDropdown"
                                            onchange="mostrarDatos()">
                                            <option value="">Escoja una opción</option>
                                            <?php foreach ($listadoActores as $responsable) {
                                                $nombreCompleto = $responsable['name'] . " (" . $responsable['cargo_descripcion'] . ")";
                                                echo "<option value='{$responsable['name']}' data-cedula='{$responsable['numero_identificacion']}' data-parentesco='{$responsable['cargo_descripcion']}' data-email='{$responsable['email']}'>{$nombreCompleto}</option>";

                                            } ?>
                                        </select>
                                    </div>
                                    <div class="checkbox-label" id="familiarDropdown">
                                        <input type="checkbox" id="checkboxEstudiante" onclick="mostrarDatosEstudiante()" />
                                        <label style="color: black;" for="checkboxEstudiante">Seleccionar como
                                            responsable al Estudiante</label>
                                    </div>


                                </div>


                                <div class="form-control" id="div_crea_acuerdo">
                                    <div>
                                        <div class="card-header"
                                            style="background-color: #ab0a3d; text-align: center;border: 1px solid black;">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input style="margin-top: 5px;" class="form-control" type="text"
                                                        id="firmas_nombre" placeholder="Nombre" />
                                                    <input style="margin-top: 5px;" class="form-control" type="text"
                                                        id="firmas_cedula" placeholder="Cédula" />
                                                    <input style="margin-top: 5px;" class="form-control" type="text"
                                                        id="firmas_parentesco" placeholder="Parentesco" />
                                                    <input style="margin-top: 5px;" class="form-control" type="text"
                                                        id="firmas_cargo" placeholder="E-mail" />
                                                </div>

                                                <div class="btn-acuerdo" style="margin-top: 1rem;">
                                                    <button type="button" class="btn btn-primary btn-sm " id="icono_acuerdo"
                                                        onclick="guardar_firmas()"
                                                        style="background-color: #ff9e18;border: none;"
                                                        title="Guardar Firmas"><b>Guardar Firmas</b> <i
                                                            class="fas fa-save"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body" id="div_muestra_firmas"
                                            style="border: 1px solid black;margin-top: 10px;background-color: #eee;">
                                            <table class="table table-striped table-bordered my-text-small"
                                                style="color: white; text-align: center;border: 1px solid black;font-weight: bold;">
                                                <thead>
                                                    <td style="background-color: #ab0a3d;"><b> Nombre </b></td>
                                                    <td style="background-color: #ab0a3d;"><b> Cédula </b></td>
                                                    <td style="background-color: #ab0a3d;"><b> Parentesco </b></td>
                                                    <td style="background-color: #ab0a3d;"><b> E-mail </b></td>
                                                    <td style="background-color: #ab0a3d;"><b> Borrar firma </b></td>
                                                </thead>
                                                <tbody style="color: black;">
                                                    <?php
                                                    foreach ($listFirmas as $firma) {
                                                        ?>
                                                        <tr>
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

                            <div class="row">
                                <div class="col-lg-6">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>
                                                <?= $form->field($model, 'path_archivo')->fileInput(['maxlength' => true]) ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-lg-6"
                                    style="display: flex; justify-content: center; align-items: center; flex-direction: column; text-align: center;margin-top: -5px;">
                                    <div class="form-group">
                                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success btn-acuerdo', 'style' => 'background-color: #ff9e18;border: none;']) ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php ActiveForm::end(); ?>

                        <div class="row" style="margin: 0 auto;margin: 10px">
                            <?php echo Html::a('Guardar y Enviar Correo', ['enviar-correo', 'id' => $modelEstudiante['id'], 'id_seguimiento' => $model->id], ['class' => 'btn btn-primary btn-acuerdo']); ?>
                        </div>

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

            var secuencial_acuerdo = $('#secuencial_acuerdo').val();
            var acuerdo_acuerdo = $('#acuerdo_acuerdo').val();
            var cumplio_acuerdo = 0;
            var fecha_max_cumplimiento_acuerdo = $('#fecha_cumplimiento_acuerdo').val();
            var id_seguimiento_acuerdo = '<?= $model->id ?>';

            var params = {
                secuencial: secuencial_acuerdo,
                acuerdo: acuerdo_acuerdo,
                responsable: 'N/A',
                cumplio: cumplio_acuerdo,
                fecha_max_cumplimiento: fecha_max_cumplimiento_acuerdo,
                id_seguimiento: id_seguimiento_acuerdo,
                parentesco: 'N/A',
                cargo: 'N/A',
                cedula: 'N/A',
            }

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function (response) { },
                success: function (response) {
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

    <script>
        function mostrarDatos() {
            var select = document.getElementById("familiarDropdown");
            var nombreCampo = document.getElementById("firmas_nombre");
            var cedulaCampo = document.getElementById("firmas_cedula");
            var parentescoCampo = document.getElementById("firmas_parentesco");
            var emailCampo = document.getElementById("firmas_cargo");

            var selectedOption = select.options[select.selectedIndex];
            if (selectedOption.value === "") {

                nombreCampo.value = "";
                cedulaCampo.value = "";
                parentescoCampo.value = "";
                emailCampo.value = "";
            } else {

                var cedula = selectedOption.getAttribute("data-cedula");
                var parentesco = selectedOption.getAttribute("data-parentesco");
                var email = selectedOption.getAttribute("data-email");


                nombreCampo.value = selectedOption.value;
                cedulaCampo.value = cedula;
                parentescoCampo.value = parentesco;
                emailCampo.value = email;
            }
        }

    </script>

    <script>
        function mostrarDatosAcuerdo() {
            var select = document.getElementById("AcuerdoDropdown");
            var nombreCampo = document.getElementById("responsable_acuerdo");
            var cedulaCampo = document.getElementById("cedula");
            var parentescoCampo = document.getElementById("parentesco");
            var emailCampo = document.getElementById("Acuerdo_cargo");

            var selectedOption = select.options[select.selectedIndex];
            if (selectedOption.value === "") {

                nombreCampo.value = "";
                cedulaCampo.value = "";
                parentescoCampo.value = "";
                emailCampo.value = "";
            } else {

                var cedula = selectedOption.getAttribute("data-cedula");
                var parentesco = selectedOption.getAttribute("data-parentesco");
                var email = selectedOption.getAttribute("data-email");


                nombreCampo.value = selectedOption.value;
                cedulaCampo.value = cedula;
                parentescoCampo.value = parentesco;
                emailCampo.value = email;
            }
        }

    </script>

    <script>
        function mostrarDatosEstudiante() {
            var checkbox = document.getElementById("checkboxEstudiante");
            var nombreCampo = document.getElementById("firmas_nombre");
            var cedulaCampo = document.getElementById("firmas_cedula");
            var parentescoCampo = document.getElementById("firmas_parentesco");
            var emailCampo = document.getElementById("firmas_cargo");

            if (checkbox.checked) {

                var last_name = '<?= $modelEstudiante['last_name'] ?>';
                var first_name = '<?= $modelEstudiante['first_name'] ?>';
                var middle_name = '<?= $modelEstudiante['middle_name'] ?>';
                var x_institutional_email = '<?= $modelEstudiante['x_institutional_email'] ?>';
                var x_account_owner_ident = '<?= $modelEstudiante['x_account_owner_ident'] ?>';

                nombreCampo.value = last_name + " " + first_name + " " + middle_name;
                cedulaCampo.value = x_account_owner_ident;
                parentescoCampo.value = "Estudiante";
                emailCampo.value = x_institutional_email;
            } else {

                nombreCampo.value = "";
                cedulaCampo.value = "";
                parentescoCampo.value = "";
                emailCampo.value = "";
            }
        }
    </script>


    <!-- <script>
        $(document).ready(function () {
            $("#enviar-correo-link").click(function (e) {
                e.preventDefault();

                var id = $(this).data("id");


                $.ajax({
                    url: "enviar-correo",
                    method: "POST",
                    data: { id: id },
                    success: function (response) {

                        console.log("Correo enviado con éxito");
                    },
                    error: function () {

                        console.error("Error al enviar el correo");
                    }
                });
            });
        });
    </script> -->