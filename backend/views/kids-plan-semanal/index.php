<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan Semanal';

// echo '<pre>';
// // print_r($pca->opCourse);
// print_r($semanas);
?>

<div class="kids-plan-semanal-index1">

    <div class="" style="padding-left: 40px; padding-right: 40px">

        <div class="m-0 vh-50 row justify-content-center align-items-center">
            <div class="card shadow col-lg-12 col-md-12">

                <!-- comienza encabezado -->
                <div class="row" style="background-color: #ccc; font-size: 12px">
                    <div class="col-md-12 col-sm-12">
                        <p style="color:white">
                            |
                            <?=
                                Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                    ['site/index'],
                                    ['class' => 'link']
                                );
                            ?>
                            |
                            <?=
                                Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificaciones</span>',
                                    [
                                        'kids-menu/index1'
                                    ]
                                );
                            ?>
                            |

                        </p>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <h5 style="color:white">Prebásica Plan Semanal</h5>
                    </div>
                    <hr>
                    <div class="col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <strong>NIVEL:
                                    <?= $pca->opCourse->name ?>
                                </strong>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Fin de encabezado -->

                <!--comienza cuerpo de documento-->
                <div class="row" style="background-color: #fff; margin-top:20px; margin-bottom:10px;">
                    <div class="col-md-5 col-lg-5">
                        <label for="">Seleccion una experiencia</label>
                        <select class="form-select" id="select-exp" onchange="cambia_select()">
                            <option value="" selected="">--- Experiencia ---</option>
                            <?php
                            foreach ($experiencias as $key => $exp):
                                ?>
                                <option value="<?= $exp->id ?>"><?= $exp->experiencia ?></option>
                                <?php
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <div class="col-md-5 col-lg-5">
                        <label for="">Seleccione una semana</label>
                        <select class="form-control" name="semana_id" id="semana_id">
                            <option value="" selected="">--- Semana ---</option>
                            <?php foreach ($semanas as $keySem => $semana) {
                                ?>
                                <option value="<?= $semana['semana_id'] ?>"><?= $semana['semana_nombre'] ?></option>
                                <?php
                            }
                            ?>

                        </select>
                    </div>

                    <div class="col-lg-2 col-md-2" style="margin-top: 20px;" >
                        <button id="btn_inserta_experiencia" type="button" class="btn btn-success">Agregar</button>
                    </div>

                </div>

                <div class="row" style="margin-top:10px;background-color: #ccc;" id="div-exp">
                    <div class="col-md-6 col-sm-6">
                        <div class="" style="padding:10px">
                            <h4 class="text-primero" id="title-exp">Seleccione una experiencia</h4>
                            <!-- Muestra texto del select -->
                        </div>
                    </div>
                </div>

                <!-- Muestra contenido que viene del _ajax-semanas.php -->
                <div id="resp-ajax1"></div>




                <!--finaliza cuerpo de documento-->

            </div>

        </div>

    </div>
</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
    crossorigin="anonymous"></script>
<script>
    $(function () {
        semanas(0);
        // $("#table-exp").dataTable();
    });


    //on change del select para mostrar texto en div
    function cambia_select() {
        var text = $('select[id="select-exp"] option:selected').text();
        var idExp = $('select[id="select-exp"] option:selected').val();
        //alert(idExp);
        $("#title-exp").html('Estás trabajando con : "' + text + '"');
        // $("#div-exp").show();
        semanas(idExp);
    };

    function semanas(idExp) {
        // alert(idExp);
        var cursoId = '<?= $pca->opCourse->id ?>';
        var pcaId = '<?= $pca->id ?>';
        //alert(pcaId);
        // alert(idExp);
        var url = "<?= Url::to(['ajax-semanas']) ?>";
        var params = {
            op_course_id: cursoId,
            pca_id: pcaId,
            experiencia_id: idExp
        }

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: function (resp) {
                $("#resp-ajax1").html(resp);
            }
        });

    }

    //Funcion para agregar experiencia y semana onclick button
    $("#btn_inserta_experiencia").on("click", function () {
        $("#btn_inserta_experiencia").prop("disabled", true);
        var experiencia_id = $("#select-exp").val();
        var semana_id = $("#semana_id").val();

        if (experiencia_id == '' || semana_id == '') {
            alert("Debe seleccionar experiencia y semana!");
            $("#btn_inserta_experiencia").prop("disabled", false);
            return false;
        }

        agregar(semana_id, experiencia_id);
        $("#btn_inserta_experiencia").prop("disabled", false);




    });


    function agregar(semanaId, experienciaId) {

        // alert(experienciaId);
        // alert(semanaId);
        if (experienciaId == 0) {
            alert('Debe seleccionar una experiencia!!!');
            return false;
        }

        var url = '<?= Url::to(['ajax-insert-experiencia']) ?>';
        var params = {
            experiencia_id: experienciaId,
            semana_id: semanaId
        };

        $.ajax({
            url: url,
            data: params,
            type: 'POST',
            success: function () {
                semanas(experienciaId);
            }
        });

    }


</script>