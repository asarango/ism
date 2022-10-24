<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeceIntervencionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dece Intervencions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-intervencion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dece Intervencion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_estudiante',
            'fecha_intervencion',
            'razon',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
