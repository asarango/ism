<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\KidsPlanSemanalHoraClaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kids Plan Semanal Hora Clases';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kids-plan-semanal-hora-clase-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Kids Plan Semanal Hora Clase', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'plan_semanal_id',
            'clase_id',
            'detalle_id',
            'fecha',
            //'created_at',
            //'created',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
