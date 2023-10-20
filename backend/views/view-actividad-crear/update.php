<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ViewActividadCrear */

$this->title = 'Update View Actividad Crear: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'View Actividad Crears', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="view-actividad-crear-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
