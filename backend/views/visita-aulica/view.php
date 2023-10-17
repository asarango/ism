<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\VisitaAulica */

$this->params['breadcrumbs'][] = ['label' => 'Visita Aulicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$this->title = 'Visitas áulicas';
// echo "<pre>";
// print_r($visitas);
// die();
$codEStud = json_encode($estudiantes)
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>


<div class="visita-aulica-view">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10">
            <div class="row align-items-center p-2">
                <div class="col-lg-1">
                    <h3><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h3>
                </div>
                <div class="col-lg-8">
                    <h3>
                        <?= Html::encode($this->title) . ' - ' . $trimestre->name ?>
                    </h3>
                    <p><?= ''
                            . 'Coordinador: '
                            . $clase->paralelo->dece_nombre . ''
                            . $clase->paralelo->name . ' - '
                            . $clase->paralelo->name . ' Clase: '
                            . $clase->id

                        ?></p>
                    <!-- TOTAL DE VISTAS -->
                    <div>
                        <?php
                        foreach ($visitas as $visita) {
                            echo $visitas;
                        }

                        // echo $codEStud
                        ?>
                    </div>
                </div>
                <!-- <div class="row">
                    <p>
                        <?php $clase->paralelo->create_date ?>
                    </p>
                    <p>
                        Grupal?<input type="checkbox">
                    </p>
                </div> -->

                <div class="col-lg-3 col-md-3" style="text-align: right; margin-top: -5px;">
                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-share" width="15" height="15" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.247 0 .484 .045 .702 .127" />
                        <path d="M19 12h2l-9 -9l-9 9h2v7a2 2 0 0 0 2 2h5" />
                        <path d="M16 22l5 -5" />
                        <path d="M21 21.5v-4.5h-4.5" />
                        </svg> Regresar
                            </span>',
                        ['index']
                    );
                    ?>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-lg-12" style="padding: 1.5rem;">

                    <h6 style="text-align: center;">Visitas al año</h6>

                    <div class="row">
                        <div class="col-md-4 card" style="padding: 1.5rem;">
                            <?php
                            foreach ($estudiantes as $estudiante) {
                                echo "Estudiante: " . $estudiante['estudiante'] . "";

                                // echo "Materias:\n";
                                echo "<ol>";
                                foreach ($estudiante['materias'] as $materia) {
                                    echo "<li>Materia: " . $materia['materia'] . "</li>";
                                    echo "<li>Total de Visitas: " . $materia['total_visitas'] . "</li>";
                                }
                                echo "</ol>";
                            }
                            ?>
                        </div>
                        <div class="col-md-8 card">
                            <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    const xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
    const yValues = [55, 49, 44, 24, 15];
    const barColors = ["red", "green", "blue", "orange", "brown"];

    new Chart("myChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: "World Wine Production 2018"
            }
        }
    });
</script>