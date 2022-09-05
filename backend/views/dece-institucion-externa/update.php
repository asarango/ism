<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceInstitucionExterna */

$this->title = 'Update Dece Institucion Externa: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dece Institucion Externas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dece-institucion-externa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
