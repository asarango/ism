<?php

use backend\models\ResUsers;
use backend\models\ScholarisHorariov2Hora;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Retirar falta';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comportamiento-retirar-falta">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>

                        <?= $model->student->first_name . ' ' . $model->student->middle_name . ' ' . $model->student->last_name ?>
                        /
                        <?= $model->fecha ?>
                        /
                        <?= $model->scholarisPerido->nombre ?>


                    </small>
                </div>
            </div><!-- FIN DE CABECERA -->
            <!-- inicia menu  -->

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    );
                    ?>

                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #65b2e8">
                                <i class="fa fa-briefcase" aria-hidden="true"></i> Lista de comportamiento</span>',
                        ['index', 'id' => $asistenciaId],
                        ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <!-- inicio de menu derecha -->
                <div class="col-lg-6 col-md-6" style="text-align: right;">


                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->

            <div class="" style="margin-top: 20px;">
                <p style="margin: 20px;"><b>El estudiante tiene inasistencia en las horas detalladas a continuación: </b></p>
            </div>

            <ul>
                <?php
                foreach ($asistencias as $asistencia) {
                    $hour = get_hour($asistencia->hora_id);
                ?>

                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4" style="background-color: #ff9e18;">
                                
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $hour->nombre  ?></h5>
                                    <p class="card-text">
                                        <?= $asistencia->clase->profesor->x_first_name . ' ' . $asistencia->clase->profesor->last_name ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>

            </ul>


            <div class="" style="margin-top: 20px;">
                <p style="margin: 20px;"><b>Desea modificar el estado de falta al día?: </b></p>
                <p style="margin: 20px; padding-right: 20px;">
                    <?= Html::a('SI',['retirar-falta', 
                                'falta_id' => $model->id, 
                                'asistencia_id' => $asistencia->id,
                                'accion' => 'procesar'  ],
                                ['class' => 'btn btn-outline-success']
                                ) ?>
                    <?= Html::a('NO',['index', 'index', 'id' => $asistencia->id ], ['class' => 'btn btn-outline-danger']) ?>
                </p>

                
            </div>
            


            <!-- finaliza cuerpo de card -->

        </div>
    </div>
</div>

<?php
function get_hour($horaId)
{
    $hour = ScholarisHorariov2Hora::findOne($horaId);
    return $hour;
}

?>