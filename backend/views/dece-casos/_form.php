<?php

use backend\models\DeceCasos;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\DeceMotivos;
use backend\models\DeceRegistroSeguimiento;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceCasos */
/* @var $form yii\widgets\ActiveForm */
//*** motivos */ 


$arrayEstado = cargaArreglo("estado_seg");
$arrayMotivo = cargaArreglo("motivo");
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
//busca todos los casos del estudiante
$modelCasosHist = DeceCasos::find()
    ->where(['id_estudiante' => $model->id_estudiante])
    ->andWhere(['id_periodo' => $model->id_periodo])
    ->all();

//buscamos el numero de seguimientos que tiene el alumno
$modelRegSeguimiento = DeceRegistroSeguimiento::find()
    ->where(['id_caso' => $model->numero_caso])
    ->orderBy(['estado' => SORT_DESC, 'fecha_inicio' => SORT_ASC])
    ->all();

?>
<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>

<div class="">
    <legend><b>Estudiante: </b>
        <?php $nombreEstudiante = $model->estudiante->middle_name . ' ' . $model->estudiante->first_name . ' ' . $model->estudiante->last_name ?>
        <?php $periodo = $model->periodo->nombre ?>
        <span style="color:red"><?= $nombreEstudiante ?><span>
    </legend>
</div>
<div class="">
    <legend><b>Número Caso: </b>
        <span style="color:red"><?= $model->numero_caso ?></span>
    </legend>
</div>
<hr>
<div class="row">
    <div class="dece-casos-form col-lg-5 col-ms-5">
        <div class="row">
            <h3 style="color:red">Histórico Casos</h3>
            <div style="overflow-x:scroll;overflow-y:scroll;">
                <table class="table table-success table-striped table-bordered my-text-small">
                    <tr class="table-primary">
                        <td>Caso</td>
                        <td>Fecha Creación</td>
                        <td>Última Modificación</td>
                        <td>Estado</td>
                        <td>Motivo</td>
                        <td>Editar</td>
                        <td>Ver</td>
                    </tr>
                    <?php 
                        foreach ($modelCasosHist as $modelReg) {
                            
                    ?>
                            <tr>
                                <td><?= $modelReg->numero_caso ?></td>
                                <td><?= substr($modelReg->fecha_inicio, 0, 10) ?></td>
                                <td><?= substr($modelReg->fecha_fin, 0, 10) ?></td>
                                <td><?= $modelReg->estado ?></td>
                                <td><?= $modelReg->motivo ?></td>
                                <td>
                                <?=
                                Html::a(
                                    '<i class="fa fa-edit" aria-hidden="true"></i>',
                                    ['dece-casos/update', 'id' => $modelReg->id],
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
                                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel"><b>Dece Caso : <?= $modelReg->id ?></b></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-striped table-hover">
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
                                                            <td><b>Detalle: </b></td>
                                                            <td><?= $modelReg->detalle ?></td>
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
                    ?>
                </table>
            </div>
        </div>
        <hr>
        <div class="row">
            <h5 style="color:red">Seguimientos Caso: <?= $model->numero_caso ?></h5>
            <div style="overflow-x:scroll;overflow-y:scroll;">
                <table class="table table-success table-striped table-bordered my-text-small">
                    <tr class="table-primary">
                        <td>Caso</td>
                        <td>Fecha Creación</td>
                        <td>Última Modificación</td>
                        <td>Estado</td>
                        <td>Motivo</td>
                        <td>Ver</td>
                    </tr>
                    <?php
                    foreach ($modelRegSeguimiento as $modelReg) {
                    ?>
                        <tr>
                            <td><?= $modelReg->id_caso ?></td>
                            <td><?= substr($modelReg->fecha_inicio, 0, 10) ?></td>
                            <td><?= substr($modelReg->fecha_fin, 0, 10) ?></td>
                            <td><?= $modelReg->estado ?></td>
                            <td><?= $modelReg->motivo ?></td>
                           
                            <td>
                                <!--boton VER  boton llama modal -->
                                <button type="button" class="rounded-pill" data-bs-toggle="modal" data-bs-target="<?php echo "#staticBackdrop$modelReg->id"; ?>">
                                    <i class="fas fa-glasses" style="color:blueviolet;"></i>
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="<?php echo "staticBackdrop$modelReg->id"; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel"><b>Dece Caso : <?= $modelReg->id_caso ?></b></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-striped table-hover">
                                                    <tr>
                                                        <td><b>Número Caso: </b></td>
                                                        <td><?= $modelReg->id_caso ?></td>
                                                    </tr>
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
                    ?>
                </table>
            </div>

        </div>
    </div>
    <div class="dece-casos-form col-lg-7 col-ms-7">

        <?php $form = ActiveForm::begin(); ?>

        <?php
        if ($model->isNewRecord) { ?>
            <?= $form->field($model, 'fecha_inicio')->textInput() ?>

            <?= $form->field($model, 'fecha_fin')->hiddenInput()->label(false) ?>
        <?php
        } else { ?>
            <?= $form->field($model, 'fecha_inicio')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'fecha_fin')->textInput() ?>
        <?php
        }
        ?>

        <?= $form->field($model, 'numero_caso')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'id_estudiante')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'id_clase')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'id_periodo')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'estado')->dropDownList($arrayEstado, ['prompt' => 'Selecione Opción']) ?>

        <?= $form->field($model, 'motivo')->dropDownList($arrayMotivo, ['prompt' => 'Selecione Opción'])  ?>

        <?= $form->field($model, 'detalle')->textarea(['rows' => 6]) ?>
        <script>
            CKEDITOR.replace("dececasos-detalle");
        </script>

        <?= $form->field($model, 'id_usuario')->hiddenInput()->label(false) ?>
        <br>

        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>