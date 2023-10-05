<?php

use backend\models\OpStudent;
use Mpdf\Tag\Input;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actividad #: ' . $modelActividad->id . ' | ' . $modelActividad->title;

// echo "<pre>";
// print_r($modelActividad);
// die();


?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="pdf_viewer.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<!-- JS HTML2CANVAS  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>

<style>
    .row-calificacion {
        margin: 20px auto;
        max-width: 300px;
        background-color: #f8f8f8;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
    }

    .box {
        margin: 20px auto;
        max-width: 300px;
        background-color: #f8f8f8;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
    }

    .box-name {
        /* margin: 20px auto; */
        max-width: 800px;
        background-color: #f8f8f8;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;

    }

    .box-name:hover {
        background-color: #ab0a3d;
        color: #ff9e18;
        transform: translateY(-6px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.4);
    }

    h5 {
        text-align: center;
        font-weight: bold;
    }

    .radio-label {
        display: block;
        margin-bottom: 10px;
        font-weight: normal;
    }

    .radio-label input[type="radio"] {
        margin-right: 5px;
        display: none;
        /* Oculta los radios */
    }

    .radio-button {
        display: inline-block;
        padding: 5px 10px;
        /* border: 1px solid #ccc; */
        border-radius: 4px;
        cursor: pointer;
    }

    .radio-label input[type="radio"]:checked+.radio-button {
        background-color: #ab0a3d;
        color: #ff9e18;
        /* border: 1px solid #007bff; */
    }

    .radio-label:hover {
        background-color: #ab0a3d;
        color: white;
        border-radius: 10px;
        transform: translateY(-6px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.4);
    }
</style>


