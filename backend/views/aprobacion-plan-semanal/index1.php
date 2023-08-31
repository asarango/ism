<?php

use backend\models\PlanificacionVerticalDiplomaHabilidades;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Aprobaciones de plan semanal  ';
$this->params['breadcrumbs'][] = $this->title;

// echo "<pre>";
// print_r($docentes);
// die();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<style>
    body {
        background-color: #f0f2f5;
    }

    .col {
        background-color: #eee;
        border: 1px solid #dddfe2;
        border-radius: 8px;
        padding: 10px;
    }

    .list-group-item {
        border: none;
        text-align: center;
        /* border: #385898 solid 1px; */
        text-decoration: none;
        color: black;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 12px;
        background-color: #eee;
    }

    .list-group-item a {
        background-color: #0a1f8f;
        text-decoration: none;
        color: white;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .list-group-item a:hover {
        /* text-decoration: underline; */
        background-color: #eee;
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        color: black;
    }

    #div-docentes {
        margin-top: 20px;
        display: none;
    }

</style>

<div class="planificacion-vertical-pai-criterios-index">
    <!-- CABECERA -->
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>

                </div>
                <div class="col-lg-3 col-md-3">
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Aprobaciones</span>',
                            ['planificacion-aprobacion/index'],
                            ['class' => 'link']
                        );
                    ?>


                </div> <!-- fin de menu izquierda -->
                <hr>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- inicia cuerpo de card -->

            <div class="row" style="padding: 20px">

                <div class="col col-lg-3 col-md-3">
                    <div id="div-select">
                        <select name="niveles" onchange="showDocentes()" id="select-semana"
                            class="form-control select2 select2-hidden-accessible no-border-select2"
                            style="width: 100%;">
                            <option selected="selected" value="">Selecciona una semana...</option>
                            <?php foreach ($semanas as $nivel): ?>
                                <option value="<?= $nivel['id'] ?>"><?= $nivel['nombre_semana'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="div-docentes">
                        <ul class="list-group">
                            <?php foreach ($docentes as $docente): ?>
                                <li class="card list-group-item">
                                    <a style="padding: 10px;" class="card" href="#"
                                        onclick="showDetail(<?= $docente['id'] ?>);">
                                        <?= $docente['docente'] ?>
                                    </a>

                                </li>

                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class=" col-lg-9 col-md-9" style="text-align: center;">
                    <p>Pre-Visualización</p>
                    <div class="table table-responsive" id="div-detalle">

                    </div>

                </div>

            </div>

            <!-- fin cuerpo de card -->
        </div>
    </div>
</div>

<script>
    $('#single-select-field').select2({
        theme: "bootstrap-5",
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder')
    });
</script>

<!-- SCRIPT PARA SELECT2 -->
<script>
    buscador();
    function buscador() {
        $('.select2').select2({
            closeOnSelect: true
        });
    }

</script>

<script>
    function showDocentes() {
        $("#div-docentes").show();
    }

    function showDetail(facId) {
        let semanaId = document.getElementById("select-semana").value;
        let url = '<?= Url::to(['ajax-detalle']) ?>';

        var params = {
            fac_id: facId,
            semana_id: semanaId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () { },
            success: function (response) {
                $("#div-detalle").html(response);
            }
        });

    }
</script>