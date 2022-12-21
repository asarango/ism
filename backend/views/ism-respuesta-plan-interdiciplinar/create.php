<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmRespuestaPlanInterdiciplinar */

$this->title = 'Create Ism Respuesta Plan Interdiciplinar';
$this->params['breadcrumbs'][] = ['label' => 'Ism Respuesta Plan Interdiciplinars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-respuesta-plan-interdiciplinar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
