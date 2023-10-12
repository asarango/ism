<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>4. Enfoques del aprendizaje</b></h6>
        <p>Todas las asignaturas del IB deben contribuir al desarrollo de las habilidades de los
            enfoques del aprendizaje de los alumnos. Para dar un ejemplo de cómo lo haría,
            elija un tema del esquema del curso que permita a los alumnos desarrollar específicamente una o
            varias de las categorías de habilidades (sociales, de pensamiento, comunicación, autogestión e
            investigación).</p>

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
                    <?php $dataTemaEnfoque = search_data_text($plan, 'datos', 'tema_enfoque'); ?>
                    <textarea name="tema-enfoque-tdc" id="tema-enfoque-tdc"><?= $dataTemaEnfoque['contenido']; ?></textarea>
                </form-group>

            </div>


            <div>
                <b>
                    Contribución al desarrollo de las habilidades de los enfoques del aprendizaje de los alumnos
                    (incluida una o varias categorías de habilidades)
                </b>

                <form-group>
                    <?php $dataCont = search_data_text($plan, 'datos', 'contibu_enfoque'); ?>
                    <textarea name="cont-tdc" id="cont-tdc"><?= $dataCont['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>

<script>
    ClassicEditor
        .create(document.querySelector('#tema-enfoque-tdc'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataTemaEnfoque['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#cont-tdc'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataCont['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>

<!-- <script>
    CKEDITOR.replace("tema-enfoque");
    CKEDITOR.replace("contibu-enfoque");
</script> -->