<?php

use backend\models\PlanificacionVerticalDiplomaHabilidades;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use yii\helpers\Html;

//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación Semanal | ' . $semana['nombre_semana'];
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($seccion);
// die();

$helper = new \backend\models\helpers\HelperGeneral();
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
                    <small>
                        <?=  ' | desde: ' . $desde . ' | hasta: ' . $hasta .' | '?>
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


                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    |
                    <?php
                    $fechaAnterior = date("Y-m-d", strtotime($desde . "- 7 days"));
                    $fechaSiguiente = date("Y-m-d", strtotime($desde . "+ 7 days"));

                    echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="fa fa-briefcase" aria-hidden="true"></i> Anterior</span>',
                            ['index1', 'desde' => $fechaAnterior],
                            ['class' => 'link']
                    );
                    ?>

                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Siguiente</span>',
                            ['index1', 'desde' => $fechaSiguiente],
                            ['class' => 'link']
                    );
                    ?>

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->


            <hr>

            <!-- inicia cuerpo de card -->

            <div class="row">
                <div class="col-lg-9 col-md-9">
                    <div class="card" style="padding: 20px; margin-bottom: 20px; border: solid 1px #ab0a3d">
                        
                        <p style="color: #ab0a3d">
                            <b><u>Detalle de actividades de la semana</u></b>
                        </p>
                        
                        <div class="table table-responsive" style="font-size: 10px">

                        <table class="table table-condensed table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">DÍA</th>
                                    <th class="text-center" width="5%">HORA</th>
                                    <th class="text-center" width="10%">ASIGNATURA</th>
                                    <th class="text-center" width="15%">TÍTULO</th>
                                    <th class="text-center" width="23%">ENSEÑANZAS</th>
                                    <th class="text-center" width="19%">TAREAS</th>
                                    <th class="text-center" width="8%">INSUMO</th>
                                    <th class="text-center" width="5%">ES CAL</th>
                                    <th class="text-center" width="5%">TIPO</th>
                                    <th class="text-center" width="5%"></th>
                                </tr>
                            </thead>
                            <?php
                            foreach ($actividades as $actividad) {

                                $dia = $helper->get_dia_fecha($actividad['inicio']);
                                ?>
                                <tr>
                                    <td><?= $dia ?></td>
                                    <td class="text-center"><?= $actividad['sigla'] ?></td>
                                    <td><?= $actividad['materia'] ?></td>
                                    <td><?= $actividad['title'] ?></td>
                                    <td><?= $actividad['enseñanza'] ?></td>
                                    <td><?= $actividad['tareas'] ?></td>
                                    <td><?= $actividad['nombre_nacional'] ?></td>
                                    <td class="text-center"><?= $actividad['es_calificado'] ?></td>
                                    <td class="text-center"><?= $actividad['tipo_calificacion'] ?></td>
                                    <td class="text-center">
                                        <?= 
                                            Html::a('<i class="fas fa-edit"></i>',['scholaris-actividad/actividad',
                                                'actividad' => $actividad['id']
                                            ]);
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>

                    </div>
                    </div>
                    
                </div> 
<!--                fin de div card de actividades-->
                
                <div class="col-lg-3 col-md-3">
                    <div class="card" style="padding: 10px; border: solid 1px #0a1f8f; background-color: #eee;">
                        
                        <p style="color: #0a1f8f"><b><u>Detalle de plan semanal</u></b></p>
                        
                        <div class="table table-responsive">
                        <table class="table table-striped table-condensed" style="font-size: 10px; color: black">
                            <thead>
                                <tr>
                                    <th class="text-center">CURSO</th>
                                    <th class="text-center">PLAN</th>
                                    <th class="text-center">APROB</th>
                                    <th class="text-center">ACCIÓN</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                foreach ($cursos as $curso) {
                                    $cursoId = $curso['course_template_id'];
                                    ?>
                                    <tr>
                                        <td class="" style="color: black"><?= $curso['course_template'] ?></td>

                                        <?php
//                                            echo '<pre>';
//                                            print_r($planesSemanales);
//                                            die();
//                                            
                                        if ($planesSemanales['semana_id']) {
                                            echo '<td class="text-center">';
                                            if ($planesSemanales['experiencias_aprendizaje'] == 'No conf' || $planesSemanales['evaluacion_continua'] == 'No conf') {
                                                echo '<i class="fas fa-pause-circle"></i>';
                                                echo '</td>';
                                                echo '<td class="text-center">';
                                                if ($planesSemanales['es_aprobado']) {
                                                    echo '<i class="fas fa-thumbs-up" style="color: green"></i>';
                                                } else {
                                                    echo '<i class="fas fa-thumbs-down" style="color: #ab0a3d"></i>';
                                                }

                                                echo '</td>';
                                            } else {
                                                echo '<i class="fas fa-check-circle" style="color: green"></i>';
                                                echo '</td>';
                                                echo '<td class="text-center">';
                                                if ($planesSemanales['es_aprobado']) {
                                                    echo '<i class="fas fa-thumbs-up" style="color: green"></i>';
                                                } else {
                                                    echo '<i class="fas fa-thumbs-down" style="color: #ab0a3d"></i>';
                                                }

                                                echo '</td>';
                                            }
                                        } else {
                                            echo '<td class="text-center">';
                                            echo '<i class="fas fa-times-circle" style="color: #ab0a3d"></i>';
                                            echo '</td>';
                                            echo '<td class="text-center">';
                                            echo '<i class="fas fa-thumbs-down" style="color: #ab0a3d"></i>';
                                            echo '</td>';
                                        }
                                        echo '<td class="text-center">';
                                        echo Html::a('<i class="fas fa-cogs" style="color: black"></i>', ['configurar',
                                            'op_course_template_id' => $curso['course_template_id'],
                                            'semana_id' => $semana['semana_id'],
                                            'pep_planificacion_id' => $planesSemanales['planificacion_id'],
                                        ]);
                                        echo '</td>';

//                                        foreach ($planesSemanales as $ps) {
//                                            if ($curso['course_template_id'] == $ps['op_course_template_id']) {
//                                                if (!is_null($ps['experiencias_aprendizaje']) || !is_null($ps['evaluacion_continua']) || !is_null($ps['semana_id'])) {
//                                                    echo '<td class="text-center">';
//                                                    echo '<i class="fas fa-check-circle" style="color: green"></i>';
//                                                    echo '</td>';
//                                                    echo '<td class="text-center">';
//                                                    if($ps['es_aprobado']){
//                                                        echo '<i class="fas fa-thumbs-up" style="color: green"></i>';
//                                                    }else{
//                                                        echo '<i class="fas fa-thumbs-down" style="color: #ab0a3d"></i>';
//                                                    }
//                                                    
//                                                    echo '</td>';
//                                                } else {
//                                                    echo '<td class="text-center">';
//                                                    echo '<i class="fas fa-times-circle" style="color: #ab0a3d"></i>';
//                                                    echo '</td>';
//                                                    echo '<td class="text-center">';
//                                                    echo '<i class="fas fa-thumbs-down" style="color: #ab0a3d"></i>';
//                                                    echo '</td>';
//                                                }
//
//                                                echo '<td class="text-center">';
//                                                echo Html::a('<i class="fas fa-cogs" style="color: #0a1f8f"></i>', ['configurar',
//                                                    'planificacion_id' => $ps['planificacion_id'],
//                                                    'op_course_template_id' => $ps['op_course_template_id'],
//                                                    'semana_id' => $semana['semana_id']
//                                                ]);
//                                                echo '</td>';
//                                            }
//                                        }
                                        ?>

                                    </tr> 
    <?php
}
?>
                            </tbody>

                        </table>
                    </div>  
                    </div>
                </div>
            </div>




            <!-- fin cuerpo de card -->
        </div>
    </div>
</div>

<script>
    function ver_detalle(fecha) {
        var url = '<?= yii\helpers\Url::to(['detalle']) ?>';

        params = {
            fecha: fecha
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (resp) {
                $('#div-detalle').html(resp);
            }
        });

    }
</script>
