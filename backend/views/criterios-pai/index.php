<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asignación de criterios PAI';
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<div class="criterios-pai-index" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/calendario.png" width="34px" style="" class="img-thumbnail"></h4>
                </div>

                <div class="col-lg-4 col-md-4">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>

                <div class="col-lg-2 col-md-2">
                    <select name="cursos" onchange="showAreasAll()" id="cursos" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                        <option selected="selected" value="">Escoja un curso...</option>
                        <?php
                        foreach ($courses as $nivel) {
                            echo '<option value="' . $nivel['id'] . '">' . $nivel['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-lg-3 col-md-3">
                    <div id="div-area" style="display: none">
                        <select name="areas" onchange="showAreaOne()" id="areas" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                            <option selected="selected" value="">Escoja área...</option>
                            <?php
                            foreach ($areas as $area) {
                                echo '<option value="' . $area['id'] . '">' . $area['nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!--botones derecha-->
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    );
                    ?>
                    |
                </div>
                <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->

            </div>


            <!--comienza cuerpo de documento-->
            <hr>
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-lg-4 col-md-4">

                    <p>
                        <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="btn-add" style="display: none; background-color: #0a1f8f; font-size: 11px;">
                            Agregar nuevo criterio
                        </a>
                    </p>

                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <p>
                                <select name="criterio" id="criterio" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                                    <option selected="selected" value="">Escoja un criterio...</option>
                                    <?php
                                    foreach ($criterios as $crit) {
                                        echo '<option value="' . $crit->id . '">' . $crit->nombre . '</option>';
                                    }
                                    ?>
                                </select>
                            </p>

                            <p>
                                <select name="criterio-literal" id="criterio-literal" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                                    <option selected="selected" value="">Escoja un criterio literal...</option>
                                    <?php
                                    foreach ($criteriosLiteral as $cLiteral) {
                                        echo '<option value="' . $cLiteral->id . '">' . $cLiteral->nombre_espanol . '</option>';
                                    }
                                    ?>
                                </select>
                            </p>


                            <p>
                                <select name="descriptor" id="descriptor" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                                    <option selected="selected" value="">Escoja un descriptor...</option>
                                    <?php
                                    foreach ($descriptores as $descrip) {
                                        echo '<option value="' . $descrip->id . '">' . $descrip->nombre . '</option>';
                                    }
                                    ?>
                                </select>
                            </p>


                            <p>
                                <select name="descriptor-literal" id="descriptor-literal" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                                    <option selected="selected" value="">Escoja un descriptor literal...</option>
                                    <?php
                                    foreach ($decripLiteral as $dLiteral) {
                                        echo '<option value="' . $dLiteral->id . '">' . $dLiteral->descripcion . '</option>';
                                    }
                                    ?>
                                </select>
                            </p>

                            <p>
                                <button class="btn" onclick="add_descriptor()" style="background-color: #ff9e18; width: 100%; color: white">Agregar descriptor</button>
                            </p>
                        </div>
                    </div>
                </div>


                <div class="col-lg-8 col-md-8">
                    <div class="row" id="div-detalle-x-area"></div>
                </div>
            </div>
            <!--finaliza cuerpo de documento-->


        </div>
    </div>

</div>

<script>
    buscador();

    function buscador() {
        $('.select2').select2({
            closeOnSelect: true
        });
    }



    function showAreasAll() {
        $("#div-area").show();
        var course = $('#cursos').val();
        var url = '<?= Url::to(['actions']) ?>';
        var params = {
            course_id: course,
            field: 'search_areas'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                $("#table-body").html(response);
                //console.log(response);
            }
        });
    }


    function showAreaOne() {

        var course = $('#cursos').val();
        var area = $('#areas').val();

        if (course == null && area == null) {
            alert('es nulo');
        } else {
            $('#btn-add').show();
        }

        var url = '<?= Url::to(['actions']) ?>';
        var params = {
            course_id: course,
            area_id: area,
            field: 'search_by_area'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                $("#div-detalle-x-area").html(response);
                //console.log(response);
            }
        });
    }


    function add_descriptor() {


        var course = $('#cursos').val();
        var area = $('#areas').val();
        var criterio = $('#criterio').val();
        var criterioLiteral = $('#criterio-literal').val();
        var descriptor = $('#descriptor').val();
        var descriptorLiteral = $('#descriptor-literal').val();

        var url = '<?= Url::to(['actions']) ?>';
        var params = {
            course_id: course,
            area_id: area,
            criterio_id: criterio,
            criterio_literal_id: criterioLiteral,
            descriptor_id: descriptor,
            descriptor_literal_id: descriptorLiteral,
            field: 'add_descriptor'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                showAreaOne();
            }
        });


    }
</script>