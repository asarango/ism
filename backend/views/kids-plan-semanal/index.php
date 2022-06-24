<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan Semanal';

// echo '<pre>';
// print_r($pca->opCourse);
// print_r($experiencias);
?>

<div class="kids-plan-semanal-index1">

    <div class="" style="padding-left: 40px; padding-right: 40px">

        <div class="m-0 vh-50 row justify-content-center align-items-center">
            <div class="card shadow col-lg-12 col-md-12">

                <!-- comienza encabezado -->
                <div class="row" style="background-color: #ccc; font-size: 12px">
                    <div class="col-md-12 col-sm-12">
                        <p style="color:white">
                            <?= $this->title ?>
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
                           
                        </p>
                    </div>
                    <hr>
                    <div class="col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <strong>NIVEL: <?=$pca->opCourse->name?></strong>
                                </div>
                            </div>
                    </div>

                </div>
                <!-- Fin de encabezado -->

                <!--comienza cuerpo de documento-->
                <div class="row" style="background-color: #fff; margin-top:20px; margin-bottom:10px;">
                  <div class="col-md-12 col-sm-12">
                    <label for="">ESCOJA UNA EXPERIENCIA</label>
                  <select class="form-select" id="select-exp" onchange="cambia_select()" >
                    <option value="" selected="" >--- Seleccione ---</option>
                    <?php 
                    foreach ($experiencias as $key => $exp):
                        ?>
                        <option value="<?=$exp->id?>"><?=$exp->experiencia ?></option>
                        <?php
                    endforeach;
                    ?>
                    </select>
                  </div>
                </div>

                <div class="row" style="margin-top:10px;background-color: #ccc;" id="div-exp">
                    <div class="col-md-12 col-sm-12">
                        <div class="" style="padding:10px" >
                            <h4 class="text-primero" id="title-exp">Seleccione una experiencia</h4> <!-- Muestra texto del select -->
                        </div>
                    </div>
                </div>

                <!-- Muestra contenido que viene del _ajax-semanas.php -->
                <div id="resp-ajax1"></div>




                <!--finaliza cuerpo de documento-->

            </div>

        </div>

    </div>
</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script>
$(function(){
    semanas(0);
    // $("#table-exp").dataTable();
});


//on change del select para mostrar texto en div
function cambia_select(){
    var text = $('select[id="select-exp"] option:selected').text();
    var idExp = $('select[id="select-exp"] option:selected').val();
    // alert(idExp);
    $("#title-exp").html('Estás trabajando con : "'+text+'"');
    // $("#div-exp").show();
    semanas(idExp);
};

function semanas(idExp){
    var cursoId = '<?= $pca->opCourse->id ?>';
    // alert(cursoId);
    var url = "<?=Url::to(['ajax-semanas']) ?>";
    var params = {
        op_course_id : cursoId,
        experiencia_id: idExp
    }

    $.ajax({
        url:url,
        data:params,
        type: 'GET',
        success: function(resp){
            $("#resp-ajax1").html(resp);
        }
    });

}

</script>