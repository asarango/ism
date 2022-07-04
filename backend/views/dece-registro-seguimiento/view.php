<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroSeguimiento */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dece Registro Seguimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dece-registro-seguimiento-view">

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
            'id_clase',
            'id_estudiante',
            'fecha_inicio',
            'fecha_fin',
            'estado',
            'motivo',
            'submotivo',
            'submotivo2',
            'persona_solicitante',
            'atendido_por',
            'atencion_para',
            'responsable_seguimiento',
        ],
    ]) ?>

</div>
