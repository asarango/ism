<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BitacoraAprobacionesPlanificaciones */

$this->title = 'Create Bitacora Aprobaciones Planificaciones';
$this->params['breadcrumbs'][] = ['label' => 'Bitacora Aprobaciones Planificaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bitacora-aprobaciones-planificaciones-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
