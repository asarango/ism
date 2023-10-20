<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ViewStudiantesCvSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'CV - Estudiantes';
$this->params['breadcrumbs'][] = $this->title;
// echo "<pre>";
// print_r($searchModel);
// die();

?>

<style>
    body {
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .course-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .course-info {
        flex: 1;
        padding-right: 20px;
    }

    .course-title {
        font-size: 1.2rem;
        color: #ab0a3d;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        background-color: #f2f2f2;
        color: #333;
        font-weight: bold;
        border-radius: 5px;
    }

    .action-links {
        display: flex;
        align-items: center;
    }

    .action-link {
        text-decoration: none;
        padding: 8px 12px;
        background-color: #0a1f8f;
        color: #fff;
        border-radius: 5px;
        transition: background-color 0.2s ease;
        margin-right: 5px;
    }

    .action-link:hover {
        background-color: #eee;
    }
</style>



<div class="view-studiantes-cv-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <h1>
                <?= Html::encode($this->title) ?></h1>
            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <!-- <p>
                <= Html::a('Create View Studiantes Cv', ['create'], ['class' => 'btn btn-success']) ?>
            </p> -->

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'estudiante_id',
                    'estudiante:ntext',
                    'inscription_id',
                    // 'seccion',
                    'curso',
                    'paralelo',
                    'inscription_state',
                    /** INICIO BOTONES DE ACCION * */
                    [
                        'class' => 'yii\grid\ActionColumn',
                        //                    'width' => '150px',
                        'template' => '{detalle}',
                        'buttons' => [
                            'detalle' => function ($url, $model) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, [
                                    'title' => 'Ver Detalle', 'data-toggle' => 'tooltip',
                                    'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                ]);
                            }
                        ],
                        'urlCreator' => function ($action, $model, $key) {
                            if ($action === 'detalle') {
                                return \yii\helpers\Url::to(['detalle', 'inscription_id' => $model->inscription_id]);
                            }
                            //                        else if ($action === 'update') {
                            //                            return \yii\helpers\Url::to(['update', 'id' => $key]);
                            //                        }
                        }
                    ],
                    /** FIN BOTONES DE ACCION * */
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>


</div>