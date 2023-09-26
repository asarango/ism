<?php

use backend\models\PlanSemanalBitacora;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan Semanal';
$contador = 0;

// echo '<pre>';
// print_r($docentes);
// die();
?>
<script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>

<div class="m-0 vh-50 row justify-content-center align-items-center">
    <div class="card shadow col-lg-12 col-md-12">
        <div class="row align-items-center p-2">
            <div class="col-lg-1 col-md-1">
                <h4><img src="../ISM/main/images/submenu/calendario.png" width="34px" style="" class="img-thumbnail"></h4>
            </div>

            <div class="col-lg-9 col-md-9">
                <h5 class="titulo">
                    <?= Html::encode($this->title) ?>
                </h5>
            </div>
            <div class="col-lg-2 col-md-2" style="text-align: right;">
                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                    ['site/index'],
                    ['class' => 'link']
                );
                ?>
            </div>
            <hr>
        </div>

        <div class="row">
            <div class="col-lg-8">

                <iframe width="100%" height="600" src="<?= Url::toRoute([
                                                            'aprobacion-plan-semanal-diploma/ver-plan-semanal',
                                                            'semanaId' => $semanaId,
                                                            'user' => $user,
                                                            'periodo' => $periodo
                                                            // 'docentes' => $docenteId
                                                        ]) ?>">
                </iframe>

            </div>

            <div class="col-lg-5 col-md-5" style="padding-top: 40px">

                <?php
                $form = ActiveForm::begin([
                    'action' => Url::to(['aprobar-plan-semanal', 'semanaId' => $semanaId,
                    'user' => $user,
                    'periodo' => $periodo]),
                    'method' => 'post'


                ]);
                ?>
                <!-- <input type="hidden" value="<?='' 
                // $template_id 
                ?>"> -->

                <!--CKEDITOR-->
                <!--EDITOR DE TEXTO KARTIK-->
                <textarea name="revision_coordinacion_observaciones" id="editor">
                        <?= $cabecera->revision_coordinacion_observaciones ?>
                    </textarea>
                <script>
                    CKEDITOR.replace('editor', {
                        customConfig: '/ckeditor_settings/config.js'
                    })
                </script>



                <?php
                if ($cabecera->estado == 'APROBADO') {
                ?>
                    <div class="alert alert-success" role="alert" style="text-align:center">
                        ¡Usted aprobó Planificaciones <i class="fas fa-thumbs-up"></i>!
                    </div>
                <?php
                } elseif ($cabecera->estado == 'EN_COORDINACION') {
                ?>
                    <br>
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
                        <!-- <i class="far fa-hand-point-down" style="font-size: 20px;color: #0a1f8f"></i> -->
                        <?=
                        Html::a(
                            '<i class="fas fa-check-circle"> Aprobar Planificación</i>',
                            [
                                'aprobacion', 'cabecera_id' => $cabecera->id,
                                'template_id' => $template_id

                            ],
                            ['class' => 'btn btn-success my-text-medium']
                        );
                        ?>
                    </div>
                <?php
                } elseif ($cabecera->estado == 'DEVUELTO') {
                ?>
                    <div class="alert alert-warning" role="alert" style="text-align:center">
                        ¡Se ha enviado a modificar al profesor!
                    </div>
                <?php
                } elseif ($cabecera->estado == 'INICIANDO') {
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
</div>