<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaAlumnosNovedades */
/* @var $form yii\widgets\ActiveForm */
?>

<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>

<div class="scholaris-asistencia-alumnos-novedades-form">
    

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'asistencia_profesor_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'comportamiento_detalle_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'observacion')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'grupo_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'es_justificado')->checkbox() ?>

    <?= $form->field($model, 'codigo_justificacion')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?php //$form->field($model, 'acuerdo_justificacion')->textarea(['rows' => 6]) ?>
    
    <br>
    <textarea name="contenido" class="form-control" >
    <?=  
        $model->acuerdo_justificacion ? $model->acuerdo_justificacion : ''; 
    ?>
    </textarea>
    
    <script>
            CKEDITOR.replace( 'contenido',{
                customConfig: '/ckeditor_settings/config.js'                                
                } );
    </script>

    <br>
    <div class="form-group">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
