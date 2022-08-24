<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanUnidadNee */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Plan Unidad Nees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="plan-unidad-nee-view">

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
            'nee_x_unidad_id',
            'curriculo_bloque_unidad_id',
            'destrezas:ntext',
            'actividades:ntext',
            'recursos:ntext',
            'indicadores_evaluacion:ntext',
            'tecnicas_instrumentos:ntext',
            'detalle_pai_dip:ntext',
        ],
    ]) ?>

</div>
