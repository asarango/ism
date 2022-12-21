<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IsmSeccionPlanInterdiciplinarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ism Seccion Plan Interdiciplinars';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-seccion-plan-interdiciplinar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ism Seccion Plan Interdiciplinar', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'num_seccion',
            'nombre_seccion',
            'activo:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
