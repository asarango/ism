<?php

use backend\models\OpFaculty;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VisitaAulicaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Visita Aulicas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visita-aulica-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Visita Aulica', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'clase_id',
            [
                'attribute' => 'clase_id',
                'label' => 'Docente',
                'value' => function ($model) {
                    return $model->clase->profesor->last_name;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'clase_id',
                    \yii\helpers\ArrayHelper::map(OpFaculty::find()->all(), 'id', 'last_name'),
                    ['class' => 'form-control', 'prompt' => 'Seleccione un docente']
                ),
            ],
            'estudiantes_asistidos',
            'aplica_grupal:boolean',
            'psicologo_usuario',
            //'fecha',
            //'hora_inicio',
            //'hora_finalizacion',
            //'observaciones_al_docente:ntext',
            //'fecha_firma_dece',
            //'fecha_firma_docente',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
