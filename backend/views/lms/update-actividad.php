<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//echo '<pre>';
//print_r($modelActividad);
//
//
//die();


$this->title = 'Lms - ' . $modelActividad->lms->ismAreaMateria->materia->nombre;
$this->params['breadcrumbs'][] = $this->title;
?>

<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
<link rel="stylesheet" href="estilo.css"/>


<div class="lms-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1"><h4><img src="ISM/main/images/submenu/aula.png" width="64px" style="" class="img-thumbnail"></h4></div>
                <div class="col-lg-11">
                    <?= Html::encode($this->title) ?>
                    <small>
                        <b> | Semana N°: </b><?= $modelActividad->lms->semana_numero ?> |
                        <b>Semana N°: </b><?= $modelActividad->lms->hora_numero ?> |
                        <b>Tema de la Hora N°: </b><?= $modelActividad->lms->titulo ?> |
                    </small>
                    <b>Actividad: </b><?= $modelActividad->titulo ?>
                </div>
            </div>

            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |                                
                    <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>
                    |                                
                    <?=
                    Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fas fa-clock"></i> Configuración horas</span>',
                            ['index1',
                                'clase_id' => $clase_id,
                                'semana_numero' => $numero_semana,
                                'nombre_semana' => $nombre_semana,
                            ], ['class' => 'link']);
                    ?>                
                    |
                </div> <!-- fin de menu izquierda -->

                <!-- inicio de menu derecha -->
                
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <hr>


            <!--incia cuerpo-->
            <div class="row" style="text-align: center; background-color: #ccc; padding: 5px">
                <div class="col-lg-4 col-md-4" style="">
                    
                    <?= $this->render('criterios-pai',[
                        'campo' => 'actualizar',
                        'id' => $modelActividad->id,
                        'lms_id' => $modelActividad->lms_id,
                        'clase_id' => $clase_id,
                        'semana_numero' => $numero_semana,
                        'nombre_semana' => $nombre_semana,
                        'seccion' => $seccion
                    ]) ?>
                    
                    <div class="card">
                        <div class="card-header" style="background-color: #65b2e8; color: white">
                            Archivos
                            <!-- Button trigger modal -->
                            <a type="button" class="" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="fas fa-plus-circle" style="color: whitesmoke"></i>
                            </a>

                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" 
                                 data-bs-keyboard="false" tabindex="-1" 
                                 aria-labelledby="staticBackdropLabel" 
                                 aria-hidden="true"
                                 style="color:black; text-align: left">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Archivos</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <?= Html::beginForm(['acciones'], 'post', ['enctype' => 'multipart/form-data']) ?>
                                        <div class="modal-body">
                                            <input type="hidden" name="lms_id" value="<?= $modelActividad->lms_id ?>"><!-- comment -->
                                            <input type="hidden" name="lms_actividad_id" value="<?= $modelActividad->id ?>"><!-- comment -->
                                            <input type="hidden" name="path_ism_area_materia_id" value="<?= $modelActividad->lms->ism_area_materia_id ?>"><!-- comment -->
                                            <input type="hidden" name="campo" value="upload"><!-- comment -->
                                            <input type="hidden" name="claseId" value="<?= $clase_id ?>"><!-- comment -->
                                            <input type="hidden" name="nombreSemana" value="<?= $nombre_semana ?>"><!-- comment -->
                                            <input type="hidden" name="numeroSemana" value="<?= $numero_semana ?>"><!-- comment -->

                                            <div class="form-group">
                                                <label for="aliasArchivo" class="form-label">Alias archivo</label>
                                                <input type="text" name="alias_archivo" class="form-control" 
                                                       required="" placeholder="Alias archivo"><!-- comment -->
                                            </div>



                                            <div class="mb-3">
                                                <label for="formFile" class="form-label"><i class="fas fa-paperclip"></i>Archivo</label>
                                                <input class="form-control" type="file" name="archivo" id="formFile" required="">
                                            </div>

                                            <div class="form-group" style="margin-top: 10px">
                                                <label for="esCalificado" class="form-label">Es publicado?</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="es_publicado" type="checkbox" id="flexSwitchCheckChecked">
                                                </div>
                                            </div>



                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Subir archivo</button>
                                        </div>
                                        <?= Html::endForm() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="text-align: left">
                            <ul>
                                <?php
                                foreach ($modelActividad->lmsActividadXArchivos as $archivo) {
                                    ?>
                                    <li class="zoom">
                                        <a href="http://localhost/<?= $archivo->archivo ?>" target="_blank">
                                            <i class="fas fa-paperclip"> <?= $archivo->alias_archivo ?></i>
                                        </a>
                                    </li>

                                    <?php
                                }
                                ?>

                            </ul>                            
                        </div>
                    </div>

                    <div class="card" style="margin-top: 15px">
                        <div class="card-header" style="background-color: green; color: white">Estados</div>
                        <div class="card-body" style="text-align: left">
                            <?php
                            if ($modelActividad->es_calificado) {
                                echo '<i class="fas fa-check" style="color: green"> Es calificado...</i>';
                            } else {
                                echo '<i class="fas fa-ban" style="color: red"> No es calificada</i>';
                            }

                            echo '<br>';

                            if ($modelActividad->es_publicado) {
                                echo '<i class="fas fa-check" style="color: green"> Es publicado...</i>';
                            } else {
                                echo '<i class="fas fa-ban" style="color: red"> No es publicado</i>';
                            }

                            echo '<br>';

                            if ($modelActividad->es_aprobado) {
                                echo '<i class="fas fa-check" style="color: green"> Es aprobado...</i>';
                            } else {
                                echo '<i class="fas fa-ban" style="color: red"> No es aprobado</i>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="card" style="margin-top: 15px">
                        <div class="card-header" style="background-color: #eee; color: black">Actualizaciones</div>
                        <div class="card-body" style="text-align: left">
                            <b>Creado por:</b>
                            <?= $modelActividad->created ?>
                            <br>
                            <b>Creado el:</b>
                            <?= $modelActividad->created_at ?>

                            <br>
                            <br>
                            <b>Actualizado por:</b>

                            <?= $modelActividad->updated ?>
                            <br>
                            <b>Actualizado el:</b>
                            <?= $modelActividad->updated_at ?>
                        </div>
                    </div>                                        
                </div>


                <div class="col-lg-8 col-md-8">
                    <div class="row" style="background-color: white; height: 600px;
                         overflow-y: scroll;
                         overflow-x: scroll;
                         text-align: left">

                        <?= Html::beginForm(['acciones'], 'post', ['enctype' => 'multipart/form-data']) ?>

                        <input type="hidden" name="campo" value="actualizar">
                        <input type="hidden" name="id" value="<?= $modelActividad->id ?>">
                        <input type="hidden" name="lms_id" value="<?= $modelActividad->lms_id ?>">
                        <input type="hidden" name="clase_id" value="<?= $clase_id ?>">
                        <input type="hidden" name="semana_numero" value="<?= $numero_semana ?>">
                        <input type="hidden" name="nombre_semana" value="<?= $nombre_semana ?>">

                        <div class="form-group" style="margin-top: 10px"><!-- INICIO DE TITULO -->
                            <label for="titulo" class="form-label">TÍTULO</label>
                            <input type="text" name="titulo" value="<?= $modelActividad->titulo ?>" class="form-control">
                        </div> <!-- FIN DE TITULO -->

                        <!-- INICIO DE DESCRIPCIÓN -->
                        <div class="form-group" style="margin-top: 10px">
                            <label for="descripcion" class="form-label">DESCRIPCIÓN</label>
                            <textarea name="descripcion"><?= $modelActividad->descripcion ?></textarea>
                            <script>
                                CKEDITOR.replace('descripcion');
                            </script>
                        </div>
                        <!-- FIN DE DESCRIPCIÓN -->


                        <!-- INICIO DE TAREA -->
                        <div class="form-group" style="margin-top: 10px">
                            <label for="tarea" class="form-label">TAREA</label>
                            <textarea name="tarea"><?= $modelActividad->tarea ?></textarea>
                            <script>
                                CKEDITOR.replace('tarea');
                            </script>
                        </div>
                        <!-- FIN DE TAREA -->

                        <!-- INICIO DE INDICACIONES -->
                        <div class="form-group" style="margin-top: 10px">
                            <label for="material" class="form-label">INDICACIONES</label>
                            <textarea name="material"><?= $modelActividad->material_apoyo ?></textarea>

                            <script>
                                CKEDITOR.replace("material", {
                                    //                            toolbar: [ 'bold', 'italic', 'link', 'undo', 'redo',redo 'numberedList', 'bulletedList' ]
                                    customConfig: "/ckeditor_settings/config.js"
                                });

                            </script>
                        </div>
                        <!-- FIN DE INDICACIONES -->


                        <!-- INCIA ES CALIFICADO -->
                        <div class="form-group" style="margin-top: 10px">
                            <label for="esCalificado" class="form-label">ES CALIFICADO?</label>
                            <div class="form-check form-switch">

                                <?php
                                if ($modelActividad->es_calificado) {
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                }
                                ?>

                                <input class="form-check-input" name="es_calificado" type="checkbox" id="flexSwitchCheckChecked" <?= $checked ?> >
                            </div>
                        </div>
                        <!-- FIN ES CALIFICADO -->


                        <!-- INCIA ES PUBLICADO -->
                        <div class="form-group" style="margin-top: 10px">
                            <label for="esPublicado" class="form-label">ES PUBLICADO?</label>
                            <div class="form-check form-switch">

                                <?php
                                if ($modelActividad->es_publicado) {
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                }
                                ?>

                                <input class="form-check-input" name="es_publicado" type="checkbox" id="flexSwitchCheckChecked" <?= $checked ?> >
                            </div>
                        </div>
                        <!-- FIN ES PUBLICADO -->

                        <br>
                        <button type="submit" class="btn btn-outline-primary">Actualizar</button>


                    </div>

                    <?= Html::endForm() ?>

                </div>
            </div>

        </div>
        <!--fin de cuerpo-->

    </div>
</div>
</div>


<script>




</script>