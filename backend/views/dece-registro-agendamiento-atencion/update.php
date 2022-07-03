<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroAgendamientoAtencion */

$this->title = 'Actualización Agendamiento Atencion: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dece Registro Agendamiento Atencions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dece-registro-agendamiento-atencion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'idRegSeguimiento'=>$idSeguimiento,
    ]) ?>

</div>
