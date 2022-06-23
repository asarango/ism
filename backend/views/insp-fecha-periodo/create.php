<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\InspFechaPeriodo */

$this->title = 'Create Insp Fecha Periodo';
$this->params['breadcrumbs'][] = ['label' => 'Insp Fecha Periodos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insp-fecha-periodo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
