<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmAreaMateria */

$this->title = 'Create Ism Area Materia';
$this->params['breadcrumbs'][] = ['label' => 'Ism Area Materias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ism-area-materia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
