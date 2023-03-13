<!--pasa variables objetos:
$planUnidad
$pudPep;-->
<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\controllers\PudPepController;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionVerticalDiploma;
use backend\models\PudAprobacionBitacora;
use backend\models\PudPai;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'PUD - ' . $planUnidad->curriculoBloque->last_name . ' - ' . $planUnidad->unit_title;
$this->params['breadcrumbs'][] = $this->title;

$modelPudPai = PudPai::find()
->where(['planificacion_bloque_unidad_id' => $planUnidad->id])
->one();

$pud_dip_porc_avance = array( "porcentaje" => 0);

if($modelPudPai)
{
    $pud_dip_porc_avance = pud_pai_porcentaje_avance( $planUnidad->id);    
}

$pud_dip_porc_avance['porcentaje'] ? $porcentaje = $pud_dip_porc_avance['porcentaje'] : $porcentaje = 0; // revisar select porque devuelve NULL

//guarda el valor del porcentaje de avance en planificacion bloque unidad
$modelPlanBloqUni = PlanificacionBloquesUnidad::findOne($planUnidad->id);
$modelPlanBloqUni->avance_porcentaje = $porcentaje;
$modelPlanBloqUni->save();


//consulta para extraer el porcentaje de avance del PUD PAI
function pud_pai_porcentaje_avance( $planBloqueUniId)
{
    $pud_dip_porc_avance = 0;
    //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados    
    $obj2 = new backend\models\helpers\Scripts();
    $pud_dip_porc_avance = $obj2->pud_pai_porcentaje_avance($planBloqueUniId); 
    return $pud_dip_porc_avance;
}
//consulta para extraer los mensajes del coordinador cuando se halla enviado el PUD
$modelPudBitacora = PudAprobacionBitacora::find()
    ->where(['unidad_id' => $planUnidad->id])
    ->orderBy(['fecha_notifica' => SORT_DESC])
    ->one();
$estadoAprobacion = 'SIN ESTADO';
if($modelPudBitacora){$estadoAprobacion = $modelPudBitacora->estado_jefe_coordinador;}

?>

<!--Scripts para que funcionen AJAX'S-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>

