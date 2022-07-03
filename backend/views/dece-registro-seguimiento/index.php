<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DeceRegistroSeguimientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dece - Seguimientos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-registro-seguimiento-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Registro', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_clase',
            'id_estudiante',
            'fecha_inicio',
            'fecha_fin',
            'estado',
            'motivo',
            'submotivo',
            //'submotivo2',
            //'persona_solicitante',
            //'atendido_por',
            //'atencion_para',
            //'responsable_seguimiento',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
