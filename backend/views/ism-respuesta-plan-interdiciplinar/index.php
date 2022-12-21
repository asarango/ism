<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IsmRespuestaPlanInterdiciplinarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ism Respuesta Plan Interdiciplinars';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-respuesta-plan-interdiciplinar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ism Respuesta Plan Interdiciplinar', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_grupo_plan_inter',
            'id_contenido_plan_inter',
            'respuesta:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
