<!--pasa variables objetos:
$planUnidad
$pudPep;-->
<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\controllers\PudPepController;
use backend\models\PlanificacionVerticalDiploma;
use backend\models\PudAprobacionBitacora;
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

$modelPVD = PlanificacionVerticalDiploma::find()
        ->where(['planificacion_bloque_unidad_id' => $planUnidad->id])
        ->one();

$pud_dip_porc_avance = array("porcentaje" => 0);

if ($modelPVD) {
    $pud_dip_porc_avance = pud_dip_porcentaje_avance($modelPVD->id, $planUnidad->id);
}

//consulta para extraer el porcentaje de avance del PUD DIPLOMA
function pud_dip_porcentaje_avance($planVertDiplId, $planBloqueUniId) {
    $pud_dip_porc_avance = 0;
    //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados    
    $obj2 = new backend\models\helpers\Scripts();
    $pud_dip_porc_avance = $obj2->pud_dip_porcentaje_avance($planVertDiplId, $planBloqueUniId);

    return $pud_dip_porc_avance;
}

//consulta para extraer los mensajes del coordinador cuando se halla enviado el PUD
$modelPudBitacora = PudAprobacionBitacora::find()
        ->where(['unidad_id' => $planUnidad->id])
        ->orderBy(['fecha_notifica' => SORT_DESC])
        ->one();
?>
<!--Scripts para que funcionen AJAX'S-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>


