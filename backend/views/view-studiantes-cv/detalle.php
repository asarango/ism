<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ViewStudiantesCvSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'DETALLES DEL ESTUDIANTE';
$this->params['breadcrumbs'][] = $this->title;

$nombreEstudiante = $inscription->student->last_name . ' ' . $inscription->student->middle_name . ' ' . $inscription->student->first_name;
$birth = $inscription->student->birth_date;
$edad = edad($birth);
$cursoEstud = $inscription->course->code;

// echo "<pre>";
// print_r($inscription);
// die();
// print_r($inscription->curso->paralelo);
?>

<style>
    .col-lg-2 {
        background-color: #1b325f;
        color: white;
        padding: 10px;
        border-radius: 5px;
    }

    .col-lg-12 {
        font-size: 18px;
        /* font-weight: bold; */
        margin-bottom: 10px;
    }

    p {
        font-size: 12px;
        margin-bottom: 10px;
    }

    .icon-tabler {
        width: 24px;
        height: 24px;
        margin-right: 5px;
    }

    h4 {
        font-size: 18px;
    }

    .centrar {
        text-align: center;
    }

    /* estilos para menu */

    .menu-izquierdo {
        /* width: 250px; */
        /* background-color: #333; */
        padding: 20px;
    }

    .menu-izquierdo p {
        color: black;
        margin: 0;
        padding: 10px 0;
        transition: color 0.3s;
        font-weight: bold;
        font-size: 14px;
    }

    .menu-izquierdo span {
        color: white;
        margin: 0;
        padding: 10px 0;
        transition: color 0.3s;
        font-weight: normal;
        font-size: 10px;
    }

    .menu-izquierdo p:hover {
        color: #ff6600;
    }

    .menu-izquierdo span:hover {
        color: #ff6600;
    }

    @media screen and (min-width: 1920px) {

        .menu-izquierdo p {
            color: black;
            margin: 0;
            padding: 10px 0;
            transition: color 0.3s;
            font-weight: bold;
            font-size: 17px;
        }

        .menu-izquierdo span {
            font-size: 15px;
        }

        .menu-izquierdo span:hover {
            color: #ff6600;
            font-weight: bold;
        }
    }

    .linea-vertical {
        border-left: 1px solid #000;
        height: 100px;
    }

    .back {
        background-color: white;
        border-radius: 80px;
    }

    .cur {
        color: white;
        font-weight: bold;
        display: flex;
        justify-content: start;
    }

    .cur:hover {
        color: #ff6600;
        font-weight: bold;
        transition: color 0.3s;
    }

    .cur1 {
        color: white;
        font-weight: bold;
        display: flex;
        justify-content: flex-end;
    }

    .cur1:hover {
        color: #ff6600;
        font-weight: bold;
        transition: color 0.3s;
    }

    /* estilos para scroll */
    .scroll1 {
        max-height: 200px;
        overflow-y: auto;
        /* overflow-x: hidden; */
        scrollbar-width: thin;
    }

    .scroll1::-webkit-scrollbar {
        width: 6px;
    }

    .scroll1::-webkit-scrollbar-thumb {
        background-color: #999;
        border-radius: 3px;
    }

    .scroll1::-webkit-scrollbar-thumb:hover {
        background-color: #666;
    }

    .scroll1::-webkit-scrollbar-track {
        background-color: #f0f0f0;
    }

    /* .div-grafico {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 300px;
    } */
</style>

