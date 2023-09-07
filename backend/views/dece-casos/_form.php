<?php

use backend\models\DeceCasos;
use yii\jui\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\DeceMotivos;
use backend\models\DeceRegistroSeguimiento;
use backend\models\PlanificacionOpciones;


/* @var $this yii\web\View */
/* @var $model backend\models\DeceCasos */
/* @var $form yii\widgets\ActiveForm */
//*** motivos */ 
$modelPathArchivo = PlanificacionOpciones::find()
    ->where(['tipo' => 'VER_ARCHIVO'])
    ->andWhere(['categoria' => 'PATH_DECE_SEG'])
    ->one();

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

$ahora = date('Y-m-d H:i:s');

// echo "<pre>";
// print_r($arrayEstado);
// die();



?>
<script src="//cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>

<div class="dece-casos-form col-lg-12 col-ms-12">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    if ($model->isNewRecord) { ?>
        <label for="exampleInputEmail1" class="form-label">Fecha Creación</label>
        <input type="date" id="fecha_inicio" class="form-control" name="fecha_inicio" require="true"
            value="<?= $model->fecha_inicio; ?>">

        <?= $form->field($model, 'fecha_fin')->hiddenInput()->label(false) ?>
        <?php
    } else { ?>
        <?= $form->field($model, 'fecha_inicio')->hiddenInput()->label(false) ?>

        <label for="exampleInputEmail2" class="form-label">Fecha Actualización</label>
        <input type="date" id="fecha_fin" class="form-control" name="fecha_fin" require="true"
            value="<?= $model->fecha_fin; ?>">
        <?php
    }
    ?>

    <?= $form->field($model, 'numero_caso')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'id_estudiante')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'id_clase')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'id_periodo')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'estado')->dropDownList(
        $arrayEstado,
        ['options' => ['PENDIENTE' => ['selected' => true]]]
    ) ?>

    <?= $form->field($model, 'motivo')->dropDownList($arrayMotivo, ['prompt' => 'Selecione Opción']) ?>

    <?= $form->field($model, 'detalle')->textarea(['rows' => 4]) ?>
    <!-- <script>
                    CKEDITOR.replace("dececasos-detalle");
                </script> -->
    <?= $form->field($model, 'id_usuario')->hiddenInput()->label(false) ?>
    <br>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>