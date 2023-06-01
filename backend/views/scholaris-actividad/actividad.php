<?php

use Codeception\Command\Shared\Style;
use yii\helpers\Html;
use backend\models\PlanificacionOpciones;
use backend\models\ScholarisArchivosprofesor;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actividad #: ' . $modelActividad->id . ' | ' . $modelActividad->title;


/** Extrae path donde se almacena los archivos */
$path_archivo_profesor = PlanificacionOpciones::find()
    ->where([
        'tipo' => 'SUBIDA_ARCHIVO',
        'categoria' => 'PATH_PROFE'
    ])->one();
$path_archivo_profesor_ver = PlanificacionOpciones::find()
    ->where([
        'tipo' => 'VER_ARCHIVO',
        'categoria' => 'PATH_PROFE'
    ])->one();

/** Extraer listado de archivos subidos por actividad */
$modelArchivoProfesor = ScholarisArchivosprofesor::find()
    ->where([
        'idactividad' => $modelActividad->id
    ])
    ->orderBy(['orden' => SORT_ASC])
    ->all();

$modelActividadConsulta = new ScholarisArchivosprofesor();
// echo"<pre>";
// print_r($materialApoyo);
// die();
?>


<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>


<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10">
            <div class=" row" style="margin-top:10px;">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h4>
                </div>
                <?php
                if ($modelActividad->calificado == true) {
                    $calificado = '<i class="fas fa-check-square fa-md" style="color: #3bb073;"></i>';
                } else {
                    $calificado = '<i class="fas fa-times-circle fa-lg" style="color: #c1331a;"></i>';
                }
                ?>
                <div class="col-lg-6 col-md-6">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
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
                        ?>  )
                    </p>
                </div>

                <div class="col-lg-5 col-md-5" style="text-align: right;">
                    <?php

                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fa fa-plus-circle" aria-hidden="true">
                        </i> Mis Asignaturas</span>',
                        ['profesor-inicio/index'],
                        ['class' => '', 'title' => 'Mis Asignaturas']
                    );
                    ?>
                    |
                    <?php
                    if ($modelActividad->calificado == 'true') {
                        if ($modelActividad->tipo_calificacion == 'P') {
                            if (count($modelCriterios) > 0 && $modelActividad->calificado == 'SI') {
                                echo Html::a(
                                    '<span class="badge rounded-pill bg-cuarto"><i class="fas fa-highlighter"></i> Calificar</span>',
                                    ['calificar', "id" => $modelActividad->id],
                                    ['class' => 'link']
                                );
                                $cantidadCalif = backend\models\ScholarisCalificaciones::find()->where(['idactividad' => $modelActividad->id])->all();
                            } else {

                            }
                        } else {
                            echo Html::a(
                                '<span class="badge rounded-pill bg-cuarto"><i class="fas fa-highlighter"></i> Calificar</span>',
                                ['calificar', "id" => $modelActividad->id],
                                ['class' => 'link']
                            );
                        }
                    }
                    ?>
                    |
                    <?php

                    if ($estado == 'abierto') {
                        echo Html::a('<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fas fa-highlighter"></i> Editar</span>', [
                            'update',
                            "id" => $modelActividad->id
                        ], ['class' => 'link', 'target' => 'blank']);
                    }

                    ?>
                    |
                    <?php
                    echo Html::a('<span class="badge rounded-pill" style="background-color: red"><i class="far fa-trash-alt"></i> Eliminar</span>', [
                        'eliminar',
                        "id" => $modelActividad->id
                    ], ['class' => 'link', 'target' => 'blank']);
                    ?>
                </div>
                <hr>
            </div>
            <!-- /Fin Cabecera**********<<Inicio Cuerpo>>**********************/ -->

            <!-- /*****Dentro de Actividades*****/ -->

            <?php
            if ($lmsActividad->es_calificado == 1) {
                $es_calificado = '<i class="fas fa-check-square fa-lg" style="color: #3bb073;"></i>';
            } else {
                $es_calificado = '<i class="fas fa-times-circle fa-lg" style="color: #c1331a;"></i>';
            }
            if ($lmsActividad->es_publicado == 1) {
                $es_publicado = '<i class="fas fa-check-square fa-lg" style="color: #3bb073;"></i>';
            } else {
                $es_publicado = '<i class="fas fa-times-circle fa-lg" style="color: #c1331a;"></i>';
            }
            if ($lmsActividad->es_aprobado == 1) {
                $es_aprobado = '<i class="fas fa-check-square fa-lg" style="color: #3bb073;"></i>';
            } else {
                $es_aprobado = '<i class="fas fa-times-circle fa-lg" style="color: #c1331a;"></i>';
            }
            if ($lmsActividad->retroalimentacion == 1) {
                $retroalimentacion = '<i class="fas fa-check-square fa-lg" style="color: #3bb073;"></i>';
            } else {
                $retroalimentacion = '<i class="fas fa-times-circle fa-lg" style="color: #c1331a;"></i>';
            }

            ?>

            <div class="row">
                <div class="col-lg-8 col-md-8" style="margin-bottom:20px">

                    <div class="card ">
                        <div class="card-header text-center">
                            <?=
                                '<span style = "font-weight: bold; font-size:15px; Color:#0a1f8f;"><u>Actividades</u></span>';
                            ?>

                        </div>
                        <div class="card-body" style="margin-top:0px">
                            <div class="row text-center">
                                <div class="col-lg-4 col-md-4 " style="border-right:1px solid #C7CBD9">
                                    <?= '<span style = "Color:#9e28b5">Tipo de Actividad</span>'
                                        . '<br>' . '<h6>' . $lmsActividad->tipo_actividad_id;
                                    ?>
                                </div>

                                <div class="col-lg-4 col-md-4" style="border-right:1px solid #C7CBD9">
                                    <?= '<span style = "Color:#9e28b5">¿Es Calificado?</span>'
                                        . '<br>' . $es_calificado;
                                    ?>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <?= '<span style = "Color:#9e28b5">¿Es Publicado?</span>'
                                        . '<br>' . $es_publicado;
                                    ?>
                                </div>
                            </div>
                            <hr>
                            <!-- 2da fila de Actividades -->
                            <div class="row text-center">
                                <div class="col-lg-6 col-md-6" style="border-right:1px solid #C7CBD9">
                                    <?= '<span style = "Color:#9e28b5">¿Es Aprobado?</span>'
                                        . '<br>' . $es_aprobado;
                                    ?>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <?= '<span style = "Color:#9e28b5">Retroalimentación</span>'
                                        . '<br>' . $retroalimentacion;
                                    ?>
                                </div>
                            </div>
                            <!-- fin 2da fila -->
                            <hr>
                            <!-- inicio 3ra fila -->
                            <div class="row align-items-center">
                                <div class="col">
                                    <?= '<span style = "Color:#9e28b5"><h6><b>Título</b></h6></span>' . '<br>'
                                        . $lmsActividad->titulo;
                                    ?>
                                </div>
                            </div>
                            <hr>

                            <!-- fin 3ra fila -->

                            <!-- Inicio 4ta fila -->
                            <div class="row align-items-center">
                                <div class="col">
                                    <?= '<span style = "Color:#9e28b5"><h6><b>Descripción</b></h6></span>' . '<br>'
                                        . $lmsActividad->descripcion;
                                    ?>
                                </div>
                            </div>
                            <hr>
                            <!-- fin 4ta fila -->

                            <!-- Inicio 5ta fila -->
                            <div class="row align-items-center">
                                <div class="col">
                                    <?= '<span style = "Color:#9e28b5"><h6><b>Tarea</b></h6></span>' . '<br>'
                                        . $lmsActividad->tarea;
                                    ?>
                                </div>
                            </div>
                            <!-- fin 5ta fila -->
                        </div>
                    </div>
                </div>

                <!-- /*****Dentro de material de Apoyo*****/ -->
                <div class="col-lg-4 col-md-4">
                    <div class="">
                        <div class=" text-center">
                            <?=
                                '<span style = "font-weight: bold; font-size:15px; Color:#0a1f8f;"><u>Material de Apoyo</u></span>';
                            ?>

                        </div>
                        <?php
                        foreach ($materialApoyo as $material) {
                            ?>
                            <div class="card-body">
                                <div class="card shadow  mb-3" style="max-width: 540px ;">
                                    <div class="row g-1">
                                        <div class="col-lg-2 col-md-2 align-items-right" style="margin-top:10px;">

                                            <!-- Link para hacer seleccionable la imagen y descargar un archivo desde un metodo de clase en controlador-->
                                            <!-- <?= Html::a(
                                                '<img src="../ISM/main/images/submenu/icono-descarga.png" width="25px">',
                                                [
                                                    'download',
                                                    "path" => $material->archivo
                                                ],
                                                ['target' => '_blank']
                                            ) ?> -->
                                            <!-- <i class="fas fa-file-pdf fa-lg " style="color: #ab0a3d; font-size: 100px;"></i> -->
                                        </div>
                                        <div class="col-md-10">
                                            <div class="card-body">

                                                <h6 class="card-title text-cuarto"><b>
                                                        <?= Html::a(
                                                            '<img src="../ISM/main/images/submenu/icono-descarga.png" width="25px">',
                                                            [
                                                                'download',
                                                                "path" => $material->archivo,
                                                            ],
                                                            ['target' => '_blank', 'title' => 'Clic para Descargar']
                                                        ) ?>
                                                        <?= $material->alias_archivo ?>
                                                    </b>
                                                </h6>
                                                <p class="card-text"></p>
                                                <p class="card-text"><small class="text-body-secondary"><i>
                                                            <?= '- Last update: ' . $lmsActividad->updated_at ?>
                                                        </i></small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                </div>
            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>