<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\PlanificacionOpciones;
use backend\models\PudPaiController;
use backend\models\PlanificacionVerticalDiploma;
use backend\controllers\HelperPudPaiController;
use backend\models\PudPai;

//busqueda en planificacion_vertical_diploma, para evaluar el porcentaje  

$obj2 = new backend\models\helpers\Scripts();
$pud_dip_porc_avance = $obj2->pud_pai_porcentaje_avance_individual('todos', $planUnidad->id);

$opcion = '1.1.-';

$modelPudPai = PudPai::find()
    ->where(['planificacion_bloque_unidad_id' => $planUnidad->id])
    ->andWhere('ultima_seccion IS NOT NULL')
    ->one();
if ($modelPudPai) {
    $opcion = $modelPudPai->ultima_seccion;
}

$iconoOk = 'fas fa-check';
$colorOk = 'green';
$colorNotOk = 'red';
$iconoColor = 'blue';
?>
<br>
<ul>
    <li>
        <b>1.- DATOS INFORMATIVOS</b>
        <ul>
            <li class="zoom"><a href="#" onclick="ver_detalle('1.1.-');">1.1.- Ver datos
                    <i class="<?= $iconoOk; ?>" title="FALTA INGRESAR DATOS" style="color: green;"></i>
                </a></li>
        </ul>
    </li>
    <hr>

    <li>
        <b>2.- INDAGACIÓN</b>
        <ul>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('2.1.-');">2.1.- Propósito de la Integración
                    <i class="<?= $iconoOk; ?>" title="FALTA INGRESAR DATOS" style="color: green;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('2.2.-');">2.2.- Conceptos Clave
                    <i class="<?= $iconoOk; ?>" title="FALTA INGRESAR DATOS" style="color: green;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('2.3.-');">2.3.- Enunciado de la Indagación
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('2.4.-');">2.4.- Preguntas de Indagación
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('2.5.-');">2.5.- Contexto Global
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
        </ul>
    </li>
    <hr>

    <li>
        <b>3.- ENFOQUES DEL APRENDIZAJE</b>
        <ul>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('3.1.-');">3.1.- Habilidad y Exploración
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <!-- <li class="zoom">
                <a href="#" onclick="ver_detalle('3.2.-');">3.2.- Exploración
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li> -->
            <!-- <li class="zoom">
                <a href="#" onclick="ver_detalle('3.2.-');">3.2.- Actividad
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('3.4.-');">3.3.- Atributo del Perfil
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li> -->
        </ul>
    </li>
    <hr>
    <li>
        <b>4.- OBJETIVOS DE DESARROLLO SOSTENIBLE</b>
        <ul>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('4.1.-');">4.1.-Competencia
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('4.2.-');">4.2.-Actividad
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('4.3.-');">4.3.-Objetivo
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('4.4.-');">4.4.-Relación ODS-IB
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
        </ul>
    </li>
    <hr>

    <li>
        <b>5.- EVALUACIÓN</b>
        <ul>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('5.1.-');">5.1.- Criterios Interdisciplicarios
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('5.2.-');">5.2.- Evaluaciones Formativas Disciplinarias
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('5.3.-');">5.3.- Evaluaciones Formativas Interdiciplinarias
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('5.4.-');">5.4.- Evaluación Sumativa
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
        </ul>
    </li>
    <hr>

    <li>
        <b>6.- ACCIÓN: ENSEÑANZA Y APRENDIZAJE A TRAVÉS DE LA INDAGACIÓN INTERDISCIPLINARIA</b>
        <ul>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('6.1.-');">6.1.- Acción
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
        </ul>
    </li>
    <hr>
    <li>
        <b>7.- PROCESO DE APRENDIZAJE INTERDISCIPLINARIO</b>
        <ul>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('7.1.-');">7.1.- Experiencias de Aprendizaje y Estrategias de Enseñanza Interdisciplinarios
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('7.2.-');">7.2.- Atención a las Necesidades Educativas Especiales
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
        </ul>
    </li>
    <hr>
    <li>
        <b>8.- RECURSOS</b>
        <ul>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('8.1.-');">8.1.- Recursos
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
        </ul>
    </li>
    <hr>

    <li>
        <b>9.- REFLEXIÓN</b>
        <ul>
            <li class="zoom">
                <a href="#" onclick="ver_detalle('9.1.-');">9.1.- Reflexión
                    <i class="<?= $iconoOk; ?>" title="" style="color: <?= $iconoColor; ?>;"></i>
                </a>
            </li>
        </ul>
    </li>
    <hr>
