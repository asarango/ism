<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VisitaAulica */

$this->title = 'Create Visita Aulica';
$this->params['breadcrumbs'][] = ['label' => 'Visita Aulicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visita-aulica-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
