<?php

use backend\models\ScholarisTipoActividad;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\LmsActividad */
/* @var $form yii\widgets\ActiveForm */

$tipoActividad = ScholarisTipoActividad::find()->where([
    'tipo' => 'N'
])
->orderBy('orden')
->all();

$listaTipo = ArrayHelper::map($tipoActividad, 'id', 'nombre_nacional');

$today = date('Y-m-d H:i:s');
$user = Yii::$app->user->identity->usuario;

?>

<div class="lms-actividad-form" style="padding: 5px 50px 10px 50px">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lms_id')->hiddenInput(['value' => $lmsId])->label(false) ?>

    <?= $form->field($model, 'tipo_actividad_id')->dropDownList($listaTipo,[
            'prompt' => 'Seleccione tipo ...'
        ]) 
    ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tarea')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'material_apoyo')->textarea(['rows' => 6]) ?>

    <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'es_calificado')->checkbox(['checked' => true]);
        }else{
            echo $form->field($model, 'es_calificado')->checkbox();
        }
    ?>

    <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'es_publicado')->checkbox(['checked' => true]);
        }else{
            echo $form->field($model, 'es_publicado')->checkbox();
        }
    ?>

    <!-- <= $form->field($model, 'es_aprobado')->checkbox() ?> -->

    <!-- <= $form->field($model, 'retroalimentacion')->textarea(['rows' => 6]) ?> -->

    <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'created')->hiddenInput(['value' => $user])->label(false);
        }else{
            echo $form->field($model, 'created')->hiddenInput(['maxlength' => true])->label(false);
        }
    ?>

    <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'created_at')->hiddenInput(['value' => $today])->label(false);
        }else{
            echo $form->field($model, 'created_at')->hiddenInput()->label(false);
        }
    ?>

    <?= $form->field($model, 'updated')->hiddenInput(['value' => $user])->label(false) ?>

    <?= $form->field($model, 'updated_at')->hiddenInput(['value' => $today])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
