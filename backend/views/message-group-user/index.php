<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MessageGroupUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Message Group Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-group-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Message Group User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'message_group_id',
            'usuario',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
