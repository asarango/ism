<?php

use backend\models\DeceAsistente;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use backend\models\DeceMotivos;
use backend\models\DeceRegistroSeguimiento;
use backend\models\OpParent;
use backend\models\OpStudent;
use backend\models\ResPartner;
use backend\models\PlanificacionOpciones;
use backend\models\helpers\Scripts;


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
        $array[$dato[$campo]]=$dato[$campo];
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
->orderBy(['estado'=>SORT_DESC,'fecha_inicio'=>SORT_ASC])
->all();





//extrae usuarios del sistema, para mosrtrar en atendido por 
$objScript = new Scripts();
$usuarios = $objScript->mostrarUsuarioParaDece();
$arrayUsuario= array();
//recorremos arreglo
foreach ($usuarios as $usu) {
    $arrayUsuario[$usu['usuario']]=$usu['usuario'];
}

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
                            <td><?= $modelEstudiante->first_name . ' ' . $modelEstudiante->middle_name . ' ' . $modelEstudiante->last_name ?></td>
                        </tr>
                        <tr>
                            <td><b>Fecha Nacimiento: </b></td>
                            <td><?= $modelEstudiante->birth_date ?></td>
                        </tr>                       
                        <tr>
                            <td><b>Representante: </b></td>
                            <td><?= $modelRepresentante->name ?></td>
                        </tr>                       
                    </table>
                    <h3 style="color:red">Hist??rico Seguimientos</h3>
                    <div style="overflow-x:scroll;overflow-y:scroll;" >                        
                        <table class="table table-success table-striped table-bordered my-text-small">
                            <tr class="table-primary">
                                <td>No.</td>
                                <td>Fecha Creaci??n</td>
                                <td>??ltima Modificaci??n</td>
                                <td>Estado</td>
                                <td>Motivo</td>
                                <td>Editar</td>
                                <td>Ver</td>
                            </tr>
                            <?php if ($modelRegSeguimiento) {
                                foreach ($modelRegSeguimiento as $modelReg) {
                            ?>
                                    <tr>
                                        <td><?= $modelReg->id ?></td>
                                        <td><?= substr($modelReg->fecha_inicio,0,10) ?></td>
                                        <td><?= substr($modelReg->fecha_fin,0,10) ?></td>
                                        <td><?= $modelReg->estado ?></td>
                                        <td><?= $modelReg->motivo ?></td>
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
                                                                <td><b>Fecha Creaci??n: </b></td>
                                                                <td><?= substr($modelReg->fecha_inicio, 0, 10) ?></td>
                                                            </tr>                                                            
                                                            <tr>
                                                                <td><b>??ltima Modificaci??n: </b></td>
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
                    <h3 style="color:blueviolet"><b>Seguimiento</b></h3>
                    <div class="dece-registro-seguimiento-form">

                        <?php $form = ActiveForm::begin(); 
                         
                        ?>                        

                        <?= $form->field($model, 'id_estudiante')->hiddenInput(['value' => $model->id_estudiante])->label(false); ?>

                        <?= $form->field($model, 'id_clase' )->hiddenInput(['value' =>$model->id_clase])->label(false); ?>

                        <?= $form->field($model, 'id_caso' )->hiddenInput(['value' =>$model->id_caso])->label(false); ?>
                       

                        <?php if($model->isNewRecord){ ?>
                             <?= $form->field($model, 'fecha_inicio')->textInput(['type' => 'date' ]) ?>
                             <?= $form->field($model, 'fecha_fin')->hiddenInput()->label(false) ?>
                        <?php } else {?>
                           <?= $form->field($model, 'fecha_fin')->textInput(['type' => 'date' ]) ?>
                           <?= $form->field($model, 'fecha_inicio')->hiddenInput()->label(false) ?>
                         <?php }?>
                       
                        <?= $form->field($model, 'estado')->dropDownList($arrayEstado, ['prompt' => 'Seleccione Estado']) ?>

                        <?= $form->field($model, 'motivo')->dropDownList($arrayMotivos, ['prompt' => 'Seleccione Motivo']) ?>                

                        <?= $form->field($model, 'atendido_por')->dropDownList($arrayUsuario,['prompt' => 'Seleccione Opci??n']) ?>

                        <?= $form->field($model, 'responsable_seguimiento')->dropDownList($arrayResponsableSeg, ['prompt' => 'Seleccione Opci??n']) ?>

                        <?= $form->field($model, 'pronunciamiento')->textarea() ?>
                            <script>
                                    CKEDITOR.replace("deceregistroseguimiento-pronunciamiento");
                            </script>
                        <?= $form->field($model, 'acuerdo_y_compromiso')->textarea() ?>
                            <script>
                                CKEDITOR.replace("deceregistroseguimiento-acuerdo_y_compromiso");
                            </script>
                        <?= $form->field($model, 'eviencia')->textarea() ?>
                            <script>
                                CKEDITOR.replace("deceregistroseguimiento-eviencia");
                            </script>
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