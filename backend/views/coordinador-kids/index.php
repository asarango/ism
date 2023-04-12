<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aprobaciones plan semanal KIDS';
?>

<div class="coordinador-pep-index" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/calendario.png" width="34px" style="" class="img-thumbnail"></h4>
                </div>

                <div class="col-lg-4 col-md-4">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>

                <div class="col-lg-3 col-md-3">
                    
                </div>

                <div class="col-lg-2 col-md-2">
                    
                </div>

                <!--botones derecha-->
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    |
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                    ?>
                    |
                </div>
                <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->

            </div>


            <!--comienza cuerpo de documento-->
            <div class="row" style="border-top: solid 1px #ccc">
                <div class="col-lg-3 col-md-3" style="overflow-y: scroll; height: 60vh;">
                    <?php
                        foreach($weeks as $week){
                            ?>
                                <div class="row zoom" style="border-bottom: solid 1px #eee; height:40px; align-items: center;">
                                    <div class="col-lg-12">
                                        <a href="#" style="color: #ab0a3d" onclick="show_detail(<?= $week['id'] ?>)">
                                            <i class="fas fa-calendar-week"></i> <?= $week['nombre_semana'] ?>
                                        </a>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>                    
                </div>

                <div class="col-lg-9 col-md-9">
                    <div id="div-detalle-semana"></div>
                </div>
            </div>
            <!--finaliza cuerpo de documento-->


        </div>
    </div>

</div>

<script>
    function show_detail(weekId) {
        let url = '<?= Url::to(['acciones']) ?>';      

        params = {
            week_id: weekId,
            action : 'detail'   
        };        

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $("#div-detalle-semana").html(response);
            }
        });        
    }
</script>
