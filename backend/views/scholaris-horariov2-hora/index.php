<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisHorariov2HoraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Horariov2 Horas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-horariov2-hora-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Horariov2 Hora', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'numero',
            'sigla',
            'nombre',
            'desde',
            'hasta',

            /** INICIO BOTONES DE ACCION * */
                        [
                            'class' => 'yii\grid\ActionColumn',
//                    'width' => '150px',
                            'template' => '{update}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-edit"></i>', $url, [
                                        'title' => 'Actualizar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                    ]);
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key) {
                                if ($action === 'update') {
                                    return \yii\helpers\Url::to(['update', 'id' => $key]);
                                }
//                        else if ($action === 'update') {
//                            return \yii\helpers\Url::to(['update', 'id' => $key]);
//                        }
                            }
                        ],
                        /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