<?php if($estadoAprobacion=="ENVIADO" || $estadoAprobacion=="APROBADO"){?>
<style>
    .ocultar a {
        pointer-events: none;
        cursor:default;
        color:red;
    }
    
</style>
<?php } ?>
<div class="pud-pep-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"  class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <h6>
                            (
                            Curso: <?= $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name ?> |
                            Id: <?= $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->id ?> |
                            Materia: <?= $planUnidad->planCabecera->ismAreaMateria->materia->nombre ?> |
                            Id: <?= $planUnidad->planCabecera->ismAreaMateria->materia->id ?> 
                            )
                        </h6>
                    </small>
                </div>
            </div><!-- FIN DE CABECERA -->

           
            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i>Temas</span>',
                            ['planificacion-bloques-unidad/index1', 'id' => $planUnidad->plan_cabecera_id],
                            ['class' => 'link']
                    );
                    ?>
                    |                    
                    <?php
                    //porcentaje de avance
                    if ($pud_dip_porc_avance['porcentaje'] == '100') {
                        if ($modelPudBitacora == false) {
                            echo Html::a(
                                '<span class="badge rounded-pill" style="background-color: blue"><i class="fa fa-briefcase" aria-hidden="true"></i>Enviar Aprobación</span>',
                                ['planificacion-bloques-unidad/envio-aprobacion', 'modelPlanBloqUnidad' => $planUnidad->id],
                                ['class' => 'link']
                            );
                        } elseif ($modelPudBitacora->estado_jefe_coordinador == 'ENVIADO') {
                            echo '<span class="badge rounded-pill" style="background-color: orange"><i class="fa fa-briefcase" aria-hidden="true"></i>Esperando Respuesta</span>';
                        } elseif ($modelPudBitacora->estado_jefe_coordinador == 'DEVUELTO') {

                            echo Html::a(
                                '<span class="badge rounded-pill" style="background-color: purple"><i class="fa fa-briefcase" aria-hidden="true"></i>Reenviar</span>',
                                ['planificacion-bloques-unidad/envio-aprobacion', 'modelPlanBloqUnidad' => $planUnidad->id],
                                ['class' => 'link']
                            );
                        } elseif ($modelPudBitacora->estado_jefe_coordinador == 'APROBADO') {
                            echo '<span class="badge rounded-pill" style="background-color: green"><i class="fa fa-briefcase" aria-hidden="true"></i>Aprobado</span>';
                        }
                        echo '|';
                    }
                    ?>

                    <?= " Avance: " . $pud_dip_porc_avance['porcentaje'] . "%" ?>
                </div> <!-- fin de menu izquierda -->

                <!-- inicio de menu derecha -->
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                |
                <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"> Generar Reporte PDF <i class="fas fa-file-pdf"></i></span>',
                            ['genera-pdf', 'planificacion_unidad_bloque_id' => $planUnidad->id],
                            ['class' => 'link', 'target' => '_blank']
                    );
                    ?>
                    |  
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row my-text-medium" style="margin-top: 25px; margin-bottom: 5px;">

                <!-- comienza menu de pud-->
                <div class="col-lg-2 col-md-2" style="overflow-y: scroll; height: 650px; width: 300px; border-top: solid 1px #ccc;">
                    <?= $this->render('menu', [
                        'planUnidad' => $planUnidad
                    ]); ?>
                </div>
                <!-- termina menu de pud -->

                <!-- comienza detalle -->
                <?php
                if ($modelPudBitacora == false || $modelPudBitacora->estado_jefe_coordinador == 'APROBADO') { ?>                    
                    <div id="div-detalle" class="col-lg-9 col-md-9" style="border-top: solid 1px #ccc;" >

                    </div>
                <?php } elseif ($modelPudBitacora->estado_jefe_coordinador == 'ENVIADO' || $modelPudBitacora->estado_jefe_coordinador == 'DEVUELTO') { ?>                    
                    <div id="div-detalle"  class="col-lg-5 col-md-5" style="border-top: solid 1px #ccc; " >

                    </div>
                    <div id="div-novedades" class="col-lg-4 col-md-4 " style="border-top: solid 1px #ccc; ">
                        <div class="" style="align-items: center; display: flex; justify-content: center;">
                            <div class="card" style="width: 100%; margin-top:20px">
                                <div class="card-header">
                                    <div class="row">
                                        <P style="color:red">AQUI PODRA VISUALIZAR LAS NOVEDADES ENVIADAS POR EL COORDINADOR </p>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row" style="overflow-y: scroll; overflow-x: scroll;">
                                        <?=$modelPudBitacora->respuesta ?>                                       

                                    </div>
                                </div>
                            </div>
                        </div>                            
                    </div>
                <?php } ?>
                <!-- termina detalle -->
            </div>
            <!-- fin cuerpo de card -->
    </div>
</div>

<!--<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>-->

