<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>6. Desarrollo del perfil de la comunidad de aprendizaje del IB</b></h6>
        <p>También se espera que, mediante las asignaturas, los alumnos desarrollen los atributos del perfil de la
            comunidad de aprendizaje del IB. Para dar un ejemplo de cómo lo haría,
            elija un tema del esquema del curso y explique de qué manera los contenidos y las
            habilidades relacionadas fomentarían el desarrollo de los atributos del perfil de la
            comunidad de aprendizaje del IB que usted decida.</p>

    </div>
    <div class="card-body">
        <!-- <div class="row">
            <div class="col-lg-3 col-md-3"></div>
            <div class="col-lg-9 col-md-9">
                Contribución al desarrollo de los atributos del perfil de la comunidad de aprendizaje del IB
            </div>
        </div> -->

        <div class="row">
            <b>Tema</b>
            <div>
                <form-group>
                    <?php $dataPerfil = search_data_text($plan, 'datos', 'tema_perfil'); ?>
                    <textarea name="tema-perfil" id="tema-perfil"><?= $dataPerfil['contenido']; ?></textarea>
                </form-group>

            </div>


            <div>
                <b>
                    Contribución al desarrollo de los atributos del perfil de la comunidad de aprendizaje del IB
                </b>
                <form-group>
                    <?php $dataContPerfil = search_data_text($plan, 'datos', 'contibu_perfil'); ?>
                    <textarea name="cont-perfil" id="cont-perfil"><?= $dataContPerfil['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>

<script>
    ClassicEditor
        .create(document.querySelector('#tema-perfil'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataPerfil['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#cont-perfil'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataContPerfil['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>