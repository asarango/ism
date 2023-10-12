<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>5. Mentalidad internacional</b></h6>
        <p>Todas las asignaturas del IB deben contribuir al desarrollo de una mentalidad internacional en los alumnos.
            Para dar un ejemplo de cómo lo haría, elija un tema del esquema del curso que permita a
            los alumnos analizarlo desde distintas perspectivas culturales.
            Explique brevemente por qué elige ese tema y qué recursos utilizaría para alcanzar este objetivo.</p>

    </div>
    <div class="card-body">
        <!-- <div class="row">
            <div class="col-lg-3 col-md-3"></div>
            <div class="col-lg-9 col-md-9">

            </div>
        </div> -->

        <div class="row">
            <div>
                <b>Tema</b>
                <form-group>
                    <?php $dataMen = search_data_text($plan, 'datos', 'tema_menta'); ?>
                    <textarea name="tema-mentalidad-tdc" id="tema-mentalidad-tdc"><?= $dataMen['contenido']; ?></textarea>
                </form-group>

            </div>

            <div>
                <b>
                    Contribución al desarrollo de una mentalidad internacional
                    (incluidos los recursos que utilizaría)
                </b>
                <form-group>
                    <?php $dataMenCont = search_data_text($plan, 'datos', 'contibu_menta'); ?>
                    <textarea name="mentalidad-cont" id="mentalidad-cont"><?= $dataMenCont['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>

<script>
    ClassicEditor
        .create(document.querySelector('#tema-mentalidad-tdc'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataMen['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#mentalidad-cont'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataMenCont['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>