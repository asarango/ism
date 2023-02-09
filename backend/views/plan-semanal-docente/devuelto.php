<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Revisar planificación';
?>

<style>
    .btn-whatsapp {
        position: fixed;
        /* width: 60px; */
        /* height: 60px; */
        bottom: 200px;
        right: 40px;
        /* background-color: #898b8d; */
        /* color: #FFF; */
        /* border-radius: 50px; */
        text-align: center;
        /* font-size: 30px; */
        /* box-shadow: 2px 2px 3px #999; */
        z-index: 100;
    }

    .btn-whatsapp:hover {
        text-decoration: none;
        color: #25d366;
        background-color: #fff;
    }

    .my-float {
        margin-top: 16px;
    }
</style>

<div class="scholaris-asistencia-profesor-index" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/calendario.png" width="34px" style="" class="img-thumbnail"></h4>
                </div>

                <div class="col-lg-5 col-md-5">
                    <h5><?= Html::encode($this->title) ?></h5>
                    <small>
                        <?=
                        $week->nombre_semana . ' | <b>desde:</b> '
                            . $week->fecha_inicio . ' <b>hasta:</b> '
                            . $week->fecha_finaliza
                        ?>
                    </small>
                </div>

                <div class="col-lg-2 col-md-2">

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
            <hr>

            <?php
            foreach ($bitacora as $bit) {
            ?>            
                <div class="card" style="margin-bottom: 10px; padding: 10px; font-size: 10px">
                    <a data-bs-toggle="collapse" href="#collapseCourse<?= $bit['curso_id'] ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <u><?= $bit['curso'] ?></u>
                    </a>                    
                <div class="collapse" id="collapseCourse<?= $bit['curso_id'] ?>">
                    <div class="card-body">
                        <?= $bit['obervaciones'] ?>
                    </div>
                </div>
                </div>
            <?php
            }
            ?>
            <!--finaliza cuerpo de documento-->


        </div>
    </div>

    <div class="btn-whatsapp">
        <?php
            echo Html::a('<img src="../ISM/main/images/states/enviar.png">', ['acciones', 
                'action'    => 'enviar',
                'week_id'   => $week->id
            ]);                   
        ?>
    </div>

</div>