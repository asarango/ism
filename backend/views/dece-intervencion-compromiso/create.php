<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencionCompromiso */

$this->title = 'Create Dece Intervencion Compromiso';
$this->params['breadcrumbs'][] = ['label' => 'Dece Intervencion Compromisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-intervencion-compromiso-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
