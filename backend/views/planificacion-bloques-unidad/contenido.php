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


?>

<!-- JS y CSS Ckeditor -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>



<div class="planificacion-desagregacion-cabecera-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-9">
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
                <div class="col-lg-2 col-md-2" style="text-align: right;">
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
            </div><!-- FIN DE CABECERA -->



            <!-- inicia cuerpo de card -->
            <div class="row" style="margin: 0.5rem 1rem 1rem 1rem;">

                


                <div class="col-lg-12">
                    <?php
                    // echo "<pre>";
                    // print_r ($planUnidad);
                    // die();
                    
                    ?>

                    <div class="row">


                        <div id="div-mostrar-Arbol" class="col-md-6 card-body">
                    
                        </div>

                        <div id="div-mostrar-Tema" class="col-md-6 card-body">

                        </div>
                    </div>
                </div>

                <!--Columna donde muestra contenido-->
                <!-- <div class="col-lg-6 col-md-6 card">
                    <div style="text-align: center">
                        <h5>TEMARIO</h5>
                    </div>
                </div> -->
            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>




<!--Funciones PHP-->
<?php

function busca_subtitulos2($subtituloId)
{
    $model = backend\models\PlanificacionBloquesUnidadSubtitulo2::find()->where([
        'subtitulo_id' => $subtituloId
    ])
        ->orderBy('orden')
        ->all();

    return $model;
}

?>

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

    mostrarTema();

    function mostrarTema() {
        var url = '<?= Url::to(['tema']) ?>';
        var planUnidadId = '<?= $planUnidad->id ?>';

        var params = {
            plan_unidad_id: planUnidadId
        }

        $.ajax({
            data: params,
            url: url,
            type: 'get',
            beforeSend: function (response) { },
            success: function (response) {
                $('#div-mostrar-Tema').html(response);
            }
        });
    }
</script>