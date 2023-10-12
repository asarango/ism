<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>8. Otros recursos</b></h6>
        <p>
            Describa otros recursos que usted y sus alumnos puedan utilizar en el colegio,
            si hay planes para mejorarlos y los plazos.
            Incluya cualquier recurso existente en la comunidad fuera del colegio que pueda contribuir
            a implementar satisfactoriamente su asignatura.
        </p>

    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <form-group>
                    <?php $dataRecursos = search_data_text($plan, 'datos', 'recursos'); ?>
                    <textarea name="recursos-tema" id="recursos-tema"><?= $dataRecursos['contenido']; ?></textarea>
                </form-group>

            </div>
        </div>
    </div>
</div>

<script>
    ClassicEditor
        .create(document.querySelector('#recursos-tema'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataRecursos['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>