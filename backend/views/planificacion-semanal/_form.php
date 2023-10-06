<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionSemanal */
/* @var $form yii\widgets\ActiveForm */

// echo "<pre>";
// print_r($pud_origen);
// die();

?>

<script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script>


<div class="EditarPlanificacionSemanal-form">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card" style="margin-bottom: 1rem">
            <!-- INICIO FORMULARIO -->
            <div style="padding: .5rem">
                <?= Html::beginForm(['update'], 'post') ?>
                <input type="hidden" name="id" value="<?= $model->id; ?>">
                <div>
                    <label for="edit2">
                        <h6>Tema de clase:</h6>
                    </label>
                    <textarea name="tema" id="edit2"> <?= $model['tema']?> </textarea>
                </div>
                <hr>
                <div>
                    <label for="edit3">
                        <h6>Actividades:</h6>
                    </label>
                    <?php
                        if(strlen($model['actividades']) < 12){
                            echo '<textarea name="actividades" id="edit3">'.$actividades.'</textarea>';
                        }else{
                            echo '<textarea name="actividades" id="edit3">'.$model['actividades'].'</textarea>';
                        }
                    ?>
                    
                </div>
                <hr>
                <div>
                    <label for="edit4">
                        <h6>Diferenciación NEE:</h6>
                    </label>
                    <textarea name="diferenciacion_nee" id="edit4"><?= $model['diferenciacion_nee']?></textarea>
                </div>
                <input type="hidden" name="pud_origen" value="<?= $pud_origen?>">
                <input type="hidden" name="plan_bloque_unidad_id" value="<?= $plan_bloque_unidad_id?>">
                <input type="hidden" name="semana_defecto" value="<?= $semana_defecto?>">
                <hr>
                <div>
                    <label for="edit5">
                        <h6>Recursos:</h6>
                    </label>
                    <textarea name="recursos" id="edit5"><?= $model['recursos']?></textarea>
                </div>
                <div  style="margin-top: 1rem; margin-bottom: 1rem;">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']); ?>
                </div>
                <?= Html::endForm() ?>
            </div>
            <!-- FIN DE FOMULARIO -->
        </div>
    </div>
</div>

<script>
    for (let i = 1; i <= 8; i++) {
        ClassicEditor
            .create(document.querySelector(`#edit${i}`), {
                placeholder: 'Añadir una nota...'
            })
            .catch(error => {
                console.error(error);
            });
    }
</script>