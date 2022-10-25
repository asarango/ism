<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencionCompromiso */
/* @var $form yii\widgets\ActiveForm */

$fechaActual = date('Y-m-d');
$hora = date('H:i:s');
$arrayTipoCompromiso = array("ESTUDIANTE","REPRESENTANTE","DOCENTE","DECE");

?>

<div class="dece-intervencion-compromiso-form">

    <?php $form = ActiveForm::begin(); ?>
    <label style='font-size:14px;' for="fecha_max_cumplimiento">Compromiso de Parte de:</label><br>
    <select name="select" class="form-select" aria-label="Default select example">
            <?php
            $cont =0;
            foreach($arrayTipoCompromiso as $array)
            { 
            ?>   
                <option value="tipo_compromiso"><?=$arrayTipoCompromiso[$cont] ?></option> 
            <?php
                $cont =$cont+1;
            }
            ?>
    </select>

   

    <?= $form->field($model, 'id_dece_intervencion')->hiddenInput(['value'=>$id_intervencion])->label(false) ?>
    <div class="row">
            <div class="col-lg-8">
                <label style='font-size:14px;' for="text_compromiso">Detalle:</label><br>
                <input type="textarea" id="text_compromiso" class="form-control" name="text_compromiso" require="true" value="">
      
            </div>
            <div class="col-lg-4">
                <!-- <?= $form->field($model, 'fecha_max_cumplimiento')->textInput() ?> -->

                <?php
                    if ($model->isNewRecord) 
                    {                                   
                ?>
                    <label style='font-size:14px;' for="fecha_max_cumplimiento">Fecha Máxima de Cumplimiento</label><br>
                    <input type="date" id="fecha_max_cumplimiento" class="form-control" name="fecha_max_cumplimiento" require="true" value="<?= $fechaActual; ?>">
                <?php
                    }else{                                           
                ?>
                    <label style='font-size:14px;' for="fecha_max_cumplimiento">Fecha Máxima de Cumplimiento</label><br>
                    <input type="date" id="fecha_max_cumplimiento" class="form-control" name="fecha_max_cumplimiento" require="true" value="<?= $fechaActual; ?>">
                <?php
                    }                                           
                ?>
            </div>        
    </div>
    <br>
    <?= $form->field($model, 'revision_compromiso')->hiddenInput(['maxlength' => true])->label(false) ?>

    <!-- <?= $form->field($model, 'esaprobado')->checkbox() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
