<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoObjetivosGenerales */

$this->title = 'Update Plan Curriculo Objetivos Generales: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Plan Curriculo Objetivos Generales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="plan-curriculo-objetivos-generales-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
