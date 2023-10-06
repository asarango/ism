<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\ScholarisActividad;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Crear nueva actividad';
//$this->params['breadcrumbs'][] = $this->title;

// echo "<pre>";
// print_r($trimestres);
// die();

?>
<div class="scholaris-actividad-create">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/actividad-fisica.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
                <hr>
            </div>
        </div>
        <!-- COMIENZA GRIDVIEW -->
        <div>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    // Columna 1
                    [
                        'attribute' => 'trimestres',
                        'label' => 'Trimestres',
                    ],

                    // Columna 2
                    [
                        'attribute' => 'Semanas',
                        'label' => 'Semanas',
                    ],

                    // Columna 3
                    [
                        'clase' => 'clase',
                        'label' => 'Clase',
                    ],

                    // Columna 4
                    [
                        'Plan Semanal' => 'Plan Semanal',
                        'label' => 'Plan Semanal',
                    ],
                ],
            ]) ?>

        </div>
        <!-- TERMINA GRIDVIEW -->
    </div>
</div>