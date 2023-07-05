<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Copiar Planificacion TOC Vertical';
$this->params['breadcrumbs'][] = $this->title;

// echo "<pre>";
// print_r($clase);
// die();
?>

<div class="planificacion-toc-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-9 col-md-9" style="text-align: left;">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>
                    <p>
                        <?=
                            '<small>' . $clase->ismAreaMateria->materia->nombre .
                            ' - (' . $clase->id . ') - ' .
                            'Clase #:' . $clase->id .
                            ' - ' .
                            $clase->paralelo->course->name . ' - ' . $clase->paralelo->name . ' / ' .
                            $clase->profesor->last_name . ' ' . $clase->profesor->x_first_name .
                            '</small>';
                        ?>
                    </p>
                </div>
                <!-- INICIO BOTONES DERECHA -->
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9.00002 15.3802H13.92C15.62 15.3802 17 14.0002 17 12.3002C17 10.6002 15.62 9.22021 13.92 9.22021H7.15002" stroke="#ffffff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8.57 10.7701L7 9.19012L8.57 7.62012" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g>
                            </svg> Planificación Vertical</span>',
                            ['toc-plan-vertical/index1', 'clase_id' => $clase->id],
                            ['class' => '', 'title' => 'Planificación Vertical TOC']
                        );
                    ?>
                    <!-- FIN BOTONES DERECHA -->
                </div>
                <hr>
            </div>
            <div class="row ">
                <div id="nombre" style="text-align: center;">

                </div>
                <div style="color: red;">
                    <small>*Advertencia: Por favor, tenga en cuenta que al hacer clic en el botón "Copiar
                        Planificación",
                        se reemplazarán los avances actuales con los datos de la planificación seleccionada.</small>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3" style="margin-bottom: 10px;">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <?php
                        foreach ($clases as $class) {
                            $url = $class['url'];
                            $nombre = '<h4>Planificación Paralelo ' . $class['paralelo'] . ' - ' . $class['docente'] . '</h4><small>';
                            ?>
                            <ul class="list-group" style="margin-bottom: 1px; font-size: 12px;">
                                <li class="list-group-item active" aria-current="true">
                                    <?= $class['paralelo'] . ' - ' . $class['docente'];
                                    if ($class['paralelo'] == $clase->paralelo->name) { ?>
                                        <div class="row">
                                            <div class="col-lg-6" style="text-align: center;">
                                                <a href="#embeded-url"
                                                    onclick="showpdf('. <?= $url ?> . ','. <?= $nombre ?> .')">
                                                    <span class="badge rounded-pill" style="background-color: #4DBE29">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-file-text" width="16"
                                                            height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff"
                                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                            <path
                                                                d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                            <path d="M9 9l1 0" />
                                                            <path d="M9 13l6 0" />
                                                            <path d="M9 17l6 0" />
                                                        </svg> Ver
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <?php
                                    } else { ?>

                                <div class="row">
                                    <div class="col-lg-6" style="text-align: center;">
                                        <a href="#embeded-url" onclick="showpdf('<?= $url ?>','<?= $nombre ?>')"><span
                                                class="badge rounded-pill" style="background-color: #4DBE29">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-file-text" width="16" height="16"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                    <path
                                                        d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                    <path d="M9 9l1 0" />
                                                    <path d="M9 13l6 0" />
                                                    <path d="M9 17l6 0" />
                                                </svg> Ver </span></a>
                                    </div>
                                    <div class="col-lg-6 " style="text-align: center" ;>
                                        <?=
                                            Html::a(
                                                '<span class="badge rounded-pill" style="background-color: #898b8d">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-copy" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                                                    <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                                                  </svg> Copiar</span>',
                                                ['execute-copy', 'clase_hasta' => $clase->id, 'clase_desde' => $class['clase_id']],
                                                ['class' => '', 'title' => 'Copiar esta Planificación']
                                            );
                                        ?>
                                    </div>
                                </div>
                                </li>
                                </ul>
                            <?php }
                        } ?>
                    </div>
                </div>
                <div id='embeded-url' class=" col-lg-9 col-md-9 col-sm-9">

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function showpdf(url, nombre) {
        let emb = '<embed src="/' + url + '" type="application/pdf" width="100%" height="600px" />';
        $("#embeded-url").html(emb);
        $("#nombre").html(nombre);
        //document.getElementById("pdf-embed").src = url;

    }
</script>