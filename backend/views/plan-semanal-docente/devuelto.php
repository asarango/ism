<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Revisar planificación';
?>

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
            Página en construcción !!!
            <p>
                <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Link with href
                </a>                
            </p>
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                    Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
                </div>
            </div>
            <?php
            print_r($bitacora);
            ?>
            <!--finaliza cuerpo de documento-->


        </div>
    </div>

</div>