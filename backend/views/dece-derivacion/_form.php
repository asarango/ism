<?php

use backend\models\DeceDerivacion;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\OpParent;
use backend\models\OpStudent;
use backend\models\ResPartner;
use backend\models\DeceInstitucionExterna;
use backend\models\helpers\HelperGeneral;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceDerivacion */
/* @var $form yii\widgets\ActiveForm */
$arrayTipoDerivacion = array('Interna'=>'Interna','Externa'=>'Externa');
//llamo a grupo para buscar id alumno e id clase, $id_grupo es parametro de entrada
$modelEstudiante = OpStudent::findOne($model->id_estudiante);
$representante = OpParent::findOne($modelEstudiante->x_representante);
$modelRepresentante = ResPartner::findOne($representante->name);
    //buscamos el numero de seguimientos que tiene el alumno
$modelRegDerivacion = DeceDerivacion::find()
->where(['id_casos' => $model->id_casos])
->orderBy(['fecha_derivacion'=>SORT_ASC])
->all();
//institucion externa derivacion
$arrayInstExterna = DeceInstitucionExterna::find()->asArray()->all();

$numDivisionesIntExterna = count($arrayInstExterna)/4;
$numDivisionesIntExterna = intval($numDivisionesIntExterna)+1;
?>

<div class="dece-derivacion-form">

<div class="m-0 vh-50 row justify-content-center align-items-center">

    <div class="row p-4 ">
        <div class="card col-lg-5 col-ms-5">
            <!-- RENDERIZA A LA VISTA datos_estudiante.php -->
            <h3 style="color:blueviolet"><b>Datos Estudiante</b></h3>
                    <table class="table table-responsive">
                        <tr>
                            <td><b>No. Caso: </b></td>
                            <td><?= $model->casos->numero_caso ?></td>
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
                            <td><?= $modelEstudiante->birth_date . ' ('.$edad.' años)'  ?></td>
                        </tr>                       
                        <tr>
                            <td><b>Representante: </b></td>
                            <td><?= $modelRepresentante->name ?></td>
                        </tr>                       
                    </table>
                    <h3 style="color:red">Histórico Derivaciones</h3>
                    <div style="overflow-x:scroll;overflow-y:scroll;" >                        
                        <table class="table table-success table-striped table-bordered my-text-small">
                            <tr class="table-primary">
                                <td>No.</td>
                                <td>Fecha Creación</td>
                                <td>Última Modificación</td>
                                <td>Motivo</td>
                                <td>Editar</td>
                                <td>Ver</td>
                            </tr>
                            <?php if ($modelRegDerivacion) {
                                foreach ($modelRegDerivacion as $modelReg) {
                            ?>
                                    <tr>
                                        <td><?= $modelReg->id ?></td>
                                        <td><?= substr($modelReg->fecha_inicio,0,10) ?></td>
                                        <td><?= substr($modelReg->fecha_fin,0,10) ?></td>
                                        <td></td>
                                        <td>
                                        <?=
                                            Html::a(
                                                '<i class="fa fa-edit" aria-hidden="true"></i>',
                                                ['dece-registro-seguimiento/update', 'id' =>$modelReg->id ],
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
                                                        <h5 class="modal-title" id="staticBackdropLabel"><b>Seguimiento No:  <?= $modelReg->id ?></b></h5>
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
                                                                <td><b>Pronunciamiento: </b></td>
                                                                <td><?= $modelReg->pronunciamiento ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Acuerdo y Compromiso: </b></td>
                                                                <td><?= $modelReg->acuerdo_y_compromiso ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Evidencia: </b></td>
                                                                <td><?= $modelReg->eviencia ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Atendido Por: </b></td>
                                                                <td><?= $modelReg->atendido_por ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Responsable Seguimiento:</b></td>
                                                                <td><?= $modelReg->responsable_seguimiento ?></td>
                                                            </tr>    
                                                            <tr>
                                                                <?php $arrayArchivo = array("", "");
                                                                if (strlen($modelReg->path_archivo) > 0) {
                                                                    $arrayArchivo = explode("##", $modelReg->path_archivo);
                                                                }
                                                                ?>
                                                                <td><b>Archivo: </b></td>
                                                                <td><a target="_blank" href="<?= $modelPathArchivo->opcion . $arrayArchivo[0].'/' . $arrayArchivo[1] ?>">
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
                <h3 style="color:blueviolet"><b>Derivación</b></h3>
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'tipo_derivacion')->dropDownList($arrayTipoDerivacion,['prompt' => 'Seleccione Opción']) ?>

                <?= $form->field($model, 'id_estudiante')->hiddenInput()->label(false)?>

                <br>
                <h4><u>Datos Personales de Quien Deriva</u></h4>

                <?= $form->field($model, 'nombre_quien_deriva')->textarea(['rows' => 1]) ?>

                <?php
                                if ($model->isNewRecord) { ?>
                                    <label for="exampleInputEmail1" class="form-label">Fecha Creación</label>
                                    <input type="date" id="fecha_derivacion" class="form-control" name="fecha_derivacion" require="true" value="<?= $model->fecha_derivacion;?>">

                                    <?= $form->field($model, 'fecha_modificacion')->hiddenInput()->label(false) ?> 
                                <?php
                                } else { ?>
                                    <?= $form->field($model, 'fecha_derivacion')->hiddenInput()->label(false) ?>
                                    
                                    <label for="exampleInputEmail2" class="form-label">Fecha Actualización</label>
                                    <input type="date" id="fecha_modificacion" class="form-control" name="fecha_modificacion" require="true" value="<?= $model->fecha_modificacion;?>">
                                <?php
                                }
                ?>
                <div>
                    <h4><u>Institución Externa</u></h4>
                    <table class="table table-info table-hover"> 
                        
                            <?php
                            $arrayDividido = array_chunk($arrayInstExterna, $numDivisionesIntExterna); 
                            foreach($arrayDividido as $array)
                            {
                            ?>
                            <tr>
                                <?php   
                                    foreach($array as $inst)
                                    {
                                ?>
                                    <td style='font-size:10px;'><?=$inst['nombre']?></td> 
                                    <td>
                                        <a href='#' style='color:gray;' onclick="">                                            
                                            <i class="fas fa-check-circle"></i>                                            
                                        </a>
                                    </td>
                                <?php
                                    }//fin foreach 2
                                ?>
                            </tr> 
                            <?php
                            }//fin foreach 2            
                            ?>
                    </table>
                    <?= $form->field($model, 'otra_institucion_externa')->textInput() ?>
                </div>

                
                


                <h4><u>Valoración del Caso</u></h4>

                <?= $form->field($model, 'motivo_referencia')->textarea(['rows' => 3]) ?>

                <?= $form->field($model, 'historia_situacion_actual')->textarea(['rows' => 3]) ?>

                <?= $form->field($model, 'accion_desarrollada')->textarea(['rows' => 3]) ?>

                <?= $form->field($model, 'tipo_ayuda')->textarea(['rows' => 3]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

</div>
