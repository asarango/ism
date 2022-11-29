<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MessageGroupUser */

$this->title = 'Create Message Group User';
$this->params['breadcrumbs'][] = ['label' => 'Message Group Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-group-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
