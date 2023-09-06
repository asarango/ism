<?php

use yii\helpers\Html;
use backend\models\TocPlanUnidadHabilidad;
use backend\models\ScholarisActividad;
use Mpdf\Tag\Small;
use Mpdf\Tag\Span;
use yii\helpers\Url;


/* @var $this yii\web\View */



$this->title = 'Modificar Planificacion Semanal ';

// echo "<pre>";
// print_r($model);
// die();
?>


<div class="planificacion-semanal-update">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card  shadow col-lg-8 col-md-8 col-sm-8">
            <div class="row align-items-center p-2">
                <div class="col-lg-1 col-md-1 col-sm-1">
                    <h4><img src="../ISM/main/images/submenu/plan.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>

                    <p>
                        <?= $model->clase->paralelo->course->name . ' ' . '"' . $model->clase->paralelo->name . '"' . '/' . ' Hora de clase:' . $model->hora->nombre . '
                         ' . '"' . '/' . ' ' . $model->clase->profesor->last_name . ' ' . $model->clase->profesor->x_first_name . '' ?>
                    </p>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3" style="text-align: right;">
                    
                    <?= Html::button(
                        '<span class="badge rounded-pill" style="background-color: #ff9e18"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up"
                        width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" 
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                       <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                       <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                       <path d="M19 22v-6" />
                       <path d="M22 19l-3 -3l-3 3" />
                       </svg>Regresar</span>',
                        ['class' => 'btn btn-default', 'onclick' => 'history.go(-1); return false;']
                    ) ?>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <?= $this->render('_form', ['model' => $model
                , 'pud_origen' => $pud_origen
                , 'plan_bloque_unidad_id' => $plan_bloque_unidad_id]); ?>
                </div>
            </div>

        </div>
    </div>
</div>