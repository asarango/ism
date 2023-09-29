<?php

use backend\models\DeceDerivacion;
use yii\helpers\Html;
use yii\helpers\Url;
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

$user = Yii::$app->user->identity;
?>
<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>
<div class="dece-derivacion-form">

<div class="m-0 vh-50 row justify-content-center align-items-center">

    <div class="row p-4 ">
        <div class="card col-lg-5 col-ms-5">
            <!-- RENDERIZA A LA VISTA datos_estudiante.php -->
            <h4 style="color:blueviolet"><b>Datos Estudiante</b></h4>
                    <table class="table table-responsive">
                        <tr>
                            <td><b>No. Caso: <?php echo '<pre>'; ?></b></td>
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
                        <tr>
                            <td><b>Email Representante: </b></td>
                            <td><?= $modelRepresentante->email ?></td>
                        </tr> 
                        <tr>
                            <td><b>Telèfono: </b></td>
                            <td><?= $modelRepresentante->phone . ' - ' . $modelRepresentante->mobile .' - '. $modelRepresentante->x_work_phone ?></td>
                        </tr>                      
                    </table>
                    <h3 style="color:red">Histórico Derivaciones</h3>
                    <div style="overflow-x:scroll;overflow-y:scroll;" >                        
                        <table class="table table-success table-striped table-bordered my-text-small">
                            <tr class="table-primary">
                                <td>No.</td>
                                <td>Fecha Creación</td>
                                <td>Última Modificación</td>
                                <td>Tipo Derivación</td>
                                <td>Editar</td>
                                <td>Ver</td>
                            </tr>
                            <?php if ($modelRegDerivacion) {
                                foreach ($modelRegDerivacion as $modelReg) {
                            ?>
                                    <tr>
                                        <td><?= $modelReg->numero_derivacion ?></td>
                                        <td><?= $modelReg->fecha_derivacion ?></td>
                                        <td><?= $modelReg->fecha_modificacion ?></td>
                                        <td><?= $modelReg->tipo_derivacion ?></td>
                                        <td>
                                        <?=
                                            Html::a(
                                                '<i class="fa fa-edit" aria-hidden="true"></i>',
                                                ['dece-derivacion/update', 'id' =>$modelReg->id ],
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
                                                        <h5 class="modal-title" id="staticBackdropLabel"><b>Derivación No:  <?= $modelReg->id ?></b></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped table-hover" style="font-size:15px">
                                                            <tr>
                                                                <td><b>Fecha Creación: </b></td>
                                                                <td><?= $modelReg->fecha_derivacion ?></td>
                                                            </tr>                                                            
                                                            <tr>
                                                                <td><b>Última Modificación: </b></td>
                                                                <td><?= $modelReg->fecha_modificacion ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Tipo Derivación: </b></td>
                                                                <td><?= $modelReg->tipo_derivacion ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Nombre Quien Deriva: </b></td>
                                                                <td><?= $modelReg->nombre_quien_deriva ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Motivo de Referencia: </b></td>
                                                                <td><?= $modelReg->motivo_referencia ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Historia de la Situación Actual: </b></td>
                                                                <td><?= $modelReg->historia_situacion_actual ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Acciones a Desarrollar: </b></td>
                                                                <td><?= $modelReg->accion_desarrollada ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Tipo Ayuda: </b></td>
                                                                <td><?= $modelReg->tipo_ayuda ?></td>
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
                
                <?php if($model->isNewRecord){ ?>
                        <h4 style="color:blueviolet"><b>Derivación</b></h4>
                      <?php } else {?>
                        <h4 style="color:blueviolet"><b>Derivación No. <?= $model->numero_derivacion?></b></h4>
                        <h6 style="color:blueviolet"><b>Fecha Creación: <?= $model->fecha_derivacion?></b></h6>
                <?php }?>

                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'tipo_derivacion')->dropDownList($arrayTipoDerivacion,['prompt' => 'Seleccione Opción']) ?>

                <?= $form->field($model, 'id_estudiante')->hiddenInput()->label(false)?>
                <?= $form->field($model, 'id_casos')->hiddenInput()->label(false)?>
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

                <br>
                <h4><u>Datos personales de quién deriva</u></h4>
                <?= $form->field($model, 'cargo_quien_deriva')->textInput() ?>
                <?php
                if($model->isNewRecord)                            
                {
                ?>
                    <?= $form->field($model, 'nombre_quien_deriva')->textInput(['value' => $resUser->partner->name]) ?>
                <?php
                }
                else
                {
                ?>
                <?= $form->field($model, 'nombre_quien_deriva')->textInput(['value' => $model->nombre_quien_deriva]) ?>
                 <?php
                }
                ?>

               
                <div>
                    <h4><u>Institución Externa</u></h4>
                    <table class="table table-info table-hover">                         
                            <?php
                            if($model->isNewRecord)
                            {
                                $arrayDividido = array_chunk($arrayInstExterna, $numDivisionesIntExterna); 
                                foreach($arrayDividido as $array)
                                {
                                ?>
                                <tr>
                                    <?php   
                                        foreach($array as $inst)
                                        {
                                    ?>
                                        <td>                                        
                                            <label style='font-size:15px;' for="<?=$inst['id']?>"> <?=$inst['nombre']?></label><br>
                                            <input style='align-items:center;font-size:12px;' type="checkbox" id ="<?=$inst['id']?>" name="<?=$inst['code']?>" value="<?=$inst['code']?>" >
                                        </td>
                                    <?php
                                        }//fin foreach 2
                                    ?>
                                </tr> 
                                <?php
                                }//fin foreach 1   
                            }
                            else
                            {

                                $arrayDividido = array_chunk($arrayInstExtUpdate, $numDivisionesIntExterna); 
                                // echo '<pre>';
                                // print_r($arrayDividido);
                                // die();

                                foreach($arrayDividido as $array)
                                {
                                ?>
                                <tr>
                                    <?php   
                                        foreach($array as $inst)
                                        {
                                            if($inst['seleccionado']=='si')
                                            {
                                    ?>
                                            <td>                                        
                                                <label style='font-size:15px;' for="<?=$inst['id']?>"> <?=$inst['nombre']?></label><br>
                                                <input style='align-items:center;font-size:18px;' type="checkbox" id ="<?=$inst['id']?>" name="<?=$inst['code']?>" value="<?=$inst['code']?>" checked="true">
                                            </td>
                                    <?php
                                            }
                                            else
                                            { 
                                    ?>
                                            <td>                                        
                                                <label style='font-size:15px;' for="<?=$inst['id']?>"> <?=$inst['nombre']?></label><br>
                                                <input style='align-items:center;' type="checkbox" id ="<?=$inst['id']?>" name="<?=$inst['code']?>" value="<?=$inst['code']?>" >
                                            </td>
                                    <?php
                                            }
                                        }//fin foreach 2
                                        
                                    ?>
                                </tr> 
                                <?php
                                }//fin foreach 1 
                            }         
                            ?>
                    </table>
                    <?= $form->field($model, 'otra_institucion_externa')->textInput() ?>
                </div>

                <h4><u>Valoración del Caso</u></h4>

                <?= $form->field($model, 'motivo_referencia')->textarea(['rows' => 4]) ?>
                            <!-- <script>
                                    CKEDITOR.replace("decederivacion-motivo_referencia");
                            </script> -->

                <?= $form->field($model, 'historia_situacion_actual')->textarea(['rows' => 4]) ?>
                            <!-- <script>
                                    CKEDITOR.replace("decederivacion-historia_situacion_actual");
                            </script> -->

                <?= $form->field($model, 'accion_desarrollada')->textarea(['rows' => 4]) ?>
                            <!-- <script>
                                    CKEDITOR.replace("decederivacion-accion_desarrollada");
                            </script> -->

                <?= $form->field($model, 'tipo_ayuda')->textarea(['rows' => 4]) ?>
                            <!-- <script>
                                    CKEDITOR.replace("decederivacion-tipo_ayuda");
                            </script> -->

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

</div>

