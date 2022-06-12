<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IsmAreaMateriaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ism Area Materias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-area-materia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ism Area Materia', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'malla_area_id',
            'materia_id',
            'promedia:boolean',
            'porcentaje',
            //'imprime_libreta:boolean',
            //'es_cuantitativa:boolean',
            //'tipo',
            //'asignatura_curriculo_id',
            //'curso_curriculo_id',
            //'orden',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
