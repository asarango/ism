<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsPlanSemanalHoraClase */

$this->title = 'Create Kids Plan Semanal Hora Clase';
$this->params['breadcrumbs'][] = ['label' => 'Kids Plan Semanal Hora Clases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kids-plan-semanal-hora-clase-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
