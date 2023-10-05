<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\controllers\NeeController;
use backend\models\NeexClase;

//print_r($model);
//echo '<pre>';
//print_r($materiasNee);


?>

<style>
    .toast {
        background-color: #333;
        color: white;
        padding: 10px;
        position: fixed;
        bottom: 20px;
        right: 20px;
        border-radius: 5px;
        display: none;
        /* Inicialmente, el toast está oculto */
    }

    .section-title {
        text-align: start;
        margin-top: 10px;
        color: #0a1f8f;
    }

    form {
        margin-top: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #f8f8f8;
    }

    label {
        display: block;
        font-weight: bold;
        margin-top: 10px;
    }

    input[type="number"],
    input[type="date"],
    textarea {
        width: 100%;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    hr {
        border: none;
        border-top: 1px solid #ccc;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .alert {
        margin-top: 20px;
        padding: 10px;
        background-color: #dff0d8;
        border: 1px solid #b2dba1;
        color: #3c763d;
        border-radius: 4px;
        display: none;
    }
</style>

<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>

<div class="row">
    <h5 class="section-title">2.- DETALLE DE NEE</h5>
</div>

<form>

    <?php
    if ($model->es_permanente == true) {
        $checked = 'checked';
    } else {
        $checked = '';
    }

    ?>



    <div class="row">
        <div class="col-lg-4" style="display: flex;justify-content: center;margin-top: 25px">
            <label for="es_permanente">¿Es permanente?
                <input type="checkbox" name="es_permanente" onchange="update_permanente()" id="es_permanente" <?= $checked; ?>>
            </label>
        </div>
        <div class="col-lg-4">
            <label for="grado">Grado NEE</label>
            <input type="number" name="grado" id="grado" value="<?= $model->grado ?>" min="1" max="3" onchange="update(this, 'grado')" placeholder="Grado">

        </div>
        <div class="col-lg-4">
            <label for="fecha_diagnostico">Fecha de diagnóstico</label>
            <input type="date" name="fecha_diagnostico" id="fecha_diagnostico" value="<?= $model->fecha_diagnostico ?>" onchange="update(this, 'fecha_diagnostico')" placeholder="Fecha de diagnóstico">

        </div>
    </div>

    <label for="diagnostico">Diagnóstico</label>
    <textarea name="diagnostico" id="diagnostico" cols="30" rows="10" onchange="update(this, 'diagnostico')"><?= $model->diagnostico ?></textarea>
    <br>

    <label for="recomendaciones">Recomendaciones</label>
    <textarea name="recomendaciones" id="recomendaciones" cols="30" rows="10" onchange="update(this, 'recomendaciones')"><?= $model->recomendaciones ?></textarea>

    <hr>

    <label for="fecha_salida_nee">Fecha de salida de NEE</label>
    <input type="date" name="fecha_salida_nee" id="fecha_salida_nee" value="<?= $model->fecha_salida_nee ?>" onchange="update(this, 'fecha_salida_nee')">
    <br>

    <label for="observacion_salida_nee">Observaciones de salida de NEE</label>
    <textarea name="observacion_salida_nee" id="observacion_salida_nee" cols="30" rows="10" onchange="update(this, 'observacion_salida_nee')"><?= $model->observacion_salida_nee ?></textarea>

</form>

<div class="alert alert-success" id="div-alert" style="display: none;">
    Campo guardado correctamente!!!
</div>


<script>
    function update(obj, campo) {
        let neeId = '<?= $model->id ?>';
        let url = '<?= Url::to(['update-nee']) ?>';


        mostrarToast();

        params = {
            'campo': campo,
            'valor': obj.value,
            'nee_id': neeId
        };

        $.ajax({
            url: url,
            data: params,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                // muestra_preguntas();

            }
        })

    }

    function mostrarToast() {
        $('#div-alert').show();

        setTimeout(function() {
            $("#div-alert").fadeOut();
        }, 2000);
    }

    // FUNCION PARA ACTUALIZAR CHECK DE es_permanente

    function update_permanente() {
        let neeId = '<?= $model->id ?>';
        let url = '<?= Url::to(['update-permanente']) ?>';

        params = {
            'nee_id': neeId
        };

        $.ajax({
            url: url,
            data: params,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                // muestra_preguntas();

            }
        })
    }
</script>