<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AdaptacionCurricularXBloque */

$this->title = 'Create Adaptacion Curricular X Bloque';
$this->params['breadcrumbs'][] = ['label' => 'Adaptacion Curricular X Bloques', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adaptacion-curricular-xbloque-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
