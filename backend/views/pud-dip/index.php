<!--pasa variables objetos:
$planUnidad
$pudPep;-->
<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\controllers\PudPepController;
use backend\models\PlanificacionVerticalDiploma;
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

$pud_dip_porc_avance = pud_dip_porcentaje_avance($modelPVD->id, $planUnidad->id);
//consulta para extraer el porcentaje de avance del PUD DIPLOMA
function pud_dip_porcentaje_avance($planVertDiplId, $planBloqueUniId)
{
    $pud_dip_porc_avance = 0;
    //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados    
    $obj2 = new backend\models\helpers\Scripts();
    $pud_dip_porc_avance = $obj2->pud_dip_porcentaje_avance($planVertDiplId, $planBloqueUniId);

    return $pud_dip_porc_avance;
}

?>
<!--Scripts para que funcionen AJAX'S-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>


<div class="pud-pep-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
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
                    <?= "Porcentaje de Avance: " . $pud_dip_porc_avance['porcentaje'] . "%" ?>
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
            </div>
            <!-- finaliza menu cabecera  -->

            <!-- inicia cuerpo de card -->
            <div class="row my-text-medium" style="margin-top: 25px; margin-bottom: 5px;">
                <!-- comienza menu de pud-->
                <div class="col-lg-3 col-md-3" style="overflow-y: scroll; height: 650px; border-top: solid 1px #ccc;">
                    <?= $this->render('menu', [
                        'planUnidad' => $planUnidad,                        
                    ]); ?>
                </div>
                <!-- termina menu de pud -->

                <!-- comienza detalle -->
                <div id="div-detalle" class="col-lg-9 col-md-9" style="border-top: solid 1px #ccc;">

                </div>
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
            var control = "#"+accion_update;
            params = {
                id_plani_vert_dip: id,
                contenido: contenido,
                accion : accion_update
            }
            $.ajax({
                data: params,
                url: url,
                type: 'GET',
                beforeSend: function() {},
                success: function() {
                  /*if (contenido.length>50)
                    {
                        $(control).css({'color':'green'});
                       
                    }else{                       
                       $(control).css({'color':'red'});
                    }*/                   
                    //ver_detalle(accion_update);
                    location.reload();

                }
            });

        }

        function update_campos_check(id_pvd, idPvd_Op, accion_update_op, tipoProceso) {
            //idPvd_Op: esta variable toma dos valores de id's, de dos tablas diferentes
            // tablas: planificacion vertical diploma relacion tdc, cuando elimina
            // tablas: planificacion opciones, cuando agrega
            var url = buscar_url(accion_update_op);
            params = {
                id_plani_vert_dip: id_pvd,
                id_pvd_op: idPvd_Op,
                tipo_proceso: tipoProceso,
                accion : accion_update_op
            }
            $.ajax({
                data: params,
                url: url,
                type: 'GET',
                beforeSend: function() {},
                success: function() {
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
                case '5.1.-':
                    respuesta = '<?= Url::to(['pud-dip/update-habilidades']) ?>';
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
    </script>