<?php

use backend\models\PlanificacionOpciones;
use backend\models\PudDipController;
use backend\models\PlanificacionVerticalDiploma;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\helpers;
?>
<br>
<?php
//busqueda en planificacion_vertical_diploma, para evaluar el porcentaje
$modelOpciones = PlanificacionOpciones::find()
->where(['tipo'=>'PUD_NUM_CART_VALIDACION'])
->one();

$modelPVD = PlanificacionVerticalDiploma::find()
->where(['planificacion_bloque_unidad_id'=>$planUnidad->id])
->one();

$opcion=$modelPVD->ultima_seccion;
$iconoOk = 'fas fa-check';
$colorOk = 'green';
$colorNotOk = 'red';
$numCaracteresOk = $modelOpciones->categoria;

$pud_dip_porc_avance = pud_dip_porcentaje_avance($modelPVD->id,$planUnidad->id);

$contador_5_4 = contador_de_consultar_lenguaje_y_aprendizaje_ckeck($modelPVD->id);
$contador_5_6 = consultar_conexion_cas_ckeck($modelPVD->id);
//metodo usado para 5.4.- llamada a lenguaje y aprendizaje
function contador_de_consultar_lenguaje_y_aprendizaje_ckeck($planVertDiplId) 
{
    $contador_5_4 = 0;
    //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados  
   $obj = new backend\models\helpers\Scripts();       

   $modelPlanifVertDiplTDC = $obj->pud_dip_consultar_lenguaje_y_aprendizaje_ckeck($planVertDiplId);
  
   foreach($modelPlanifVertDiplTDC as $tdc)
   {
        if($tdc['es_seleccionado'])
        { 
            $contador_5_4 = $contador_5_4+ 1; 
        }        
   }
   return $contador_5_4;
} 
 //metodo usado para 5.6.- llamada a Conexion CAS
 function consultar_conexion_cas_ckeck($planVertDiplId) 
 {
    $contador_5_6 = 0;
    //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados    
    $obj1 = new backend\models\helpers\Scripts();  
    $modelPlanifVertDiplTDC = $obj1->pud_dip_consultar_conexion_cas_ckeck($planVertDiplId);  
    foreach($modelPlanifVertDiplTDC as $tdc)
    {
            if($tdc['es_seleccionado'])
            { 
                $contador_5_6 = $contador_5_6+ 1; 
            }        
    }
    return $contador_5_6; 
 } 


