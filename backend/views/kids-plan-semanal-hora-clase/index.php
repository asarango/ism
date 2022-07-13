<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\KidsPlanSemanalHoraClaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kids Plan Semanal Hora Clases';
$this->params['breadcrumbs'][] = $this->title;
 
// echo '<pre>';
// print_r($destrezasDisponibles);
// print_r($modelDestrezas);
// print_r($modelDestrezas[0]->microDestreza->destreza->nombre);
$semana = $model->planSemanal->semana->nombre_semana;
$dia = $model->detalle->dia->nombre;
$hora = $model->detalle->hora->sigla;
$curso = $model->clase->paralelo->course->name;
$paralelo = $model->clase->paralelo->name;
$materia = $model->clase->ismAreaMateria->materia->nombre;
$hoy = date('d-m-Y');
//  print_r($destrezasDisponibles);
// die();

?>
<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>

<div class="kids-plan-semanal-hora-clase-index">

    <div class="" style="padding-left: 40px; padding-right: 40px">

        <div class="m-0 vh-50 row justify-content-center align-items-center">
            <div class="card shadow col-lg-12 col-md-12">

                <!-- comienza encabezado -->
                <div class="row" style="background-color: #ccc; font-size: 12px">
                    <div class="col-md-6 col-sm-6">
                        <p style="color:white">
                            |                                
                            <?=
                            Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                    ['site/index'], ['class' => 'link']);
                            ?>                
                            |
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificaciones</span>',
                                    [
                                        'kids-menu/index1'
                                    ]
                            );
                            ?>    
                            |
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Plan Semanal</span>',
                                    [
                                        'kids-plan-semanal/detalle',
                                        'kids_plan_semanal_id' => $model->plan_semanal_id
                                        
                                    ]
                            );
                            ?>    
                            | 
                        </p>
                    </div>

                    <div class="col-md-6 col-sm-6" style="text-align:end">
                        <p>
                        |
                        <strong class="badge bg-tercero text-primero"><?=$semana?></strong>
                        |
                        <strong class="badge bg-tercero text-primero">HORA: <?=$dia.' '.$hora?></strong>
                        |
                        </p>    
                    </div>

                    <div class="col-md-12 col-sm-12" style="text-align:center">
                        <div>
                            <h4 class="text-primero">"<?=$model->planSemanal->kidsUnidadMicro->experiencia?>"</h4>    
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">

                            </div>
                            <div class="col-md-4 col-sm-4" style="text-align:center">
                                <strong class="text-primero" ><?=$curso.' "'.$paralelo.'"'?> - <?=$materia?></strong>
                            </div>
                            
                        </div> 
                    </div>
                </div>
                <!-- Fin de encabezado -->

                <!--comienza cuerpo de documento-->
                <div style="background-color:#fff">
                    <div class="row" style="padding:10px">
                        <div class="col-md-4 col-sm-4">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <?= Html::beginForm(['ingresa-destreza'], 'post', ['id' => 'form_destreza']) ?>

                                        <input type="hidden" value="<?=$model->id?>" name="hora_clase_id" >

                                        <select class="form-select" aria-label="Default select example" 
                                            name="micro_destreza_id" id="destreza" require="">
                                                    <option value="" selected>--- Seleccione Destreza ---</option>
                                                    <?php
                                                    foreach($destrezasDisponibles as $destrezaDis){
                                                    ?>
                                                    <option value="<?=$destrezaDis['id'] ?>"><?=$destrezaDis['destreza'] ?></option>
                                                <?php
                                                }
                                            ?>
                                        </select>
                                    <?= Html::endForm() ?>
                                </div>
                            </div>

                            <!-- Muestra cards de destrezas seleccionadas -->
                            <div class="row">
                                <div class="col-md-12 col-sm-12" style="padding:10px">
                                <?php 
                                foreach($modelDestrezas as $destrezaSel){
                                    ?>
                                    <div class="card shadow p-3 " style="margin-top:10px">
                                        <div style="text-align:end">
                                            <!-- BOTON AGREGAR TAREA -->
                                            <a type="button" data-bs-toggle="modal" data-bs-target="#tareaModal<?=$destrezaSel['id'] ?>">
                                                Agregar tarea &nbsp;
                                                <i class="fas fa-sticky-note" style="color:#65b2e8" title="Agregar Tarea"></i>
                                            </a>

                                                <!-- MODAL AGREGAR TAREA -->
                                                <div class="modal fade" id="tareaModal<?=$destrezaSel['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">AGREGAR TAREA</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <?= Html::beginForm(['kids-destreza-tarea/crear-tarea'], 'post', ['enctype' => 'multipart/form-data']) ?>

                                                                <input type="hidden" name="plan_destreza_id" value="<?=$destrezaSel['id']?>" >
                                                                <div class="row" style="text-align:start">

                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="mb-3">
                                                                            <label for="exampleInputEmail1" class="form-label">TITULO</label>
                                                                            <input type="text" class="form-control" name="titulo" require="">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="mb-3">
                                                                            <label for="exampleInputEmail1" class="form-label">FECHA DE PRESENTACION</label>
                                                                            <input type="date" class="form-control" name="fecha_presentacion" require="">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" require="" type="radio" name="publicado_al_estudiante" id="exampleRadios1" value="1" checked >
                                                                            <label class="form-check-label" for="exampleRadios1">
                                                                                PUBLICAR
                                                                            </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                            <input class="form-check-input" require="" type="radio" name="publicado_al_estudiante" id="exampleRadios2" value="0">
                                                                            <label class="form-check-label" for="exampleRadios2">
                                                                                NO PUBLICAR
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 col-sm-6">
                                                                        <div class="mb-3">
                                                                            <label for="formFileMultiple" class="form-label">Subir archivos</label>
                                                                            <input class="form-control" type="file" id="formFileMultiple" name="archivo[]" multiple>
                                                                        </div>                    
                                                                    </div>

                                                                    <div class="col-md-12 col-sm-12">
                                                                        <div class="mb-3">
                                                                            <label for="exampleInputPassword1" class="form-label">CONTENIDO</label>
                                                                            <textarea name="detalle_tarea" require="" class="form-control" ></textarea>
                                                                            <script>
                                                                                CKEDITOR.replace( 'detalle_tarea',{
                                                                                    customConfig: '/ckeditor_settings/config.js'                                
                                                                                    } );
                                                                            </script>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12 col-sm-12">
                                                                        <div class="mb-3">
                                                                            <label for="exampleInputPassword1" class="form-label">RECURSOS</label>
                                                                            <textarea name="materiales" require="" class="form-control" ></textarea>
                                                                            <script>
                                                                                CKEDITOR.replace( 'materiales',{
                                                                                    customConfig: '/ckeditor_settings/config.js'                                
                                                                                    } );
                                                                            </script>
                                                                        </div>
                                                                    </div>

                                                                    <button type="submit" class="btn btn-primary">Agregar Tarea/Evaluación</button>                   

                                                                </div>        

                                                                <?= Html::endForm() ?>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>

                                        <!-- MUESTRA INFORMACIÓN DESTREZA - AMBITO Y TAREAS -->
                                        <strong class="text-segundo">Ámbito:</strong>
                                        <p class="text-segundo"><?=$destrezaSel['ambito'] ?></p>
                                        <br>
                                        <strong class="text-primero">Destreza:</strong>
                                        <p class="text-primero"><?=$destrezaSel['destreza'] ?></p>
                                        <div class="row card-footer">
                                            <strong>TAREAS/EVALUACIÓN</strong> 
                                            <div class="table table-responsive">
                                                <table class="table table-hover table-stripped my-text-small">
                                                    <thead>
                                                        <tr>
                                                            <th>VER</th>
                                                            <th>TITULO</th>
                                                            <th>F.PRESENTACION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        foreach($destrezaSel['tareas'] as $tarea ){
                                                            ?>
                                                        <tr>
                                                            <td style="width:30px">
                                                                <?=Html::a(
                                                                    '<i class="fas fa-pencil-alt" style="font-size:12px; color:#0a1f8f"  ></i>',
                                                                    [
                                                                        'kids-destreza-tarea/update',
                                                                        'id' => $tarea['id']
                                                                    ]
                                                                );?>
                                                            </td>
                                                            <td><?=$tarea['titulo'] ?></td>
                                                            <td><?=$tarea['fecha_presentacion'] ?></td>
                                                        </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <?php
                                }
                                ?>
                                </div>
                            </div>
                        </div>

                        <!-- LADO DERECHO CKEDITOR -->
                        <div class="col-md-8 col-sm-8">

                        <h5><b>DETALLE DE ACTIVIDADES PARA LA HORA CLASE</b></h5>
                        <?= Html::beginForm(['actualizar-actividad'], 'post') ?>

                            <input type="hidden" name="hora_clase_id" value="<?=$model->id?>" >
                            <textarea name="contenido" class="form-control" ><?=$model->actividades?></textarea>
                            <script>
                                CKEDITOR.replace( 'contenido',{
                                    customConfig: '/ckeditor_settings/config.js'                                
                                    } );
                            </script>

                             <div style="text-align:end; margin-top:5px">
                                <button type="submit" class="btn btn-success" >Actualizar</button>
                             </div>       

                        <?= Html::endForm() ?>
                        </div>
                    </div>
                </div>
                

                <!--finaliza cuerpo de documento-->

            </div>

        </div>

    </div>
</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script>
    //Funcion para tomar id del option del select
    $("#destreza").on("change",function(){
        var idDestreza = $('select[id=destreza]').val();
        $("#form_destreza").submit();
    })
</script>