<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BitacoraAprobacionesPlanificaciones */

$this->title = 'Update Bitacora Aprobaciones Planificaciones: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bitacora Aprobaciones Planificaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bitacora-aprobaciones-planificaciones-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
