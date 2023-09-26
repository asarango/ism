<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan Semanal';

// echo '<pre>';
// print_r($blocks);
// die();
?>

<style>
    .titulo {
        text-align: left;
        font-weight: bold;
        font-size: 20px;
    }
</style>

<div class="scholaris-asistencia-profesor-index" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/calendario.png" width="34px" style="" class="img-thumbnail"></h4>
                </div>

                <div class="col-lg-2 col-md-2">
                    <h5 class="titulo">
                        <?= Html::encode($this->title) ?>
                    </h5>
                </div>

                <div class="col-lg-3 col-md-3">
                    <select name="" id="select-bloques" onchange="showWeeks(this)" class="form-control">
                        <option value="">Seleccione Trimestre</option>
                        <?php
                        foreach ($blocks as $block) {
                        ?>
                            <option value="<?= $block['id'] ?>"><?= $block['bloque'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="col-lg-4 col-md-4">
                    <div id="div-semanas"></div>
                </div>

                <!--botones derecha-->
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    );
                    ?>
                </div>
                <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
                <hr>
            </div>


            <!--comienza cuerpo de documento-->
            <div id="div-detail-week"></div>
            <!--finaliza cuerpo de documento-->

        </div>
    </div>

</div>

<script>
    function showWeeks(obj) {
        let blockId = obj.value;
        let url = '<?= Url::to(['acciones']) ?>';

        params = {
            block_id: blockId,
            action: 'weeks'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function() {},
            success: function(response) {
                let html = response;

                if (html.includes('Sem1')) {
                    html = html.replace('Sem1', 'Semana Nº1');
                }

                if (html.includes('Sem2')) {
                    html = html.replace('Sem2', 'Semana Nº2');
                }

                if (html.includes('Sem3')) {
                    html = html.replace('Sem3', 'Semana Nº3');
                }

                if (html.includes('Sem4')) {
                    html = html.replace('Sem4', 'Semana Nº4');
                }

                if (html.includes('Sem5')) {
                    html = html.replace('Sem5', 'Semana Nº5');
                }

                if (html.includes('Sem6')) {
                    html = html.replace('Sem6', 'Semana Nº6');
                }

                if (html.includes('Sem7')) {
                    html = html.replace('Sem7', 'Semana Nº7');
                }

                if (html.includes('Sem8')) {
                    html = html.replace('Sem8', 'Semana Nº8');
                }

                if (html.includes('Sem9')) {
                    html = html.replace('Sem9', 'Semana Nº9');
                }

                if (html.includes('Sem10')) {
                    html = html.replace('Sem10', 'Semana Nº10');
                }

                if (html.includes('Sem11')) {
                    html = html.replace('Sem11', 'Semana Nº11');
                }

                if (html.includes('Sem12')) {
                    html = html.replace('Sem12', 'Semana Nº12');
                }

                if (html.includes('Sem13')) {
                    html = html.replace('Sem13', 'Semana Nº13');
                }

                if (html.includes('Sem14')) {
                    html = html.replace('Sem14', 'Semana Nº14');
                }
                if (html.includes('Sem15')) {
                    html = html.replace('Sem15', 'Semana Nº15');
                }

                $("#div-semanas").html(html);
            }
        });
    }


    function showWeek(obj) {
        let weekId = obj.value;
        let url = '<?= Url::to(['acciones']) ?>';

        params = {
            week_id: weekId,
            action: 'detail-week'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function() {},
            success: function(response) {
                $("#div-detail-week").html(response);
            }
        });


    }
</script>