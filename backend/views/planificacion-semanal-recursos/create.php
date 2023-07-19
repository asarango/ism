<?php

use yii\helpers\Html;



$this->title = 'Create Planificacion Semanal Recursos';


// echo"<pre>";
// print_r($planificacionSemanal);
// die();
?>
<div class="planificacion-semanal-recursos-create">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-11 col-sm-8">
            <div class="row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/plan.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8 col-md-8">
                    <h2>
                        <?= Html::encode($this->title) ?>
                    </h2>
                    <p>
                        <?= '<b><small>' . $planificacionSemanal->clase->paralelo->course->name.' '.' '.'"'.
                        $planificacionSemanal->clase->paralelo->name.'"'.' '.'/'.' Hora de clase:'.' '. $planificacionSemanal->hora->nombre.' '.' '
                        .'/'.' '.'Docente:'.' '. $planificacionSemanal->clase->profesor->last_name . ' ' 
                        . $planificacionSemanal->clase->profesor->x_first_name . '</small></b>' ?>
                    </p>
                </div>
                <!-- BOTONES DE ACCION -->
                <div class="col-lg-3 col-md-3" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up" 
                            width="12" height="12" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                            <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                            </svg> Regresar</span>',
                            ['planificacion-semanal-recursos/index'],
                            ['class' => 'link']
                        );
                    ?>
                </div>
                <hr>
                <!-- FIN DE BOTONES DE ACCION -->
            </div>
            <!-- INICIO CUERPO DE LA CARD -->
            <div class="row">
                <div>
                    <?= $this->render('_form', [
                        'planificacionSemanalId' => $planificacionSemanalId,
                    ]) ?>
                </div>
            </div>
            <!-- FIN CUERPO DE LA CARD -->
        </div>
    </div>

</div>