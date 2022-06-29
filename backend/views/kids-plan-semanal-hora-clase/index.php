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
//  print_r($destrezasDisponibles);
// die();

?>
<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>

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
                                    <div class="card shadow p-3 zoom" style="margin-top:10px">
                                    <strong class="text-segundo">Ámbito:</strong>
                                        <p class="text-segundo"><?=$destrezaSel['ambito'] ?></p>
                                        <br>
                                        <strong class="text-primero">Destreza:</strong>
                                        <p class="text-primero"><?=$destrezaSel['destreza'] ?></p>
                                        
                                    </div>
                                    <?php
                                }
                                ?>
                                </div>
                            </div>

                        
                        </div>
                        <div class="col-md-8 col-sm-8">
                        <?= Html::beginForm(['ingresa-destreza'], 'post', ['id' => 'form_destreza']) ?>
                            <textarea name="contenido" class="form-control" ></textarea>
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