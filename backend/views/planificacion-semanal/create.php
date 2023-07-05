<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionSemanal */

$this->title = 'Modificar planificación Semanal';
$this->params['breadcrumbs'][] = ['label' => 'Planificacion Semanals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="planificacion-semanal-create">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8 col-sm-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/plan.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-9 col-md-9">
                    <h2>
                        <?= Html::encode($this->title) ?>
                    </h2>
                </div>
                <div class="col-lg- col-md-2">
                    
                </div>
                <hr>
            </div>
            

        </div>
    </div>
</div>