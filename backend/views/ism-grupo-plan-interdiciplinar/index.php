<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IsmGrupoPlanInterdiciplinarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grupo Plan Interdiciplinar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-grupo-plan-interdiciplinar-index">

<div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-2">
                    <h4><img src="ISM/main/images/submenu/reunion.png" width="100px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-10">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div><!-- FIN DE CABECERA -->
            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->
        </div>
</div>

    <!-- <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ism Grupo Plan Interdiciplinar', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <!-- <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_bloque',
            'id_op_course',
            'nombre_grupo',
            'id_periodo',
            //'created_at',
            //'created',
            //'updated_at',
            //'updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?> -->
</div>
