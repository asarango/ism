<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;


$this->title = 'Kids Reportes';

// echo '<pre>';
// print_r($paralelos);
// die();
// print_r($quimestres);
?>

<div class="scholaris-asistencia-profesor-index" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12 " style="background-color: #ccc;">

            <!--comienza cabecera de documento-->
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <p>
                        |                                
                        <?=
                        Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                ['site/index'], ['class' => 'link']);
                        ?>                
                        |                                
                        <?=
                        Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificaciones</span>',
                                ['kids-menu/index1'], ['class' => 'link']);
                        ?>                
                        | 
                    </p>
                </div>
            </div> 

            <div class="row">
                <div class="col-md-12 col-sm-12">
                <strong style="color:white; font-size:20px"><?= Html::encode($this->title) ?></strong>
                </div>
            </div>    
            <!--finaliza cabecera de documento-->

            <!-- Comineza cuerpo  -->
            <div class="row" style="background-color: #fff; margin-top:20px">

                <div class="col-md-4 col-sm-4">
                    <div class="list-group">
                    <?php 
                            foreach($paralelos as $paralelo){
                            ?>
                            <!-- <li class="list-group-item" onclick="muestra_alumnos(<?=$paralelo['paralelo_id']?>)" > <?=$paralelo['curso'].' - '.$paralelo['paralelo'] ?></li> -->
                            <a class="list-group-item list-group-item-action btn inactive" id="pestana-<?=$paralelo['paralelo']?>"
                            type="button" onclick="muestra_alumnos(<?=$paralelo['paralelo_id']?>,`<?=$paralelo['paralelo']?>`)">
                                <?=$paralelo['curso'].' - '.$paralelo['paralelo'] ?>
                            </a>
                            <?php
                            }
                            ?>
                    </div>
                </div>

                <div class="col-md-8 col-sm-8" id="div-alumnos"></div> <!-- trae respuesta de _ajax-alumnos -->
            </div>

            <!-- Fin  cuerpo  -->
        </div>
    </div>
</div>


<script>
    function muestra_alumnos(idParalelo,nombrePestana){
        $(".active").removeClass('active');
        $("#pestana-"+nombrePestana+"").addClass('active');

        var url = "<?=Url::to(['lista-estudiantes']) ?>";
        var params = {paralelo_id : idParalelo};

        $.ajax({
            url: url,
            data: params,
            type:'GET',
            success:function(resp){
                $("#div-alumnos").html(resp);
            }
        });
    }
</script>