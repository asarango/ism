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
//print_r($modelActividad->lmsActividads);
//
//die();


$this->title = 'Lms - ' . $modelActividad->lms->ismAreaMateria->materia->nombre;
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>-->
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

<link rel="stylesheet" href="estilo.css"/>


<div class="lms-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1"><h4><img src="ISM/main/images/submenu/aula.png" width="64px" style="" class="img-thumbnail"></h4></div>
                <div class="col-lg-11">
                    <h4>
                        <?= Html::encode($this->title) ?>
                        <small>
                            <b> | Semana N°: </b><?= $modelActividad->lms->semana_numero ?> |
                            <b>Semana N°: </b><?= $modelActividad->lms->hora_numero ?> |
                            <b>Tema de la Hora N°: </b><?= $modelActividad->lms->titulo ?> |
                        </small>
                    </h4>
                    <h3><b>Actividad: </b><?= $modelActividad->titulo ?></h3>
                </div>
            </div>
            <hr>

            <p>
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
            </p>

            <!--incia cuerpo-->
            <div class="row" style="text-align: center; background-color: #ccc; padding: 5px">
                <div class="col-lg-3 col-md-3" style="">
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
                                            <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <?= Html::beginForm(['acciones'], 'post', ['enctype' => 'multipart/form-data']) ?>
                                        <div class="modal-body">
                                            
                                            
                                            <input type="hidden" name="lms_id" value="<?= $modelActividad->lms_id ?>"><!-- comment -->
                                            <input type="hidden" name="lms_actividad_id" value="<?= $modelActividad->id ?>"><!-- comment -->
                                            <input type="hidden" name="path_ism_area_materia_id" value="<?= $modelActividad->lms->ism_area_materia_id ?>"><!-- comment -->
                                            <input type="hidden" name="campo" value="upload"><!-- comment -->
                                                                                        
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
                        <div class="card-body">
                            jdhkjdfkj dklfñldskf
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-5">
                    <div class="">
                        contenido
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="" style="background: white">
                        Opciones
                    </div>
                </div>
            </div>
            <!--fin de cuerpo-->

        </div>
    </div>
</div>


<script>




</script>