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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<!-- JS HTML2CANVAS  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>


<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row " style="margin-top: 10px;">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" style=""
                            class="img-thumbnail"></h4>
                </div>
                <?php
                if ($modelActividad->calificado == true) {
                    $calificado = '<i class="fas fa-check-square fa-md" style="color: #3bb073;"></i>';
                } else {
                    $calificado = '<i class="fas fa-times-circle fa-lg" style="color: #c1331a;"></i>';
                }
                ?>
                <div class="col-lg-9 col-md-9">
                    <h5>
                        <?= Html::encode($this->title) ?>
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
                <div class="col-lg-2 col-md-2" style="text-align: right;">
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
            <div class="row" style="margin-top: 0px;">

                <div class="col-lg-12 col-md-12 text-quinto" style="text-align: center;">
                    <?=
                        '<h5>' . $group->alumno->last_name . " " . $group->alumno->first_name . " " .
                        $group->alumno->middle_name . '</h5>';
                    ?>
                </div>
            </div>

            <!-- *****inicio pdf***** -->

             <div class=" row " style="margin: 10px;">
                   <table class="table table-striped">
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Actividad</th>
                                <th scope="col">Calificación</th>
                                <th scope="col">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $actividad=0;
                            $actividad=$actividad+1;
                            $fecha_hoy=date("dd-mm-YY");
                            if($fecha_hoy > $modelActividad->bloque->desde && $fecha_hoy < $modelActividad->bloque->hasta){
                                $estado ="ABIERTO";
                            }else{$estado = "CERRADO";}
                            foreach ($calificaciones as $calificar) {
                                $calificacionId=$calificar['calificacion_id'];
                                echo '<tr>
                                    <th scope="row" valign="middle">'.$actividad.'</th>
                                    <td valign="middle">' . $calificar['nombre_nacional'] . '</td>';
                                    echo '<td>';
                                    if($estado=="ABIERTO"){
                                    echo '<input type="text" class="form-control" 
                                            id="al'. $calificacionId.'" 
                                            value="' . $calificar['calificacion'] . '" 
                                            onchange="cambiarNota('.$calificar['calificacion_id'].')">';
                                    }else 
                                    {
                                        echo $calificar['calificacion'];
                                    }
                                    echo '</td>';
                                    
                                    echo '<td>';
                                    if($estado=="ABIERTO"){
                                    echo '<input type="text" class="form-control" 
                                            id="observacion'. $calificacionId.'" 
                                            value="' . $calificar['observacion'] . '"
                                            onchange="cambiarNota('.$calificar['calificacion_id'].')">';
                                    }else
                                    {
                                        echo $calificar["observacion"];
                                    }
                                    echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                             
                        </table>
                    </table>
                </div>
            <div class="row" style="margin-top: 0px;">
                <!-- <div class=" row " style="margin: 10px;">
                    <div class="form-floating mb-1 col-lg-1 col-md-11">
                        <input type="number" min="10" max="100" class="form-control" id="floatingInput"
                            value="100">
                        <label for="floatingInput">Calificación</label>
                    </div>
                    <div class="form-floating mb-1 col-lg-10 col-md-10">
                        <input type="string" class="form-control" id="floatingInput" placeholder="Password">
                        <label for="floatingPassword">Observaciones</label>
                    </div>

                </div> -->
                <div>
                    <!-- **para subir archivo pdf simple**
                    <embed src="/files/students/10/5361xx.pdf" type="application/pdf"
                    width="100%" height="600px" /> -->

                    <div id="pdf-viewer-container">
                        <canvas id="pdf-canvas"></canvas>
                    </div>



                    <div id="target-element">
                        <!-- Contenido de la etiqueta que deseas capturar -->
                    </div>

                    <script>
                        //llamado de ajax

                        // URL del archivo PDF
                        var pdfUrl = '/files/students/10/Plan Semanal 30.pdf';

                        // Variables para el lienzo de dibujo
                        var drawingCanvas = document.getElementById('pdf-canvas');
                        var drawingContext = drawingCanvas.getContext('2d');
                        var isDrawing = false;
                        var lastX = 0;
                        var lastY = 0;

                        // Carga del archivo PDF
                        pdfjsLib.getDocument(pdfUrl).promise.then(function (pdf) {
                            // Obtiene la primera página del PDF
                            pdf.getPage(1).then(function (page) {
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
                                onrendered: function (canvas) {
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
                                success: function () {
                                    //alert('Imagen guardada!');
                                }
                            });
                        }
                    </script>


                </div>


                <!-- fin pdf -->
            </div>
        </div>
    </div>

    <script>
    $(function () {
        $('.input').keyup(function (e) {
            if (e.keyCode == 38)//38 para arriba
                mover(e, -1);
            if (e.keyCode == 40)//40 para abajo
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
        var obs='#observacion'+ id;
        var obs2=$(obs).val();
        var group=<?=$group->id?>;
        // alert(group);
        var url = '<?= Url::to(['update-score']) ?>';
        params = {
            calificacion_id: id,
            nota: nota,
            observacion: obs2,
            group_id:group 
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
</script>