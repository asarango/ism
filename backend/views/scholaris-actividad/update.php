<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisActividad */

$this->title = 'Actualizando Actividad: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de Actividad', 'url' => ['actividad', 'actividad' => $model->id]];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualiza Actividad';
?>

<div class="scholaris-actividad-update;">
    <div class="m-0 vh-50 row justify-content-center align-items-center ">
        <div class="card shadow col-lg-10">
            <div class="row" style="margin-top:10px;">
                <div class="col-lg-1 cold-md-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h4>
                </div>
                <div class="col-lg-7 col-md-7" style="margin-top: 20px;">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <hr>
            </div>
            <div class="col-lg-11" style="margin-bottom: 40px;margin-left:45px; ">
                <div style="align-text: center;">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'modelCalificaciones' => $modelCalificaciones,
                        'horas' => $horas
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>