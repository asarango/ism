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

// echo '<pre>';
// print_r($hours);


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
                    <h4><img src="../ISM/main/images/submenu/calendario.png" width="34px" style=""
                            class="img-thumbnail"></h4>
                </div>

                <div class="col-lg-4 col-md-4">
                    <h5>
                        <?= Html::encode($this->title) ?>
                    </h5>
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
                        //Recorro array para pintar las planificaciones
                        // print_r($aDiasPlans);
                        // die();
                        

                        foreach ($aDiasPlans as $keyDia => $dia) {
                            ?>
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_<?= $dia["nombre"] ?>" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            <?= $dia["curso"] . " / " . $dia["nombre"] . " / " . $dia["fecha"] ?>
                                        </button>
                                    </h2>
                                    <div id="collapse_<?= $dia["nombre"] ?>" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <?php
                                            if (!isset($dia["planificaciones"])) {
                                                echo "No tiene clases en este día el profesor!";
                                            } else {
                                                foreach ($dia["planificaciones"] as $keyPlan => $plan) {
                                                    ?>
                                                    <div class="accordion" id="accordionExample" style="margin-bottom: 10px;">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingOne">
                                                                <button class="accordion-button" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapse_<?= $keyPlan ?>" aria-expanded="true"
                                                                    aria-controls="collapseOne">
                                                                    <?= $plan["materia"] ?> / PARALELO: <?= $plan["paralelo"] ?> /
                                                                    HORA: <?= $plan["hora"] ?>
                                                                </button>
                                                            </h2>
                                                            <div id="collapse_<?= $keyPlan ?>"
                                                                class="accordion-collapse collapse show"
                                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">

                                                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                        <li class="nav-item" role="presentation">
                                                                            <button class="nav-link" id="home-tab"
                                                                                data-bs-toggle="tab"
                                                                                data-bs-target="#home_<?= $keyPlan ?>" type="button"
                                                                                role="tab" aria-controls="home" aria-selected="true"
                                                                                onclick="get_info_planificacion(<?= $plan['clase_id'] ?>, <?= $plan['detalle_id'] ?>, `actividades`, <?= $keyPlan ?>)">
                                                                                Detalle Planificación
                                                                            </button>
                                                                        </li>

                                                                    </ul>


                                                                    <div class="tab-content" id="myTabContent">
                                                                        <div class="tab-pane fade" id="home_<?= $keyPlan ?>"
                                                                            role="tabpanel" aria-labelledby="home-tab"></div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
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
                            'action' => Url::to([
                                'change-state',
                                'plan_id' => $plans->id,
                                'week_id' => $week->id,
                                'action' => 'cambiarestado'
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
                        } elseif ($plans->estado == 'ENVIO_COORDINACION') {
                            ?>
                            <br>
                            <input type="hidden" name="plan_id" value="<?= $plans->id ?>">
                            <input type="hidden" name="week_id" value="<?= $week->id ?>">
                            <input type="hidden" name="cursoId" value="<?= $cursoId ?>">
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
                                        [
                                            'change-state',
                                            'plan_id' => $plans->id,
                                            'week_id' => $week->id,
                                            'curso_id' => $cursoId,
                                            // 'curso_id' => $week->id,
                                            'action' => 'cambiarestado',
                                            'observaciones' => 'APROBADO',
                                            'estado' => 'APROBADO',
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

<script>
    //Funcion que trae info de planificacion de la clase
    function get_info_planificacion(curso_id, detalle_id, bandera, indicador) {

        // alert(indicador);

        var plan_semanal_id = "<?= $planSemanalId ?>";

        var url = "<?= Url::to(["info-planificacion"]) ?>";
        var params = {
            plan_semanal_id: plan_semanal_id,
            curso_id: curso_id,
            detalle_id: detalle_id,
            bandera: bandera
        };

        $.ajax({
            url: url,
            data: params,
            type: 'POST',
            success: function (resp) {
                $("#home_" + indicador).html(resp);
            }
        });

        // $("#clase_planificacion_id").val(id);



    }
</script>