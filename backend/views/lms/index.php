<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lms - ' . $modelClase->ismAreaMateria->materia->nombre . ' - ' . $nombreSemana;
//$this->params['breadcrumbs'][] = $this->title;
?>
<!--<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>-->
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

<link rel="stylesheet" href="estilo.css"/>


<div class="lms-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1"><h4><img src="ISM/main/images/submenu/aula.png" width="64px" style="" class="img-thumbnail"></h4></div>
                <div class="col-lg-11"><h4><?= Html::encode($this->title) ?></h4></div>
            </div>
            <hr>

            <p>
                |                                
                <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>
                |                                
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fas fa-clock"></i> Asignaturas</span>',
                        ['profesor-inicio/index'], ['class' => 'link']);
                ?>                
                |
            </p>


            <!--incia cuerpo-->
            <div class="row p-3" style="background-color: #ccc">


                <div class="col-lg-4 col-md-4" 
                     style="background-color: #eee; padding-top: 10px; height: 100vh;">
                    <ul>
                        <?php
                        foreach ($lms as $lm) {
                            $lm->estado_activo ? $show = 'show' : $show = '';
                            ?>
                            <li>
                                <a href="#" onclick="muestra_detalle(<?= $lm->id ?>, <?= $modelClase->id ?>)">
                                    <?= $lm->hora_numero . ' ' . $lm->titulo ?>
                                </a>                            

                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-lg-8 col-md-8">
                    <div class="" id="div-detalle">

                        <?php
                        if ($modelDetalleActivo) {
                            

                            echo $this->render('detalle', [
                                'modelDetalleActivo'    => $modelDetalleActivo,
                                'tipoActividadNac'      => $tipoActividadNac,
                                'tipoActividadPai'      => $tipoActividadPai,
                                'claseId'               => $modelClase->id,
                                'nombreSemana'          => $nombreSemana,
                                'actividades'           => $actividades,
                                'seccion'               => $seccion
                            ]);
                        } else {
                            ?>
                            <div class="row" style="margin-left: 5px; background-color: white; padding: 10px 10px 10px 0px">
                                <b>No existe un tema escogido!!!</b>
                            </div>
                        <?php
                        }
                            ?>

                        </div>
                    </div>                                

                </div>
                <!--fin de cuerpo-->

            </div>
        </div>
    </div>


    <script>

        function muestra_detalle(id, claseId) {
            
            var url = '<?= Url::to(['detalle']) ?>';
            var nombreSemana = '<?= $nombreSemana ?>';

        var params = {
            lms_id: id,
            clase_id: claseId,
            nombre_semana: nombreSemana
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (response) {
                $('#div-detalle').html(response);
            }
        });

    }


</script>