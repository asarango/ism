<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmCriterioDescriptorArea */

$this->title = 'Update Ism Criterio Descriptor Area: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ism Criterio Descriptor Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ism-criterio-descriptor-area-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
