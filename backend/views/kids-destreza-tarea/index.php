<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KidsDestrezaTareaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kids Destreza Tareas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kids-destreza-tarea-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Kids Destreza Tarea', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'plan_destreza_id',
            'fecha_presentacion',
            'titulo',
            'detalle_tarea:ntext',
            //'materiales:ntext',
            //'publicado_al_estudiante:boolean',
            //'created_at',
            //'created',
            //'updated_at',
            //'updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
