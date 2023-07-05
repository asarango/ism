<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Tareas';
$this->params['breadcrumbs'][] = ['label' => 'Semanas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


// echo "<pre>";
// print_r($semana);
// die();
?>


<div class="Tareas-form">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8 ">
            <div class="row align-items-center p-2">

                <div class="col-lg-1 col-md-1 col-sm-1">
                    <h4><img src="../ISM/main/images/submenu/plan.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                    <p>

                        <?= '<b><small>' . $semana->clase->paralelo->course->name . ' ' . '"' . $semana->clase->paralelo->name .
                            '' . '"' . '/' . ' ' . $semana->clase->profesor->last_name . ' ' . $semana->clase->profesor->x_first_name .
                            '</small></b>' ?>

                    </p>
                </div>
                <!-- BOTONES -->
                <div class="col-lg-3 col-md-3">

                </div>
                <hr>
            </div>
            <div>
                <div class="card" style="padding: 1rem; justify-content: center; margin-bottom: 1rem;">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'title')->textInput() ?>
                    <?= $form->field($model, 'plan_semanal_id')->textInput(['value' => $semana->id])->label(false) ?>
                    <?= $form->field($model, 'tipo_actividad_id')->dropDownList($tipoActividades, ['prompt' => 'Seleccionar tipo de actividad']) ?>
                    <div style="margin-top: 1rem">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>



