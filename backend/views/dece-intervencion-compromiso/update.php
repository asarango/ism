<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencionCompromiso */

$this->title = 'Update Dece Intervencion Compromiso: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dece Intervencion Compromisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dece-intervencion-compromiso-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
