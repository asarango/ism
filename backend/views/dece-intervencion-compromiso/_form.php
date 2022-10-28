<?php

use backend\models\CurriculoMecBloque;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencionCompromiso */
/* @var $form yii\widgets\ActiveForm */

$fechaActual = date('Y-m-d');
$hora = date('H:i:s');
$arrayTipoCompromiso = array("ESTUDIANTE", "REPRESENTANTE", "DOCENTE", "DECE");
$bloques = CurriculoMecBloque::find()->where(['is_active'=>true])->orderBy('id')->all();


?>

<div class="dece-intervencion-compromiso-form">

    <!-- <?php $form = ActiveForm::begin(); ?>   -->

    <!-- <?= $form->field($model, 'id_dece_intervencion')->textInput(['value' => $id_intervencion]) ?> -->

    <input class="form-control" hidden="false" type="text" id="id_intervencion" name="id_intervencion" value="<?= $id_intervencion ?>">
    <div class="row">

        <div class="col-lg-1">
            <label style='font-size:14px;' for="fecha_max_cumplimiento">Bloque:</label><br>
            <select id="bloque" name="bloque" class="form-select" aria-label="Default select example">
                <?php               
                foreach ($bloques as $item) {
                ?>
                    <option value="<?= $item->shot_name ?>"><?= $item->shot_name ?></option>
                <?php                   
                }
                ?>
            </select>
        </div>
        <div class="col-lg-2">
            <label style='font-size:14px;' for="fecha_max_cumplimiento">Compromiso de:</label><br>
            <select id="tipo_compromiso" name="tipo_compromiso" class="form-select" aria-label="Default select example" style="width: 150px;">
                <?php
                $cont = 0;
                foreach ($arrayTipoCompromiso as $array) {
                ?>
                    <option value="<?= $arrayTipoCompromiso[$cont] ?>"><?= $arrayTipoCompromiso[$cont] ?></option>
                <?php
                    $cont = $cont + 1;
                }
                ?>
            </select>
        </div>
        <div class="col-lg-7">
            <label style='font-size:14px;' for="text_compromiso">Descripción Compromiso:</label><br>
            <textarea class="form-control" id="text_detalle" name="text_detalle" aria-label="With textarea" rows="1"></textarea>
        </div>
        <div class="col-lg-2">
            <!-- <?= $form->field($model, 'fecha_max_cumplimiento')->textInput() ?> -->
            <label style='font-size:14px;' for="fecha_max_cumplimiento">Fecha Cumplimiento:</label><br>
            <input type="date" id="fecha_max_cumplimiento" class="form-control" name="fecha_max_cumplimiento" require="true" value="<?= $fechaActual; ?>">            
        </div>
    </div>
    <br>
    <!-- <?= $form->field($model, 'revision_compromiso')->hiddenInput(['maxlength' => true])->label(false) ?> -->
    <!-- <?= $form->field($model, 'esaprobado')->checkbox() ?> -->

    <!-- <div class="form-group">
        <?= Html::submitButton('Guardar123', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?> -->
    <button class="bnt btn-primary" onclick="guardar()">
        Guardar
    </button>
     <!-- SE MOSTRARA UNA TABLA ACORDE EL TIPO DE INFORMACION A LLENAR -->

     <hr>
     <div id="tabla_compromisos">

     </div>

</div>
<script>
    $(window).on("load",function(){
        muestraTablaCompromiso();
    });
    function guardar()
    {
       
        var bloque = $('#bloque').val();
        var tipo_compromiso = $('#tipo_compromiso').val();
        var detalle = $('#text_detalle').val();
        var fecha_compromiso = $('#fecha_max_cumplimiento').val();
        var id_intervencion = $('#id_intervencion').val();

        var url = '<?= Url::to(['dece-intervencion-compromiso/create']) ?>';
    
        var params = {
                bloque: bloque,
                tipo_compromiso : tipo_compromiso,
                detalle : detalle,
                fecha_compromiso : fecha_compromiso,
                id_intervencion :id_intervencion,
            };
            
            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (response) {
                    muestraTablaCompromiso();
                }
            })

    }

</script>
