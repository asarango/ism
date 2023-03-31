<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeceSeguimientoFirmasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dece Seguimiento Firmas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-seguimiento-firmas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dece Seguimiento Firmas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_reg_seguimiento',
            'nombre',
            'cedula',
            'parentesco',
            //'cargo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
