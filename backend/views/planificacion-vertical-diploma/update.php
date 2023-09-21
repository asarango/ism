<?php

use backend\models\PlanificacionVerticalDiploma;
use PHPUnit\Framework\Constraint\IsFalse;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actualizar Planificación Vertical Diploma';
$this->params['breadcrumbs'][] = $this->title;

//print_r($planUnidad);



?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>

<div class="planificacion-vertical-pai-criterios-index">
    <!-- CABECERA -->
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                    <small><b>BLOQUE Nº: </b>
                        <?php
                        //$modelPlanifVertDipl = PlanificacionVerticalDiploma::find();
                        ?>
                        <?= $modelPlanifVertDipl->planificacionBloqueUnidad->curriculoBloque->last_name ?><b> |
                            <?= $modelPlanifVertDipl->planificacionBloqueUnidad->unit_title ?>
                        </b> |
                        <?= $modelPlanifVertDipl->planificacionBloqueUnidad->planCabecera->ismAreaMateria->materia->nombre ?></b>
                    </small>
                </div>
                <div class="col-lg-3" style="text-align: right;">
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
                        '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificación Vertical</span>',
                        ['index1', 'unidad_id' => $modelPlanifVertDipl->planificacionBloqueUnidad->id],
                        ['class' => 'link']
                    );
                    ?>
                </div>
                <hr>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- inicia cuerpo de card -->

            <div class="card p-4" style="margin-top: -5px;margin-right: 5px;margin-left: 5px;margin-bottom: 5px; font-size: 10px">
                <?php $form = ActiveForm::begin(); ?>

                <div class="row p-3">
                    <div class="col-lg-2 col-md-2"><b>Objetivos de la Unidad</b></div>
                    <div class="col-lg-10 col-md-10">

                        <?= $form->field($modelPlanifVertDipl, 'objetivo_asignatura')->textarea(['rows' => 3])->label(false) ?>
                        <script>
                            CKEDITOR.replace("planificacionverticaldiploma-objetivo_asignatura");
                        </script>
                    </div>
                </div>

                <div class="row p-3">
                    <div class="col-lg-2 col-md-2"><b>Concepto Clave</b></div>
                    <div class="col-lg-10 col-md-10">
                        <?= $form->field($modelPlanifVertDipl, 'concepto_clave')->textarea(['rows' => 3])->label(false) ?>
                        <script>
                            CKEDITOR.replace("planificacionverticaldiploma-concepto_clave");
                        </script>
                    </div>
                </div>

                <?php
                if ($modelPlanifVertDipl->contenido == 'none') {
                    $concatenatedSubtitulos = '';

                    foreach ($subtitulos as $subtitulo) {
                        $concatenatedSubtitulos .= '&#9679; ' . $subtitulo['subtitulo'] . "\n";
                    }

                    $contenidoCk = $concatenatedSubtitulos;
                } else {
                    $contenidoCk = $modelPlanifVertDipl->contenido;
                }
                ?>

                <div class="row p-3">
                    <div class="col-lg-2 col-md-2"><b>Contenido</b></div>
                    <div class="col-lg-10 col-md-10">                        
                        <?= $form->field($modelPlanifVertDipl, 'contenido')->textarea(['rows' => 3, 'value' => $contenidoCk])->label(false) ?>
                        

                        <script>
                            CKEDITOR.replace('planificacionverticaldiploma-contenido');
                        </script>
                    </div>

                </div>

                <div class="row p-3">
                    <div class="col-lg-2 col-md-2"><b>Evaluación PD</b></div>
                    <div class="col-lg-10 col-md-10">
                        <?= $form->field($modelPlanifVertDipl, 'objetivo_evaluacion')->textarea(['rows' => 3])->label(false) ?>
                        <script>
                            CKEDITOR.replace("planificacionverticaldiploma-objetivo_evaluacion");
                        </script>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 10px; text-align: right;">
                    <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

            <!--             inicia formulario de tdc 
            <div class="card shadow" style="margin: 20px;">
            <div class="row p-3">
                <?=
                $this->render('formtdc', [
                    'modelPlanifVertDiplTDC' => $modelPlanifVertDiplTDC,
                    'modelPlanifVertDiplId' => $modelPlanifVertDipl->id
                ]);
                ?>
            </div>            
            </div>
             finaliza formulario de tdc 

                    <hr>
             inicia formulario de habilidades 
            <div class="card shadow" style="margin: 20px;">
            <div class="row p-3">
                <?=
                $this->render('formhabilidades', [
                    'modelPlanifVertDiplId' => $modelPlanifVertDipl->id,
                    'modelPlanifVertDiplHab' => $modelPlanifVertDiplHab
                ]);
                ?>
            </div>            
            </div>            -->
            <!-- finaliza formulario de habilidades -->

            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>