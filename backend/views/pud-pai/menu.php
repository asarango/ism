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
$pud_dip_porc_avance = $obj2->pud_pai_porcentaje_avance_individual('todos',$planUnidad->id);


$opcion = '1.1.-';

$modelPudPai = PudPai::find()
->where(['planificacion_bloque_unidad_id'=>$planUnidad->id])
->andWhere('ultima_seccion IS NOT NULL')
->one();
if($modelPudPai )
{
    $opcion = $modelPudPai->ultima_seccion;
}

$iconoOk = 'fas fa-check';
$colorOk = 'green';
$colorNotOk = 'red';

?>
<ul>
    <li>      
        <b>1.- DATOS INFORMATIVOS</b>
        <ul>
            <li class="zoom"><a href="#" onclick="ver_detalle('1.1.-');">1.1.- Ver datos
                <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: green;"></i>
            </a></li>
        </ul>
    </li>
    <hr>
    
    <li>
        <b>2.- INDAGACIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('2.1.-');">2.1.- Conceptos y enunciado
                <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: green;"></i>
            </a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('2.3.-');">2.3.-Preguntas
            <?php
                    if ($pud_dip_porc_avance['dos']==0 )
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="" style="color: <?=$iconoColor;?>;"></i>
                    </a>   
            </li>                              
        </ul>
    </li>
    <hr>

    <li>
        <b>3.- EVALUACIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('3.1.-');">3.1.-Evaluación
            <?php
                    if ($pud_dip_porc_avance['tres']==0 )
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="" style="color: <?=$iconoColor;?>;"></i>
                    </a>   
            </li>
        </ul>
    </li>
    <hr>

    <li>
        <b>4.- ENFOQUES DEL APRENDIZAJE</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('4.1.-');">4.1.- Grupo de habilidades
                <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: green;"></i>
            </a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('4.2.-');">4.2.- Aspecto del Objetivo
                <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: green;"></i>
            </a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('4.3.-');">4.3.- Indicador de habilidad
                <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: green;"></i>
            </a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('4.4.-');">4.4.- Habilidades<?php
                    if ($pud_dip_porc_avance['cuatro_cuatro']==0 )
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="" style="color: <?=$iconoColor;?>;"></i>
                    </a>   
            </li>
            <li class="zoom"><a href="#" onclick="ver_detalle('4.5.-');">4.5.- Perfil BI
                    <?php
                    if ($pud_dip_porc_avance['cuatro_cinco']==0 )
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="" style="color: <?=$iconoColor;?>;"></i>
                    </a>   
            </li>
        </ul>
    </li>
    <hr>

    <li>
        <b>5.- ACCIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('5.1.-');">5.1.- Acción
                <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: green;">
            </i>
            </a></li>            
        </ul>
    </li>
    <hr>

    <li>
            <b>6.- SERVICIO COMO ACCIÓN</b>
            <ul>                                
                <li class="zoom"><a href="#" onclick="ver_detalle('6.1.-');">6.1.- Servicio
                <?php
                    if ($pud_dip_porc_avance['seis']==0 )
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="" style="color: <?=$iconoColor;?>;"></i>
                    </a>   
            </li>            
            </ul>
    </li>
    <hr>

    <li>
        <b>7.- RECURSOS</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('7.1.-');">7.1.- Recursos
            <?php
                    if ($pud_dip_porc_avance['siete']==0 )
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="" style="color: <?=$iconoColor;?>;"></i>
                    </a>   
            </li>        
        </ul>
    </li>
    <hr>

    <li>
        <b>8.- REFLEXIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('8.1.-');">8.1.- Reflexión
            <?php
                    if ($pud_dip_porc_avance['ocho']==0 )
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="" style="color: <?=$iconoColor;?>;"></i>
                    </a>   
            </li>         
        </ul>
    </li>
    <hr>
</ul>

<script>
     // despues del código  
   document.body.onload = function() {
        ver_detalle( '<?= $opcion?>')      
    } 

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
                //todos los metodos se encuentran en pud_pai/index
                if(pestana == '2.3.-'){                 
                    muestra_preguntas();
                }else if(pestana == '3.1.-'){
                    show_sumativas_evaluaciones();
                    show_sumativas_evaluaciones2();
                    bloquear_div();
                }else if(pestana == '4.4.-'){
                    show_ensenara();
                }else if(pestana == '4.5.-'){
                    show_perfiles_seleccionados();
                }else if(pestana == '7.1.-'){
                    show_recursos();
                }else if(pestana == '8.1.-'){
                    show_reflexion_seleccionados();
                }else if(pestana=='6.1.-'){
                    show_servicios_accion_disponibles();
                    show_servicios_accion_seleccionadas();
                  
                }
                bloquear_div();
            }
        });
        
    }
</script>