<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VisitaAulica */

$this->title = 'Observaciones a estudiantes';


// echo "<pre>";
// print_r($novedades);
// die();
// $observacionEstudiante
?>


<div class="m-0 vh-50 row justify-content-center align-items-center">
    <div class="card shadow col-lg-12 col-md-12">
        <div class="row align-items-center p-2">

            <div class="col-lg-1">
                <h3><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                </h3>
            </div>
            <div class="col-lg-9">
                <h4><?= Html::encode($this->title) ?></h4>
                <p>
                    <?php
                    echo  'Estudiante: ' . $observacionEstudiante->grupo->alumno->last_name . ' '
                        . $observacionEstudiante->grupo->alumno->first_name . ' -  Materia: '
                        . $observacionEstudiante->grupo->clase->ismAreaMateria->materia->nombre . ' - '
                        . $observacionEstudiante->grupo->clase->paralelo->course->name . ' "'
                        . $observacionEstudiante->grupo->clase->paralelo->name . '"'
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
                        'update',
                        'id' => $observacionEstudiante->visita_id

                    ]
                );
                ?>
            </div>
            <hr>
        </div>

        <div class="row" style="padding: 2rem;margin-top: -3rem;">
            <?php
            // echo 'El valor de id es: ' . $observacionEstudiante->id;
            ?>
            <table class="table table-bordered table-hover">
                <thead>

                    <tr>
                        <th>Descripción</th>
                        <th>Respuesta</th>
                        <th>Observaciones</th>
                        <th>Acción</th>
                    </tr>

                </thead>
                <tbody>

                    <?php
                    foreach ($novedades  as $novedad) {
                        echo Html::beginForm(['acciones-update'], 'post');
                        // echo "<pre>";
                        // print_r($novedad->id);
                        // die();

                    ?>
                        <input type="hidden" name="id" value="<?= $novedad->id ?>">
                        <input type="hidden" name="bandera" value="individual">
                        <tr>
                            <td><?= $novedad->catalogo->opcion ?> </td>
                            <td style="text-align: center;">
                                <input type="checkbox" name="respuesta" <?= $novedad->respuesta == 1 ? 'checked' : '' ?>>
                            </td>

                            <td>
                                <textarea name="observaciones" cols="70" rows="5"><?php echo $novedad->observaciones ?></textarea>
                            </td>
                            <td>
                                <?= Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                    </svg>', ['class' => 'btn btn-primary']);
                                ?>
                            </td>
                        </tr>
                    <?php
                        echo Html::endForm();
                    }
                    ?>


                </tbody>

            </table>

        </div>
    </div>
</div>