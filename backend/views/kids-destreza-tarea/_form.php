<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// substr($model->fecha_presentacion,10);
$hoy = date('Y-m-d H:i:s');
$userUpdate = Yii::$app->user->identity->usuario;
$model->fecha_presentacion = substr($model->fecha_presentacion,0,10);

// echo '<pre>';
// echo $path;
// print_r($archivos);
// die();
/* @var $this yii\web\View */
/* @var $model backend\models\KidsDestrezaTarea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kids-destreza-tarea-form" style="margin-bottom:10px;margin-top:10px;">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <!-- TEXTO -->
        <div class="col-md-12 col-sm-12">
            <?= $form->field($model, 'plan_destreza_id')->hiddenInput()->label(false) ?>

            <div class="row">

                <div class="col-md-8 col-sm-8">
                    <div class="row">
                        
                        <div class="col-md-6 col-sm-6">
                            <?= $form->field($model, 'titulo')->textInput(['maxlength' => true])->label('TITULO') ?>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <?= $form->field($model, 'fecha_presentacion')->textInput(['type' => 'date'])->label('FECHA PRESENTACION') ?>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <?php
                                if($model->publicado_al_estudiante == 1){
                                    echo $form->field($model, 'publicado_al_estudiante')->dropDownList([1 => 'SI', 0 => 'NO'])->label('PUBLICAR AL ESTUDIANTE');
                                } else{
                                    echo $form->field($model, 'publicado_al_estudiante')->dropDownList([0 => 'NO', 1 => 'SI'])->label('PUBLICAR AL ESTUDIANTE');
                                }
                            ?>
                            
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <label for="formFileMultiple" class="form-label">AGREGAR ARCHIVOS</label>
                            <input class="form-control" type="file" id="formFileMultiple" name="archivo[]" multiple>   
                        </div>

                    </div>
                </div>

                <div class="col-md-4 col-sm-4" style="margin-top:20px">
                    <div class="card">
                        <div class="row" style="text-align:center">
                        <strong class="my-text-medium">ARCHIVOS SUBIDOS</strong>
                        <?php 
                            foreach($archivos as $archivo){
                                ?>
                                <div class="col-md-4 col-sm-4" style="margin:10px">
                                <a href="<?=$path.$archivo['archivo']?>" target="_blank">
                                    <div style="text-align:center" >
                                        <img src="imagenes/iconos/archivo32px.png" alt="">
                                    </div>
                                    <small style="text-align:justify">
                                        <?=substr($archivo['archivo'],0,6).'...' ?>
                                        &nbsp;
                                        <?=
                                        Html::a(
                                            '<i class="fas fa-trash-alt" style="color:red"></i>',
                                            [
                                                'eliminar-archivo',
                                                'path' => $path.$archivo['archivo'],
                                                'archivo_id' => $archivo['id']
                                            ]
                                        );
                                        ?>
                                    </small>
                                </a>
                                    

                                </div>
                                <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row" style="margin-top:15px">
                <div class="col-md-6 col-sm-6">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">CONTENIDO</label>
                        <textarea name="detalle_tarea" require="" class="form-control" ><?=$model->detalle_tarea?></textarea>
                        <script>
                            CKEDITOR.replace( 'detalle_tarea',{
                                customConfig: '/ckeditor_settings/config.js'                                
                                } );
                        </script>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">RECURSOS</label>
                        <textarea name="materiales" require="" class="form-control" ><?=$model->materiales?></textarea>
                        <script>
                            CKEDITOR.replace( 'materiales',{
                                customConfig: '/ckeditor_settings/config.js'                                
                                } );
                        </script>
                    </div>
                </div>

            </div>

            <?= $form->field($model, 'updated_at')->hiddenInput(['value' => $hoy])->label(false) ?>

            <?= $form->field($model, 'updated')->hiddenInput(['value' => $userUpdate])->label(false) ?>

        </div>
    </div>

    

    <div class="form-group" style="margin-top:10px;">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



