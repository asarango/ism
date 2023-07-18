<?php

use yii\helpers\Html;

// @var $this yii\web\View 
// @var $model backend\models\PlanificacionSemanalRecursos

$this->title = 'Update Planificacion Semanal Recursos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Planificacion Semanal Recursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="planificacion-semanal-recursos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
