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
                    <?php $data = search_data_text($plan, 'datos', 'tema_tdc'); ?>

                    <textarea class="form-control text-cerrado " id="tema-tdc"
                        onchange="update_text(<?= $data['id'] ?>, 'tema-tdc');"
                        name="tema-tdc"><?= $data['contenido']; ?></textarea>
                </form-group>

            </div>

            <div>
                <form-group>
                    <label for="vinculo-tdc" class="form-label">Vínculo con TdC (incluida la descripción de la
                        planificación de clase)</label>
                    <?php $data = search_data_text($plan, 'datos', 'vinculo_tdc'); ?>

                    <textarea class="form-control text-cerrado " id="vinculo-tdc"
                        onchange="update_text(<?= $data['id'] ?>, 'vinculo-tdc');"
                        name="tema-tdc"><?= $data['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>

<script>
    CKEDITOR.replace("tema-tdc");
    CKEDITOR.replace("vinculo-tdc");
</script>