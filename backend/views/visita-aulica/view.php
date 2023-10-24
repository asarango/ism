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

?>
<style>
    .custom-table {
        border-collapse: collapse;
        width: 100%;
        /* border-radius: 10px; */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        color: black;
        /* font-weight: bold; */
        font-size: 11px;
    }

    .custom-table th,
    .custom-table td {
        padding: 15px;
        /* text-align: center; */
        /* border: 1px solid #333; */

    }

    .custom-table th {
        background-color: #ab0a3d;
        color: white;
    }

    .custom-table tr:nth-child(even) {
        /* background-color: #eee; */
        color: black;
    }

    .custom-table th:first-child,
    .custom-table td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        text-align: left;
        color: black;
    }

    .custom-table th:last-child,
    .custom-table td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        text-align: right;
        color: black;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>


<div class="visita-aulica-view">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 overflow-auto" style="height: 600px;">
            <!-- INICIO ENCABEZADO -->
            <div class="row align-items-center p-2 position-sticky top-0" style="background-color: white;margin-bottom: 10px">
                <div class="col-lg-1">
                    <h3><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h3>
                </div>
                <div class="col-lg-8">
                    <h3>
                        <?= Html::encode($this->title) . ' - ' . $trimestre->name ?>
                    </h3>
                    <p>
                        <?= ''
                            . 'Coordinador: '
                            . $clase->paralelo->dece_nombre . ' - '
                            . $clase->paralelo->course->name . ' - ' . ' " '
                            . $clase->paralelo->name . ' " ' . 'Materia: '
                            . $clase->ismAreaMateria->materia->nombre . ' '
                            . '(Clase: #'
                            . $clase->id . ')'

                        ?>
                    </p>
                    <!-- TOTAL DE VISTAS -->
                </div>

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

                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ff9e18">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                        <path d="M16 3v4" />
                        <path d="M8 3v4" />
                        <path d="M4 11h16" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                        </svg> Crear visita
                            </span>',
                        [
                            'create',
                            'clase_id' => $clase->id,
                            'bloque_id' => $trimestre->id
                        ]
                    );
                    ?>
                </div>
                <!-- <hr> -->
            </div>

            <!-- FIN ENCABEZADO -->
            <div class="row ">

                <!-- INICIO VISITAS GRUPAL -->
                <div style="padding: 1.5rem;margin-top: -2rem" class="table-responsive">
                    <h2 style="text-align: center;">Visitas al año (Grupal)</h2>
                    <table class="table table-responsive table-hover custom-table">
                        <thead>
                            <tr>
                                <th style="color: white;border-bottom: none;">Trimestre</th>
                                <th style="border-bottom: 1px solid #ab0a3d;">E. Asistidos</th>
                                <th style="border-bottom: 1px solid #ab0a3d;">Tipo de visita</th>
                                <th style="border-bottom: 1px solid #ab0a3d;">Psicólogo</th>
                                <th style="border-bottom: 1px solid #ab0a3d;">Fecha</th>
                                <th style="border-bottom: 1px solid #ab0a3d;">Inicio</th>
                                <th style="border-bottom: 1px solid #ab0a3d;">Finalización</th>
                                <th style="border-bottom: 1px solid #ab0a3d;">Observaciones</th>
                                <th style="border-bottom: 1px solid #ab0a3d;">F. Dece</th>
                                <th style="border-bottom: 1px solid #ab0a3d;">F. Docente</th>
                                <th style="border-bottom: 1px solid #ab0a3d;">Editar</th>
                                <th style="color: white;border-bottom: none;">PDF</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($visitas as $visita) {
                                echo '<tr style="text-align: center;">';
                                echo '<td style="border-top: none;text-align: center;">' . $visita->bloque_id . '</td>';
                                echo '<td>' . $visita->estudiantes_asistidos . '</td>';
                                echo '<td>';
                                if ($visita->aplica_grupal > 0) {
                                    echo 'Grupal';
                                } else {
                                    echo 'Individual';
                                }
                                echo '</td>';
                                echo '<td>' . $visita->psicologo_usuario . '</td>';
                                echo '<td>' . $visita->fecha . '</td>';
                                echo '<td>' . $visita->hora_inicio . '</td>';
                                echo '<td>' . $visita->hora_finalizacion . '</td>';
                                echo '<td>' . $visita->observaciones_al_docente . '</td>';
                                echo '<td>' . $visita->fecha_firma_dece . '</td>';
                                echo '<td>' . $visita->fecha_firma_docente . '</td>';
                                echo '<td>';
                                echo Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #9e28b5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                        <path d="M13.5 6.5l4 4" />
                                      </svg>
                                    </span>',
                                    [
                                        'update',
                                        'id' => $visita->id

                                    ]
                                );
                                echo '</td>';
                                echo '<td>';
                                if (!empty($visita->observaciones_al_docente)) {
                                    echo Html::a(
                                        '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-type-pdf" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                                <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" />
                                                <path d="M17 18h2" />
                                                <path d="M20 15h-3v6" />
                                                <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
                                                </svg>
                                                    </span>',
                                        ['#']
                                    );
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>

                    </table>

                </div>
                <!-- FIN VISITAS GRUPAL -->
                <hr>
            </div>

            <div class="row" style="padding: 1.5rem;margin-top: -2.7rem;">
                <h2 style="text-align: center;">Visitas individuales</h2>

                <?php
                echo $this->render('_estudiantes-nee', [
                    'estudiantes' => $estudiantes,
                    'clase' => $clase,
                    'trimestre' => $trimestre,
                    'visita' => $visitas
                ]);

                ?>

            </div>
        </div>
    </div>

</div>

<?php



?>