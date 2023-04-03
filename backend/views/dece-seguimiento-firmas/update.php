<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceSeguimientoFirmas */

$this->title = 'Update Dece Seguimiento Firmas: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dece Seguimiento Firmas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dece-seguimiento-firmas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
