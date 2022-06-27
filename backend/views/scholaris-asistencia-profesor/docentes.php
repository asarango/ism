<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

//echo'<pre>';
//print_r($areas);

$this->title = 'Detalle de faltas de docentes';
?>

<div class="scholaris-asistencia-profesor-docentes" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/areas.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div><!-- FIN DE CABECERA -->

            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                    
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->


            <!-- #################### inicia cuerpo de card ##########################-->
            <div class="row p-5">

                <div class="row">
                    <div class="col-lg-12 col-md-12">                        
                        <div class="row card">
                            <canvas id="chart-no-justificado-docentes"></canvas>                            
                        </div>
                    </div>
                    <!--fin de columna de graficos-->                    
                </div>                
                <hr />

                <div class="row">
                    <hr />
                </div>
                
            </div><!-- ######################## fin cuerpo de card #######################-->


        </div><!-- fin de card principal -->
    </div>
</div>

<script
    src="https://code.jquery.com/jquery-2.2.4.js"
    integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    no_justificado_docente();    

    function no_justificado_docente() {
        var url = "<?= yii\helpers\Url::to(['insp-fecha-periodo/ajax-no-justificado-docentes']) ?>";

        $.ajax({
            url: url,
            type: 'GET',

            success: function (resp) {
                //console.log(resp);
                var respuesta = JSON.parse(resp);
                console.log(respuesta);
                var meses = respuesta.meses;
                var totales = respuesta.totales;

                const labels = meses;

                const data = {
                    labels: labels,
                    datasets: [{
                            label: 'Total Docentes Injustificados',
//                            backgroundColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgb(178, 213, 249)',
                            borderColor: 'rgb(0, 0, 0)',
//                            data: [0, 10, 5, 2, 20, 30, 45],
                            data: totales
                        }]
                };

                const config = {
                type: 'bar',
                        data: data,
                        options: {
//                            indexAxis: 'y',
//                            elements: {
//                                    bar: {
//                                    borderWidth: 1,
//                                    }
//                             }
                             }
                        };
                const myChart = new Chart(
                        document.getElementById('chart-no-justificado-docentes'),
                        config
                        );

            }
        });

    }



</script>
