<?php

use backend\models\PlanificacionOpciones;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$condicionClass = new backend\models\helpers\Condiciones;


$this->title = $planUnidad->unit_title . ' (' . $planUnidad->curriculoBloque->last_name . ')' . ' - TEMARIO';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>';
//print_r($subtitulos);
//die();
$estado = $planUnidad->planCabecera->estado;
$isOpen = $planUnidad->is_open;
$condicion = $condicionClass->aprobacion_planificacion($estado, $isOpen, $planUnidad->settings_status);

//echo $condicion;
//die();

$modelTrazabilidad = PlanificacionOpciones::find()
    ->where(['tipo' => 'TRAZABILIDAD_PAI'])
    ->andWhere(['seccion' => 'PAI'])
    ->all();
$arrayTrazabilidad = ArrayHelper::map($modelTrazabilidad, 'opcion', 'opcion');

$arrayVerificacion = array("SI" => "SI", "NO" => "NO", "REPLANIFICADO" => "REPLANIFICADO");

// echo "<pre>";
// print_r ($planUnidad);
// die();



?>


<!-- JS y CSS Ckeditor -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/jstree.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/themes/default/style.min.css"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>

<style>
    .btn {
        background-color: none;
    }

    /* .btn:hover {
        transform: scale(1.2);
    } */
</style>


<div class="planificacion-desagregacion-cabecera-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-9 col-md-9">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                    <small>
                        (
                        <?=
                            $planUnidad->planCabecera->ismAreaMateria->materia->nombre . ' - '
                            . $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name
                            ?>
                        )
                    </small>
                </div>
                <div class="col-lg-3 col-md-3" style="text-align: right;">
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificación Temas</span>',
                            ['index1', 'id' => $planUnidad->plan_cabecera_id],
                            ['class' => 'link']
                        );
                    ?>
                </div>
                <hr>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin: 0.5rem 1rem 1rem 1rem;">

                <div class="col-lg-12">

                    <div class="row">
                        <div id="div-mostrar-Arbol" class="card col-md-4 card-body"></div>
                        <div id="div-mostrar-Tema" class="card col-md-8 card-body"></div>
                    </div>
                </div>


            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const agregarTemaBtn = document.getElementById("agregarTemaBtn");
        const agregarTemaForm = document.getElementById("agregarTemaForm");

        agregarTemaBtn.addEventListener("click", function () {
            agregarTemaForm.style.display = "block";
        });
    });

</script>


<!--Funciones PHP-->


<?php

$title = $planUnidad->unit_title;
// echo "<pre>";
// print_r($title);
// die();

?>

<script>

    // $('#btn_mostrar_arbol').click(function (planUnidad) {
    //     mostrarArbol(
    //         var params = {
    //             planUnidad: planUnidad,
    //         }
    //     );
    // });
    mostrarArbol();

    function mostrarArbol() {
        var url = '<?= Url::to(['arbol']) ?>';
        var planUnidadId = '<?= $planUnidad->id ?>';
        // alert(planUnidadId);

        var params = {
            plan_unidad_id: planUnidadId
            // title: title
        }

        $.ajax({
            data: params,
            url: url,
            type: 'get',
            beforeSend: function (response) { },
            success: function (response) {
                $('#div-mostrar-Arbol').html(response);
            }
        });
    }


    function mostrarTema(subtituloId, planUnidadId, subtitulo) {
        event.preventDefault();
        var url = '<?= Url::to(['tema']) ?>';
        var planUnidadId = '<?= $planUnidad->id ?>';

        var params = {
            subtitulo_id: subtituloId,
            plan_unidad_id: planUnidadId,
            subtitulo: subtitulo
        };

        $.ajax({
            data: params,
            url: url,
            type: 'get',
            beforeSend: function (response) { },
            success: function (response) {
                $('#div-mostrar-Tema').html(response);
                busca_subtitulos2(subtitulo);
            }
        });
    }

    function ActualizarSub() {
        var planUnidadId = '<?= $planUnidad->id ?>';
        // alert(planUnidadId);

        var params = {
            plan_unidad_id: planUnidadId
            // title: title
        }

        $.ajax({
            data: params,
            url: url,
            type: 'get',
            beforeSend: function (response) { },
            success: function (response) {
                mostrarTema();
            }
        });
    }



</script>

<script>
    document.getElementById('enunciado_indagacion').contentEditable = true;
</script>