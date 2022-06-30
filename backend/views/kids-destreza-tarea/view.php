<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsDestrezaTarea */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kids Destreza Tareas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="kids-destreza-tarea-view">

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
            'plan_destreza_id',
            'fecha_presentacion',
            'detalle_tarea:ntext',
            'materiales:ntext',
            'publicado_al_estudiante:boolean',
            'created_at',
            'created',
            'updated_at',
            'upated',
        ],
    ]) ?>

</div>
