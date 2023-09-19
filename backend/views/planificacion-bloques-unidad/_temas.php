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
// print_r($planUnidad);
// die();

?>

<div>
    <?php foreach ($subtitulosTema as $subtituloTemaItem): ?>
        <?php
        $form = ActiveForm::begin([
            'action' => ['guardar'],
            'method' => 'post',
        ]);
        ?>

        <?= $form->field($subtituloTemaItem, 'id')->hiddenInput()->label(false) ?>
        <?= $form->field($subtituloTemaItem, 'planUnidadId')->hiddenInput(['value' => $planUnidadId])->label(false) ?>
        <?= $form->field($subtituloTemaItem, 'subtitulo')->textInput(['required' => ''])->label('Título') ?>
        <?= $form->field($subtituloTemaItem, 'orden')->textInput(['required' => ''])->label('Orden') ?>
        <?= $form->field($subtituloTemaItem, 'trazabilidad')->dropDownList($arrayTrazabilidad, ['prompt' => 'Seleccione Uno']) ?>
        <?= $form->field($subtituloTemaItem, 'verificacion')->dropDownList($arrayVerificacion, ['prompt' => 'Seleccione Uno']) ?>

        <hr>

        <div>
            <label for="experiencia">EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS
                DE ENSEÑANZA:</label>
            <textarea name="experiencia_update"
                id="experiencia-editor-update<?= $subtituloTemaItem->id ?>"><?= $subtituloTemaItem->experiencias ?></textarea>
            <script>
                CKEDITOR.replace("experiencia-editor-update<?= $subtituloTemaItem->id ?>", {
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
                id="evaluacion-editor-update<?= $subtituloTemaItem->id ?>"><?= $subtituloTemaItem->evaluacion_formativa ?></textarea>
            <script>
                CKEDITOR.replace("evaluacion-editor-update<?= $subtituloTemaItem->id ?>", {
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
                id="diferenciacion-editor-update<?= $subtituloTemaItem->id ?>"><?= $subtituloTemaItem->diferenciacion ?></textarea>
            <script>
                CKEDITOR.replace("diferenciacion-editor-update<?= $subtituloTemaItem->id ?>", {
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
        <div>
            <?= Html::submitButton('Actualizar', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    <?php endforeach; ?>
</div>