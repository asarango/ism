<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceSeguimientoAcuerdos */

$this->title = 'Create Dece Seguimiento Acuerdos';
$this->params['breadcrumbs'][] = ['label' => 'Dece Seguimiento Acuerdos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="dece-seguimiento-acuerdos-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
        'id_seguimiento' => $id_seguimiento,
    ]) ?>

</div>
