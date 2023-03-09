<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\controllers\PudPepController;
use backend\models\IsmGrupoMateriaPlanInterdiciplinar;
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


$this->title = 'PUD INTERDISCIPLINAR - ' . $planUnidad->curriculoBloque->last_name ;
$this->params['breadcrumbs'][] = $this->title;

//extraemos las materias que pértenecen al grupo para la planificacion

$modelIsmGrupoMaterias = IsmGrupoMateriaPlanInterdiciplinar::find()
->where(['id_grupo_plan_inter'=>$idGrupoInter])
->all();

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
                <div class="col-lg-6">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <h4><?= 'Tema:  ' . $planUnidad->unit_title; ?></h4>
                </div>
                <div class="col-lg-3">
                    <!-- <small>
                        <h6>
                            (
                            Curso: <?= $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name ?> |
                            Id: <?= $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->id ?> |
                            Materia: <?= $planUnidad->planCabecera->ismAreaMateria->materia->nombre ?> |
                            Id: <?= $planUnidad->planCabecera->ismAreaMateria->materia->id ?>
                            )
                        </h6>
                    </small> -->
                    <div class="card ">
                        <div class="card-header">
                           <div class="row">
                                <div class="col"><span style="color:red">Grupo</div>
                                <div class="col"><span style="color:red">Curso</div>
                                <div class="col"><span style="color:red">Materia</div>
                            </div>
                        </div>
                        <div class="card-body">
                        <?php
                            foreach($modelIsmGrupoMaterias as $modelGrupo)
                            {
                        ?>
                            <div class="row ">
                                <div class="col">
                                    <?=$modelGrupo->grupoPlanInter->nombre_grupo?>
                                </div>
                                <div class="col">
                                    <?=$modelGrupo->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name?>
                                </div>
                                <div class="col">
                                    <?=$modelGrupo->ismAreaMateria->materia->nombre ?>
                                </div>
                            </div>
                        <?php
                            }
                        ?>
                        </div>
                    </div>
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
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fas fa-file-pdf"></i>Generar Reporte PDF </span>',
                        ['pdf', 'idGrupoInter' =>$idGrupoInter,'idPlanUnidad'=>$planUnidad->id],
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