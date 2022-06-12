<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisImagenes */

$this->title = 'Creando Imagen';
$this->params['breadcrumbs'][] = ['label' => 'Imágenes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-imagenes-create" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
