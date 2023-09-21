<?php

use backend\models\PlanificacionOpciones;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

$condicionClass = new backend\models\helpers\Condiciones;

//echo $condicion;
//die();

$modelTrazabilidad = PlanificacionOpciones::find()
    ->where(['tipo' => 'TRAZABILIDAD_PAI'])
    ->andWhere(['seccion' => 'PAI'])
    ->all();
$arrayTrazabilidad = ArrayHelper::map($modelTrazabilidad, 'opcion', 'opcion');

$arrayVerificacion = array("SI" => "SI", "NO" => "NO", "REPLANIFICADO" => "REPLANIFICADO");

// echo "<pre>";
// print_r($subtitulosTema);
// die();

?>

<style>
    .scroll {
        max-height: 500px;
        overflow: auto;
        /* border: 1px solid #ccc; */
        padding: 5px;
    }

    .campos {
        display: flex;
        justify-content: space-between;
    }

    .campo2 {
        flex: 1;
        margin-right: 10px;
    }
</style>



<div class="">
    <?php
    $form = ActiveForm::begin([
        'action' => ['update-subtitle'],
        'method' => 'post',
    ]);
    ?>
    <div class="scroll card">
        <?php foreach ($subtitulosTema as $subTema): ?>



            <div style="text-align: right;">
                <?= Html::a(
                    'Eliminar Tema',
                    [
                        'delete-subtitle',
                        'id' => $subTema->id
                    ],
                    [
                        'class' => 'btn btn-danger',
                        'data-confirm' => '¿Estás seguro de que deseas eliminar este tema?, todo el contenido del mismo se eliminará.',
                    ]
                ) ?>
            </div>
            <?= $form->field($subTema, 'id')->hiddenInput()->label(false) ?>
            <?= $form->field($subTema, 'planUnidadId')->hiddenInput(['value' => $planUnidadId])->label(false) ?>
            <?= $form->field($subTema, 'subtitulo')->textInput(['required' => ''])->label('Título') ?>
            <?= $form->field($subTema, 'orden')->textInput(['required' => ''])->label('Orden') ?>
            <?= $form->field($subTema, 'verificacion')->dropDownList($arrayVerificacion, ['prompt' => 'Seleccione Uno']) ?>
            <?= $form->field($subTema, 'trazabilidad')->dropDownList($arrayTrazabilidad, ['prompt' => 'Seleccione Uno']) ?>

            <hr>

            <div>
                <label for="experiencia">EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS
                    DE ENSEÑANZA:</label>
                <textarea name="experiencia_update"
                    id="experiencia-editor-update<?= $subTema->id ?>"><?= $subTema->experiencias ?></textarea>
                <script>
                    CKEDITOR.replace("experiencia-editor-update<?= $subTema->id ?>", {
                        customConfig: "/ckeditor_settings/config.js",
                        toolbar: [
                            { name: 'document', items: ['Source'] },
                            { name: 'clipboard', items: ['Undo', 'Redo'] },
                            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
                            { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                            { name: 'styles', items: ['Format'] },
                            { name: 'insert', items: ['Link'] }
                        ]
                    });
                </script>
            </div>

            <hr>

            <div>
                <label for="experiencia">EVALUACIÓN FORMATIVAS:</label>
                <textarea name="evaluacion_update"
                    id="evaluacion-editor-update<?= $subTema->id ?>"><?= $subTema->evaluacion_formativa ?></textarea>
                <script>
                    CKEDITOR.replace("evaluacion-editor-update<?= $subTema->id ?>", {
                        customConfig: "/ckeditor_settings/config.js",
                        toolbar: [
                            { name: 'document', items: ['Source'] },
                            { name: 'clipboard', items: ['Undo', 'Redo'] },
                            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
                            { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                            { name: 'styles', items: ['Format'] },
                            { name: 'insert', items: ['Link'] }
                        ]
                    });
                </script>
            </div>

            <hr>

            <div>
                <label for="experiencia">DIFERENCIACIÓN:</label>
                <textarea name="diferenciacion_update"
                    id="diferenciacion-editor-update<?= $subTema->id ?>"><?= $subTema->diferenciacion ?></textarea>
                <script>
                    CKEDITOR.replace("diferenciacion-editor-update<?= $subTema->id ?>", {
                        customConfig: "/ckeditor_settings/config.js",
                        toolbar: [
                            { name: 'document', items: ['Source'] },
                            { name: 'clipboard', items: ['Undo', 'Redo'] },
                            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
                            { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                            { name: 'styles', items: ['Format'] },
                            { name: 'insert', items: ['Link'] }
                        ]
                    });
                </script>
            </div>

            <br>

        <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 10px;">
        <?= Html::submitButton('Actualizar', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>