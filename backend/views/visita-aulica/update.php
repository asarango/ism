<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VisitaAulica */

$this->title = 'Visita áulica -' . ' Visita ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Visita Aulicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

// echo "<pre>";
// print_r($estudiantes);
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


<div class="m-0 vh-50 row justify-content-center align-items-center scroll">
    <div class="card shadow col-lg-10 overflow-auto">
        <div class="row align-items-center p-2">
            <!-- INICIO ENCABEZADO -->
            <div class="row">
                <div class="col-lg-1">
                    <h3><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h3>
                </div>
                <div class="col-lg-9">
                    <h4><?= Html::encode($this->title) ?></h4>
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
                </div>
                <div class="col-lg-2" style="text-align: right;">
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
                        [
                            'view',
                            'clase_id' => $clase->id,
                            'bloque_id' => $trimestre->id
                        ]
                    );
                    ?>
                </div>
            </div>

            <!-- FIN ENCABEZADO -->

            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6 visita-aulica-update overflow-auto" style="height: 700px;">
                    <!-- RENDER FORM -->
                    <div class="card" style="padding: 10px">
                        <?= $this->render('_form', [
                            'model' => $model,
                            'clase' => $clase,
                            'trimestre' => $trimestre,
                        ]) ?>
                    </div>
                    <!-- RENDER FORM -->

                    <!-- CONDICIONAL PARA MOSTRAR TABLAS GRUPALES O INDIVIDUAL 1-->

                    <div class="card" style="margin-top: 10px;padding: 10px">
                        <?php
                        if ($model['aplica_grupal'] > 0) {
                        ?>
                            <!-- GRUPALES -->

                            <h6 style="text-align: center">Observación al docente (GRUPALES)</h6>

                            <?php
                            // echo "<pre>";
                            // print_r($observacionesDocente);
                            // die();
                            ?>
                            <?php foreach ($observacionesDocente as $obsDoc) { ?>
                                <?= Html::beginForm(['acciones-update'], 'post') ?>

                                <div style="background-color: #ff9e18; color: white; padding: 15px; border-radius: 10px; text-align: center;">
                                    <strong>Descripción:</strong>
                                    <?php if ($obsDoc) {
                                        echo $obsDoc->visitaCatalogo->opcion;
                                    } ?>
                                </div>

                                <div class="row" style="margin-top: 10px;margin-bottom: 10px">
                                    <div class="col-lg-3 col-md-3" style="text-align: center;margin-top: 15px;">
                                        <strong>¿Se cumple?</strong>
                                        <input type="hidden" name="id" value="<?= $obsDoc->id ?>">

                                        <?php
                                        if ($obsDoc->se_hace == 1) {
                                            echo '<input type="checkbox" name="se_hace" checked>';
                                        } else {
                                            echo '<input type="checkbox" name="se_hace">';
                                        }
                                        ?>

                                        <input type="hidden" name="bandera" value="docentes">
                                    </div>
                                    <div class="col-lg-6 col-md-6" style="text-align: center;">
                                        <!-- <input type="text" name="comentarios" value="<?= $obsDoc->comentarios ?>"> -->
                                        <textarea name="comentarios" cols="30" rows="3"><?= $obsDoc->comentarios ?></textarea>

                                        <!-- <strong>Comentarios:</strong> -->
                                    </div>
                                    <div class="col-lg-3 col-md-3" style="margin-top: 5px; text-align: center; margin-bottom: 10px;margin-top: 15px;">
                                        <?= Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                            <path d="M14 4l0 4l-6 0l0 -4" />
                                            </svg>', ['class' => 'btn btn-primary']) ?>
                                    </div>
                                </div>

                                <?= Html::endForm() ?>
                            <?php } ?>


                            <!-- FIN GRUPALES -->
                        <?php
                        } else {
                        }
                        ?>
                    </div>

                </div>

                <!-- FIN CONDICIONAL PARA MOSTRAR TABLAS GRUPALES-->

                <!-- CONDICIONAL PARA GRUPAL E INDIVIDUAL 2 -->

                <div class="card col-lg-6 col-md-6 visita-aulica-update overflow-auto" style="height: 700px;">

                    <?php
                    if ($model['aplica_grupal'] > 0) {
                    ?>
                        <!-- GRUPAL -->
                        <div class="visita-aulica-update" style="padding: 1rem;">
                            <h6 style="text-align: center">Observación a estudiantes</h6>

                            <p>
                                <?php
                                $numeroDeEstudiantes = count($estudiantes);
                                echo "Número de estudiantes: $numeroDeEstudiantes";
                                ?>
                            </p>

                            <table class="table custom-table table-hover table-responsive">

                                <thead>

                                    <tr style="text-align: center;">
                                        <th style="border-bottom: none;color: white;text-align: center">Nómina completa</th>
                                        <th style="border-bottom: 1px solid #ab0a3d;"></th>
                                        <th style="border-bottom: 1px solid #ab0a3d;">Asiste a clase</th>
                                        <th style="border-bottom: 1px solid #ab0a3d;">Observaciones</th>
                                        <th style="border-bottom: none;color: white;">Acción</th>
                                    </tr>

                                </thead>

                                <tbody>
                                    <?php
                                    $contador = 1;
                                    foreach ($estudiantes as $estud) { ?>
                                        <?= Html::beginForm(['acciones-update'], 'post') ?>
                                    <?php
                                        echo '<input type="hidden" name="bandera" value="asistencia">';
                                        echo '<input type="hidden" name="id" value="' . $estud['id'] . '">';
                                        echo '<tr style="text-align: center;">';
                                        echo '<td>';
                                        echo $contador . '. ' . $estud['estudiante'];
                                        echo '</td>';
                                        echo '<td>';
                                        if ($estud['grado'] == 1) {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-dot-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6f32be" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-5 6.66a2 2 0 0 0 -1.977 1.697l-.018 .154l-.005 .149l.005 .15a2 2 0 1 0 1.995 -2.15" stroke-width="0" fill="green" />
                                          </svg>';
                                        } elseif ($estud['grado'] == 2) {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-dot-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6f32be" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-5 6.66a2 2 0 0 0 -1.977 1.697l-.018 .154l-.005 .149l.005 .15a2 2 0 1 0 1.995 -2.15" stroke-width="0" fill="#ff9e18" />
                                          </svg>';
                                        } elseif ($estud['grado'] == 3) {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-dot-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6f32be" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-5 6.66a2 2 0 0 0 -1.977 1.697l-.018 .154l-.005 .149l.005 .15a2 2 0 1 0 1.995 -2.15" stroke-width="0" fill="#ab0a3d" />
                                          </svg>';
                                        };
                                        echo '</td>';
                                        echo '<td><input type="checkbox" name="es_presente" ' . ($estud['es_presente'] == 1 ? 'checked' : '') . '></td>';
                                        echo '<td>';
                                        echo '<textarea name="observaciones">' . $estud['observaciones'] . '</textarea>';
                                        echo '</td>';
                                        echo '<td>';
                                        echo Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M14 4l0 4l-6 0l0 -4" />
                                        </svg>', ['class' => 'btn btn-primary']);
                                        echo '</td>';
                                        echo '</tr>';
                                        $contador++;
                                        echo Html::endForm();
                                    }

                                    ?>
                                </tbody>

                            </table>

                        </div>

                        <!-- FIN GRUPAL -->
                    <?php

                    } else {
                    ?>
                        <!-- INDIVIDUAL -->
                        <div class=" visita-aulica-update">

                            <h6 style="text-align: center">Observación a estudiantes</h6>

                            <table class="table custom-table table-hover table-responsive">

                                <thead>

                                    <tr style="text-align: center;">
                                        <th style="border-bottom: none;color: white;text-align: center">Nómina completa</th>
                                        <th style="border-bottom: 1px solid #ab0a3d;"></th>
                                        <th style="border-bottom: 1px solid #ab0a3d;">Asiste a clase</th>
                                        <th style="border-bottom: 1px solid #ab0a3d;">Observaciones</th>
                                        <th style="border-bottom: none;color: white;">Acción</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <?php
                                    $contador = 1;
                                    foreach ($estudiantes as $estud) { ?>
                                        <?= Html::beginForm(['acciones-update'], 'post') ?>
                                    <?php
                                        echo '<input type="hidden" name="bandera" value="asistencia">';
                                        echo '<input type="hidden" name="id" value="' . $estud['id'] . '">';
                                        echo '<tr style="text-align: center;">';
                                        echo '<td>';
                                        // echo $contador . '.' . '<a href="">' . $estud['estudiante'] . '</a>';
                                        echo Html::a(
                                            $contador . '.' . '' . $estud['estudiante'],
                                            [
                                                'individual',
                                                'id' => $estud['id'],
                                            ]
                                        );
                                        echo '</td>';
                                        echo '<td>';
                                        if ($estud['grado'] == 1) {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-dot-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6f32be" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-5 6.66a2 2 0 0 0 -1.977 1.697l-.018 .154l-.005 .149l.005 .15a2 2 0 1 0 1.995 -2.15" stroke-width="0" fill="green" />
                                          </svg>';
                                        } elseif ($estud['grado'] == 2) {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-dot-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6f32be" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-5 6.66a2 2 0 0 0 -1.977 1.697l-.018 .154l-.005 .149l.005 .15a2 2 0 1 0 1.995 -2.15" stroke-width="0" fill="#ff9e18" />
                                          </svg>';
                                        } elseif ($estud['grado'] == 3) {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-dot-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6f32be" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-5 6.66a2 2 0 0 0 -1.977 1.697l-.018 .154l-.005 .149l.005 .15a2 2 0 1 0 1.995 -2.15" stroke-width="0" fill="#ab0a3d" />
                                          </svg>';
                                        };
                                        echo '</td>';
                                        echo '<td><input type="checkbox" name="es_presente" ' . ($estud['es_presente'] == 1 ? 'checked' : '') . '></td>';
                                        echo '<td>';
                                        echo '<textarea name="observaciones">' . $estud['observaciones'] . '</textarea>';
                                        echo '</td>';
                                        echo '<td>';
                                        echo Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M14 4l0 4l-6 0l0 -4" />
                                        </svg>', ['class' => 'btn btn-primary']);
                                        echo '</td>';
                                        echo '</tr>';
                                        $contador++;
                                        echo Html::endForm();
                                    }

                                    ?>

                                </tbody>

                            </table>


                        </div>
                        <!-- INDIVIDUALES -->

                    <?php
                    }
                    ?>


                </div>
            </div>
            <!-- FIN CONDICIONAL PARA GRUPAL E INDIVIDUAL 2-->

        </div>


    </div>

</div>