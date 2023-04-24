<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFaltas */

$this->title = 'Create Scholaris Faltas';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Faltas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-faltas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
