<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmAreaMateria */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ism Area Materias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ism-area-materia-view">

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
            'malla_area_id',
            'materia_id',
            'promedia:boolean',
            'porcentaje',
            'imprime_libreta:boolean',
            'es_cuantitativa:boolean',
            'tipo',
            'asignatura_curriculo_id',
            'curso_curriculo_id',
            'orden',
        ],
    ]) ?>

</div>
