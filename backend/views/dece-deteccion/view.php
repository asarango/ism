<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceDeteccion */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dece Deteccions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dece-deteccion-view">

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
            'numero_deteccion',
            'id_estudiante',
            'id_caso',
            'numero_caso',
            'nombre_estudiante',
            'anio',
            'paralelo',
            'nombre_quien_reporta',
            'cargo',
            'cedula',
            'fecha_reporte',
            'descripcion_del_hecho',
            'hora_aproximada',
            'acciones_realizadas',
            'lista_evidencias',
            'path_archivos',
        ],
    ]) ?>

</div>
