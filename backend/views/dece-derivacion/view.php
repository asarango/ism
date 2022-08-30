<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceDerivacion */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dece Derivacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dece-derivacion-view">

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
            'tipo_derivacion',
            'id_estudiante',
            'nombre_quien_deriva:ntext',
            'fecha_derivacion',
            'motivo_referencia:ntext',
            'historia_situacion_actual:ntext',
            'accion_desarrollada:ntext',
            'tipo_ayuda:ntext',
        ],
    ]) ?>

</div>
