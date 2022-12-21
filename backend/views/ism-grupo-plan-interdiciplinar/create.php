<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmGrupoPlanInterdiciplinar */

$this->title = 'Create Ism Grupo Plan Interdiciplinar';
$this->params['breadcrumbs'][] = ['label' => 'Ism Grupo Plan Interdiciplinars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-grupo-plan-interdiciplinar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
