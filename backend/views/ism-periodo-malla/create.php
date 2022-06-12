<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmPeriodoMalla */

$this->title = 'Create Ism Periodo Malla';
$this->params['breadcrumbs'][] = ['label' => 'Ism Periodo Mallas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-periodo-malla-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
