<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmArea */

$this->title = 'Create Ism Area';
$this->params['breadcrumbs'][] = ['label' => 'Ism Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-area-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
