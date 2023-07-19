<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionSemanal */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Planificacion Semanals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="planificacion-semanal-view">

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
            'semana_id',
            'clase_id',
            'fecha',
            'hora_id',
            'orden_hora_semana',
            'tema',
            'actividades:ntext',
            'diferenciacion_nee:ntext',
            'recursos:ntext',
            'created',
            'created_at',
            'updated',
            'updated_at',
        ],
    ]) ?>

</div>
