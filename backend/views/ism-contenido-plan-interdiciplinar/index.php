<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IsmContenidoPlanInterdiciplinarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ism Contenido Plan Interdiciplinars';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-contenido-plan-interdiciplinar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ism Contenido Plan Interdiciplinar', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_seccion_interdiciplinar',
            'nombre_campo',
            'activo:boolean',
            'heredado:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
