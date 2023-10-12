<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>9. Bibliografía/Webgrafía. Utilizar normas APA (última edición) </b></h6>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <form-group>
                    <?php $dataBiblio = search_data_text($plan, 'datos', 'bibliografia'); ?>
                    <textarea name="biblio" id="biblio"><?= $dataBiblio['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>

<script>
    ClassicEditor
        .create(document.querySelector('#biblio'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                // Esta función se ejecutará cuando cambie el contenido del editor.
                let tema = editor.getData();
                console.log(tema);
                let temaId = '<?= $dataBiblio['id'] ?>';
                update_text(temaId, tema);
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>