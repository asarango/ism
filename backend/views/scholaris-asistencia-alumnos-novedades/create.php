<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaAlumnosNovedades */

$this->title = 'Create Scholaris Asistencia Alumnos Novedades';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Asistencia Alumnos Novedades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-asistencia-alumnos-novedades-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
