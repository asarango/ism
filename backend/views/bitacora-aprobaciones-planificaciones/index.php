<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BitacoraAprobacionesPlanificacionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bitacora Aprobaciones Planificaciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bitacora-aprobaciones-planificaciones-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Bitacora Aprobaciones Planificaciones', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tipo_documento',
            'link_pdf',
            'fecha',
            'estado',
            //'enviado_a',
            //'creado_por',
            //'fecha_creado',
            //'observaciones:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
