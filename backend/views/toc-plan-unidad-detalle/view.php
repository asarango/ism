<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\TocPlanUnidadDetalle */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Toc Plan Unidad Detalles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toc-plan-unidad-detalle-view">

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
            'toc_plan_unidad_id',
            'evaluacion_pd:ntext',
            'descripcion_unidad:ntext',
            'preguntas_conocimiento:ntext',
            'conocimientos_esenciales:ntext',
            'actividades_principales:ntext',
            'enfoques_aprendizaje:ntext',
            'funciono_bien:ntext',
            'no_funciono_bien:ntext',
            'observaciones:ntext',
            'created',
            'created_at',
            'updated',
            'updated_at',
        ],
    ]) ?>

</div>
