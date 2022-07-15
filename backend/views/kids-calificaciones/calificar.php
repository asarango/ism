<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KidsDestrezaTareaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kids Calificaciones';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($modelTarea);

?>
<div class="kids-calificaciones-calificar">
    <div class="" style="padding-left: 40px; padding-right: 40px">
        <div class="m-0 vh-50 row justify-content-center align-items-center">
            <div class="card shadow col-lg-10 col-md-10">

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
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Kids Tareas</span>',
                                    [
                                        'kids-calificaciones/index1'
                                    ]
                            );
                            ?>    
                            |
                        
                        </p>
                    </div>
                    <div class="col-md-6 col-sm-6" style="text-align:end">
                        <p class="text-primero">
                            <strong style="color:white; font-size:20px"><?= Html::encode($this->title) ?></strong>
                        </p>
                    </div>
                    <div class="col-md-12 col-sm-12" style="text-align:center">
                        <div>
                            <!-- Button trigger modal -->
                        <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <h4 class="text-primero link">"<?=$modelTarea->titulo?>"</h4>    
                        </a>
                        </div>
                    </div>


                </div>
                    <!-- Fin de encabezado -->

                    <!-- Comienza cuerpo -->
                    <div id="div-estudiantes" style="margin-top:10px" ></div>
                    <!-- Fin cuerpo -->
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-tercero" id="exampleModalLabel">INFORMACIÓN DE LA TAREA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-md-6 col-sm-6 text-segundo">
                    <strong><i class="fas fa-font"></i> &nbsp; TITULO DE LA TAREA:</strong>
                </div>
                <div class="col-md-6 col-sm-6 text-primero">
                    <strong>"<?=$modelTarea->titulo?>"</strong>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 col-sm-6 text-segundo">
                    <strong><i class="fas fa-calendar"></i> &nbsp; FECHA PRESENTACIÓN:</strong>
                </div>
                <div class="col-md-6 col-sm-6 text-primero">
                    <strong><?=$modelTarea->fecha_presentacion?></strong>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 col-sm-6 text-segundo">
                    <strong><i class="fas fa-pencil-alt"></i> &nbsp; DETALLE:</strong>
                </div>
                <div class="col-md-6 col-sm-6 text-primero">
                    <strong><?=$modelTarea->detalle_tarea?></strong>
                </div>
            </div> 
            <hr>
            <div class="row">
                <div class="col-md-6 col-sm-6 text-segundo">
                    <strong><i class="fas fa-cut"></i> &nbsp; MATERIALES:</strong>
                </div>
                <div class="col-md-6 col-sm-6 text-primero">
                    <strong><?=$modelTarea->materiales?></strong>
                </div>
            </div>
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script>
$(function(){
    muestra_estudiantes();
});
    

    //Funcion que muestra listado de estudiantes con sus notas
    function muestra_estudiantes(){
        var url = "<?=Url::to(['ajax-lista-calificacion'])?>";
        var tareaId = "<?=$modelTarea->id?>";

        $.ajax({
            url: url,
            data : {
                tarea_id : tareaId
            },
            type: 'GET',
            success:function(resp){
                $("#div-estudiantes").html(resp);
            }
        });

    }
</script>
