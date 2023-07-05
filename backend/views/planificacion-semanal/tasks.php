<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Tareas';
$this->params['breadcrumbs'][] = ['label' => 'Semanas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



// echo "<pre>";
// print_r($);
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
                <div class="col-lg-3 col-md-3" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up"
                             width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" 
                             fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                            <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                            </svg> Regresar</span>',
                            ['index1', 'clase_id' =>$semana->clase_id],
                            ['class' => '', 'title' => 'Plan Semanal']
                        );
                    ?>
                </div>
                <hr>
            </div>
            <div>
                <div class="card" style="padding: 1rem; justify-content: center; margin-bottom: 1rem;">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'title')->textInput() ?>
                    <?= $form->field($model, 'plan_semanal_id')->hiddenInput(['value' => $semana->id])->label(false) ?>
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