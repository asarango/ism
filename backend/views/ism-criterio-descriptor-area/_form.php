<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmCriterioDescriptorArea */
/* @var $form yii\widgets\ActiveForm */

// print_r($criteriosLiteral);
// die();

if (!$model->isNewRecord) {
    $descriptorName     = searchData($descriptores, $model->id_descriptor, 'nombre');
    $descriptorLiteral  = searchData($descriptoresLiteral, $model->id_literal_descriptor, 'descripcion');
    $criLiteral         = searchData($criteriosLiteral, $model->id_literal_criterio, 'nombre_espanol');
    $cri                = searchData($criterios, $model->id_criterio, 'nombre');
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<div class="ism-criterio-descriptor-area-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- inicia Area -->
    <?php
    if ($model->isNewRecord) {
    ?>
        <div class="form-group" style="margin-top: 25px;">
            <label class="label" for="id_area">Area:</label>
            <select name="id_area" onchange="showDescriptor()" id="id_area" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                <option selected="selected" value="">Escoja área...</option>
                <?php
                foreach ($areas as $descriptor) {
                    echo '<option value="' . $descriptor->id . '">' . $descriptor->nombre . '</option>';
                }
                ?>
            </select>
        </div>

    <?php
    } else {
        echo $form->field($model, 'id_area')->hiddenInput()->label(false);
    }
    ?>
    <!-- termina area -->


    <!-- inicia template id -->
    <?php
    if ($model->isNewRecord) {
    ?>
        <div class="form-group" style="margin-top: 25px;">
            <label class="label" for="id_curso">Curso:</label>
            <select name="id_curso" onchange="showDescriptor()" id="id_curso" 
                    class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                <option selected="selected" value="">Escoja curso...</option>
                <?php
                foreach ($templates as $descriptor) {
                    echo '<option value="' . $descriptor->id . '">' . $descriptor->name . '</option>';
                }
                ?>
            </select>
        </div>
    <?php
    } else {
        echo $form->field($model, 'id_curso')->hiddenInput()->label(false);
    }

    ?>
    <!-- termina template id -->

    <!-- inicial id criterio -->
    <div class="form-group" style="margin-top: 25px;">
        <label class="label" for="id_criterio">Criterio:</label>
        <select name="id_criterio" onchange="showDescriptor()" id="id_criterio" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
            <?php
            if ($model->isNewRecord) {
            ?>
                <option selected="selected" value="">Escoja criterio...</option>
            <?php
            } else {
            ?>
                <option selected="selected" value="<?= $model->id_criterio ?>"><?= $cri ?></option>
            <?php
            }
            ?>

            <?php
            foreach ($criterios as $descriptor) {
                echo '<option value="' . $descriptor->id . '">' . $descriptor->nombre . '</option>';
            }
            ?>
        </select>
    </div>
    <!-- finaliza id criterio -->

    <!-- inicial criterio literal -->
    <div class="form-group" style="margin-top: 25px;">
        <label class="label" for="descriptor">Literal criterio:</label>
        <select name="id_literal_criterio" onchange="showDescriptor()" id="id_literal_criterio" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
            <?php
            if ($model->isNewRecord) {
            ?>
                <option selected="selected" value="">Escoja un literal de criterio...</option>
            <?php
            } else {
            ?>
                <option selected="selected" value="<?= $model->id_literal_criterio ?>"><?= $criLiteral ?></option>
            <?php
            }
            ?>

            <?php
            foreach ($criteriosLiteral as $descriptor) {
                echo '<option value="' . $descriptor->id . '">' . $descriptor->nombre_espanol . '</option>';
            }
            ?>
        </select>
    </div>
    <!-- finaliza criterio literal -->



    <!-- inicial descriptor -->
    <div class="form-group" style="margin-top: 25px;">
        <label class="label" for="descriptor">Item descriptor:</label>
        <select name="id_descriptor" onchange="showDescriptor()" id="id_descriptor" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
            <?php
            if ($model->isNewRecord) {
            ?>
                <option selected="selected" value="">Escoja un curso...</option>
            <?php
            } else {
            ?>
                <option selected="selected" value="<?= $model->id_descriptor ?>"><?= $descriptorName ?></option>
            <?php
            }
            ?>

            <?php
            foreach ($descriptores as $descriptor) {
                echo '<option value="' . $descriptor->id . '">' . $descriptor->nombre . '</option>';
            }
            ?>
        </select>
    </div>
    <!-- finaliza descriptor -->

    <!-- inicial descriptor literal -->
    <div class="form-group" style="margin-top: 25px;">
        <label class="label" for="descriptor">Descriptor literal:</label>
        <select name="id_literal_descriptor" onchange="showDescriptor()" id="id_literal_descriptor" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
            <?php
            if ($model->isNewRecord) {
            ?>
                <option selected="selected" value="">Escoja un literal de descriptor...</option>
            <?php
            } else {
            ?>
                <option selected="selected" value="<?= $model->id_literal_descriptor ?>"><?= $descriptorLiteral ?></option>
            <?php
            }
            ?>

            <?php
            foreach ($descriptoresLiteral as $descriptor) {
                echo '<option value="' . $descriptor->id . '">' . $descriptor->descripcion . '</option>';
            }
            ?>
        </select>
    </div>
    <!-- finaliza literal de descriptor -->

    <div class="form-group">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success', 'style' => 'margin-top: 10px; margin-bottom: 10px']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>
    buscador();

    function buscador() {
        $('.select2').select2({
            closeOnSelect: true
        });
    }
</script>


<?php

function searchData($model, $valor, $campo)
{

    foreach ($model as $m) {
        if ($m->id == $valor) {
            $response =  $m->$campo;
        }
    }

    return $response;
}

?>