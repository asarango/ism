<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LmsActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Creando Insumo';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="lms-actividad-create">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <!-- <= $week['nombre_semana'] . ' - <b>desde:</b> ' . $week['fecha_inicio'] . ' <b>hasta:</b> ' . $week['fecha_finaliza'] ?> -->
                    </small>
                    <small>
                        <h6>

                        </h6>
                    </small>
                </div>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- inicia menu cabecera -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu cabecera izquierda -->
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
                        '<span class="badge rounded-pill" style="background-color: #ff9e18">
                                <i class="fa fa-briefcase" aria-hidden="true"></i> Insumos
                        </span>',
                        [
                            'lms-actividad/index1',
                            'lms_id' => $lms,
                            'plan_bloque_unidad_id'   => $planBloqueUnidadId,
                            'action-back' => $actionBack
                        ],
                        ['class' => 'link']
                    );
                    ?>
                    |



                </div> <!-- fin de menu cabecera izquierda -->

                <!-- inicio de menu cabecera derecha -->
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Crear insumo</span>',
                        ['create']
                    ) ?>
                </div>
                <!-- fin de menu cabecera derecha -->

                <!-- finaliza menu cabecera  -->

                <!-- inicia cuerpo de card -->

                <?= $this->render('_form', [
                    'model' => $model,
                    'lmsId'   => $lms
                ]) ?>

                <!-- fin cuerpo de card -->
            </div>
        </div>