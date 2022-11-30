<?php

use backend\models\PlanificacionVerticalDiplomaHabilidades;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use yii\helpers\Html;
//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación Vertical Diploma';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($seccion);
// die();
?>

<div class="planificacion-vertical-pai-criterios-index">
    <!-- CABECERA -->
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"  class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small><b>BLOQUE Nº: </b> 
                        <?= $planUnidad->curriculoBloque->last_name ?><b> | 
                            <?= $planUnidad->unit_title ?></b> | 
                        <?= $planUnidad->planCabecera->ismAreaMateria->materia->nombre ?></b>
                    </small>
                </div>
            </div>
            <!-- FIN DE CABECERA -->


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
                            '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificación</span>',
                            ['planificacion-bloques-unidad/index1', 'id' => $planUnidad->plan_cabecera_id],
                            ['class' => 'link']
                    );
                    ?>
                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                   
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Actualizar detalles</span>',
                            ['update', 'id' => $planVerticalDiploma->id],
                            ['class' => 'link']
                    );
                    ?>
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="fas fa-grip-horizontal" aria-hidden="true"></i> Opciones Horizontal IB</span>',
                            ['horizontal', 'plan_vertical_id' => $planVerticalDiploma->id],
                            ['class' => 'link']
                    );
                    ?>                         
                    |
                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->


            <hr>

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 10px; margin-left:1px;margin-right:1px; margin-bottom:5px">
                <div class="table table-responsive">
                    <table class="table table-hover table-condensed table-striped table-bordered my-text-medium">
                        <thead>
                            <tr style="background-color: #ab0a3d; color: #eee">
                                <th class="text-center">OBJETIVOS DE LA UNIDAD</th>
                                <th class="text-center">CONCEPTOS CLAVE</th>
                                <th class="text-center">CONTENIDOS</th>
                                <th class="text-center">
                                    <!-- Button trigger modal -->
                                    <a href="#" class="zoom" data-bs-toggle="modal" 
                                       data-bs-target="#exampleModal" 
                                       style="color: white"
                                       onclick="show_habilidades()">
                                        HABILIDADES IB <i class="fas fa-mouse" style="color: white"></i>
                                    </a>
                                </th>
                                <th class="text-center">EVALUACIÓN PD</th>                                    
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td><?= verifica_vacio($planVerticalDiploma->objetivo_asignatura); ?></td>
                                <td><?= verifica_vacio($planVerticalDiploma->concepto_clave); ?></td>
                                <td><?= verifica_vacio($planVerticalDiploma->contenido); ?></td> 
                                <td>
                                    <div id="div-habilidades-seleccionadas"></div>
                                </td>
                                <td><?= verifica_vacio($planVerticalDiploma->objetivo_evaluacion); ?></td>                                                                                                           
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Seleccione Enfoque de Aprendizaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="div-habilidades"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<?php

function verifica_vacio($contenido) {
    $html = '';
    if ($contenido == 'Sin contenido') {
        $html .= '<i class="fas fa-exclamation-triangle" style="color: #ab0a3d">' . $contenido . '</i>';
    } else {
        $html .= '<p>' . $contenido . '</p>';
    }

    return $html;
}

function verifica_relacion_tdc($objeto) {
    $html = '';
    if (count($objeto) <= 0) {
        $html .= '<i class="fas fa-exclamation-triangle" style="color: #ab0a3d">Sin opciones elejidas</i><br>';
    } else {
        foreach ($objeto as $obj) {
            $html .= '<i class="fas fa-check" style="color: green"> ' . $obj->relacionTdc->opcion . '</i><br>';
        }
    }

    return $html;
}

function verifica_habilidades($objeto) {

    //$prueba = PlanificacionVerticalDiplomaHabilidades::find();

    $html = '';
    if (count($objeto) <= 0) {
        $html .= '<i class="fas fa-exclamation-triangle" style="color: #ab0a3d">Sin opciones elejidas</i><br>';
    } else {
        foreach ($objeto as $obj) {
            $html .= '<i class="fas fa-check" style="color: green"> ' . $obj['es_titulo2'] . '</i><br>';
        }
    }

    return $html;
}
?>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script>
    
    show_habilidades_seleccionadas();
    
    function show_habilidades(){
        var url = '<?= \yii\helpers\Url::to(["ajax-habilidades"]) ?>';
        var planVerticalDipId = '<?= $planVerticalDiploma->id ?>';
        
        params = {
            plan_vertical_id : planVerticalDipId
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (resp) {
                        $('#div-habilidades').html(resp);
                    }
        });
        
    }    
    
    function show_habilidades_seleccionadas(){
        var url = '<?= \yii\helpers\Url::to(["ajax-habilidades-seleccionadas"]) ?>';
        var planVerticalDipId = '<?= $planVerticalDiploma->id ?>';
        
        params = {
            plan_vertical_id : planVerticalDipId
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (resp) {
                        $('#div-habilidades-seleccionadas').html(resp);
                    }
        });
    }
    
</script>