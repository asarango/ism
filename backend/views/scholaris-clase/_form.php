<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ScholarisMateria;
use backend\models\ScholarisMalla;
use backend\models\ScholarisMallaCurso;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClase */
/* @var $form yii\widgets\ActiveForm */

$periodoId = Yii::$app->user->identity->periodo_id;
$institutoId = Yii::$app->user->identity->instituto_defecto;

$modelPerido = backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();



?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="scholaris-clase-form" style="margin-top: 20px; margin-bottom: 20px; padding-left: 50px; padding-right: 50px">
    
    <?= Html::beginForm(['create'], 'post') ?>
    
    <label class="">Curso:</label>
    
    <select name="curso" class="form-control" onchange="muestraOpciones(this)" required="">
        <option>Seleccione curso...</option>
        <?php
        foreach ($modelCursos as $curso){
            ?>
        <option value="<?= $curso['id'] ?>"><?= $curso['curso'] ?></option>
        <?php
        }
        ?>
    </select>
    
    <label class="">Paralelo:</label>
    <select id="select-paralelo" class="form-control" name="paralelo_id" required=""></select>
    
    <label class="">Asignatura:</label>
    <select id="select-materia" class="form-control" name="ism_area_materia_id" required=""></select>
    
    <label class="">Docente:</label>
    <select id="select-docente" class="form-control" name="idprofesor" required=""></select>
    
    <label class="">Horario:</label>
    <select id="select-horario" class="form-control" name="asignado_horario" required=""></select>
    
    <label class="">Tipo de bloque:</label>
    <select id="select-uso-bloque" class="form-control" name="tipo_usu_bloque" required=""></select>
    
    <label class="">Asignar compañeros del mismo paralelo?:</label>
    <select class="form-control" name="todo_alumnos" required="">
        <option value="1">SI</option>
        <option value="0">NO</option>
    </select>
    
    <label class="">Rector:</label>
    <select id="select-rector" class="form-control" name="rector_id" required=""></select>
    
    <label class="">Coordinador dece:</label>
    <select id="select-dece" class="form-control" name="coordinador_dece_id" required=""></select>
    
    <label class="">Secretaria:</label>
    <select id="select-secretaria" class="form-control" name="secretaria_id" required=""></select>
    
    <label class="">Coordinador académico:</label>
    <select id="select-coordinador" class="form-control" name="coordinador_academico_id" required=""></select>
    
    <label class="">Inspector:</label>
    <select id="select-inspector" class="form-control" name="inspector_id" required=""></select>
    
    <label class="">Dece DHI:</label>
    <select id="select-dhi" class="form-control" name="dece_dhi_id" required=""></select>
    
    <label class="">Tutor:</label>
    <select id="select-tutor" class="form-control" name="tutor_id" required=""></select>
    
    <br>
    <?= Html::submitButton('Grabar', ['class' => 'btn btn-outline-success']) ?>
    <?= Html::endForm() ?>


</div>


<script>
    function muestraOpciones(obj){
        var opCourseTemplateId = obj.value;
        muestraParalelos(opCourseTemplateId);
        muestraIsmAreaMateria(opCourseTemplateId);
        muestraDocente();
        muestraHorario();
        muestraUso();
        muestraAutoridades();
    }
    
    function muestraParalelos(opCourseTemplateId){        
        var url = '<?= Url::to(['ajax']) ?>';        
        var params = {
            op_course_template_id : opCourseTemplateId,
            bandera : 'paralelo'
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                       $('#select-paralelo').html(response); 
                    }
        });
    }
    
    function muestraIsmAreaMateria(opCourseTemplateId){        
        var url = '<?= Url::to(['ajax']) ?>';        
        var params = {
            op_course_template_id : opCourseTemplateId,
            bandera : 'materia'
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                       $('#select-materia').html(response); 
                    }
        });
    }
    
    function muestraDocente(){
        var url = '<?= Url::to(['ajax']) ?>';        
        var params = {            
            bandera : 'docente'
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                       $('#select-docente').html(response); 
                    }
        });
    }
    
    function muestraHorario(){
        var url = '<?= Url::to(['ajax']) ?>';        
        var params = {            
            bandera : 'horario'
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                       $('#select-horario').html(response); 
                    }
        });
    }
    
    function muestraUso(){
        var url = '<?= Url::to(['ajax']) ?>';        
        var params = {            
            bandera : 'uso'
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                       $('#select-uso-bloque').html(response); 
                    }
        });
    }
    
    function muestraAutoridades(){
        var url = '<?= Url::to(['ajax']) ?>';        
        var params = {            
            bandera : 'autoridades'
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                       $('#select-rector').html(response); 
                       $('#select-dece').html(response); 
                       $('#select-secretaria').html(response); 
                       $('#select-coordinador').html(response); 
                       $('#select-inspector').html(response); 
                       $('#select-dhi').html(response); 
                       $('#select-tutor').html(response); 
                    }
        });
    }
</script>