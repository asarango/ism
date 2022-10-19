<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceDeteccion */
/* @var $form yii\widgets\ActiveForm */

// echo '<pre>';
//        print_r($model);
//        die();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>


<div class="dece-deteccion-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'numero_deteccion')->textInput() ?> -->

    <!-- <?= $form->field($model, 'id_estudiante')->textInput() ?> -->

    <!-- <?= $form->field($model, 'id_caso')->textInput() ?> -->

    <!-- <?= $form->field($model, 'numero_caso')->textInput() ?> -->

    <div class="row" >
        <h6 style="color:blue;">DATOS INFORMATIVOS GENERALES</h6>
         <div class="col-lg-6">
            <?= $form->field($model, 'nombre_estudiante')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'anio')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'paralelo')->textInput(['maxlength' => true]) ?>
         </div>
    </div>
    <br>
    <div class="row">
         <h6 style="color:blue;">PERSONA QUE REPORTA</h6>
        <div class="row">
            <div class="col-lg-5">               
                <label for="exampleInputEmail1" class="form-label">Fecha</label>
                <input type="date" id="fecha_reporte" class="form-control" name="fecha_reporte" require="true" value="<?= substr($model->fecha_reporte,0,10);?>">
                
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <?= $form->field($model, 'nombre_quien_reporta')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'cedula')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        
         
        
    </div>
    <br>
    <div class="row">
        <h6 style="color:blue;">DESCRIPCIÓN DEL HECHO (qué paso, quienes se involucran, dónde, cuándo)</h6>
        <div class="row">
            <?= $form->field($model, 'hora_aproximada')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'descripcion_del_hecho')->textarea(['rows' => 6]) ?>
            <script>
                    CKEDITOR.replace("decedeteccion-descripcion_del_hecho");
            </script>
        </div>
        <div class="row">
            <?= $form->field($model, 'acciones_realizadas')->textarea(['rows' => 6])  ?>
            <script>
                    CKEDITOR.replace("decedeteccion-acciones_realizadas");
            </script>
        </div>
    </div>
    <br>
    <div class="row">
        <h6 style="color:blue;">ENLISTE LAS EVIDENCIAS</h6>
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
