<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LmsActividad */

$this->title = 'Update Lms Actividad: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lms Actividads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lms-actividad-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
