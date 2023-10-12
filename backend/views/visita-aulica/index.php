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


    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>


    <div class=" card table-responsive" style="padding: 1.5rem;">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'clase_id',
                'curso',
                'paralelo',
                'docente',
                'materia',
                'total',
                [
                    'label' => 'Crear',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a(
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-player-play-filled" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00abfb" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M6 4v16a1 1 0 0 0 1.524 .852l13 -8a1 1 0 0 0 0 -1.704l-13 -8a1 1 0 0 0 -1.524 .852z" stroke-width="0" fill="currentColor" />
                      </svg>',
                            [
                                'view',
                                'clase_id' => $model->clase_id,
                                // 'id' => $model->id
                            ],
                            [
                                'title' => 'Create Activity',
                            ]
                        );
                    },
                ],

                // ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>