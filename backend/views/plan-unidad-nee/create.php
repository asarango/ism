<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanUnidadNee */

$this->title = 'Create Plan Unidad Nee';
$this->params['breadcrumbs'][] = ['label' => 'Plan Unidad Nees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-unidad-nee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
