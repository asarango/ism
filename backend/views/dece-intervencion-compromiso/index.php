<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeceIntervencionCompromisoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dece Intervencion Compromisos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-intervencion-compromiso-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dece Intervencion Compromiso', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_dece_intervencion',
            'comp_estudiante',
            'comp_representante',
            'comp_docente',
            //'comp_dece',
            //'fecha_max_cumplimiento',
            //'revision_compromiso',
            //'esaprobado:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
