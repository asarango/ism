<?php

use backend\models\DeceAsistente;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\DeceMotivos;
use backend\models\DeceRegistroAgendamientoAtencion;
use backend\models\DeceRegistroSeguimiento;
use backend\models\OpParent;
use backend\models\OpStudent;
use backend\models\ResPartner;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\helpers\Scripts;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroSeguimiento */
/* @var $form yii\widgets\ActiveForm */

$DateAndTime = date('m-d-Y h:i:s a', time());


/*** R1 */
$modelRegAgendamiento = DeceRegistroAgendamientoAtencion::find()
    ->where(['id_reg_seguimiento' => $model->id])
    ->one();

$modelAsistentes = new DeceAsistente();
if ($modelRegAgendamiento) {
    $modelAsistentes = DeceAsistente::find()->where(['id_dece_reg_agend_atencion' => $modelRegAgendamiento->id])->all();
} else {
    $modelRegAgendamiento = new DeceRegistroAgendamientoAtencion();
}
/*** FIN R1 */
//*** motivos */ 
$motivos = DeceMotivos::find()
    ->select(['motivo',])
    ->from('dece_motivos')
    ->distinct()
    ->asArray()
    ->all();

$arrayMotivos = array();
//recorremos arreglo
foreach ($motivos as $motivo) {
    $arrayMotivos[$motivo['motivo']]=$motivo['motivo'];
}
//***  submotivos */ 
$submotivos = (new yii\db\Query())
    ->select(['submotivo',])
    ->from('dece_motivos')
    ->distinct()
    ->all();

$arraySubMotivos = array();
//recorremos arreglo
foreach ($submotivos as $motivo) {
   $arraySubMotivos[$motivo['submotivo']]=$motivo['submotivo'];
}
//** estado */  
$arrayEstado = array(
    'PENDIENTE' => 'PENDIENTE',
    'FINALIZADO' => 'FINALIZADO',
    'NO ASISTIO' => 'NO ASISTIO'
);
//** atencion */ 
$arrayAtencionPara = array(
    'PADRE' => 'PADRE',
    'MADRE' => 'MADRE',
    'ESTUDIANTE' => 'ESTUDIANTE',
    'DOCENTE' => 'DOCENTE'
);
//** responsable seguimiento */
$arrayResponsableSeg = array(
    'RECTORADO' => 'RECTORADO',
    'INSPECCIÓN' => 'INSPECCIÓN',
    'COORDINACIÓN' => 'COORDINACIÓN',
    'SUBCOORDINACIÓN' => 'SUBCOORDINACIÓN',
    'JEFATURA DE ÁREA' => 'JEFATURA DE ÁREA',
    'DECE' => 'DECE',
    'DOCENTE' => 'DOCENTE',
    'SECRETARIA' => 'SECRETARIA',
    'SOPORTE / ATENCIÓN AL CLIENTE' => 'SOPORTE / ATENCIÓN AL CLIENTE'
);

//llamo a grupo para buscar id alumno e id clase, $id_grupo es parametro de entrada
$modelGrupo = ScholarisGrupoAlumnoClase::findOne($id_grupo);
$id_student = $modelGrupo->alumno->id;
$id_clase = $modelGrupo->clase->id;

$modelEstudiante = OpStudent::findOne($id_student);
$representante = OpParent::findOne($modelEstudiante->x_representante);
$modelRepresentante = ResPartner::findOne($representante->name);

//buscamos el numero de seguimientos que tiene el alumno
$modelRegSeguimiento = DeceRegistroSeguimiento::find()
    ->where(['id_estudiante' => $id_student])
    ->all();
