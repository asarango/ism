<?php

use Codeception\Command\Shared\Style;
use yii\helpers\Html;
use backend\models\PlanificacionOpciones;
use backend\models\ScholarisArchivosprofesor;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

// echo "<pre>";
// print_r($modelActividad);
// die();

?>

<style>
    .custom-table {
        border-collapse: collapse;
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        color: black;
        font-weight: normal;
    }

    .custom-table th {
        padding: 15px;
        text-align: center;
        border: 1px solid #e0e0e0;
        text-align: left;
    }

    .custom-table td {
        padding: 15px;
        text-align: center;
        border: 1px solid #e0e0e0;
        /* text-align: left; */
    }

    .custom-table th {
        background-color: #f5f5f5;
        color: black;
    }

    .custom-table tr:nth-child(even) {
        background-color: #f9f9f9;
        color: black;
        /* text-align: left; */
    }

    .custom-table th:first-child,
    .custom-table td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        color: black;
    }

    .custom-table th:last-child,
    .custom-table td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        color: black;
    }

    @media screen and (max-width: 1080px) {
        .custom-table {
            font-weight: bold;
        }
    }
</style>

<?php
if ($modelActividad->calificado == true) {
    $calificado = 'SI';
} else {
    $calificado = 'NO';
}
?>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-4">
            <!-- <h6 style="text-align: center;">Detalles de la Actividad</h6> -->
            <table class="table custom-table">
                <tr>
                    <th>Materia</th>
                    <td><?= Html::encode($modelActividad->clase->ismAreaMateria->materia->nombre) ?></td>
                </tr>
                <tr>
                    <th>Clase #</th>
                    <td><?= Html::encode($modelActividad->clase->id) ?></td>
                </tr>
                <tr>
                    <th>Curso</th>
                    <td><?= Html::encode($modelActividad->clase->paralelo->course->name) ?></td>
                </tr>
                <tr>
                    <th>Paralelo</th>
                    <td><?= Html::encode($modelActividad->clase->paralelo->name) ?></td>
                </tr>
                <tr>
                    <th>Tema</th>
                    <td><?= Html::encode($modelActividad->title) ?></td>
                </tr>
                <tr>
                    <th>Profesor</th>
                    <td><?= Html::encode($modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name) ?></td>
                </tr>
                <tr>
                    <th>Calificado</th>
                    <td><?= Html::encode($calificado) ?></td>
                </tr>
                <tr>
                    <th>Tipo de Actividad</th>
                    <td><?= Html::encode(getActividad($modelActividad->tipo_actividad_id)) ?></td>
                </tr>
            </table>
        </div>

        <div class="col-md-8">
            <!-- <h6 style="text-align: center;">Clases disponibles para copiar</h6> -->
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">ID</th>
                        <th style="text-align: center;">Curso</th>
                        <th style="text-align: center;">Paralelo</th>
                        <th style="text-align: center;">Docente</th>
                        <th style="text-align: center;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clasesParaCopiar as $clase) : ?>
                        <tr>
                            <td><?= $clase['id'] ?></td>
                            <td><?= $clase['curso'] ?></td>
                            <td><?= $clase['paralelo'] ?></td>
                            <td><?= $clase['docente'] ?></td>
                            <td>
                                <?php if (empty($clase['actividad_original']) && empty($modelActividad->actividad_original)) : ?>
                                    <?php
                                    echo Html::a(
                                        '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fas fa-highlighter"></i> Copiar</span>',
                                        [
                                            'copiar-actividad',
                                            'clase_destino_id' => $clase['id'],
                                            'clase_original_id' => $modelActividad->clase->id,
                                            'actividad_id' => $modelActividad->id
                                        ]
                                    );
                                    // echo '<span>La actividad ha sido copiada de ' . $modelActividad->actividad_original . '-' . $modelActividad->title . '</span>';
                                    ?>
                                <?php else : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#c9de00" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <title>Esta actividad se ha copiado correctamente!</title>
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                        <path d="M9 12l2 2l4 -4" />
                                    </svg>


                                <?php endif; ?>
                            </td>


                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row" style="text-align: center;">
                <div class="col-md-12">
                    <?php
                    if ($modelActividad->actividad_original == null) {
                        echo '<h5 style="font-weight: bold">Actividad Original</h5>';
                        echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-award-filled" width="100" height="100" viewBox="0 0 24 24" stroke-width="1.5" stroke="#c9de00" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M19.496 13.983l1.966 3.406a1.001 1.001 0 0 1 -.705 1.488l-.113 .011l-.112 -.001l-2.933 -.19l-1.303 2.636a1.001 1.001 0 0 1 -1.608 .26l-.082 -.094l-.072 -.11l-1.968 -3.407a8.994 8.994 0 0 0 6.93 -3.999z" stroke-width="0" fill="currentColor" />
                            <path d="M11.43 17.982l-1.966 3.408a1.001 1.001 0 0 1 -1.622 .157l-.076 -.1l-.064 -.114l-1.304 -2.635l-2.931 .19a1.001 1.001 0 0 1 -1.022 -1.29l.04 -.107l.05 -.1l1.968 -3.409a8.994 8.994 0 0 0 6.927 4.001z" stroke-width="0" fill="currentColor" />
                            <path d="M12 2l.24 .004a7 7 0 0 1 6.76 6.996l-.003 .193l-.007 .192l-.018 .245l-.026 .242l-.024 .178a6.985 6.985 0 0 1 -.317 1.268l-.116 .308l-.153 .348a7.001 7.001 0 0 1 -12.688 -.028l-.13 -.297l-.052 -.133l-.08 -.217l-.095 -.294a6.96 6.96 0 0 1 -.093 -.344l-.06 -.271l-.049 -.271l-.02 -.139l-.039 -.323l-.024 -.365l-.006 -.292a7 7 0 0 1 6.76 -6.996l.24 -.004z" stroke-width="0" fill="currentColor" />
                            </svg>';
                    } else {
                        echo '<p style="font-weight: bold">Esta actividad ha sido previamente copiada y no se puede volver a copiar. <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exclamation-circle" width="35" height="35" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9e9e9e" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 9v4" />
                        <path d="M12 16v.01" />
                      </svg></<p>';
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>