<div class="view-studiantes-cv-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-8" style="font-weight: bold;">
                    <h4><img src="../ISM/main/images/submenu/files.png" width="64px" style="" class="img-thumbnail">
                        <?= Html::encode($this->title) ?>
                    </h4>
                </div>

                <div class="col-lg-4" style="text-align: right;font-size: 13px">
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5;color: #fff"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['view-studiantes-cv/index'],
                        ['class' => 'link']
                    );
                    ?>
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d;color: #fff"><i class="fa fa-briefcase" aria-hidden="true"></i> Regresar</span>',
                        ['view-studiantes-cv/index'],
                        ['class' => 'link']
                    );
                    ?>
                </div>
                <hr>
            </div><!-- FIN DE CABECERA -->


            <!-- Inicio Gráficos -->
            <div style="margin-top: -25px" id="div_graficos">

                <div class="row">

                    <div class="col-lg-2 col-md-2">

                        <div class="row">

                            <!-- INCIO MENU IZQUIERDO -->

                            <div class="col-lg-12 col-md-12 centrar">
                                <h6 style="text-align: center; font-weight: bold;">
                                    Datos Estudiante:
                                </h6>

                                <!-- SGV'S PARA MALE Y FEMALE STUDENT -->
                                <div style="padding: 10px;display: flex;justify-content: center;align-items: center ">
                                    <?php
                                    if ($inscription->student->gender == 'm') {
                                        echo '<svg class="card back" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 30 30"
                                                style="fill:#9cc4e4;">
                                                    <path d="M18,19v-2c0.45-0.223,1.737-1.755,1.872-2.952c0.354-0.027,0.91-0.352,1.074-1.635c0.088-0.689-0.262-1.076-0.474-1.198 c0,0,0.528-1.003,0.528-2.214c0-2.428-0.953-4.5-3-4.5c0,0-0.711-1.5-3-1.5c-4.242,0-6,2.721-6,6c0,1.104,0.528,2.214,0.528,2.214 c-0.212,0.122-0.562,0.51-0.474,1.198c0.164,1.283,0.72,1.608,1.074,1.635C10.263,15.245,11.55,16.777,12,17v2c-1,3-9,1-9,8h24 C27,20,19,22,18,19z"></path>
                                                </svg>';
                                    } else {
                                        echo '<svg class="card back" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 30 30"
                                                style="fill:#9cc4e4;">
                                                    <path d="M5,27c0-5,6.957-4.174,8-6.999V19c-3.778,0-5.914-1.884-5.914-1.884C9.06,15.473,6.326,4.043,13.049,4.043 c0,0,0.907-1.043,2.08-1.043c8.218,0,5.51,12.41,7.635,14.154c0,0-1.968,1.846-5.765,1.846v1.001C18.043,22.826,25,22,25,27H5z"></path>
                                                </svg>';
                                    }
                                    ?>
                                </div>
                                <!-- SGV'S PARA MALE Y FEMALE STUDENT -->

                                <!-- CURSO Y PARALELO -->
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 cur" style="margin-top: -10px; margin-bottom: -15px;">
                                        <span style="font-size: 15px;font-weight: bold">
                                            <div title="Nivel"><svg style="cursor: pointer" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-certificate-2" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9cc4e4" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M12 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                                    <path d="M10 7h4" />
                                                    <path d="M10 18v4l2 -1l2 1v-4" />
                                                    <path d="M10 19h-2a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-2" />
                                                </svg></div>
                                            <?php
                                            if ($cursoEstud == 'BACH2') {
                                                echo 'DIPLOMA 1';
                                            } elseif ($cursoEstud == 'BACH3') {
                                                echo 'DIPLOMA 2';
                                                // echo 'title="Paralelo"';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 cur1" style="margin-top: -10px; margin-bottom: -15px;">

                                        <span style="font-size: 15px;font-weight: bold">
                                            <div title="Paralelo">
                                                <svg style="cursor: pointer" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chalkboard" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9cc4e4" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M8 19h-3a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v11a1 1 0 0 1 -1 1" />
                                                    <path d="M11 16m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                </svg>
                                            </div>
                                            "
                                            <?php echo $cursoEstud; ?> "
                                        </span>
                                    </div>
                                </div>
                                <!-- CURSO Y PARALELO -->
                                <hr>
                                <div class="menu-izquierdo">

                                    <!-- NOMBRE DEL ESTUDIANTE -->
                                    <div class="row" style="margin-top: -40px;margin-bottom: -23px; text-align: left;">
                                        <div class="col-lg-10 col-md-10" style="margin-top: 5px">
                                            <p><span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id-badge-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9cc4e4" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M7 12h3v4h-3z" />
                                                        <path d="M10 6h-6a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1 -1v-12a1 1 0 0 0 -1 -1h-6" />
                                                        <path d="M10 3m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" />
                                                        <path d="M14 16h2" />
                                                        <path d="M14 12h4" />
                                                    </svg>
                                                    <?php echo $nombreEstudiante ?>
                                                </span></p>

                                        </div>
                                    </div>
                                    <!-- NOMBRE DEL ESTUDIANTE -->

                                    <hr>

                                    <!-- EDAD DEL ESTUDIANTE -->
                                    <div style="margin-top: -10px;margin-bottom: -10px;text-align: left;">
                                        <span>
                                            <svg style="margin-top: -7px" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cake" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9cc4e4" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M3 20h18v-8a3 3 0 0 0 -3 -3h-12a3 3 0 0 0 -3 3v8z" />
                                                <path d="M3 14.803c.312 .135 .654 .204 1 .197a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1c.35 .007 .692 -.062 1 -.197" />
                                                <path d="M12 4l1.465 1.638a2 2 0 1 1 -3.015 .099l1.55 -1.737z" />
                                            </svg>

                                            <?php echo $edad . ' años'; ?>
                                        </span>
                                    </div>
                                    <!-- EDAD DEL ESTUDIANTE -->

                                    <hr>

                                    <!-- CORREO INSTITUCIONAL -->
                                    <div title="Correo Institucional" style="margin-top: -10px;margin-bottom: -10px; text-align: left;">
                                        <span>

                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="16" height="16" viewBox="0 0 24 24" stroke-width="1" stroke="#9cc4e4" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                                                <path d="M3 7l9 6l9 -6" />
                                            </svg>

                                            <?php echo $inscription->student->x_institutional_email; ?>
                                        </span>

                                    </div>
                                    <!-- CORREO INSTITUCIONAL -->

                                    <hr>

                                    
                                    <!-- tel de emergencia -->

                                    <!-- <div title="Tel. emergencia" style="margin-top: -15px;margin-bottom: -15px;text-align: left;">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ambulance" width="16" height="16" viewBox="0 0 24 24" stroke-width="1" stroke="#9cc4e4" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
                                                <path d="M6 10h4m-2 -2v4" />
                                            </svg>
                                            (02)
                                            
                                        </span>
                                    </div>
                                    

                                    <hr> -->

                                    <!-- REPRESENTANTE DEL ESTUDIANTE -->
                                    <div style="margin-top: -15px;margin-bottom: -15px;text-align: left;">
                                        <span title="Representante">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-square-rounded" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9cc4e4" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z" />
                                                <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                                <path d="M6 20.05v-.05a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v.05" />
                                            </svg>
                                            <?php echo $inscription->student->x_account_owner; ?>
                                        </span>
                                    </div>
                                    <!-- REPRESENTANTE DEL ESTUDIANTE -->


                                    <hr>

                                    <!-- DIRECCION DE ESTUDIANTE -->
                                    <div style="text-align: left;margin-top: -15px;margin-bottom: -15px;">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9cc4e4" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                                <path d="M10 12h4v4h-4z" />
                                            </svg>
                                            <?php echo $inscription->student->x_dir_llegada; ?>
                                        </span>
                                    </div>
                                    <!-- DIRECCION DE ESTUDIANTE -->

                                </div>
                            </div>
                            <!-- <div class="col-lg-12 col-md-12">Estadística</div> -->

                            <!-- FIN MENU IZQUIERDO -->
                        </div>

                    </div>

                    <div class="col-lg-10 col-md-10 scroll" style="margin-top: 10px;margin-bottom: 20px">
                        <!-- Promedio General -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12">

                                <div id="div_promedio_general"></div>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 5px;">
                            <!-- Grafica Clases -->
                            <div class="col-lg-7 col-md-7">
                                <div class="card shadow " id="div_clases" style="padding: 5px;"></div>
                            </div>

                            <div class="col-lg-5 col-md-5">
                                <!-- Comportamiento -->
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="card shadow" id="div_comportamiento" style="padding: 10px;"></div>
                                    </div>
                                </div>
                                <!-- Grafica de Faltas -->
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="card shadow" id="div_faltas" style="padding: 10px;"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Grafica DECE -->
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-lg-1 col-md-1"> </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="card shadow" id="div_dece" style="padding: 20px;"></div>
                            </div>
                            <div class="col-lg-1 col-md-1"> </div>
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
    show_comportamiento(); //<- llamo a funcion para traer comportamiento
    show_faltas(); //<- llamo a funcion para traer faltas
    show_dece(); //<- llamo a funcion para traer DECE

    //Funcion para mostrar dashboard de promedios generales
    function show_mean_average() {
        var url = "<?= Url::to(['acciones']) ?>";
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
        var url = "<?= Url::to(['acciones']) ?>";
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

    //Funcion para mostrar novedades y comportamiento
    function show_comportamiento() {
        var url = "<?= Url::to(['acciones']) ?>";
        var inscription_id = "<?= $inscription->id ?>";
        var params = {
            'accion': 'comportamiento',
            inscription_id: inscription_id
        };

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: function(resp) {
                $("#div_comportamiento").html(resp);
            }
        });
    }

    //Funcion para mostrar faltas
    function show_faltas() {
        var url = "<?= Url::to(['acciones']) ?>";
        var inscription_id = "<?= $inscription->id ?>";
        var params = {
            'accion': 'faltas',
            inscription_id: inscription_id
        };

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: function(resp) {
                $("#div_faltas").html(resp);
            }
        });
    }

    //Funcion para mostrar DECE
    function show_dece() {
        var url = "<?= Url::to(['acciones']) ?>";
        var inscription_id = "<?= $inscription->id ?>";
        var params = {
            'accion': 'chart-dece',
            inscription_id: inscription_id
        };

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: function(resp) {
                $("#div_dece").html(resp);
            }
        });
    }
</script>

<!-- funcion para calcular la edad del estudiante -->

<?php
function edad($birth)
{
    $birth = new DateTime($birth);
    $fechaActual = new DateTime();
    $diferencia = $birth->diff($fechaActual);
    return $diferencia->y;
}

?>