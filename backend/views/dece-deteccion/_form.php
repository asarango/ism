-form dece deteccion
<?php

use backend\models\DeceDeteccion;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\helpers\HelperGeneral;
use backend\models\OpParent;
use backend\models\OpStudent;
use backend\models\ResPartner;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceDeteccion */
/* @var $form yii\widgets\ActiveForm */

// echo '<pre>';
//        print_r($array_datos_estudiante);
//        die();

//Si el arreglo con datos del estudiante, viene vacio

if ($model->isNewRecord && count($array_datos_estudiante) == 0) {
    $array_datos_estudiante['student'] = '';
    $array_datos_estudiante['curso'] = '';
    $array_datos_estudiante['paralelo'] = '';
}
if ($model->isNewRecord) {
    $model->nombre_estudiante = $array_datos_estudiante['student'];
    $model->anio = $array_datos_estudiante['curso'];
    $model->paralelo = $array_datos_estudiante['paralelo'];
    $model->nombre_quien_reporta = $resUser->partner->name;
    $model->cedula = $resUser->partner->numero_identificacion;
    $model->cargo = 'Psicólogo';
}
//llamo a grupo para buscar id alumno e id clase, $id_grupo es parametro de entrada
$modelEstudiante = OpStudent::findOne($model->id_estudiante);
$representante = OpParent::findOne($modelEstudiante->x_representante);
$modelRepresentante = ResPartner::findOne($representante->name);

$modelRegDeteccion = DeceDeteccion::find()
    ->where(['id_caso' => $model->id_caso])
    ->orderBy(['numero_deteccion' => SORT_ASC])
    ->all();

// echo '<pre>';
// print_r($model);
// die
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>



<div class="dece-deteccion-form">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="row">
            <div class="card col-lg-4 col-ms-4" style="margin-bottom: 10px;">
                <!-- RENDERIZA A LA VISTA datos_estudiante.php -->
                <div class="row">
                    <?= $this->render('_datos-estudiante', [
                        'model' => $model,
                        'modelEstudiante' => $modelEstudiante,
                        'modelRepresentante' => $modelRepresentante
                    ]) ?>
                </div>
                <div class="row">
                    <!-- RENDERIZA A LA VISTA historico_deteccion.php -->
                    <?= $this->render('_historico-deteccion', [
                        'model' => $model,
                        'modelRegDeteccion' => $modelRegDeteccion
                    ]) ?>
                </div>

                <div class="card col-lg-8 col-ms-8">

                <?php $form = ActiveForm::begin(); ?>


                <!-- <?= $form->field($model, 'numero_deteccion')->textInput() ?> -->

                <!-- <?= $form->field($model, 'id_estudiante')->textInput() ?> -->

                <!-- <?= $form->field($model, 'id_caso')->textInput() ?> -->

                <!-- <?= $form->field($model, 'numero_caso')->textInput() ?> -->

                <div class=" row ">
                    <h5 style=" color:blueviolet;"><b>DATOS INFORMATIVOS GENERALES</b></h5>

                    <div class="col-lg-6">
                        <?= $form->field($model, 'nombre_estudiante')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'anio')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'paralelo')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5 style="color:blueviolet;">PERSONA QUE REPORTA</h5>
                    <div class="row">
                        <div class="col-lg-5">
                            <label for="exampleInputEmail1" class="form-label">Fecha</label>
                            <input type="date" id="fecha_reporte" class="form-control" name="fecha_reporte"
                                require="true" value="<?= substr($model->fecha_reporte, 0, 10); ?>">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            <?= $form->field($model, 'nombre_quien_reporta')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'cedula')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5 style="color:blueviolet;">DESCRIPCIÓN DEL HECHO (qué paso, quienes se involucran, dónde, cuándo)
                    </h5>
                    <div class="row">
                        <?= $form->field($model, 'hora_aproximada')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'descripcion_del_hecho')->textarea(['rows' => 6]) ?>
                        <!-- <script>
                            CKEDITOR.replace("decedeteccion-descripcion_del_hecho");
                        </script> -->
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'acciones_realizadas')->textarea(['rows' => 6]) ?>
                        <!-- <script>
                            CKEDITOR.replace("decedeteccion-acciones_realizadas");
                        </script> -->
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5 style="color:blueviolet;">ENLISTE LAS EVIDENCIAS</h5>
                    <div class="row">
                        <?= $form->field($model, 'lista_evidencias')->textarea(['rows' => 6]) ?>
                        <script>
                            CKEDITOR.replace("decedeteccion-lista_evidencias");
                        </script>
                    </div>
                </div>


                <!-- <?= $form->field($model, 'path_archivos')->textInput(['maxlength' => true]) ?> -->

                <div class="form-group">
                    <?= Html::submitButton('GUARDAR', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>


            </div>
        </div>
    </div>
</div>