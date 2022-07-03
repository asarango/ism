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

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroSeguimiento */
/* @var $form yii\widgets\ActiveForm */

$DateAndTime = date('m-d-Y h:i:s a', time());

$modelRegAgendamiento = DeceRegistroAgendamientoAtencion::find()
    ->where(['id_reg_seguimiento' => $model->id])
    ->one();
$modelAsistentes = new DeceAsistente();
if ($modelRegAgendamiento) {
    $modelAsistentes = DeceAsistente::find()->where(['id_dece_reg_agend_atencion' => $modelRegAgendamiento->id])->all();
} else {
    $modelRegAgendamiento = new DeceRegistroAgendamientoAtencion();
}
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
    array_push($arrayMotivos, $motivo['motivo']);
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
    array_push($arraySubMotivos, $motivo['submotivo']);
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

$id_student = $id_estudiante;
$modelEstudiante = OpStudent::findOne($id_student);
$representante = OpParent::findOne($modelEstudiante->x_representante);
$modelRepresentante = ResPartner::findOne($representante->name);
?>
<div class="comportamiento-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-9 col-md-9">
            <div class="row p-4 ">
                <div class="card col-lg-4 col-ms-4">
                    <!-- RENDERIZA A LA VISTA datos_estudiante.php -->
                    <h3><b>Datos Estudiante</b></h3>
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
                            <td><?= $modelEstudiante->birth_date ?></td>
                        </tr>
                        <tr>
                            <td><b>Representante: </b></td>
                            <td><?= $modelRepresentante->name ?></td>
                        </tr>
                        <tr>
                            <td><b>Carnét Discapacidad: </b></td>
                            <td><?= $modelEstudiante->blood_group ?></td>
                        </tr>
                        <tr>
                            <td><b>Grupo Sanguineo: </b></td>
                            <td><?= $modelEstudiante->blood_group ?></td>
                        </tr>
                    </table>
                </div>
                <div class="card col-lg-8 col-ms-8">
                    <h3><b>Seguimiento</b></h3>
                    <div class="dece-registro-seguimiento-form">

                        <?php $form = ActiveForm::begin(); ?>

                        <?= $form->field($model, 'id_estudiante')->hiddenInput(['value' => $modelEstudiante->id])->label(false); ?>

                        <?= $form->field($model, 'fecha_inicio')->textInput(['type' => 'date']) ?>

                        <!-- <?= $form->field($model, 'fecha_fin')->textInput(['type' => 'date']) ?> -->

                        <?= $form->field($model, 'estado')->dropDownList($arrayEstado, ['prompt' => 'Seleccione Estado']) ?>

                        <?= $form->field($model, 'motivo')->dropDownList($arrayMotivos, ['prompt' => 'Seleccione Motivo']) ?>

                        <?= $form->field($model, 'submotivo')->dropDownList($arraySubMotivos, ['prompt' => 'Seleccione Submotivo']) ?>

                        <?= $form->field($model, 'submotivo2')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'persona_solicitante')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'atendido_por')->textInput(['maxlength' => true]) ?>

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
                                                ['dece-registro-agendamiento-atencion/create','idSeguimiento' => $model->id],
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
</div>