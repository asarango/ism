<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmSeccionPlanInterdiciplinar */

$this->title = 'Create Ism Seccion Plan Interdiciplinar';
$this->params['breadcrumbs'][] = ['label' => 'Ism Seccion Plan Interdiciplinars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-seccion-plan-interdiciplinar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
