<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisAsistenciaAlumnosNovedadesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Asistencia Alumnos Novedades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-asistencia-alumnos-novedades-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Asistencia Alumnos Novedades', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'asistencia_profesor_id',
            'comportamiento_detalle_id',
            'observacion',
            'grupo_id',
            //'es_justificado:boolean',
            //'codigo_justificacion',
            //'acuerdo_justificacion:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
