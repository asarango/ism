<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FirmarDocumentos */

$this->title = 'Update Firmar Documentos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Firmar Documentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="firmar-documentos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
