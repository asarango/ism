<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

//echo '<pre>';
//print_r($age_family['student']);
//die();

$estudiante = $student->data_student[0];
//print_r($estudiante);
$padres = $student->data_parents;

$nombreCompletoEstudiante = $estudiante->first_name . ' ' . $estudiante->middle_name . ' ' . $estudiante->last_name;
$inicialesEstudiante = getIniciales($nombreCompletoEstudiante);
$this->title = 'Registro de Necesidad';

// echo "$estudiante->first_name"."<br>";
// echo "$estudiante->middle_name"."<br>";
// echo "$estudiante->last_name"."<br>";
// echo '<pre>';
//echo $pestana;
//  print_r($opciones5);
//  print_r($student);
//  echo '<br>';
//  print_r($student->data_parents);

$datosEstudActive = '';
$showDatosEstudActive = '';

$fechaElabActive = '';
$showFechaElabActive = '';

$informePsicoActive = '';
$showInformePsicoActive = '';

if ($pestana == 'datos_estudiante') {
    $datosEstudActive = 'active';
    $showDatosEstudActive = 'show active';
}
if ($pestana == 'fecha_elab') {
    $fechaElabActive = 'active';
    $showFechaElabActive = 'show active';
}
if ($pestana == 'informe_psicopedagogico') {
    $informePsicoActive = 'active';
    $showInformePsicoActive = 'show active';
}


function getIniciales($nombre)
{
    //echo '<pre>';
    //print_r(trim($nombre));
    //die();
    //print_r($nombre);
    $nombre = str_replace('  ', ' ', $nombre);

    $name = '';
    $explode = explode(' ', $nombre);
    foreach ($explode as $x) {
        $name .=  $x[0];
    }
    return $name;
}
?>

<div class="nee-ficha">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2"><!-- INICIO DE CABECERA -->
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div style="font-weight: bold" class="col-lg-9">
                    <h5><?= Html::encode($this->title) ?></h5>
                    <p><?php echo $nombreCompletoEstudiante . ' (' . $inicialesEstudiante . ')' ?></p>
                </div>
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <!-- menu izquierda -->

                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Estudiantes NEE </span>',
                        ['index'],
                        ['class' => 'link']
                    );
                    ?>


                </div>
                <hr>

            </div><!-- FIN DE CABECERA -->

            <!-- inicia cuerpo de card -->
            <div class="row">

                <div class="col-lg-2 col-md-2" style="margin-top: 5px">
                    <div class="d-flex align-items my-text-medium">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link <?= $datosEstudActive ?>" style="text-align: start; background-color: #ff9e18; border-radius: 0px" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="false">
                                1.-DATOS ESTUDIANTE
                            </button>

                            <!-- <button class="nav-link" style="text-align: start; background-color: #ff9e18; border-radius: 0px" 
                                    id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                2.-HISTORIAL FAMILIAR Y ESCOLAR
                            </button> -->

                            <button class="nav-link" style="text-align: start; background-color: #ff9e18; border-radius: 0px" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                2.- DETALLE NEE
                            </button>

                            <button class="nav-link <?= $fechaElabActive ?>" style="text-align: start; background-color: #ff9e18; border-radius: 0px" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                                3.-ASIGNATURAS IMPLICADAS
                            </button>

                            <button class="nav-link" style="text-align: start; background-color: #ff9e18; border-radius: 0px" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                                4.-PROFESIONALES IMPLICADOS
                            </button>
                            <!-- <button class="nav-link <?= $informePsicoActive ?>" style="text-align: start; background-color: #ff9e18; border-radius: 0px" 
                                    id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-5" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                                5.-INFORME PSICOPEDAGÓGICO
                            </button>
                            
                            <button class="nav-link" style="text-align: start; background-color: #ff9e18; border-radius: 0px" 
                                    id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-6" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                                6.-ADAPTACIONES
                            </button> -->
                        </div>
                    </div>
                </div>



                <div class="col-lg-10 col-md-10" style="margin-top: 15px;padding-left: 50px;padding-right: 50px; margin-bottom:15px">
                    <div class="card shadow" style="padding: 15px">
                        <div class="tab-content" id="v-pills-tabContent">
                            <!-- RENDERIZA A LA VISTA datos_estudiante.php -->
                            <div class="tab-pane fade <?= $showDatosEstudActive ?>" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <?php
                                echo $this->render('datos_estudiante', [
                                    'estudiante' => $estudiante,
                                    'padres' => $padres,
                                    'instituto' => $instituto,
                                    'age_family' => $age_family
                                ]);
                                ?>
                            </div>

                            <!-- RENDERIZA A LA VISTA DE ADMINISTRACION DE NEE -->
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <?php
                                echo $this->render('nee-detalle', [
                                    'model' => $model
                                ]);
                                ?>
                            </div>
                            <!-- RENDERIZA A LA VISTA fecha_elab -->
                            <div class="tab-pane fade <?= $showFechaElabActive ?>" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                <?php
                                echo $this->render('fecha_elab', [
                                    'model' => $model,
                                    'materiasSelect' => $materiasSelect,
                                    'materiasNee' => $materiasNee
                                ])
                                ?>
                            </div>
                            <!-- RENDERIZA A LA VISTA profesionales_implicados -->
                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                <?php
                                echo $this->render('profesionales_implicados', [
                                    'materiasNee' => $materiasNee,
                                    'materiasSelect' => $materiasSelect
                                ])
                                ?>
                            </div>
                            <!-- RENDERIZA A LA VISTA informe_psicopedagogico -->
                            <div class="tab-pane fade <?= $showInformePsicoActive ?>" id="v-pills-5" role="tabpanel" aria-labelledby="v-pills-5-tab">
                                <?php
                                // echo $this->render('informe_psicopedagogico', [
                                //     'materiasNee' => $materiasNee
                                // ])
                                ?>
                            </div>

                            <!-- RENDERIZA A LA VISTA adaptaciones -->
                            <div class="tab-pane fade" id="v-pills-6" role="tabpanel" aria-labelledby="v-pills-5-tab">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- fin cuerpo de card -->

        </div>
    </div>

</div>