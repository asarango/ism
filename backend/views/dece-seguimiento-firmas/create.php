<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceSeguimientoFirmas */

$this->title = 'Create Dece Seguimiento Firmas';
$this->params['breadcrumbs'][] = ['label' => 'Dece Seguimiento Firmas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-seguimiento-firmas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
