<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFaltas */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Faltas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-faltas-view">

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
            'scholaris_perido_id',
            'student_id',
            'fecha',
            'fecha_solicitud_justificacion',
            'motivo_justificacion:ntext',
            'es_justificado:boolean',
            'fecha_justificacion',
            'respuesta_justificacion:ntext',
            'usuario_justifica',
            'created',
            'created_at',
            'updated',
            'updated_at',
        ],
    ]) ?>

</div>
