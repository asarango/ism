<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmRespuestaPlanInterdiciplinar */

$this->title = 'Update Ism Respuesta Plan Interdiciplinar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ism Respuesta Plan Interdiciplinars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ism-respuesta-plan-interdiciplinar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