<script>
    //metodos que se utiliza para  bloquear la edision de botones
    // document.body.onload = function() {        
        
    // }
    
    function bloquear_div()
    {        
        var resp = ('<?= $estadoAprobacion ?>'); 
        if(resp=='ENVIADO' || resp=='APROBADO')
        {    
            $('.ocultar').hide();
        }        
    }
    // fin metodo bloqueo de div

    function recargar_pagina(){
        location.reload();      
        
    }
    //Metodo para la seccion 2.3.-
    function ingresar_pregunta(obj, tipo, seccion){
        var pregunta = obj.value;        
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/ajax-crear-pregunta']) ?>';
        
        var params = {
            pregunta : pregunta,
            tipo: tipo,
            seccion: seccion,
            planificacion_bloque_unidad_id : planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(){
                muestra_preguntas();
                //location.reload();
            }
        });

    }
    //Metodo para la seccion 2.3.-
    function muestra_preguntas(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/ajax-muestra-preguntas']) ?>';      

        params = {
            planificacion_bloque_unidad_id: planUnidadId
        };        

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $("#div-preguntas").html(response);
            }
        });
    }
    //Metodo para la seccion 2.3.-
    function showEdit(id, contenido){        
        $("#input-edit").val(contenido);
        $("#input-edit-id").val(id);        
    }
    //Metodo para la seccion 2.3.- /  para actualizar cualquier pregunta factica
    function update()
    {        
        var id = $("#input-edit-id").val();
        var contenido = $("#input-edit").val();

        var url = '<?= Url::to(['helper-pud-pai/ajax-update']) ?>';

        params  = {
            contenido: contenido,
            id: id
        }

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                muestra_preguntas();
            }
        });

    }
    //Metodo para la seccion 2.3.- / para eliminar una pregunta
    function delete_pud(){
        var id = $("#input-edit-id").val();
        var url = '<?= Url::to(['helper-pud-pai/ajax-delete']) ?>';
        params = {
            id: id
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
               muestra_preguntas();
               recargar_pagina();
            }
        });
    }  

    //*********************    ACTUALIZA CAMPOS EVALUACION 4.1    ************ */       
    //metodo para 5.1.-
    function update_evaluacion_relacion(id,titulo)
    {
        var contenido   = CKEDITOR.instances['editor-sumativa2'+id].getData();        
        var url = '<?= Url::to(['helper-pud-pai/update-evaluacion']) ?>';
        
        params = {
            id: id,
            titulo: titulo,
            contenido: contenido
        }
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){             
                //$("#div-prueba").html(response);
               location.reload();
            }
        });
    }
    //metodo para 5.1.-
    function update_evaluacion_formativa(id)
    {
        var titulo      = 'no aplica';
        var contenido   = CKEDITOR.instances['editor-sumativa2'+id].getData();
        var url = '<?= Url::to(['helper-pud-pai/update-evaluacion']) ?>';
        params = {
            id: id,
            titulo: titulo,
            contenido: contenido
        }
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response)
            {
               location.reload();
            }
        });
    }
     //metodo para 5.1.-
    function update_evaluacion_sumativa(id)
    {
        var titulo      = 'no aplica';
        var contenido   = CKEDITOR.instances['editor-sumativa2'+id].getData();
        var url = '<?= Url::to(['helper-pud-pai/update-evaluacion']) ?>';
        params = {
            id: id,
            titulo: titulo,
            contenido: contenido
        }
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response)
            {
               location.reload();
            }
        });
    }
    //********************* FIN  UPDATE CAMPOS EVALUACION 4.1    ************ */


    //*********************    MUESTRA CAMPOS EVALUACION 4.1    ************ */
    //metodo para 5.1.-
    function muestra_evaluacion_relacion()
    {
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/muestra-evaluacion']) ?>';
        params = {
            planificacion_bloque_unidad_id: planUnidadId, 
            tipo:'relacion-suma-eval'
        };
       
        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $("#div-evaluacion-relacion").html(response);
            }
        });
    }
    //metodo para 5.1.-
    function muestra_evaluacion_sumativa()
    {
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/muestra-evaluacion']) ?>';
        params = {
            planificacion_bloque_unidad_id: planUnidadId,
            tipo:'eval_sumativa'        
        };
        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $("#div-evaluacion-sumativa").html(response);
            }
        });
    }
    //metodo para 5.1.-
    function muestra_evaluacion_formativa()
    {
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/muestra-evaluacion']) ?>';
        params = {
            planificacion_bloque_unidad_id: planUnidadId,
            tipo:'eval_formativa'             
        };
        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $("#div-evaluacion-formativa").html(response);
            }
        });
    }
    
    //********************* FIN   MUESTRA CAMPOS EVALUACION 4.1    ************ */

    
    //etodo para la seccion 3.0    
    function update_habilidades_nuevo_formato(id_pudpai)
    {
        var idPlanUnidad = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/update-habilidades-nuevo-formato']) ?>';
        //var id_pudpai = $("#id_pudpai"+id_pudpai).val();
        var actividad = CKEDITOR.instances['editor-actividad'+id_pudpai].getData();
        var id_relacion = $("#id_relacion"+id_pudpai).val();

        if(actividad==''){actividad='-';}

        var params = {
            actividad    :   actividad,
            id_relacion :id_relacion,
            id_pudpai : id_pudpai,
            idPlanUnidad    : idPlanUnidad,
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(){
                //show_ensenara();
                recargar_pagina();
            }
        });
    }
    //etodo para la seccion 3.0    
    function update_habilidades_perfil(id_pudpai)
    {
        var idPlanUnidad = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/update-habilidades-nuevo-formato']) ?>';
        //var id_pudpai = $("#id_pudpai"+id_pudpai).val();
        var actividad = CKEDITOR.instances['editor-actividad'+id_pudpai].getData();
        var id_relacion = $("#id_relacion"+id_pudpai).val();

        if(actividad==''){actividad='-';}

        var params = {
            actividad    :   actividad,
            id_relacion :id_relacion,
            id_pudpai : id_pudpai,
            idPlanUnidad    : idPlanUnidad,
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(){
                //show_ensenara();
                recargar_pagina();
            }
        });
    }
    function guardar_perfil(idPlanVerticalPai, idPerfil)
    {
        var url = '<?= Url::to(['helper-pud-pai/insert-perfiles']) ?>';
       //alert(idPlanVerticalPai+ ' - ' + idPerfil);

        var params = {
            idPlanVerticalPai : idPlanVerticalPai,
            idPerfil : idPerfil,
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                recargar_pagina();
            }
        });

    }

    //metodo para la seccion 3.4.-
    function show_ensenara(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/muestra-ensenara']) ?>';
        var params = {
            planUnidadId    : planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#div-como-ensenara').html(response);
            }
        });
    }

    //metodo para la seccion 3.4.-
    function update_ensenara(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/update-ensenara']) ?>';
        var comunicacion = CKEDITOR.instances['editor-comunicacion'].getData();
        var sociales = CKEDITOR.instances['editor-sociales'].getData();
        var autogestion = CKEDITOR.instances['editor-autogestion'].getData();
        var investigacion = CKEDITOR.instances['editor-investigacion'].getData();
        var pensamiento = CKEDITOR.instances['editor-pensamiento'].getData();

        if(comunicacion==''){comunicacion='-';}
        if(sociales==''){sociales='-';}
        if(autogestion==''){autogestion='-';}
        if(investigacion==''){investigacion='-';}
        if(pensamiento==''){pensamiento='-';}

        var params = {
            comunicacion    :   comunicacion,
            sociales        :   sociales,
            autogestion    :   autogestion,
            investigacion    :   investigacion,
            pensamiento    :   pensamiento,
            planUnidadId    : planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(){
                //show_ensenara();
                recargar_pagina();
            }
        });

    }
    ///8.1.-
    function show_recursos(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/muestra-recursos']) ?>';
        var params = {
            plan_unidad_id    : planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#table-recursos').html(response);
            }
        });
    }
     /// tambien del 8.1.-
    function update_recurso()
    {
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/update-recurso']) ?>';
        var bibliografico = CKEDITOR.instances['editor-bibliografico'].getData();
        var tecnologico = CKEDITOR.instances['editor-tecnologico'].getData();
        var otros = CKEDITOR.instances['editor-otros'].getData(); 
        

        if(bibliografico==''){bibliografico = '-';}
        if(tecnologico==''){tecnologico = '-';}
        if(otros==''){otros = '-';}

        var params = 
        {
            plan_unidad_id: planUnidadId,
            bibliografico : bibliografico,
            tecnologico: tecnologico,
            otros : otros
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                //show_recursos();
                recargar_pagina();
            }
        });
    }


    ////para reflexion: 9.1.-
    function show_reflexion_seleccionados(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/show-reflexion-seleccionados']) ?>';

        var params = {
            plan_unidad_id: planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#table-reflexion-seleccionadas').html(response);
            }
        });
    }

    //para reflexion: 9.1.-
    function show_reflexion_disponibles()
    {
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/show-reflexion-disponibles']) ?>';

        var params = {
            plan_unidad_id: planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#table-reflexion-disponibles').html(response);
            }
        });
    }

    ////para reflexion: 9.1.-
    function inster_reflexion(id, categoria){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/insert-reflexion']) ?>';

        var params = {
            plan_unidad_id : planUnidadId,
            id: id,
            url: url,
            tipo: categoria
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                show_reflexion_disponibles();
                show_reflexion_seleccionados();
            }
        });
    }
     ////para reflexion: 9.1.-
    function update_reflexion(id){
        var respuesta = $('#textarea-respuesta-'+id).val();
        var url = '<?= Url::to(['helper-pud-pai/update-reflexion']) ?>';
        
        params = {
            id: id,
            respuesta: respuesta
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                show_reflexion_seleccionados();
            }

        });
    }
    ////para reflexion: 9.1.-
    function eliminar_reflexion(id){
        var url = '<?= Url::to(['helper-pud-pai/delete-reflexion']) ?>';
        params = {
            id: id
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                //show_reflexion_seleccionados();
                recargar_pagina();
            }
        });
    }


    //// INICIO PARA PERFILES BI
        function show_perfiles_disponibles(){
            var planUnidadId = '<?= $planUnidad->id ?>';
            var url = '<?= Url::to(['helper-pud-pai/show-perfiles-disponibles']) ?>';

            var params = {
                plan_unidad_id : planUnidadId
            };

            $.ajax({
                data:   params,
                url:    url,
                type:   'GET',
                beforeSend: function(){},
                success: function(response){
                    $('#table-perfiles-disponibles').html(response);
                }
            });

        }

        function insert_perfil(perfil, categoria){

            var planUnidadId = '<?= $planUnidad->id ?>';
            var url = '<?= Url::to(['helper-pud-pai/insert-perfil']) ?>';
            var params = {
                plan_unidad_id : planUnidadId,
                perfil : perfil,
                categoria : categoria
            };

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function(){},
                success: function(response){
                    show_perfiles_disponibles();
                    show_perfiles_seleccionados();
                    
                }
            });

        }
        // metodo 5.5.-
        function show_perfiles_seleccionados(){
            var planUnidadId = '<?= $planUnidad->id ?>';
            var url = '<?= Url::to(['helper-pud-pai/show-perfiles-seleccionados']) ?>';

            var params = {
                plan_unidad_id : planUnidadId
            };

            $.ajax({
                data:   params,
                url:    url,
                type:   'GET',
                beforeSend: function(){},
                success: function(response){
                    $('#table-perfiles-seleccionadas').html(response);
                }
            });
        }


        function eliminar_perfil(id){
        var url = '<?= Url::to(['helper-pud-pai/delete-reflexion']) ?>';
        params = {
            id: id
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                show_perfiles_seleccionados();
                recargar_pagina();
            }
        });
    }
    //// FIN PARA PERFILES BI

    ////INICIO PARA SERVICIOS DE ACCION
    //7.1.-
    function show_servicios_accion_seleccionadas(){
        var planUnidadId = '<?= $planUnidad->id ?>';
            var url = '<?= Url::to(['helper-pud-pai/show-servicio-accion-seleccionadas']) ?>';

            var params = {
                plan_unidad_id : planUnidadId
            };

            $.ajax({
                data:   params,
                url:    url,
                type:   'GET',
                beforeSend: function(){},
                success: function(response){
                    $('#body-como-accion').html(response);
                }
            });
    }
    //7.1.-
    function show_servicios_accion_disponibles(){
            var planUnidadId = '<?= $planUnidad->id ?>';
            var url = '<?= Url::to(['helper-pud-pai/show-servicio-accion-disponibles']) ?>';

            var params = {
                plan_unidad_id : planUnidadId
            };

            $.ajax({
                data:   params,
                url:    url,
                type:   'GET',
                beforeSend: function(){},
                success: function(response){
                    $('#acciones-disponibles').html(response);
                }
            });
    }
    
    function insert_accion(opcionId){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/inserta-accion']) ?>'; 
        
        var params = {
            opcion_id       : opcionId,
            plan_unidad_id  : planUnidadId
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                        show_servicios_accion_disponibles();
                        show_servicios_accion_seleccionadas();
                    }
        });
    }
    //7.1.-
    function inserta_situacion(planUnidadId, categoria, opcion){
        var url = '<?= Url::to(['helper-pud-pai/inserta-situacion']) ?>';
        
        var params = {
            plan_unidad_id : planUnidadId,
            opcion : opcion,
            categoria : categoria
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                        show_servicios_accion_seleccionadas();
                        recargar_pagina();
                    }
        });
    }
    //7.1.-
    function elimina_situacion(id){
        var url = '<?= Url::to(['helper-pud-pai/elimina-situacion']) ?>';
        
        var params = {
            id: id
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                show_servicios_accion_seleccionadas();
                recargar_pagina();
            }
        });
    }
        
        
    ////FIN PARA SERVICIOS DE ACCION

    
</script>