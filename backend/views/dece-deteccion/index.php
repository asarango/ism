<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeceDeteccionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dece Deteccions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-deteccion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dece Deteccion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'numero_deteccion',
            'id_estudiante',
            'id_caso',
            'numero_caso',
            //'nombre_estudiante',
            //'anio',
            //'paralelo',
            //'nombre_quien_reporta',
            //'cargo',
            //'cedula',
            //'fecha_reporte',
            //'descripcion_del_hecho',
            //'hora_aproximada',
            //'acciones_realizadas',
            //'lista_evidencias',
            //'path_archivos',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
