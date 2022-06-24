<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaAlumnosNovedades */

$this->title = 'Update Scholaris Asistencia Alumnos Novedades: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Asistencia Alumnos Novedades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-asistencia-alumnos-novedades-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
