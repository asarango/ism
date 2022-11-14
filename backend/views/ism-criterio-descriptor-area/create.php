<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmCriterioDescriptorArea */

$this->title = 'Create Ism Criterio Descriptor Area';
$this->params['breadcrumbs'][] = ['label' => 'Ism Criterio Descriptor Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-criterio-descriptor-area-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
