<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TocPlanUnidadDetalle */

$this->title = 'Create Toc Plan Unidad Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Toc Plan Unidad Detalles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toc-plan-unidad-detalle-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
