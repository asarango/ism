<?php

use backend\models\DeceIntervencion;
use backend\models\DeceAreasIntervenir;
use backend\models\DeceIntervencionCompromiso;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\helpers\HelperGeneral;
use backend\models\OpParent;
use backend\models\OpStudent;
use backend\models\ResPartner;
use yii\jui\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencion */
/* @var $form yii\widgets\ActiveForm */
//llamo a grupo para buscar id alumno e id clase, $id_grupo es parametro de entrada
$modelEstudiante = OpStudent::findOne($model->id_estudiante);
$representante = OpParent::findOne($modelEstudiante->x_representante);
$modelRepresentante = ResPartner::findOne($representante->name);
//buscamos el numero de intervencion que tiene el alumno
$modelRegIntervencion = DeceIntervencion::find()
    ->where(['id_caso' => $model->id_caso])
    ->orderBy(['fecha_intervencion' => SORT_ASC])
    ->all();
//institucion externa derivacion
$arrayAreaIntervenir = DeceAreasIntervenir::find()->asArray()->all();

$numDivisionesAreaIntervenir = count($arrayAreaIntervenir) / 3;
$numDivisionesAreaIntervenir = intval($numDivisionesAreaIntervenir) + 1;
//creamos el objeto a ser instanciado de los compromisos
$modelIntCompromiso = new DeceIntervencionCompromiso();

