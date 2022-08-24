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
// //busca todos los casos del estudiante
// $modelCasosHist = DeceCasos::find()
//     ->where(['id_estudiante' => $model->id_estudiante])
//     ->andWhere(['id_periodo' => $model->id_periodo])
//     ->all();
  
// //buscamos el numero de seguimientos que tiene el alumno
// $modelRegSeguimiento = DeceRegistroSeguimiento::find()
//     ->where(['id_caso' => $model->id])
//     ->orderBy(['estado' => SORT_DESC, 'fecha_inicio' => SORT_ASC])
//     ->all();

$ahora = date('Y-m-d H:i:s');

?>
<script src="//cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>

    <div class="dece-casos-form col-lg-12 col-ms-12">

        <?php $form = ActiveForm::begin(); ?>

 

        <label for="exampleInputEmail1" class="form-label">FECHA DE PRESENTACION</label>
        <input type="date" class="form-control" name="fecha_presentacion" require="true" value="<?=$ahora?>">

        <?php
        if ($model->isNewRecord) { ?>
            <?= $form->field($model, 'fecha_inicio')->textInput(['value'=>$ahora]) ?>

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
d
        <?= $form->field($model, 'id_usuario')->hiddenInput()->label(false) ?>
        <br>

        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>