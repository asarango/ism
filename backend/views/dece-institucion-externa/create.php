<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceInstitucionExterna */

$this->title = 'Create Dece Institucion Externa';
$this->params['breadcrumbs'][] = ['label' => 'Dece Institucion Externas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-institucion-externa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
