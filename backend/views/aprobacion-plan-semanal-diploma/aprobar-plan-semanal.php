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
$userLog        = Yii::$app->user->identity->usuario;
$contador = 0;

// echo "<pre>";
// print_r($trimestre_name);
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
                <p>
                    <?php
                    echo 'Correo:' . $bitacora->docente_usuario . ' ' . '/' . ' ' . 'Fecha de envio: ' .
                        $bitacora->fecha_envio . ' ' . '/' . ' ' . $trimestre_name;
                    ?>
                </p>
            </div>
            <div class="col-lg-2 col-md-2" style="text-align: right;">
                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                    [
                        'site/index',
                    ],
                    ['class' => 'link']
                );
                ?>
                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Aprobaciones</span>',
                    ['planificacion-aprobacion/index'],
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
            <div class="col-lg-4 col-md-4" style="padding-top: 40px">


                <?php
                //                 echo '<pre>';
                // print_r($bitacora);
                // die();
                $estado = $bitacora ? $bitacora->estado : 'INICIANDO';
                if ($estado == 'APROBADO') {
                ?>
                    <div class="alert alert-success" role="alert" style="text-align:center">
                        ¡Usted aprobó Planificaciones <i class="fas fa-thumbs-up"></i>!
                    </div>
                <?php
                } elseif ($estado == 'COORDINADOR') {
                ?>
                    <div class="alert alert-info" role="alert" style="text-align:center">
                        ¡Revisar la planificación!
                    </div>

                    <?= Html::beginForm(
                        [
                            'devolver'
                        ],
                        'post'
                    )
                    ?>

                    <input type="hidden" name="semana_id" value="<?= $bitacora->semana_id ?>">
                    <input type="hidden" name="docente_usuario" value="<?= $bitacora->docente_usuario ?>">
                    <input type="hidden" name="estado" value="<?= $bitacora->estado ?>">
                    <input type="hidden" name="usuario_envia" value="<?= $bitacora->usuario_envia ?>">
                    <input type="hidden" name="usuario_recibe" value="<?= $bitacora->usuario_recibe ?>">
                    <input type="hidden" name="trimestre_name" value="<?= $trimestre_name ?>" />

                    <!--CKEDITOR-->
                    <!--EDITOR DE TEXTO KARTIK-->
                    <textarea name="revision_coordinacion_observaciones" id="editor" required>
                    <?= $bitacora->obervaciones ?>
                </textarea>
                    <script>
                        CKEDITOR.replace('editor', {
                            customConfig: '/ckeditor_settings/config.js'
                        })
                    </script>

                    <br>

                    <div class="row" style="text-align: center; padding-left: 30px;padding-right: 30px;">
                        <?=
                        Html::submitButton(
                            'Devolver Planificación',

                            [
                                'class' => 'btn btn-danger my-text-medium',
                            ]
                        )
                        ?>
                        <hr>
                        <!-- <i class="far fa-hand-point-down" style="font-size: 20px;color: #0a1f8f"></i> -->
                        <?=
                        Html::a(
                            '<i class="fas fa-check-circle"> Aprobar Planificación</i>',
                            [
                                'aprobacion',
                                'semana_id' => $bitacora->semana_id,
                                'docente_planificacion' => $bitacora->usuario_envia,
                                'trimestre_name' => $trimestre_name

                            ],
                            ['class' => 'btn btn-success my-text-medium']
                        );
                        ?>
                        <?= Html::endForm() ?>
                    </div>
                <?php
                } elseif ($estado == 'DEVUELTO') {
                ?>
                    <div class="alert alert-warning" role="alert" style="text-align:center; color: red;">
                        ¡Se ha enviado ha devuelto la planificación!
                    </div>
                <?php
                } elseif ($estado == 'INICIANDO') {
                ?>
                    <div class="alert alert-info" role="alert" style="text-align:center">
                        ¡El profesor está iniciando su planificación!
                    </div>
                <?php
                }
                ?>
            </div>

        </div>
    </div>
</div>