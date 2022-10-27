<?php

use backend\models\DeceIntervencionCompromiso;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencionCompromiso */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Dece Intervencion Compromisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

 //llamamos al compromiso, acorde lo seleccionado
$tipoConsulta = $tipo;


?>

<div class="dece-intervencion-compromiso-create">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
        'tipoConsulta'=>$tipoConsulta,
        'id_intervencion'=>$id_intervencion,
    ]) ?>

    <!-- SE MOSTRARA UNA TABLA ACORDE EL TIPO DE INFORMACION A LLENAR -->

     <hr>
     <div id="tabla_compromisos">

     </div>
   
</div>

