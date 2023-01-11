<?php

use yii\helpers\Html;

?>

<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
<div class="row"></div>
<div class="row"></div>

<div class="row">
    <div class="col">
        <div class="form-group">
            <label>Adaptación curricular: 
                <?= 
                    $lmsDocenteNee->neeXClase->nee->student->first_name.' '. 
                    $lmsDocenteNee->neeXClase->nee->student->middle_name.' '. 
                    $lmsDocenteNee->neeXClase->nee->student->last_name 
                ?>
            </label>
            <textarea name="adaptacion" id="adaptacion"></textarea>
        </div>

        <div class="form-group" style="margin: 10px 0px 10px 0px">
            <button type="" name="button" onclick="save_nee_detalle(<?= $lmsDocenteNee->id ?>)">Grabar</button>
            <?= Html::submitButton('Grabar', ['class' => 'btn btn-outline-success']) ?> aquiiiii
        </div>
    </div>
</div>


<script>
    CKEDITOR.replace('adaptacion', {
        customConfig: '/ckeditor_settings/config.js'
    });

    function save_nee_detalle(id){
        alert(id);
    }
</script>