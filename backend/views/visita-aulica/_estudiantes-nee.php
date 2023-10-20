<?php

use yii\helpers\Html;
use yii\widgets\DetailView;




?>

<?php
foreach ($estudiantes as $estudiante) {
?>
    <div class="col-lg-6 col-md-6 table-reponsive">

        <h6 style="text-align: center;">
            <?= $estudiante['estudiante']; ?>

        </h6>
        <div class="table table-reponsive">
            <table class="table custom-table table-reponsive table-hover">
                <thead>
                    <tr style="">
                        <th style="border-bottom: none;color: white;background-color: #ff9e18;" scope="col">#</th>
                        <th style="background-color: #ff9e18;" scope="col">Materia</th>
                        <th style="background-color: #ff9e18;" scope="col" style="text-align: center;">Total Visitas</th>
                        <th style="background-color: #ff9e18;" scope="col">Progreso</th>
                        <th style="background-color: #ff9e18;" scope="col">Editar</th>
                        <th style="border-bottom: none;color: white;background-color: #ff9e18;"> PDF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $contador = 1; // Inicializa el contador
                    foreach ($estudiante['materias'] as $materia) {
                    ?>
                        <tr>
                            <th style="color: white;background-color: #1b325f;" scope="row">
                                <?= $contador; ?>
                            </th>
                            <td>
                                <?= $materia['materia']; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php $materia['total_visitas'];
                                // echo "<pre>";
                                // print_r($materia);
                                // die();

                                ?>
                            </td>
                            <td>
                                <?= barra($materia['total_visitas']); ?>
                            </td>
                            <td style="text-align: center;">
                                <?php
                                if ($materia['total_visitas'] > 0) {
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
                                }
                                ?>
                            </td>
                            <td style="text-align: center;">
                                <?php
                                if ($materia['total_visitas'] > 0) {
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
                                        [
                                            '#',
                                            ''


                                        ]
                                    );
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                        $contador++; // Incrementa el contador en cada iteración
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
<?php
}
?>


<?php
function barra($total)
{
    echo '<div class="progress">
    <div class="progress-bar 
    progress-bar-striped" role="progressbar" 
    style="width: ' . $total * 40 . '%" aria-valuenow="10" aria-valuemin="0" 
    aria-valuemax="100"></div>
    </div>';
}
?>