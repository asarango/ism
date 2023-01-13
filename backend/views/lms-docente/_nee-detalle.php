<?php

use yii\helpers\Html;
use yii\helpers\Url;

if($lmsDocenteNee->neeXClase->grado_nee == 3){
    $color = 'red';
}else if($lmsDocenteNee->neeXClase->grado_nee == 2){
    $color = 'orange';
}else{
    $color = 'green';
}

?>

<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

<div class="row">
    <div class="col">
        <b><u>            
            <?=
                $lmsDocenteNee->neeXClase->nee->student->first_name . ' ' .
                $lmsDocenteNee->neeXClase->nee->student->middle_name . ' ' .
                $lmsDocenteNee->neeXClase->nee->student->last_name
            ?>
            <i class="fas fa-circle" style="color: <?= $color ?>;"></i>
        </u></b>
    </div>
</div>

<div class="row">
    <div class="col">
        <b>Diagnóstico:</b>
        <div class="alert alert-info"><?= $lmsDocenteNee->neeXClase->diagnostico_inicia ?></div>
    </div>
</div>

<div class="row">
    <div class="col">
        <b>Recomendaciones:</b>
        <div class="alert alert-warning"><?= $lmsDocenteNee->neeXClase->recomendacion_clase ?></div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="form-group">
            <label>Adaptación curricular: </label>
            <textarea name="adaptacion" id="adaptacion">
                <?= $lmsDocenteNee->adaptacion_curricular ?>
            </textarea>
        </div>

        <div class="form-group" style="margin: 10px 0px 10px 0px">
            <button class="btn btn-outline-success" name="button" onclick="save_nee_detalle(<?= $lmsDocenteNee->id ?>)">
                Grabar
            </button>
        </div>
    </div>
</div>


<script>
    CKEDITOR.replace('adaptacion', {
        customConfig: '/ckeditor_settings/config.js'
    });

    function save_nee_detalle(id) {
        var adaptacion = CKEDITOR.instances['adaptacion'].getData();
        var url = '<?= Url::to(['nee-update-adaptacion']) ?>';

        var params = {
            lms_docente_nee_id: id,
            adaptacion: adaptacion
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function() {
                //$('#div-nee-detalle').html(response);
                alert('Adaptación curricular, guardada con éxito!!!');
                location.reload();
            }
        });
    }
</script>