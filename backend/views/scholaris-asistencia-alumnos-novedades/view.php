<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaAlumnosNovedades */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Asistencia Alumnos Novedades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-asistencia-alumnos-novedades-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'asistencia_profesor_id',
            'comportamiento_detalle_id',
            'observacion',
            'grupo_id',
            'es_justificado:boolean',
            'codigo_justificacion',
            'acuerdo_justificacion:ntext',
        ],
    ]) ?>

</div>