//extrae usuario del sistema 
$objScript = new Scripts();
$usuarios = $objScript->mostrarUsuarioParaDece();
$arrayUsuario= array();
//recorremos arreglo
foreach ($usuarios as $usu) {
    $arrayUsuario[$usu['usuario']]=$usu['usuario'];
}
?>
<div class="comportamiento-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        
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
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>Representante: </b></td>
                            <td><?= $modelRepresentante->name ?></td>
                        </tr>
                        <tr>
                            <td><b>Carnét Discapacidad: </b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>Grupo Sanguineo: </b></td>
                            <td><?= $modelEstudiante->blood_group ?></td>
                        </tr>
                    </table>
                    <h3 style="color:red">Histórico Seguimiento</h3>
                    <div style="overflow-x:hidden;overflow-y:scroll;">                        
                        <table class="table table-success table-striped ">
                            <tr class="table-primary">
                                <td>ID</td>
                                <td>FECHA</td>
                                <td>ESTADO</td>
                                <td>MOTIVO</td>
                                <td>VER</td>
                            </tr>
                            <?php if ($modelRegSeguimiento) {
                                foreach ($modelRegSeguimiento as $modelReg) {
                            ?>
                                    <tr>
                                        <td><?= $modelReg->id ?></td>
                                        <td><?= substr($modelReg->fecha_inicio,0,10) ?></td>
                                        <td><?= $modelReg->estado ?></td>
                                        <td><?= $modelReg->motivo ?></td>
                                        <td>
                                                  <!-- boton llama modal -->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="<?php echo "#staticBackdrop$modelReg->id"; ?>">
                                            <i class="fas fa-glasses"></i>
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="<?php echo "staticBackdrop$modelReg->id"; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel"><b>Ficha Seguimiento: <?= $modelReg->id ?></b></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped table-hover">
                                                            <tr>
                                                                <td><b>Fecha: </b></td>
                                                                <td><?= substr($modelReg->fecha_inicio, 0, 10) ?></td>
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
                                                                <td><b>Submotivo </b></td>
                                                                <td><?= $modelReg->submotivo ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Submotivo2: </b></td>
                                                                <td><?= $modelReg->submotivo2 ?></td>
                                                            </tr>   
                                                            <tr>
                                                                <td><b>Solicitante: </b></td>
                                                                <td><?= $modelReg->persona_solicitante ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Atendido Por: </b></td>
                                                                <td><?= $modelReg->atendido_por ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Atención Para: </b></td>
                                                                <td><?= $modelReg->atencion_para ?></td>
                                                            </tr> 
                                                            <tr>
                                                                <td><b>Responsable Seguimiento</b></td>
                                                                <td><?= $modelReg->responsable_seguimiento ?></td>
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
                    <h3 style="color:blueviolet"><b>Seguimiento</b></h3>
                    <div class="dece-registro-seguimiento-form">

                        <?php $form = ActiveForm::begin(); ?>

                        <?= $form->field($model, 'id_estudiante')->hiddenInput(['value' => $modelEstudiante->id])->label(false); ?>

                        <?= $form->field($model, 'id_clase')->hiddenInput(['value' => $id_clase])->label(false); ?>

                        <?= $form->field($model, 'fecha_inicio')->textInput(['type' => 'date']) ?>

                        <!-- <?= $form->field($model, 'fecha_fin')->textInput(['type' => 'date']) ?> -->

                        <?= $form->field($model, 'estado')->dropDownList($arrayEstado, ['prompt' => 'Seleccione Estado']) ?>

                        <?= $form->field($model, 'motivo')->dropDownList($arrayMotivos, ['prompt' => 'Seleccione Motivo']) ?>

                        <?= $form->field($model, 'submotivo')->dropDownList($arraySubMotivos, ['prompt' => 'Seleccione Submotivo']) ?>

                        <?= $form->field($model, 'submotivo2')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'persona_solicitante')->dropDownList($arrayUsuario,['prompt' => 'Seleccione Opción']) ?>

                        <?= $form->field($model, 'atendido_por')->dropDownList($arrayUsuario,['prompt' => 'Seleccione Opción']) ?>

                        <?= $form->field($model, 'atencion_para')->dropDownList($arrayAtencionPara, ['prompt' => 'Seleccione Opción']) ?>

                        <?= $form->field($model, 'responsable_seguimiento')->dropDownList($arrayResponsableSeg, ['prompt' => 'Seleccione Opción']) ?>


                        <br>
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div>
                                    <?php
                                    if (!$model->isNewRecord) {
                                        echo Html::a(
                                            '<span class="btn btn-primary" style="background-color: #0a1f8f">Agendamiento Atención</span>',
                                            ['dece-registro-agendamiento-atencion/create', 'idSeguimiento' => $model->id],
                                            ['class' => 'link']
                                        );
                                    }
                                    ?>
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