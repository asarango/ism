<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AdaptacionCurricularXBloque */

$this->title = 'Update Adaptacion Curricular X Bloque: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Adaptacion Curricular X Bloques', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="adaptacion-curricular-xbloque-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
