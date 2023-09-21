<?php
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PlanificacionOpciones;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$condicionClass = new backend\models\helpers\Condiciones;

$this->title = $planUnidad->unit_title . ' (' . $planUnidad->curriculoBloque->last_name . ')' . ' - TEMARIO';
$this->params['breadcrumbs'][] = $this->title;

$estado = $planUnidad->planCabecera->estado;
$isOpen = $planUnidad->is_open;
$condicion = $condicionClass->aprobacion_planificacion($estado, $isOpen, $planUnidad->settings_status);
$planUnidadId = $planUnidad->id;

$modelTrazabilidad = PlanificacionOpciones::find()
    ->where(['tipo' => 'TRAZABILIDAD_PAI'])
    ->andWhere(['seccion' => 'PAI'])
    ->all();
$arrayTrazabilidad = ArrayHelper::map($modelTrazabilidad, 'opcion', 'opcion');

$arrayVerificacion = array("SI" => "SI", "NO" => "NO", "REPLANIFICADO" => "REPLANIFICADO");
$totalSubtitle = count($subtitulos);

// echo "<pre>";
// print_r ($subtitulos);
// die();

?>

<style>
    .btn-sub {
        margin-top: 10px;
        padding: 10px;
        font-size: 15px;
        font-weight: bold;
        color: white;
        background-color: #ab0a3d;
    }

    .btn-sub:hover {
        color: #D4AF37;
        transform: scale(1.05);
    }

    .tema {
        font-weight: bold;
        animation: vibracion 1s;
    }

    .tema:hover {
        transform: scale(1.05);
    }

    @keyframes vibracion {
        0% {
            transform: translateX(0);
        }

        10% {
            transform: translateX(-5px) rotate(-1deg);
        }

        20% {
            transform: translateX(5px) rotate(1deg);
        }

        30% {
            transform: translateX(-5px) rotate(-1deg);
        }

        40% {
            transform: translateX(5px) rotate(1deg);
        }
    }
</style>



<div style="display: flex;align-items: center;">
    <a href="#" onclick="insertar_titulo(<?= $planUnidadId . ', ' . $totalSubtitle ?>)" title="Agregar nuevo título">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-folder-plus" width="32" height="32"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="#7bc62d" fill="none" stroke-linecap="round"
            stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 19h-7a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2h4l3 3h7a2 2 0 0 1 2 2v3.5" />
            <path d="M16 19h6" />
            <path d="M19 16v6" />
        </svg></a>
</div>

<?php

