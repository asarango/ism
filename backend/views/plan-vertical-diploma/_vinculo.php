<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>3. Vínculos con Teoría del Conocimiento</b></h6>
        <p>Los profesores deben explorar los vínculos que hay entre los temas de sus respectivas asignaturas y TdC. Para
            dar un ejemplo de cómo lo haría, elija un tema del esquema del curso que permita a los alumnos establecer
            vínculos con TdC. Explique brevemente por qué elige ese tema y describa cómo planificaría la clase.</p>

    </div>
    <div class="card-body">
        <div class="row">
            <div>
                <form-group>
                    <label for="tema-tdc" class="form-label">Tema</label>
                    <?php $dataTema = search_data_text($plan, 'datos', 'tema_tdc'); ?>
                    <textarea name="tema-tdc" id="tema-tdc"><?= $dataTema['contenido']; ?></textarea>
                </form-group>

            </div>

            <div>
                <form-group>
                    <label for="vinculo-tdc" class="form-label">Vínculo con TdC (incluida la descripción de la
                        planificación de clase)</label>
                    <?php $dataVinculo = search_data_text($plan, 'datos', 'vinculo_tdc'); ?>
                    <textarea name="vinculo-tdc" id="vinculo-tdc"><?= $dataVinculo['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>

<script>
    ClassicEditor
        .create(document.querySelector('#tema-tdc'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataTema['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#vinculo-tdc'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataVinculo['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>