</ul>

<script>
    // despues del código  
    document.body.onload = function() {
        ver_detalle('<?= $opcion ?>')
    }

    function ver_detalle(pestana) 
    {
        var planUnidadId = '<?= $planUnidad->id ?>';
        //valor que esta en un campo oculto
        var idGrupoInter = $("#id_grupo_inter").val();       

        var url = '<?= Url::to(['mostrar-pantallas']) ?>';
        var params = {
            plan_unidad_id: planUnidadId,
            pestana: pestana,
            idgrupointer : idGrupoInter,
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                //alert(response);
                $("#div-detalle").html(response);
                //todos los metodos se encuentran en pud_pai/index
                // if (pestana == '2.3.-') {
                //     muestra_preguntas();
                // } else if (pestana == '4.1.-') {
                //     muestra_evaluacion_relacion();
                //     muestra_evaluacion_sumativa();
                //     muestra_evaluacion_formativa();
                // } else if (pestana == '3.1.-') {

                // } else if (pestana == '7.1.-') {

                // } else if (pestana == '8.1.-') {
                //     show_recursos();
                // } else if (pestana == '9.1.-') {
                //     show_reflexion_seleccionados();
                // } else if (pestana == '6.1.-') {
                //     show_servicios_accion_disponibles();
                //     show_servicios_accion_seleccionadas();
                // }

                // bloquear_div();
            }
        });
    }
    function update_campo(id,pestana)
    {       
        var nuevoDato = CKEDITOR.instances['editor-sumativa2'+id].getData();   
        var url = '<?= Url::to(['update-respuesta']) ?>';
        var planUnidadId = '<?= $planUnidad->id ?>';
        var params = {
            idRespuesta : id,
            nuevoDato: nuevoDato,
            planUnidadId:planUnidadId,
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                ver_detalle(pestana); 
            }
        });
    }
    function guardar_pregunta_reflexion(id_pregunta,tipo_pregunta)
    {      
        var idGrupoInter = $("#id_grupo_inter").val();    
        var url = '<?= Url::to(['guardar-pregunta-reflexion']) ?>';
       
        var params = {
            idGrupoInter : idGrupoInter,
            id_pregunta: id_pregunta,
            tipo_pregunta:tipo_pregunta,
        };        
  
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                $("#table-reflexion-disponibles").html(response);                
              
            }
        });        
    }
    function eliminar_pregunta_reflexion(id_pregunta)
    {
        var idGrupoInter = $("#id_grupo_inter").val();    
        var url = '<?= Url::to(['eliminar-pregunta-reflexion']) ?>';      

        
        var params = {
            idGrupoInter : idGrupoInter,
            id_pregunta: id_pregunta,
        };   
  
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {                                   
                $("#table-reflexion-selecionadas").html(response);    
            }
        });  

    }
    function actualizar_pregunta(id_pregunta)
    {
        var respuesta = $("#respuesta_"+id_pregunta).val();
        var url = '<?= Url::to(['actualizar-pregunta-reflexion']) ?>';
        var params = {
            id_pregunta : id_pregunta,
            respuesta : respuesta,
        };         
  
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {                                   
                //$("#table-reflexion-selecionadas").html(response);    
            }
        });  

    }
    function quitar_agregar_seleccion(bandera,id_Respuesta,pestana)
    {        
        var url = '<?= Url::to(['quitar-agregar-seleccion']) ?>';
        var params = {
            id_Respuesta : id_Respuesta,
            pestana : pestana,
            bandera : bandera,
        };         
  
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {                                   
                ver_detalle(pestana);  
            }
        }); 

    }
    function actualizar_pregunta_opciones(id_pregunta)
    {
        var respuesta = $("#respuesta_op"+id_pregunta).val();
        var url = '<?= Url::to(['actualizar-pregunta-opciones']) ?>';
        var params = {
            id_pregunta : id_pregunta,
            respuesta : respuesta,
        };         

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {                                   
                //$("#table-reflexion-selecionadas").html(response);    
            }
        });  

    }
    function recargar_pagina(){
        //location.reload();      
        
    }
</script>