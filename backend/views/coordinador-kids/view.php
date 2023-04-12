<?php

use backend\models\LmsActividad;
use backend\models\LmsDocenteNee;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Revisión plan semanal KIDS';
?>
<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>

<style>
    .btn-whatsapp {
        position: fixed;
        /* width: 60px; */
        /* height: 60px; */
        top: 120px;
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

<div class="coordinador-pep-view" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/calendario.png" width="34px" style="" class="img-thumbnail"></h4>
                </div>

                <div class="col-lg-4 col-md-4">
                    <h5><?= Html::encode($this->title) ?></h5>
                    <smal>
                        <?= $plans->semana->nombre_semana ?> |
                        <?= $plans->created ?>
                    </smal>
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
            <div class="row" style="border-top: solid 1px #ccc;">
                <div class="col-lg-6 col-md-6">
                    <div class="" style="border-right: solid 1px #ab0a3d; 
                                            box-shadow: 5px 5px 5px 5px #ab0a3d;
                                            padding: 5px;">
                        <h6><b>Planificación semanal</b></h6>
                        <?php
                        //inicio de foreach principal de dias y fechas
                        $contadorNoPlanificado = 0;
                        foreach ($dates as $date) {
                        ?>

                            <!-- inicio de dia y fecha -->
                            <div class="row" style="margin-top: 30px;">
                                <div class="col-lg-12 col-md-12" style="color: #0a1f8f;">
                                    <i class="fas fa-clock"></i>
                                    <?= $date['nombre'] . ' ' . $date['fecha'] ?>
                                    <hr>
                                </div>
                            </div>
                            <!-- fin de dia y fecha -->

                            <?php
                            //inicio de horas

                            foreach ($hours as $hour) {
                                if ($date['numero'] == $hour['dia_numero']) {

                                    if ($hour['responsable_planificacion'] == $user) {
                                        $color = '#ff9e18';
                                    } else {
                                        $color = '#9e28b5';
                                    }

                            ?>
                                    <div class="row" style="margin: 0px 50px 50px 50px; 
                    color: <?= $color ?>; 
                    border: solid 1px <?= $color ?>; 
                    ">
                                        <nav aria-label="breadcrumb" style="background-color: <?= $color ?>;">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item active" aria-current="page" style="color: white;"><?= $hour['hora'] ?></li>
                                                <li class="breadcrumb-item active" aria-current="page" style="color: white;"><?= $hour['curso'] ?></li>
                                                <li class="breadcrumb-item active" aria-current="page" style="color: white;"><?= $hour['materia'] ?></li>
                                                <li class="breadcrumb-item active" aria-current="page" style="color: white;"><?= $hour['responsable_planificacion'] ?></li>
                                            </ol>
                                        </nav>


                                        <?php
                                        $detail = get_planification_by_hour($hour['detalle_id'], $hour['clase_id']);

                                        if ($detail) {
                                            if ($detail['fecha'] == $date['fecha']) {
                                                if ($detail['titulo'] == 'NO CONFIGURADO') {
                                                    $contadorNoPlanificado++;
                                                    echo '<div class="col">';
                                                    echo '<p>';
                                                    echo '<img src="../ISM/main/images/actions/trabajando.gif" width="70px" style="" class="">';
                                                    echo '<b>' . $detail['titulo'] . '</b>';
                                                    echo '</p>';
                                                    echo '</div>';
                                                } else {
                                                    echo '<div class="col" style="overflow-y: scroll; height: 400px">';
                                                    echo '<p><b>TÍTULO: </b>' . $detail['titulo'] . '</p>';

                                                    echo '<div class="row">';
                                                    echo '<div class="col-lg-3">';

                                                    echo '</div>';
                                                    echo '</div>';

                                                    echo '<p><b>ACTIVIDADES</b>' . $detail['descripcion_actividades'] . '</p>';

                                                    echo '<p style="margin-top:10px">';
                                                    echo '<b>TAREAS / EVALUACIONES</b><br>';

                                                    $homeWork = LmsActividad::find()->where(['lms_id' => $detail['lms_id']])->all();

                                                    foreach ($homeWork as $hw) {
                                                        echo '<span class="badge rounded-pill" style="background-color: #0a1f8f; margin-right:3px;">
                                        <i class="fas fa-book-reader"> ' . $hw->titulo . '</i>'
                                                            . '</span>';
                                                    }
                                                    echo '</p>';

                                                    echo '<p style="margin-top:10px">';
                                                    echo '<b>ADAPATACIONES CURRICULARES</b><br>';
                                                    $adaptaciones = LmsDocenteNee::find()->where(['lms_docente_id' => $detail['id']])->all();
                                                    echo '<ul style="font-size: 10px">';
                                                    foreach ($adaptaciones as $adaptacion) {
                                                        echo '<li style="margin-bottom: 10px"><i class="fas fa-child"></i> '
                                                            . '<b><u>' . $adaptacion->neeXClase->nee->student->first_name . ' ' . $adaptacion->neeXClase->nee->student->last_name . '</u></b>'
                                                            . '<br>' . $adaptacion->adaptacion_curricular
                                                            . '</li>';
                                                    }

                                                    echo '</ul>';

                                                    echo '</p>';
                                                    echo '</div>';
                                                }
                                            } else {
                                                $contadorNoPlanificado++;
                                                echo 'Hora libre';
                                            }
                                        } else {
                                            $contadorNoPlanificado++;
                                            echo '<div class="col">
                                <img src="../ISM/main/images/actions/no.gif" 
                                     width="50px" style="" class="">Sin planificar</div>';
                                        }

                                        ?>
                                    </div>

                            <?php
                                }
                            }
                            //fin de horas
                            ?>


                        <?php //fin de foreach principal de dias y fechas
                        }
                        ?>
                    </div>
                </div>
                <!-- fin de div de planificación de docente -->


                <!-- inicio de formulario para ingresar retroalimentacion -->
                <div class="col-lg-6 col-md-6">
                    <div class="btn-whatsapp" style="padding: 45px;">
                        <h6><b>Observaciones</b></h6>

                        <?php
                        $form = ActiveForm::begin([
                            'action' => Url::to(['change-state', 
                                                    'plan_id' => $plans->id,
                                                    'week_id' => $week->id,
                                                    'action'  => 'cambiarestado'
                                                ]),
                            'method' => 'post'
                        ]);
                        ?>

                        <!--CKEDITOR-->
                        <!--EDITOR DE TEXTO KARTIK-->
                        <textarea name="observaciones" id="editor">
                        <!-- <= $plans->obervaciones ?> -->
                    </textarea>
                        <script>
                            CKEDITOR.replace('editor', {
                                customConfig: '/ckeditor_settings/config.js'
                            })
                        </script>


                        <?php
                        if ($plans->estado == 'APROBADO') {
                        ?>
                            <div class="alert alert-success" role="alert" style="text-align:center">
                                ¡Usted aprobó Planificaciones <i class="fas fa-thumbs-up"></i>!
                            </div>
                        <?php
                        } elseif ($plans->estado == 'COORDINADOR') {
                        ?>
                            <br>
                            <input type="hidden" name="estado" value="DEVUELTO">
                            <input type="hidden" name="user_teacher" value="<?= $user ?>">
                            <div class="row" style="text-align: center; padding-left: 30px;padding-right: 30px;">
                                <?=
                                Html::submitButton(
                                    'Devolver Planificación',
                                    [
                                        'class' => 'btn btn-danger my-text-medium'
                                    ]
                                )
                                ?>
                                <hr>
                                <i class="far fa-hand-point-down" style="font-size: 20px;color: #0a1f8f"></i>
                                <?=
                                // Html::a port yii2 ?  
                                Html::a(
                                    '<i class="fas fa-check-circle"> Aprobar Planificación</i>',
                                    ['change-state', 
                                        'plan_id' => $plans->id,
                                        'week_id' => $week->id,
                                        'action'  => 'cambiarestado',
                                        'observaciones' => 'APROBADO',
                                        'estado'    => 'APROBADO',
                                        'user_teacher' => $user
                                    ],
                                    ['class' => 'btn btn-success my-text-medium']
                                );
                                ?>
                            </div>
                        <?php
                        } elseif ($plans->estado == 'DEVUELTO') {
                        ?>
                            <div class="alert alert-warning" role="alert" style="text-align:center">
                                ¡Se ha enviado a modificar al profesor!
                            </div>
                        <?php
                        } elseif ($plans->estado == 'INICIANDO') {
                        ?>
                            <div class="alert alert-info" role="alert" style="text-align:center">
                                ¡El profesor está iniciando su planificación!
                            </div>
                        <?php
                        }
                        ?>

                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
            <!-- fin de retroalimentación -->

        </div>
        <!--finaliza cuerpo de documento-->


    </div>
</div>

</div>


<?php

function get_planification_by_hour($detalleHorarioId, $claseId)
{
    $con = Yii::$app->db;
    $query = "select 	ld.id 
                    ,ld.fecha 
                    ,lm.titulo 
                    ,lm.descripcion_actividades 
                    ,lm.id as lms_id
                    ,ld.clase_id
                    ,ld.observaciones                    
                from 	lms_docente ld
                    left join lms lm on lm.id = ld.lms_id 
                where 	ld.horario_detalle_id = $detalleHorarioId
                    and ld.clase_id = $claseId;";

    $res = $con->createCommand($query)->queryOne();
    return $res;
}
?>