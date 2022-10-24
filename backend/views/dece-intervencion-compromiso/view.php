<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencionCompromiso */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dece Intervencion Compromisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dece-intervencion-compromiso-view">

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
            'id_dece_intervencion',
            'comp_estudiante',
            'comp_representante',
            'comp_docente',
            'comp_dece',
            'fecha_max_cumplimiento',
            'revision_compromiso',
            'esaprobado:boolean',
        ],
    ]) ?>

</div>