<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-11 col-md-11">
            <div class=" row " style="margin-top: 10px;margin-bottom: 10px;">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <?php
                if ($modelActividad->calificado == true) {
                    $calificado = '<i class="fas fa-check-square fa-md" style="color: #3bb073;"></i>';
                } else {
                    $calificado = '<i class="fas fa-times-circle fa-lg" style="color: #c1331a;"></i>';
                }
                ?>
                <div class="col-md-9 col-md-9">
                    <h5>
                        <b>
                            <?= Html::encode($this->title) ?>
                        </b>
                    </h5>
                    <p>(
                        <?=
                        ' <small>' . $modelActividad->clase->ismAreaMateria->materia->nombre .
                            ' - ' .
                            'Clase #:' . $modelActividad->clase->id .
                            ' - ' .
                            $modelActividad->clase->paralelo->course->name . ' - ' . $modelActividad->clase->paralelo->name . ' / ' .
                            $modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name . ' / ' .
                            'Es calificado: ' . $calificado . ' / ' .
                            'Tipo de actividad: ' . $modelActividad->tipo_calificacion .
                            '</small>';
                        ?>
                        )
                    </p>

                </div>

                <!-- <div class="col-lg-4 col-md-4 text-quinto"> -->
                <?php
                // '<h5>' . $group->alumno->last_name . " " . $group->alumno->first_name . " " .
                // $group->alumno->middle_name . '</h5>';
                ?>
                <!-- </div> -->

                <div class="col-lg-2 col-md-2" style="text-align: center;">

                    <?php echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #800080"><i class="fas fa-chart-line"></i> Detalle - Actividad</span>',
                        ['scholaris-actividad/calificar', "id" => $modelActividad->id],
                        ['class' => '', 'title' => ' Detalle - Actividad']
                    ); ?>
                    <!-- |
                    <?php echo Html::a(
                        '<span class="badge rounded-pill bg-cuarto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Calificación Detallada</span>',
                        ['calificacion/index1', "actividad_id" => $modelActividad->id],
                        ['class' => '', 'title' => '']
                    ); ?> -->


                </div>
                <hr>
            </div>
            <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->

            <!-- comienza cuerpo  -->

            <!-- *****inicio pdf***** -->

            <div class="row" style="padding: 1.2rem;margin-top: -1rem;">
                <!-- inicia col de pdf -->
                <div class="col-lg-9 col-md-9">
                    <div class="row" style="margin-top: 0px;">
                        <div class="box-name" style="text-align: center;">
                            <?=
                            '<h5>' . $group->alumno->last_name . " " . $group->alumno->first_name . " " .
                                $group->alumno->middle_name . '</h5>';
                            ?>
                        </div>
                        <div>
                            <!-- **para subir archivo pdf simple** -->
                            <div id="pdf-viewer-container">
                                <!-- <canvas id="pdf-canvas"></canvas> -->
                            </div>

                            <div id="target-element">
                                <!-- Contenido de la etiqueta que deseas capturar -->
                            </div>

                            <script>
                                //llamado de ajax

                                // URL del archivo PDF
                                var pdfUrl = '/files/homeworks/introduccion.pdf';

                                // Variables para el lienzo de dibujo
                                var drawingCanvas = document.getElementById('pdf-canvas');
                                var drawingContext = drawingCanvas.getContext('2d');
                                var isDrawing = false;
                                var lastX = 0;
                                var lastY = 0;

                                // Carga del archivo PDF
                                pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                                    // Obtiene la primera página del PDF
                                    pdf.getPage(1).then(function(page) {
                                        var scale = 1.75;
                                        var viewport = page.getViewport({
                                            scale: scale
                                        });

                                        // Crea un lienzo en el elemento <canvas> para renderizar la página del PDF
                                        var canvas = document.getElementById('pdf-canvas');
                                        var context = canvas.getContext('2d');
                                        canvas.width = viewport.width;
                                        canvas.height = viewport.height;

                                        // Renderiza la página en el lienzo
                                        var renderContext = {
                                            canvasContext: context,
                                            viewport: viewport
                                        };
                                        page.render(renderContext);

                                        // Agrega eventos para el dibujo en el lienzo de dibujo
                                        drawingCanvas.addEventListener('mousedown', startDrawing);
                                        drawingCanvas.addEventListener('mousemove', draw);
                                        drawingCanvas.addEventListener('mouseup', endDrawing);
                                        drawingCanvas.addEventListener('mouseout', endDrawing);
                                    });
                                });

                                function startDrawing(e) {
                                    isDrawing = true;
                                    [lastX, lastY] = [e.offsetX, e.offsetY];
                                }

                                function draw(e) {
                                    if (!isDrawing) return;
                                    drawingContext.beginPath();
                                    drawingContext.moveTo(lastX, lastY);
                                    drawingContext.lineTo(e.offsetX, e.offsetY);
                                    drawingContext.strokeStyle = 'red';
                                    drawingContext.lineWidth = 2;
                                    drawingContext.stroke();
                                    [lastX, lastY] = [e.offsetX, e.offsetY];
                                }

                                function endDrawing() {
                                    isDrawing = false;
                                }

                                // function capturar(){
                                //     // alert('capturando');
                                //     var targetElement = document.getElementById('target-element');
                                //     var canvas = document.querySelector('#pdf-canvas');
                                //     var context = canvas.getContext('2d');
                                //     // Establecer el tamaño del lienzo igual al tamaño de la etiqueta
                                //     canvas.width = targetElement.offsetWidth;
                                //     canvas.height = targetElement.offsetHeight;
                                //     // Dibujar la etiqueta en el lienzo
                                //     context.drawImage(targetElement, 0, 0);

                                //     var screenshotDataURL = canvas.toDataURL('image/jpeg',1.0);

                                //     window.open(screenshotDataURL);
                                // }

                                //Para tomar foto del google maps    
                                function capturar() {
                                    //        alert('clicn en crear');

                                    html2canvas($("#pdf-viewer-container"), {
                                        useCORS: true,
                                        onrendered: function(canvas) {
                                            var myImage = canvas.toDataURL("image/png");
                                            guarda_imagen_div(myImage);
                                            // $("#mapa-google").hide();

                                            //console.log(myImage);
                                            // $("#txt_imagen64_div").val(myImage);
                                        }
                                    });
                                }

                                function guarda_imagen_div(myImage) {
                                    var url = 'guardar_imagen.php';
                                    var params = {
                                        myImage
                                    };
                                    //console.log(params);return false;

                                    $.ajax({
                                        url: url,
                                        data: params,
                                        type: 'POST',
                                        success: function() {
                                            //alert('Imagen guardada!');
                                        }
                                    });
                                }
                            </script>
                        </div>


                        <!-- fin pdf -->
                    </div>
                </div>
                <!-- fin de col de pdf -->

                <!-- inicia col de formularios de calificaciones -->
                <div class="col-lg-3 col-md-3 card" style="padding: 10px;">
                    <div class="row">
                        <?php
                        $actividad = 0;
                        $actividad = $actividad + 1;
                        $fecha_hoy = date("Y-m-d");

                        // if($fecha_hoy > $modelActividad->bloque->desde && $fecha_hoy < $modelActividad->bloque->hasta){
                        if ($fecha_hoy < $modelActividad->bloque->hasta) {
                            $estado = "ABIERTO";
                        } else {
                            $estado = "CERRADO";
                        }

                        echo '<div style="text-align: center;">';

                        echo '<h6><b>Ingrese la calificación normal:</b></h6>';

                        echo '<h6><b>Actividad #: </b>' . $actividad . '</h6>';

                        foreach ($calificaciones as $calificar) {
                            $calificacionId = $calificar['calificacion_id'];
                            echo '<p><b>Insumo: </b>' . $calificar['nombre_nacional'] . '</p>';
                            echo '<p>';
                            if ($estado == "ABIERTO") {
                                echo '<input type="text" class="form-control" style="text-align: center;" 
                                            id="al' . $calificacionId . '" 
                                            value="' . $calificar['calificacion'] . '" 
                                            placeholder="Ingrese la nota..." 
                                            onchange="cambiarNota(' . $calificar['calificacion_id'] . ')">';
                            } else {
                                echo $calificar['calificacion'];
                            }
                            echo '</p>';

                            echo '<p>';
                            if ($estado == "ABIERTO") {
                                // echo '<input type="text" class="form-control" 
                                //         id="observacion' . $calificacionId . '" 
                                //         value="' . $calificar['observacion'] . '"
                                //         placeholder="Ingrese la observaciones ..."
                                //         onchange="cambiarNota(' . $calificar['calificacion_id'] . ')">';
                                echo '<textarea class="form-control" style="text-align: center;" 
                                            id="observacion' . $calificacionId . '" 
                                            value="' . $calificar['observacion'] . '"
                                            placeholder="Ingrese la observaciones ..."
                                            onchange="cambiarNota(' . $calificar['calificacion_id'] . ')">' . $calificar['observacion'] . '</textarea>';
                            } else {
                                echo $calificar["observacion"];
                            }
                            echo '</p>';

                            echo '</div>';
                        }
                        ?>
                    </div>


                    <hr>


                    <div class="row" style="padding: 10px;text-align: center;margin-bottom: -30px;">


                        <?php

                        if ($modelActividad->ods_pud_dip_id > 0) {
                            $ods = get_parametro_ods($modelActividad->ods_pud_dip_id);
                            echo '<div class="box" style="margin-top: -10px">';
                            echo $ods['categoria'] . '<br>';
                            echo $ods['opcion'];
                            echo '</div>'

                        ?>

                            <div class="row box" style="font-weight: bold;margin-top: -10px">
                                <div class="" style="text-align: center;">
                                    Calificacion obtenida:

                                    <?php
                                    $notaOds = valida_calificacion_ods($calificacionOds);

                                    echo '<br>**************<br>';
                                    echo $notaOds . '<br>';

                                    echo '****************';

                                    ?>

                                </div>
                            </div>


                            <div class="row row-calificacion" style="margin-top: -10px;">
                                <div class="col-lg-12 col-md-12">
                                    <h5 class="text-center font-weight-bold">Cambiar nota</h5>
                                    <div class="radio-options" style="text-align: center">
                                        <label class="radio-label">
                                            <input type="radio" name="gender" value="0" onclick="changeOds(<?= $group->id ?>, <?= $modelActividad->id ?>, 0); changeRadioState(this);">
                                            <span class="radio-button">NO EVALUADO</span>
                                        </label>

                                        <label class="radio-label">
                                            <input type="radio" name="gender" value="1" onclick="changeOds(<?= $group->id ?>, <?= $modelActividad->id ?>, 1); changeRadioState(this);">
                                            <span class="radio-button">INICIADO</span>
                                        </label>

                                        <label class="radio-label">
                                            <input type="radio" name="gender" value="2" onclick="changeOds(<?= $group->id ?>, <?= $modelActividad->id ?>, 2); changeRadioState(this);">
                                            <span class="radio-button">EN PROCESO</span>
                                        </label>

                                        <label class="radio-label">
                                            <input type="radio" name="gender" value="3" onclick="changeOds(<?= $group->id ?>, <?= $modelActividad->id ?>, 3); changeRadioState(this);">
                                            <span class="radio-button">ADQUIRIDO</span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                        <?php
                        } else {
                            echo 'No existe parámetros ODS para este insumo';
                        }
                        ?>
                    </div>


                </div>
                <!-- Finaliza el col de fomularios de calificaciones -->

            </div>


        </div>
    </div>


    <?php //FUNCIONES PHP
    function get_parametro_ods($odsPudDipId)
    {
        $con = Yii::$app->db;
        $query = "select	op.categoria 
                            ,op.opcion 
                    from 	pud_dip pud 
                            inner join dip_opciones op on op.opcion = pud.opcion_texto 
                    where 	pud.id = $odsPudDipId;";

        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    function valida_calificacion_ods($modelCalificacionOds)
    {
        // $nota = 0;
        if (!$modelCalificacionOds) {
            $nota = 'Cara sin calificar';
        } else if ($modelCalificacionOds->calificacion == 0) {
            $nota = 'Cara sin calificar';
        } else if ($modelCalificacionOds->calificacion == 1) {
            $nota = 'Cara de iniciado';
        } else if ($modelCalificacionOds->calificacion == 2) {
            $nota = 'Cara de en proceso';
        } else if ($modelCalificacionOds->calificacion == 3) {
            $nota = 'Cara de adquirido';
        }

        return $nota;
    }



    //# FIN DE FUNCIONES PHP
    ?>



    <script>
        $(function() {
            $('.input').keyup(function(e) {
                if (e.keyCode == 38) //38 para arriba
                    mover(e, -1);
                if (e.keyCode == 40) //40 para abajo
                    mover(e, 1);
            });
        });


        function mover(event, to) {
            let list = $('input');
            let index = list.index($(event.target));
            index = Math.max(0, index + to);
            list.eq(index).focus();
        }
    </script>

    <script>
        document.getElementById("calificar").focus();

        function cambiarNota(id) {
            var idx = '#al' + id;
            var nota = $(idx).val();
            var obs = '#observacion' + id;
            var obs2 = $(obs).val();
            var group = <?= $group->id ?>;
            // alert(group);
            var url = '<?= Url::to(['update-score']) ?>';
            params = {
                calificacion_id: id,
                nota: nota,
                observacion: obs2,
                group_id: group
            }
            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                success: function() {
                    // alert("cambio exitoso");

                }
            });


        }

        function NumCheck(e, field) {
            key = e.keyCode ? e.keyCode : e.which

            // backspace
            if (key == 8)
                return true

            // 0-9
            if (key > 47 && key < 58) {
                if (field.value == "")
                    return true

                regexp = /.[0-9]{2}$/
                return !(regexp.test(field.value))
            }

            // .

            if (key == 46) {
                if (field.value == "")
                    return false
                regexp = /^[0-9]+$/
                return regexp.test(field.value)
            }
            // other key

            if (key == 9)
                return true

            return false
        }



        //funcion para grabar calificación ODS
        function changeOds(grupoId, actividadId, calificacionOds) {
            var url = '<?= Url::to(['califica-ods']) ?>';
            params = {
                grupo_id: grupoId,
                actividad_id: actividadId,
                calificacion_ods: calificacionOds
            }
            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                success: function() {
                    // alert("cambio exitoso");
                    location.reload();
                }
            });

        }
    </script>

    <script>
        function changeRadioState(element) {
            // Deselecciona todos los botones de radio
            const radios = document.querySelectorAll('input[name="gender"]');
            radios.forEach(radio => radio.checked = false);

            // Marca el botón de radio seleccionado
            element.checked = true;
        }

        // Funciones originales de cambio de nota
        function changeOds(groupID, actividadID, value) {
            // Agrega aquí tu lógica original para cambiar la nota
            // Utiliza groupID, actividadID y value según sea necesario
        }
    </script>

    <!-- agrega un icono dependiendo de la califiacion  -->
    <script>
        function checkValue(input, iconContainerId) {
            const calificacion = parseFloat(input.value.replace(',', '.'));

            const iconContainer = document.getElementById(iconContainerId);
            iconContainer.innerHTML = ''; // Limpiar el icono previo

            if (!isNaN(calificacion)) {
                if (calificacion < 70) {
                    iconContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mood-nervous" width="40" height="40" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 10h.01" /><path d="M15 10h.01" /><path d="M8 16l2 -2l2 2l2 -2l2 2" /></svg>';
                } else if (calificacion >= 70 && calificacion <= 85) {
                    iconContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mood-smile" width="40" height="40" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 10l.01 0" /><path d="M15 10l.01 0" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg>';
                } else if (calificacion > 85 && calificacion <= 100) {
                    iconContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mood-check" width="40" height="40" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20.925 13.163a8.998 8.998 0 0 0 -8.925 -10.163a9 9 0 0 0 0 18" /><path d="M9 10h.01" /><path d="M15 10h.01" /><path d="M9.5 15c.658 .64 1.56 1 2.5 1s1.842 -.36 2.5 -1" /><path d="M15 19l2 2l4 -4" /></svg>';
                }
            }
        }
    </script>