<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>


<!-- Boton Reflexion -->
<span type="button" class="badge rounded-pill" style="background-color: #ab0a3d" data-bs-toggle="modal"
    data-bs-target="#exampleModal">
    <!-- <button type="button" class="btn btn-primary" > -->
    Reflexiones
    <?= $contadorReflexion ?> / 3
    <!-- </button> -->
</span>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle de Reflexiones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= Html::beginForm(['update-reflexion'], 'post', ['enctype' => 'multipart/form-data']) ?>

                <input type="hidden" name="id" value="<?= $reflexion->id ?>">
                <input type="hidden" name="plan_semanal_id" value="<?= $reflexion->plan_semanal_id ?>">

                <label for=""><b>Reflexión Antes:</b></label>
                <textarea name="antes_reflexion"><?= $reflexion->antes ?></textarea>
                <br>
                <label for=""><b>Reflexión Durante:</b></label>
                <textarea name="durante_reflexion"><?= $reflexion->durante ?></textarea>
                <br>
                <label for=""><b>Reflexión Después:</b></label>
                <textarea name="despues_reflexion"><?= $reflexion->despues ?></textarea>
                <br>
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>


                <?= Html::endForm() ?>



            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
        </div>
    </div>
</div>


<script>
    ClassicEditor
        .create(document.querySelector('textarea[name="antes_reflexion"]'))
        .catch(error => {
            //console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('textarea[name="durante_reflexion"]'))
        .catch(error => {
            //console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('textarea[name="despues_reflexion"]'))
        .catch(error => {
            //console.error(error);
        });
</script>