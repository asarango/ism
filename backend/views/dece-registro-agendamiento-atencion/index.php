<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DeceRegistroAgendamientoAtencionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dece Registro Agendamiento Atencions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-registro-agendamiento-atencion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dece Registro Agendamiento Atencion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_reg_seguimiento',
            'fecha_inicio',
            'fecha_fin',
            'estado',
            //'pronunciamiento',
            //'acuerdo_y_compromiso',
            //'evidencia',
            //'path_archivo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
