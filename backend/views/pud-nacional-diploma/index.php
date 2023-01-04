<!--pasa variables objetos:
$planUnidad
$pudPep;-->
<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'PUD NACIONAL - DIPLOMA - ' . $planUnidad->curriculoBloque->last_name .
    ' - ' . $planUnidad->unit_title .
    ' - ' . $planUnidad->planCabecera->ismAreaMateria->materia->nombre;
$this->params['breadcrumbs'][] = $this->title;


?>

<!--Scripts para que funcionen AJAX'S-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<!-- <script src="https://cdn.ckeditor.com/4.17.1/basic/ckeditor.js"></script> -->


<div class="pud-pep-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8">
                    <h6><?= Html::encode($this->title) ?></h6>
                    <small>
                        <h6>
                            <?= $totalSemanas ?> Semanas
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

            <div class="accordion" id="accordionExample" style="margin-top: 10px; margin-bottom: 10px;">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" 
                                style="background-color: #e7f1ff;">
                            Objetivos específicos de la unidad
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <?php
                            foreach ($objetivos as $obj) {
                            ?>
                                <p>
                                    <strong><?= $obj->codigo ?></strong> <?= $obj->contenido ?>
                                </p>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" 
                                style="background-color: #e7f1ff;">
                            ¿Qué va a aprender?<br>
                            Destrezas con criterios de desempeño
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <?php
                            foreach ($criterios as $cri) {
                            ?>
                                <strong><?= $cri->criterioEvaluacion->code ?></strong> <?= $cri->criterioEvaluacion->description ?>
                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>

                <!-- inicio de actividades de aprendizaje -->
                <?= Html::beginForm(['add-activities'], 'post', ['enctype' => 'multipart/form-data']) ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapseThree" aria-expanded="false" 
                                aria-controls="collapseThree"
                                style="background-color: #e7f1ff;">
                            ¿Cómo va a aprender?<br>
                            Actividades de aprendizaje
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">

                            <input type="hidden" name="id" value="<?= $planUnidad->id ?>">

                            <textarea name="actividades" id="editor">
                                    <?= $planUnidad->actividades_aprendizaje ?>
                                </textarea>

                            <div class="form-group">
                                <?= Html::submitButton('GUARDAR', ['class' => 'btn btn-success']) ?>
                            </div>

                        </div>
                    </div>
                </div>


                <?= Html::endForm() ?>
                <!-- fin de actividades de aprendizaje -->


                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" 
                                type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapseFour" 
                                aria-expanded="false" aria-controls="collapseThree"
                                style="background-color: #e7f1ff;">
                            ¿Qué y cómo evaluar?<br>
                            Indicadores de evaluación de la unidad
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <?php
                                foreach($indicators as $ind){
                                    ?>
                                        <strong><?= $ind['code'] ?></strong> <?= $ind['description'] ?>
                                    <?php
                                }

                            ?>                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- fin cuerpo de card -->
        </div>
    </div>


    <script>
        var actividad = CKEDITOR.replace('editor', {
            customConfig: '/ckeditor_settings/config.js',
        });
    </script>