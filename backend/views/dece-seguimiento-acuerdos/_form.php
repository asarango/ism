<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use backend\models\DeceSeguimientoAcuerdos;
/* @var $this yii\web\View */
/* @var $model backend\models\DeceSeguimientoAcuerdos */
/* @var $form yii\widgets\ActiveForm */

$maxItemAcuerdos = DeceSeguimientoAcuerdos::find()
->where(['id_reg_seguimiento'=>$id_seguimiento])
->max('secuencial');


       
?>

<div class="dece-seguimiento-acuerdos-form">
<br>

<div class="card "> 
<h5 class="card-header">Detalle de Acuerdo</h5>
<div class="card-body">

    <?php $form = ActiveForm::begin (); ?>
    
        <?= $form->field($model, 'id_reg_seguimiento')->hiddenInput(['value'=>$id_seguimiento])->label(false) ?>
          
        <div class="row">            
            <div class="col-lg-1">
                <?= $form->field($model, 'secuencial')->textInput(['value'=>$maxItemAcuerdos+1])->label('Item') ?>
            </div>
            <div class="col-lg-5">
                <?= $form->field($model, 'acuerdo')->textarea(['rows' => 1]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'responsable')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'fecha_max_cumplimiento')->textInput(['type' => 'date'])->label('Fecha Cumplimiento') ?>
            </div>
            <!-- <?= $form->field($model, 'cumplio')->checkbox() ?> -->
        </div>
        <br>
    <div class="form-group">
        <?= Html::submitButton('Agregar Acuerdo', ['class' => 'btn btn-success','style'=>'float: right;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
</div>

