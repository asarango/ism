<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ViewActividadCrear */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'View Actividad Crears', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
// echo "<pre>";
// print_r($model);
// die();

?>
<div class="view-actividad-crear-view">

<div>
    <div class="card shadow">

    </div>
</div>

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
            'title',
            'inicio',
            'fin',
            'insumo.nombre_nacional',
            // 'tema',
            
            // 'login',
        ],
    ]) ?>

</div>
