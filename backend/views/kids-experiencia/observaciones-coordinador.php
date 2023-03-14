<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
<h2>Observacion coordinador</h2>
<div class="row">
    <div class="col-md-12 col-sm-12">
    


    <?= Html::beginForm(['observacion'], 'post', ['enctype' => 'multipart/form-data']) ?>
    
    <?= Html::input('hidden', 'micro_id', $micro['id']) ?>

    <?=  
        $inputId = $observacionCoordinador ? '<input type="hidden" name="id" value="'.$observacionCoordinador['id'].'" >' : ''; 
    ?>

    <?=  
        $inputBandera = $observacionCoordinador ? '<input type="hidden" name="bandera" value="actualizar" >' : '<input type="hidden" name="bandera" value="crear" >'; 
    ?>

    <textarea name="contenido" class="form-control" >
    <?=  
        $inputObservacion = $observacionCoordinador ? $observacionCoordinador['observacion'] : ''; 
    ?>
    </textarea>
    <script>
            CKEDITOR.replace( 'contenido',{
                customConfig: '/ckeditor_settings/config.js'                                
                } );
    </script>
        <button type="submit" class="btn btn-primary">Guardar</button>
    
    <?= Html::endForm() ?>
    
    </div>
</div>


