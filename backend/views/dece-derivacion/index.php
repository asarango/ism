<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeceDerivacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dece Derivacions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-derivacion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dece Derivacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tipo_derivacion',
            'id_estudiante',
            'nombre_quien_deriva:ntext',
            'fecha_derivacion',
            //'motivo_referencia:ntext',
            //'historia_situacion_actual:ntext',
            //'accion_desarrollada:ntext',
            //'tipo_ayuda:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