foreach ($subtitulos as $sub) {

    echo '<ul class="card" style="margin: 0px; padding: 0px;margin-top: 10px;background-color: #eee">';
    echo '<li style="background-color: #ab0a3d;border-radius: 5px;padding: 5px;" class="tema" style="display: flex; justify-content: center; align-items: center;">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-folders" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffbf00" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 4h3l2 2h5a2 2 0 0 1 2 2v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" /> <path d="M17 17v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h2"/></svg>';
    echo '<a style="cursor:pointer; font-size: 13px; color: white; text-align: center; margin-left: 10px;" onclick="mostrarTema(' . $sub->id . ', \'' . addslashes($sub->subtitulo) . '\'); busca_subtitulos2(' . $sub->id . ');" style="cursor:pointer; font-weight: bold; font-size: 13px; color: black; text-align: center; margin-left: 10px;">' . $sub->subtitulo . '</a>';
    echo '</li>';
    // echo 'Total Subtitulos: ' . $totalSubtitle;

    $subtitulo2 = busca_subtitulos2($sub->id);
    $count = count($subtitulo2);

    echo '<ul class="" style="text-align: right; margin-left: 5rem; list-style-type: none;margin-top: 10px;">';
    foreach ($subtitulo2 as $index => $subt) {
        echo '<li style="font-weight: bold; font-size: 12px; display: flex; align-items: center; margin-bottom: 10px;">' .
            '<span style="display: inline-block; width: 1em; text-align: center;">&bull;</span>' .
            '<span style="margin-top: -10px;">' . $subt->contenido . '<a href="' . Url::to(['delete-subtitle2', 'id' => $subt->id]) . '" class="btn btn-sm ml-2"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff2825" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></a>' . '</span>' .
            '</li>';
    }
    echo '</ul>';

    $modalId = 'exampleModal' . $sub->id;

    echo '<button title="Agregar subtema" type="button" class="btn" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rounded-plus-filled" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="#7bc62d" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M12 2l.324 .001l.318 .004l.616 .017l.299 .013l.579 .034l.553 .046c4.785 .464 6.732 2.411 7.196 7.196l.046 .553l.034 .579c.005 .098 .01 .198 .013 .299l.017 .616l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.464 4.785 -2.411 6.732 -7.196 7.196l-.553 .046l-.579 .034c-.098 .005 -.198 .01 -.299 .013l-.616 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.785 -.464 -6.732 -2.411 -7.196 -7.196l-.046 -.553l-.034 -.579a28.058 28.058 0 0 1 -.013 -.299l-.017 -.616c-.003 -.21 -.005 -.424 -.005 -.642l.001 -.324l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.464 -4.785 2.411 -6.732 7.196 -7.196l.553 -.046l.579 -.034c.098 -.005 .198 -.01 .299 -.013l.616 -.017c.21 -.003 .424 -.005 .642 -.005zm0 6a1 1 0 0 0 -1 1v2h-2l-.117 .007a1 1 0 0 0 .117 1.993h2v2l.007 .117a1 1 0 0 0 1.993 -.117v-2h2l.117 -.007a1 1 0 0 0 -.117 -1.993h-2v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z" fill="currentColor" stroke-width="0" />
    </svg>';
    echo '</button>';

    echo '<div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
    echo '<div class="modal-dialog">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<h1 class="modal-title fs-5" id="exampleModalLabel">Subtítulos</h1>';
    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '</div>';
    echo '<div class="modal-body">';

    foreach ($subtitulo2 as $index => $sub2) {
        // echo "<pre>";
        // print_r ($subtitulo2);
        // die();
        echo '<li style="font-size: 20px; font-weight: bold; text-align: center">' . ($index + 1) . '. ' . $sub2->contenido . '<a href="' . Url::to(['delete-subtitle2', 'id' => $sub2->id]) . '" class="btn btn-sm ml-2"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff2825" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></a>';
        echo '</li>';
    }

    $form = ActiveForm::begin([
        'action' => ['create-subtitle2'],
        'method' => 'post',
    ]);

    $ultimoOrden = $count + 1;

    echo Html::hiddenInput('subtitulo_id', $sub->id);
    echo Html::textInput('contenido', '', ['class' => 'form-control centered-placeholder', 'placeholder' => 'Agregue un subtítulo', 'style' => 'text-align: center;']);
    echo Html::hiddenInput('orden', $ultimoOrden);
    echo Html::hiddenInput('planUnidadId', $sub->plan_unidad_id);
    echo '<hr>';
    echo '<div class="form-group" style="text-align: end">';
    echo Html::submitButton('Agregar Subtítulo', ['class' => 'btn btn-sub']);
    echo '</div>';

    ActiveForm::end();

    echo '</ul>';
}
?>

<?php

function busca_subtitulos2($subtituloId)
{
    $model = PlanificacionBloquesUnidadSubtitulo2::find()
        ->where(['subtitulo_id' => $subtituloId])
        ->orderBy('orden')
        ->all();

    return $model;
}
?>

<script>
    function insertar_titulo($planUnidadId, $totalSubtitle) {

        var url = 'create-title';

        var params = {
            plan_unidad_id: $planUnidadId,
            total_subtitulos: $totalSubtitle
        }

        $.ajax({
            data: params,
            url: url,
            type: 'post',
            beforeSend: function (response) {

            },
            success: function (response) {
                mostrarArbol();
                // $('#div-mostrar-Arbol').html(response);
            }
        });
    }
</script>