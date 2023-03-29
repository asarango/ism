<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceSeguimientoAcuerdos */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dece Seguimiento Acuerdos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dece-seguimiento-acuerdos-view">

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
            'id_reg_seguimiento',
            'secuencial',
            'acuerdo:ntext',
            'responsable',
            'fecha_max_cumplimiento',
            'cumplio:boolean',
        ],
    ]) ?>

</div>
