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
// print_r($lmsActividad);
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
            <!-- render de formularios LMS (lms anterior o DIPL) -->
            
            
            <!--**************** modificar puesto que debemos tr4aer desde la planificacion semanal***************** -->
            <?php
            if(empty($lmsActividad['id'])){
                include '_lms-planificacion-semanal.php';
            }else{
                include '_lms-comunitario.php';
            }
            ?>          
        </div>
    </div>
</div>