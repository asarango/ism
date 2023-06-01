<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueActividad */

$this->title = 'Bloques de Unidad - Parciales: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lista de Parciales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-bloque-actividad-update" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8 co-ms1-8 col-xs-8">
            <div class="row" style="margin-top: 10px;">
                <div class="col-lg-1 col-md-1 col-ms-1 col-xs-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8" style="margin-top: 10px;">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                </div>
                <!-- botones (Bloques/Inicio)-->
                <div class="col-lg-3 col-md-3 col-ms-3 col-xs-3" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8">
                            <i class="fas fa-home"></i> Bloques
                        </span>',
                            ['index'],
                            ['class' => 'link']
                        );
                    ?>
                    |
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                    ?>
                </div>
                <hr>
            </div>
            <!-- comienzo cuerpo -->
            <?= $this->render('_form', [
                'model' => $model,
                'instituto' => $instituto,
                'modelComoCalifica' => $modelComoCalifica
            ]) ?>
        </div>
    </div>
</div>