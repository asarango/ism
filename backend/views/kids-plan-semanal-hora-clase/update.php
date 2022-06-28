<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsPlanSemanalHoraClase */

$this->title = 'Update Kids Plan Semanal Hora Clase: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kids Plan Semanal Hora Clases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kids-plan-semanal-hora-clase-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
