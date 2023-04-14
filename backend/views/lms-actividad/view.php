<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\LmsActividad */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lms Actividads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="lms-actividad-view">

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
            'lms_id',
            'tipo_actividad_id',
            'titulo',
            'descripcion:ntext',
            'tarea:ntext',
            'material_apoyo:ntext',
            'es_calificado:boolean',
            'es_publicado:boolean',
            'es_aprobado:boolean',
            'retroalimentacion:ntext',
            'created',
            'created_at',
            'updated',
            'updated_at',
        ],
    ]) ?>

</div>
