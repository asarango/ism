<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$condicionClass = new backend\models\helpers\Condiciones;

$this->title = $planUnidad->unit_title . ' (' . $planUnidad->curriculoBloque->last_name . ')' . ' - TEMARIO';
$this->params['breadcrumbs'][] = $this->title;

$estado = $planUnidad->planCabecera->estado;
$isOpen = $planUnidad->is_open;
$condicion = $condicionClass->aprobacion_planificacion($estado, $isOpen, $planUnidad->settings_status);
$planUnidadId = $planUnidad->id;

// echo "<pre>";
// print_r($subtitulos);
// die();
?>


<!-- llama los datos de la variable subtitulos -->
<?php

foreach ($subtitulos as $sub) {
    echo '<ul>';
    echo '<li style="display: flex; align-items: center">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-folders" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffbf00" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 4h3l2 2h5a2 2 0 0 1 2 2v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" /> <path d="M17 17v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h2" /></svg>';
    echo '<a onclick="mostrarTema(' . $sub->id . ')" style="cursor:pointer; font-weight: bold; font-size: 13px; color: black; text-align: center; margin-left: 10px;" >'
        . $sub->subtitulo . '</a>';
    echo '</li>';

    // echo '<li> ' . $sub->experiencias . ' </li>';
    $subtitulo2 = busca_subtitulos2($sub->id);

    foreach ($subtitulo2 as $sub2) {
        echo '<li> ' . $sub2->contenido . '</li>';
    }

    echo '</ul>';
}
?>


<?php
function busca_subtitulos2($subtituloId)
{
    $model = backend\models\PlanificacionBloquesUnidadSubtitulo2::find()->where([
        'subtitulo_id' => $subtituloId,


    ])
        ->orderBy('orden')
        ->all();

    return $model;
}
?>