?>
<ul>
    <li>
        
        <b>1.- DATOS INFORMATIVOS</b> 
        <ul>
            <li class="zoom"><a  href="#" onclick="ver_detalle('1.1.-');">1.1.- Ver Datos
            <i id="1.1.-" class="<?=$iconoOk;?>" title="DATOS INGRESADOS" style="color: <?=$colorOk;?>;"></i>
            </a>
            </li>
            
        </ul>
    </li>
    <hr>
    <li>
        <b>2.- DESCRIPCIÓN Y TEXTOS DE LA UNIDAD</b>
        <ul>
            <li class="zoom"><a  href="#" onclick="ver_detalle('2.1.-');">2.1.- Descripción y Texto de la Unidad
            <?php

             if (strlen($modelPVD->descripcion_texto_unidad)<$numCaracteresOk)
             { $iconoColor = $colorNotOk;}
             else
             { $iconoColor = $colorOk;}
            ?>
            <i id="i" class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: <?=$iconoColor;?>;"></i>
            </a>            
            </li>
        </ul>
    </li>
    <hr>
    
    <li>
        <b>3.- EVALUACIÓN DEL PD PARA LA UNIDAD</b>
        <ul>                                          
            <li class="zoom"><a href="#" onclick="ver_detalle('3.1.-');">3.1.- Evaluación del PD para la unidad</a>
            <i class="<?=$iconoOk;?>" title="DATOS INGRESADOS" style="color: <?=$colorOk;?>;"></i>
            </a>
            </li> 
                                           
        </ul>
    </li>
    <hr>

    <li>
        <b>4.- INDAGACIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('4.1.-');">4.1.- Objetivos de Tranferencia</a>
            <i class="<?=$iconoOk;?>" title="DATOS INGRESADOS" style="color: <?=$colorOk;?>;"></i>
            </a>
            </li>
        </ul>
    </li>
    <hr>

    <li>
        <b>5.- ACCIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('5.1.-');">5.1.- Contenido, Habilidades y Conceptos: Conocimientos Esenciales
                <?php
                if (strlen($modelPVD->habilidades)<$numCaracteresOk)
                { $iconoColor = $colorNotOk;}
                else
                { $iconoColor = $colorOk;}
                ?>
                <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: <?=$iconoColor;?>;"></i>
                </a>            
            </li>
            <li class="zoom"><a href="#" onclick="ver_detalle('5.2.-');">5.2.- Proceso de aprendizaje
                <?php
                    if (strlen($modelPVD->proceso_aprendizaje)<$numCaracteresOk)
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: <?=$iconoColor;?>;"></i>
                    </a>            
            </li>
            <li class="zoom"><a href="#" onclick="ver_detalle('5.3.-');">5.3.- Enfoque del aprendizaje (EDA):</a>
            <i class="<?=$iconoOk;?>" title="DATOS INGRESADOS" style="color: <?=$colorOk;?>;"></i>
            </a>
            </li>
            <li class="zoom"><a href="#" onclick="ver_detalle('5.4.-');">5.4.- Lenguaje y Aprendizaje
            <?php                   
                    if ($contador_5_4==0)
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: <?=$iconoColor;?>;"></i>
                    </a>            
            </li>
            <li class="zoom"><a href="#" onclick="ver_detalle('5.5.-');">5.5.- Conexiones con TDC</a>
            <i class="<?=$iconoOk;?>" title="DATOS INGRESADOS" style="color: <?=$colorOk;?>;"></i>
            </a>
            </li>
            <li class="zoom"><a href="#" onclick="ver_detalle('5.6.-');">5.6.- Conexiones con CAS
            <?php                   
                    if ($contador_5_6==0)
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: <?=$iconoColor;?>;"></i>
                    </a>            
            </li>
        </ul>
    </li>
    <hr>

    <li>
        <b>6.- RECURSOS</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('6.1.-');">6.1.- Recursos
            <?php
                if (strlen($modelPVD->recurso)<$numCaracteresOk)
                { $iconoColor = $colorNotOk;}
                else
                { $iconoColor = $colorOk;}
                ?>
                <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: <?=$iconoColor;?>;"></i>
                </a>            
            </li>            
        </ul>
    </li>
    <hr>

    <li>
        <b>7.- REFLEXIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('7.1.-');">7.1.- Lo que fuincionó bien
                <?php
                    if (strlen($modelPVD->reflexion_funciono)<$numCaracteresOk)
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: <?=$iconoColor;?>;"></i>
                    </a>            
            </li>           
            <li class="zoom"><a href="#" onclick="ver_detalle('7.2.-');">7.2.- Lo que nó fuincionó bien
                <?php
                    if (strlen($modelPVD->reflexion_no_funciono)<$numCaracteresOk)
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: <?=$iconoColor;?>;"></i>
                    </a>            
            </li>          
            <li class="zoom"><a href="#" onclick="ver_detalle('7.3.-');">7.3.- Observaciones, Cambios y sugerencias
                <?php
                    if (strlen($modelPVD->reflexion_observacion)<$numCaracteresOk)
                    { $iconoColor = $colorNotOk;}
                    else
                    { $iconoColor = $colorOk;}
                    ?>
                    <i class="<?=$iconoOk;?>" title="FALTA INGRESAR DATOS" style="color: <?=$iconoColor;?>;"></i>
                    </a>            
            </li>         
            
        </ul>
    </li>   
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
                           
            }
        });        
    }
</script>