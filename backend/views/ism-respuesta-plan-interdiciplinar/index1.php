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


$this->title = 'PUD INTERDISCIPLINAR - ' . $planUnidad->curriculoBloque->last_name . ' - ' . $planUnidad->unit_title;
$this->params['breadcrumbs'][] = $this->title;


?>

<!--Scripts para que funcionen AJAX'S-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>


<div class="ism-respuesta-plan-interdiciplinar-index">
    <input id="id_grupo_inter" type="text" hidden="true" readonly="true" value="<?=$idGrupoInter?>"/>
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class="row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" class="img-thumbnail"></h4>
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
                <div class="col-lg-2 col-md-2" style="overflow-y: scroll; height: 650px;width: 25%;   border-top: solid 1px #ccc;">
                    <?= $this->render('menu', [
                        'planUnidad' => $planUnidad
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
</div>