?>
<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>
<div class="dece-intervencion-form">
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
                    <tr>
                        <td><b>Email Representante: </b></td>
                        <td><?= $modelRepresentante->email ?></td>
                    </tr>
                    <tr>
                        <td><b>Telèfono: </b></td>
                        <td><?= $modelRepresentante->phone . ' - ' . $modelRepresentante->mobile . ' - ' . $modelRepresentante->x_work_phone ?></td>
                    </tr>
                </table>
                <h3 style="color:red">Histórico Intervenciones</h3>
                <div style="overflow-x:scroll;overflow-y:scroll;">
                    <table class="table table-success table-striped table-bordered my-text-small">
                        <tr class="table-primary">
                            <td>No.</td>
                            <td>Fecha Creación</td>
                            <td>Razón</td>
                            <td>Obj. General</td>
                            <td>Editar</td>
                            <td>Ver</td>
                        </tr>
                        <?php if ($modelRegIntervencion) {
                            foreach ($modelRegIntervencion as $modelReg) {
                        ?>
                                <tr>
                                    <td><?= $modelReg->numero_intervencion ?></td>
                                    <td><?= $modelReg->fecha_intervencion ?></td>
                                    <td><?= $modelReg->razon ?></td>
                                    <td><?= $modelReg->acciones_responsables ?></td>
                                    <td>
                                        <?=
                                        Html::a(
                                            '<i class="fa fa-edit" aria-hidden="true"></i>',
                                            ['dece-intervencion/update', 'id' => $modelReg->id],
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
                                                        <h5 class="modal-title" id="staticBackdropLabel"><b>Intervención No: <?= $modelReg->id ?></b></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped table-hover">
                                                            <tr>
                                                                <td><b>Fecha Creación: </b></td>
                                                                <td><?= $modelReg->fecha_intervencion ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Razón: </b></td>
                                                                <td><?= $modelReg->razon ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Objetivo General: </b></td>
                                                                <td><?= $modelReg->objetivo_general ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Acciones / Responsables: </b></td>
                                                                <td><?= $modelReg->acciones_responsables ?></td>
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
                <div class="row">
                    <div class="col-lg-6">
                        <?php if ($model->isNewRecord) { ?>
                            <h5 style="color:blueviolet"><b>Intervención</b></h5>
                        <?php } else { ?>
                            <h3 style="color:blueviolet"><b>Intervención No. <?= $model->numero_intervencion ?></b></h3>
                            <h6 style="color:blueviolet"><b>Fecha Creación: <?= $model->fecha_intervencion ?></b></h6>
                        <?php } ?>
                    </div>
                    <div class="col-lg-6">
                        <?php
                        if (!($model->isNewRecord)) {
                        ?>
                            <table class="table table-info">
                                <tr>
                                    <td>
                                        <span style="color:blueviolet; font-size:12px;"><b>Compromisos de las partes involucradas</b></span>
                                    </td>
                                    <td>
                                        <!--boton VER  boton llama modal para COMPROMISO BLOQUE 1 -->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#btn_compromiso_b1" onclick="muestraTablaCompromiso()">
                                            VER
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        <?php
                        }
                        ?>


                        <!-- Modal B1-->
                        <?php
                        if (!($model->isNewRecord)) {
                        ?>
                            <div class="modal fade" id="btn_compromiso_b1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel"><b>Compromisos de las Partes Involucradas</b></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                            <?= $this->render('/dece-intervencion-compromiso/create', [
                                                'model' => $modelIntCompromiso,
                                                'id_intervencion' => $model->id,
                                            ]) ?>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                    </div>
                </div>

                <?php $form = ActiveForm::begin(); ?>


                <?= $form->field($model, 'id_estudiante')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'id_caso')->hiddenInput()->label(false) ?>

                <?php
                if ($model->isNewRecord) {
                ?>
                    <label for="exampleInputEmail1" class="form-label">Fecha Creación</label>
                    <input type="date" id="fecha_intervencion" class="form-control" name="fecha_intervencion" require="true" value="<?= $model->fecha_intervencion; ?>">
                <?php
                }
                ?>

                <?= $form->field($model, 'razon')->textarea(['rows' => 4]) ?>
                <!-- <script>
                    CKEDITOR.replace("deceintervencion-razon");
                </script> -->

                <div>
                    <hr>
                    <h6><u>Áreas a Intervenir</u></h6>
                   
                    <table class="table table-info table-hover">
                        <?php                        
                        if ($model->isNewRecord) {
                            $arrayDividido = array_chunk($arrayAreaIntervenir, $numDivisionesAreaIntervenir);
                            foreach ($arrayDividido as $array) {
                        ?>
                                <tr>
                                    <?php
                                    foreach ($array as $inst) {
                                    ?>
                                        <td>
                                            <label style='font-size:14px;' for="<?= $inst['id'] ?>"> <?= $inst['nombre'] ?></label><br>
                                            <input style='align-items:center;' type="checkbox" id="<?= $inst['id'] ?>" name="<?= $inst['code'] ?>" value="<?= $inst['code'] ?>">
                                        </td>
                                    <?php
                                    } //fin foreach 2
                                    ?>
                                </tr>
                            <?php
                            } //fin foreach 1   
                        } else {
                            
                            $arrayDividido = array_chunk($arrayAreaIntervenirUpdate, $numDivisionesAreaIntervenir);
                            foreach ($arrayDividido as $array) {
                            ?>
                                <tr>
                                    <?php
                                    foreach ($array as $inst) {                                        
                                        if ($inst['seleccionado'] == 'si') {
                                    ?>
                                            <td>
                                                <label style='font-size:14px;' for="<?= $inst['id'] ?>"> <?= $inst['nombre'] ?></label><br>
                                                <input style='align-items:center;' type="checkbox" id="<?= $inst['id'] ?>" name="<?= $inst['code'] ?>" value="<?= $inst['code'] ?>" checked="true">
                                            </td>
                                        <?php
                                        } else {
                                        ?>
                                            <td>
                                                <label style='font-size:14px;' for="<?= $inst['id'] ?>"> <?= $inst['nombre'] ?></label><br>
                                                <input style='align-items:center;' type="checkbox" id="<?= $inst['id'] ?>" name="<?= $inst['code'] ?>" value="<?= $inst['code'] ?>">
                                            </td>
                                    <?php
                                        }
                                    } //fin foreach 2

                                    ?>
                                </tr>
                        <?php
                            } //fin foreach 1 
                        }
                        ?>
                    </table>
                    <?= $form->field($model, 'otra_area')->textInput() ?>
                </div>

                <br>
                <h6><u>Lineamiento del proceso de intervención</u></h6>
                <?= $form->field($model, 'objetivo_general')->textarea(['rows' => 4]) ?>
                <!-- <script>
                    CKEDITOR.replace("deceintervencion-objetivo_general");
                </script> -->
                <?= $form->field($model, 'acciones_responsables')->textarea(['rows' => 4]) ?>
                <!-- <script>
                //     CKEDITOR.replace("deceintervencion-acciones_responsables");
                // </script> -->

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>
<script>
    $(window).on("load", function() {
        muestraTablaCompromiso();
    });

    function muestraTablaCompromiso() {
        //alert("Ingresoo ****");    
        var url = '<?= Url::to(['dece-intervencion-compromiso/mostrar-tabla']) ?>';
        var id_intervencion = '<?= $model->id ?>';
        var params = {
            id_intervencion: id_intervencion
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                //alert("iNTERMEDIO ****");
                $('#tabla_compromisos').html(response);

            }
        });

        //alert("Termino ****");
    }
</script>