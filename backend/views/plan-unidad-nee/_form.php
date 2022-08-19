<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanUnidadNee */
/* @var $form yii\widgets\ActiveForm */
?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="plan-unidad-nee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nee_x_unidad_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'curriculo_bloque_unidad_id')->hiddenInput()->label(false) ?>
   
    <?= $form->field($model, 'destrezas')->textarea(['rows' => 6]) ?>
   
    <?= $form->field($model, 'actividades')->textarea(['rows' => 6]) ?>
   
    <?= $form->field($model, 'recursos')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'indicadores_evaluacion')->textarea(['rows' => 6]) ?>

    <?php 
    if (false){
    ?>

    <?= $form->field($model, 'tecnicas_instrumentos')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'detalle_pai_dip')->textarea(['rows' => 6]) ?>
    <?php 
    }
    ?>
    <br>

    <div class="form-group">
        <?= Html::submitButton('GUARDAR', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'], // toggled buttons
        ['blockquote', 'code-block'],

        [{'header': 1}, {'header': 2}], // custom button values
        [{'list': 'ordered'}, {'list': 'bullet'}],
        [{'script': 'sub'}, {'script': 'super'}], // superscript/subscript
        [{'indent': '-1'}, {'indent': '+1'}], // outdent/indent
        [{'direction': 'rtl'}], // text direction

        [{'size': ['small', false, 'large', 'huge']}], // custom dropdown
        [{'header': [1, 2, 3, 4, 5, 6, false]}],

        [{'color': []}, {'background': []}], // dropdown with defaults from theme
        [{'font': []}],
        [{'align': []}],

        ['clean'], // remove formatting button
        ['video']                                         // remove formatting button
    ];

    var quillExp = new Quill('#editor-experiencia', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });
</script> 
