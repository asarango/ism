<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmMallaArea */

$this->title = 'Create Ism Malla Area';
$this->params['breadcrumbs'][] = ['label' => 'Ism Malla Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-malla-area-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
