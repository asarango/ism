<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeceSeguimientoAcuerdosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dece Seguimiento Acuerdos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-seguimiento-acuerdos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dece Seguimiento Acuerdos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_reg_seguimiento',
            'secuencial',
            'acuerdo:ntext',
            'responsable',
            //'fecha_max_cumplimiento',
            //'cumplio:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
