<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FirmarDocumentos */

$this->title = 'Create Firmar Documentos';
$this->params['breadcrumbs'][] = ['label' => 'Firmar Documentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firmar-documentos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
