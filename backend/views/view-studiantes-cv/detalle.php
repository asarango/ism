<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ViewStudiantesCvSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detalle Estudiante';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- <style>
    body {
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .course-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .course-info {
        flex: 1;
        padding-right: 20px;
    }

    .course-title {
        font-size: 1.2rem;
        color: #ab0a3d;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        background-color: #f2f2f2;
        color: #333;
        font-weight: bold;
        border-radius: 5px;
    }

    .action-links {
        display: flex;
        align-items: center;
    }

    .action-link {
        text-decoration: none;
        padding: 8px 12px;
        background-color: #0a1f8f;
        color: #fff;
        border-radius: 5px;
        transition: background-color 0.2s ease;
        margin-right: 5px;
    }

    .action-link:hover {
        background-color: #eee;
    }
</style> -->


<style>
    .col-lg-2 {
        background-color: red;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
    }

    .col-lg-12 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    p {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .icon-tabler {
        width: 24px;
        height: 24px;
        margin-right: 5px;
    }
</style>


<div class="view-studiantes-cv-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/files.png" width="64px" style="" class="img-thumbnail">
                    </h4>
                </div>
                <div class="col-lg-10">
                    <h4>
                        <b>
                            <?= Html::encode($this->title) ?>
                        </b>
                    </h4>

                </div>
                <div class="col-lg-1">
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5;color: #fff"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    );
                    ?>
                </div>
                <hr>
            </div><!-- FIN DE CABECERA -->


            <!-- Inicio Gráficos -->
            <div id="div_graficos" >

                <div class="row">

                    <div class="col-lg-2 col-md-2">

                        <div class="row">
                            <div class="col-lg-12 col-md-12">Datos Estudiante
                                <?php
                                // echo "<pre>";
                                // print_r($inscription->student);
                                // die();
                                ?>

                                <p>
                                    <?php echo $inscription->student->last_name . ' ' . $inscription->student->middle_name . ' ' . $inscription->student->first_name

                                    ?>
                                    <?php
                                    if ($inscription->student->gender == 'm') {

                                        // echo 'Genero: ';
                                        echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-gender-male" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M10 14m-5 0a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                                        <path d="M19 5l-5.4 5.4" />
                                        <path d="M19 5h-5" />
                                        <path d="M19 5v5" />
                                        </svg>';
                                    } else {
                                        // echo 'Genero: ';
                                        echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-gender-female" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 9m-5 0a5 5 0 1 0 10 0a5 5 0 1 0 -10 0" />
                                        <path d="M12 14v7" />
                                        <path d="M9 18h6" />
                                        </svg>';
                                    }
                                    ?>
                                </p>

                            </div>
                            <div class="col-lg-12 col-md-12">Estadística</div>
                        </div>

                    </div>

                    <div class="col-lg-10 col-md-10">
                        <!-- Promedio General -->
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-lg-12 col-md-12">
                                <div id="div_promedio_general"></div>
                            </div>

                            <div class="row" style="margin-bottom: 10px;">
                                <!-- Grafica Clases -->
                                <div class="col-lg-6 col-md-6">
                                    <div class="card shadow" id="div_clases" style="padding: 5px;"></div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div id=""></div>
                                </div>

                            </div>

                        </div>
                    </div>



                </div>

                <!-- Fin Gráficos -->

            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
    show_mean_average(); //<- llamo a funcion para traer graficas de promedio general
    show_chatClases(); //<- llamo a funcion para traer graficas clases

    //Funcion para mostrar dashboard de promedios generales
    function show_mean_average() {
        var url = "<?= Url::to(['acciones'])  ?>";
        var inscription_id = "<?= $inscription->id ?>";
        var params = {
            'accion': 'promedios',
            inscription_id: inscription_id
        };

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: function(resp) {
                $("#div_promedio_general").html(resp);
            }
        });

    }

    //Funcion para mostrar  graficos de las clases del Estudiante
    function show_chatClases() {
        var url = "<?= Url::to(['acciones'])  ?>";
        var inscription_id = "<?= $inscription->id ?>";
        var params = {
            'accion': 'chart-clases',
            inscription_id: inscription_id
        };

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: function(resp) {
                $("#div_clases").html(resp);
            }
        });
    }
</script>