<div class="pud-pep-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <h6>
                            (
                            Curso: <?= $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name ?> |
                            Materia: <?= $planUnidad->planCabecera->ismAreaMateria->materia->nombre ?>
                            )
                        </h6>
                    </small>
                </div>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- inicia menu cabecera -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu cabecera izquierda -->
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
                    <?php
                    if ($pud_dip_porc_avance['porcentaje'] == '100') {
                        if (isset($modelPudBitacora->estado_jefe_coordinador) == 'APROBADO') {
                            ?>
                            <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <span class="badge rounded-pill" style="background-color: gray"><i class="fas fa-file-signature"></i> Firmas</span>
                            </a>
                    
                            <?php
                        }
                    }
                    ?>





                </div> <!-- fin de menu cabecera izquierda -->

                <!-- inicio de menu cabecera derecha -->
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fas fa-file-pdf"></i>  Generar Reporte PDF</span>',
                            ['pud-dip/pdf-pud-dip', 'planificacion_unidad_bloque_id' => $planUnidad->id],
                            ['class' => 'link', 'target' => '_blank']
                    );
                    ?>
                    |

                </div>
                <!-- fin de menu cabecera derecha -->
                <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Planificación Firmada</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <b>Firmado y Aprobado el : <b/><?= $firmaAprobado['firmado_el'] ?>, por <?= $firmaAprobado['firmado_por'] ?>
                                                <hr />
                                                <b>Firmado y Aprobado el : <b/><?= $firmaDocente['firmado_el'] ?>, por <?= $firmaDocente['firmado_por'] ?>
                                        </div>
                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
            </div>
            <!-- finaliza menu cabecera  -->

            <!-- inicia cuerpo de card -->
            <div class="row my-text-medium" style="margin-top: 25px; margin-bottom: 5px;">
                <!-- comienza menu de pud-->
                <div class="col-lg-3 col-md-3" style="overflow-y: scroll; height: 650px; border-top: solid 1px #ccc;">
                    <?=
                    $this->render('menu', [
                        'planUnidad' => $planUnidad
                    ]);
                    ?>
                </div>
                <!-- termina menu de pud -->

                <!-- comienza detalle -->
                <?php if ($modelPudBitacora == false || $modelPudBitacora->estado_jefe_coordinador == 'APROBADO') { ?>                    
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
                                    
                                        <?= $modelPudBitacora->respuesta ?>                                       

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

        /** FUNCION PARA MODIFICACION DE TEXTO PARA CAMPOS SIMPLES */
        function update_campo_simple_pud_dip(id, accion_update) {
            var contenido = CKEDITOR.instances['editor-text-unidad'].getData();
            var url = buscar_url(accion_update);
            var control = "#" + accion_update;
            params = {
                id_plani_vert_dip: id,
                contenido: contenido,
                accion: accion_update
            }
            $.ajax({
                data: params,
                url: url,
                type: 'GET',
                beforeSend: function () {},
                success: function () {                    
                    location.reload();

                }
            });

        }
        
        /** FUNCION PARA MODIFICACION DE EVALUACIONES */
        function update_evaluaciones(id, accion_update, campo) {
            
            var contenido = CKEDITOR.instances['editor-text-unidad'].getData();
            var url = buscar_url(accion_update);
            var control = "#" + accion_update;
            params = {
                evaluacion_id: id,
                contenido: contenido,
                accion: accion_update,
                campo: campo
            }
            $.ajax({
                data: params,
                url: url,
                type: 'GET',
                beforeSend: function () {},
                success: function () {                    
                    //location.reload();

                }
            });

        }
        
        
         /** FUNCION PARA MODIFICACION DE PUD_DIP */
        function update_pud_dip_boolean(id) 
        {            
//            var contenido = CKEDITOR.instances['editor-text-unidad'].getData();
            var url = '<?= Url::to(['update-pud-dip']) ?>';
            params = {
                id: id,
                contenido: '',
                campo_de: 'seleccion'
            };
            
            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function () {                    
                    location.reload();

                }
            });

        }
        
        function update_pud_dip_texto(id) {
            var contenido = $("#detalle-metacognicion").val();
            alert(id);
            alert(contenido);
            

        }
        
        

        function update_campos_check(id_pvd, idPvd_Op, accion_update_op, tipoProceso) {
            //idPvd_Op: esta variable toma dos valores de id's, de dos tablas diferentes
            // tablas: planificacion vertical diploma relacion tdc, cuando elimina
            // tablas: planificacion opciones, cuando agrega
            // alert(accion_update_op);
            var url = buscar_url(accion_update_op);
            params = {
                id_plani_vert_dip: id_pvd,
                id_pvd_op: idPvd_Op,
                tipo_proceso: tipoProceso,
                accion: accion_update_op
            }
            $.ajax({
                data: params,
                url: url,
                type: 'GET',
                beforeSend: function () {},
                success: function () {
                    //ver_detalle(accion_update_op);
                    location.reload();
                }
            });
        }

        function buscar_url(accion_update) {
            var respuesta = '';
            switch (accion_update) {
                case '2.1.-':
                    respuesta = '<?= Url::to(['pud-dip/update-descri-text-uni']) ?>';
                    break;
                case '4.2.-':
                    respuesta = '<?= Url::to(['pud-dip/update-habilidades']) ?>';
                    break;
                case '5.1.-':
                    respuesta = '<?= Url::to(['pud-dip/update-evaluaciones']) ?>';
                    break;
                    
                case '5.2.-':
                    respuesta = '<?= Url::to(['pud-dip/update-proceso-aprendizaje']) ?>';
                    break;
                    
                case '5.4.-':
                    respuesta = '<?= Url::to(['pud-dip/update-lenguaje-aprendizaje']) ?>';
                    break;
                case '5.4.1.-':
                    respuesta = '<?= Url::to(['pud-dip/update-lenguaje-aprendizaje-check']) ?>';
                    break;
                case '5.5.-':
                    respuesta = '<?= Url::to(['pud-dip/update-conexion-tdc']) ?>';
                    break;
                case '5.6.-':
                    respuesta = '<?= Url::to(['pud-dip/update-conexion-cas']) ?>';
                    break;
                case '5.6.1.-':
                    respuesta = '<?= Url::to(['pud-dip/update-conexion-cas-check']) ?>';
                    break;
                case '6.1.-':
                    respuesta = '<?= Url::to(['pud-dip/update-recursos']) ?>';
                    break;
                case '7.1.-':
                    respuesta = '<?= Url::to(['pud-dip/update-funciono']) ?>';
                    break;
                case '7.2.-':
                    respuesta = '<?= Url::to(['pud-dip/update-no-funciono']) ?>';
                    break;
                case '7.3.-':
                    respuesta = '<?= Url::to(['pud-dip/update-observacion']) ?>';
                    break;
            }
            return respuesta;
        }

        //// FIN PARA PERFILES BI
        
        
        
        ////proceso de aprendizaje
        
        function registra_proceso_aprendizaje(id){
            var url = '<?= Url::to(['actualiza-opcion']) ?>';
            var params = {
                id: id
            };
            
            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function () {
                    //ver_detalle(accion_update_op);
                    location.reload();
                }
            });
        }
        
        ////fin de proceso de aprendizaje
    </script>