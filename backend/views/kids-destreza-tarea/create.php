<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsDestrezaTarea */

$this->title = 'Create Kids Destreza Tarea';
$this->params['breadcrumbs'][] = ['label' => 'Kids Destreza Tareas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kids-destreza-tarea-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
