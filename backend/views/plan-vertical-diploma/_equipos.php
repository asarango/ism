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
                    <?php $dataEquipos = search_data_text($plan, 'datos', 'equipos'); ?>
                    <textarea name="equipos-tema" id="equipos-tema"><?= $dataEquipos['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>


<script>
    ClassicEditor
        .create(document.querySelector('#equipos-tema'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataEquipos['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>