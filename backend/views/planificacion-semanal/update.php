<?php

use yii\helpers\Html;
use backend\models\TocPlanUnidadHabilidad;
use Mpdf\Tag\Small;
use Mpdf\Tag\Span;
use yii\helpers\Url;
use function Symfony\Component\String\s;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionSemanal */



$this->title = 'Modificar Planificacion Semanal ';
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
                        <?= $model->clase->paralelo->course->name . ' '.'"'.'B'.'"'.'/'.' Hora de clase:' .$model->hora->nombre. '
                         '.'"'.'/'.' '. $model->clase->profesor->last_name . ' ' . $model->clase->profesor->x_first_name.'' ?>
                    </p>
                </div>
                <!-- <div class="col-lg-3 col-md-3 col-sm-3" style="text-align: right;">
                    
                </div> -->
                <hr>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <?= $this->render('_form', ['model' => $model]); ?>
                </div>
            </div>

        </div>
    </div>
</div>