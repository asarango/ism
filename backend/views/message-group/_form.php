<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MessageGroup */
/* @var $form yii\widgets\ActiveForm */

$list = ArrayHelper::map($source, 'id', 'nombre');

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<div class="message-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'scholaris_periodo_id')->hiddenInput(['value' => $periodoId])->label(false) ?>
    <?= $form->field($model, 'source_table')->hiddenInput(['value' => $catego])->label(false) ?>

    <div class="form-group" style="margin-top: 25px;">
            <label class="label" for="source_id">Recurso:</label>
            <select name="source_id" onchange="showDescriptor()" id="cource_id" 
                    class="form-control select2 select2-hidden-accessible" 
                    style="width: 99%;" tabindex="-1" aria-hidden="true">
                <option selected="selected" value="">Escoja recurso...</option>
                <?php
                foreach ($source as $s) {
                    echo '<option value="' . $s['id'] . '">' . $s['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= 
        $form->field($model, 'tipo')->dropDownList([
            'PADRES'        => 'PADRES',
            'ESTUDIANTES'   => 'ESTUDIANTES',
            'DOCENTES'      => 'DOCENTES',
            'TODOS'         => 'TODOS'
        ]) 
    ?>

    <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'estado')->checkbox(['checked' => 'checked']);
        }else{
            echo $form->field($model, 'estado')->checkbox();
        }
        
    ?>

    <div class="form-group">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success', 'style' => 'margin-top: 10px; margin-bottom: 10px']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    buscador();

    function buscador() {
        $('.select2').select2({
            closeOnSelect: true
        });
    }
</script>