<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeceCasosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Incidente/Caso';
$this->params['breadcrumbs'][] = $this->title;

// echo "<pre>";
// print_r($casos);
// die();
?>
<!--Scripts para que funcionen AJAX de select 2 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />


<div class="dece-casos-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/autismo.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-5 col-md-5">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                </div>
                <!-- FIN DE CABECERA -->
                <!-- inicia menu  -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- menu izquierda -->

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
                            '<span class="badge rounded-pill" style="background-color:#9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Registro General Acompañamiento</span>',
                            ['reg-gen-acompaniamiento'],
                            ['class' => 'link']
                        );
                    ?>
                </div> <!-- fin de menu izquierda -->



                <!-- finaliza menu menu  -->
                <hr>
                <!--Inicia Card Principal-->
                <div class="row" style="margin-top: 20px">
                    <div class="col-lg-12 col-md-12 text-center">
                        <?= Html::beginForm(['create', 'idEstudiante' => 0], 'post') ?>
                        <div class="row">
                            <div class="col-lg-10 col-md-10">
                                Total:
                                <?= count($estudiantes) ?>
                                <select id="idAlumno" name="idAlumno"
                                    class="form-control select2 select2-hidden-accessible" style="width: 100%;"
                                    tabindex="-1" aria-hidden="true">
                                    <option selected="selected" value="">Escoja un estudiante...</option>
                                    <?php
                                    foreach ($estudiantes as $estudiante) {
                                        echo '<option value="' . $estudiante['id'] . '">' . $estudiante['student'] . ' - ' . $estudiante['curso'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2" style="text-align: center; margin-top: 28px;">
                                <?= Html::submitButton('Crear Caso', [
                                    'class' => 'submit btn btn-primary my-text-medium',
                                    
                                ])
                                    ?>
                            </div>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                    <hr>
                    <div class="col-lg-12 col-md-12">
                        <h6 class="my-text-medium">ESTUDIANTES EN SEGUIMIENTO:
                            <?= count($casos) ?>
                        </h6>
                        <div class="table responsive">
                            <table class="table table-hover table-striped my-text-medium">
                                <thead>
                                    <tr style="text-align:center">

                                        <td><strong>Estudiante</strong></td>
                                        <td><strong>Casos</strong></td>
                                        <td><strong>Acompañamientos.</strong></td>
                                        <td><strong>Detecciones.</strong></td>
                                        <td><strong>Derivaciones.</strong></td>
                                        <td><strong>Intervenciones.</strong></td>
                                        <td><strong>Acción.</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($casos as $caso) {

                                        $nombreEstudiante = $caso['nombre'];
                                        //$nombreEstudiante = $caso->estudiante->middle_name . ' ' . $caso->estudiante->first_name . ' ' . $caso->estudiante->last_name
                                        ?>
                                        <tr style="text-align:center">
                                            <td>
                                                <?= $nombreEstudiante ?>
                                            </td>
                                            <td><strong>
                                                    <?= $caso['casos'] ?>
                                                </strong></td>
                                            <td><strong>C:
                                                    <?= $caso['casos_seguimiento'] ?> <br> A:
                                                    <?= $caso['seguimiento'] ?>
                                                </strong></td>
                                            <td><strong>C:
                                                    <?= $caso['casos_deteccion'] ?> <br> D:
                                                    <?= $caso['deteccion'] ?>
                                                </strong></td>
                                            <td><strong>C:
                                                    <?= $caso['casos_derivacion'] ?> <br> D:
                                                    <?= $caso['derivacion'] ?>
                                                </strong></td>
                                            <td><strong>C:
                                                    <?= $caso['casos_intervencion'] ?> <br> I:
                                                    <?= $caso['intervencion'] ?>
                                                </strong></td>
                                            <td>
                                                <?= Html::a(
                                                    '<span class="badge  rounded-pill" style="background-color:red;font-size:14px;">Ver</span>',
                                                    ['dece-casos/historico', 'id' => $caso['id_estudiante'], 'id_clase' => 0],
                                                    ['class' => 'link']
                                                ); ?>
                                            </td>
                                        </tr>
                                        <?php
                                    } //fin for
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr style="text-align:center">
                                        <th><strong style="color:brown">Totales --></strong></th>
                                        <th>
                                            <?= $conteoEjesDeAccion[0] ?>
                                        </th>
                                        <th>A:
                                            <?= $conteoEjesDeAccion[1] ?>
                                        </th>
                                        <th>D:
                                            <?= $conteoEjesDeAccion[2] ?>
                                        </th>
                                        <th>D:
                                            <?= $conteoEjesDeAccion[3] ?>
                                        </th>
                                        <th>I:
                                            <?= $conteoEjesDeAccion[4] ?>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>

                <!-- <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'numero_caso',
                        'id_estudiante',
                        'id_periodo',
                        'estado',
                        //'fecha_inicio',
                        //'fecha_fin',
                        //'detalle:ntext',
                        //'id_usuario',
                
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?> -->
            </div>
        </div>
    </div>
</div>
<!-- SCRIPT PARA SELECT2 -->
<script>
    buscador();

    function buscador() {
        $('.select2').select2({
            closeOnSelect: true
        });
    }
</script>