<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FirmarDocumentosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Firmar Documentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firmar-documentos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Firmar Documentos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tabla_source',
            'documento_id',
            'nombre',
            'cargo',
            //'cedula',
            //'fecha_firma',
            //'tipo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
