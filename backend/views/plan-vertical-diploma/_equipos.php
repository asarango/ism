<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>7. Instalaciones y equipos</b></h6>
        <p>
            La enseñanza de esta asignatura requiere instalaciones y equipos para que el proceso de
            enseñanza y aprendizaje sea satisfactorio.
            Describa las instalaciones y los equipos que haya en el colegio para permitir y fomentar el
            desarrollo del curso.
            Incluya cualquier plan que haya para mejorarlos y los plazos.
        </p>

    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <form-group>
                    <?php $data = search_data_text($plan, 'datos', 'equipos'); ?>
                    <textarea class="form-control text-cerrado " id="div-equipos"
                        onchange="update_text(<?= $data['id'] ?>, 'div-equipos');"
                        name="div-equipos"><?= $data['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>

<script>
    CKEDITOR.replace("div-equipos");
</script>