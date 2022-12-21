<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmContenidoPlanInterdiciplinar */

$this->title = 'Create Ism Contenido Plan Interdiciplinar';
$this->params['breadcrumbs'][] = ['label' => 'Ism Contenido Plan Interdiciplinars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-contenido-plan-interdiciplinar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
