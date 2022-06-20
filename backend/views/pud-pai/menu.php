<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<ul>
    <li>
        <b>1.- DATOS INFORMATIVOS</b>
        <ul>
            <li class="zoom"><a href="#" onclick="ver_detalle('1.1.-');">1.1.- Ver datos</a></li>
        </ul>
    </li>
    <hr>
    
    <li>
        <b>2.- INDAGACIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('2.1.-');">2.1.- Conceptos y enunciado</a></li>
            <!-- <li class="zoom"><a href="#" onclick="ver_detalle('enunciado');">Enunciado</a></li> -->
            <li class="zoom"><a href="#" onclick="ver_detalle('2.3.-');">2.3.-Preguntas</a></li>                                
        </ul>
    </li>
    <hr>

    <li>
        <b>3.- EVALUACIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('3.1.-');">3.1.-Evaluación</a></li>
        </ul>
    </li>
    <hr>

    <li>
        <b>4.- ENFOQUES DEL APRENDIZAJE</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('4.1.-');">4.1.- Grupo de habilidades</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('4.2.-');">4.2.- Aspecto del Objetivo</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('4.3.-');">4.3.- Indicador de habilidad</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('4.4.-');">4.4.- Como se enseñará</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('4.5.-');">4.5.- Perfil BI</a></li>
        </ul>
    </li>
    <hr>

    <li>
        <b>5.- ACCIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('5.1.-');">5.1.- Acción</a></li>            
        </ul>
    </li>
    <hr>

    <li>
            <b>6.- SERVICIO COMO ACCIÓN</b>
            <ul>                                
                <li class="zoom"><a href="#" onclick="ver_detalle('6.1.-');">6.1.- Servicio</a></li>            
            </ul>
    </li>
    <hr>

    <li>
        <b>7.- RECURSOS</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('7.1.-');">7.1.- Recursos</a></li>            
        </ul>
    </li>
    <hr>

    <li>
        <b>8.- REFLEXIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('8.1.-');">8.1.- Reflexión</a></li>            
        </ul>
    </li>
    <hr>
</ul>

<script>
    function ver_detalle( pestana ){
        var planUnidadId = '<?= $planUnidad->id ?>';        
        var url = '<?= Url::to(['pestana']) ?>';

        var params = {
            plan_unidad_id  : planUnidadId,
            pestana         : pestana
        };

        $.ajax({
            data    : params,
            url     : url,
            type    : 'GET',
            beforeSend: function(){},
            success : function(response){
                $("#div-detalle").html(response);
                
                if(pestana == 'preguntas'){                 
                    muestra_preguntas();
                }else if(pestana == 'evaluacion'){
                    show_sumativas_evaluaciones();
                    show_sumativas_evaluaciones2();
                }else if(pestana == 'como_se_ensenara'){
                    show_ensenara();
                }else if(pestana == 'perfil_bi'){
                    show_perfiles_seleccionados();
                }else if(pestana == 'recursos'){
                    show_recursos();
                }else if(pestana == 'reflexion'){
                    show_reflexion_seleccionados();
                }else if(pestana=='servicio_accion'){
                    show_servicios_accion_disponibles();
                    show_servicios_accion_seleccionadas();
                }
            }
        });
        
    }
</script>