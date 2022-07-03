<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroAgendamientoAtencion */

$this->title = 'Crear Dece Agendamiento Atencion';
$this->params['breadcrumbs'][] = ['label' => 'Dece Registro Agendamiento Atencions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dece-registro-agendamiento-atencion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'idRegSeguimiento'=>$idSeguimiento,
    ]) ?>

</div>
