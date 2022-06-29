<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan Semanal';

// echo '<pre>';
// print_r($kidsPlanSemanal);
// print_r($kidsPlanSemanal->id);
// print_r($kidsPlanSemanal->kidsUnidadMicro->pca->opCourse);
// print_r($kidsPlanSemanal->semana->nombre_semana);
// print_r($experiencias);
// print_r($arrayDias);
// die();
$inicia = $kidsPlanSemanal->kidsUnidadMicro->fecha_inicia;
$termina = $kidsPlanSemanal->kidsUnidadMicro->fecha_termina;
?>

<div class="kids-plan-semanal-detalle">

    <div class="" style="padding-left: 40px; padding-right: 40px">

        <div class="m-0 vh-50 row justify-content-center align-items-center">
            <div class="card shadow col-lg-12 col-md-12">

                <!-- comienza encabezado -->
                <div class="row" style="background-color: #ccc; font-size: 12px">
                    <div class="col-md-12 col-sm-12">
                        <p style="color:white">
                            |                                
                            <?=
                            Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                    ['site/index'], ['class' => 'link']);
                            ?>                
                            |
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificaciones</span>',
                                    [
                                        'kids-menu/index1'
                                    ]
                            );
                            ?>    
                            |
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Experiencias</span>',
                                    [
                                        'kids-plan-semanal/index',
                                        'pca_id'=> $kidsPlanSemanal->kids_unidad_micro_id
                                    ]
                            );
                            ?>    
                            |
                           
                        </p>
                    </div>
                    <hr>
                    <div class="col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-3 col-sm-3">
                                <strong>#SEMANA: <?=$kidsPlanSemanal->semana->nombre_semana?></strong>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <strong>EXPERIENCIA: <?=$kidsPlanSemanal->kidsUnidadMicro->experiencia?></strong>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <strong>DURACIÓN DE EXPERIENCIA: 
                                    <?php
                                    
                                        // $diff = new DateTime();
                                        $fecha_actual = date("d-m-Y");
                                        echo date("Y-m-d",strtotime($termina."-1 days")); 
                                    ?>
                                </strong>
                            </div>
                            <div class="col-md-3 col-sm-3">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin de encabezado -->

                <!--comienza cuerpo de documento-->
               <div class="row" style="text-align:center; margin-top:10px">
                    <div class="col-md-12 col-sm-12">
                        <h4><?=$kidsPlanSemanal->semana->nombre_semana?> - <?=$kidsPlanSemanal->kidsUnidadMicro->experiencia?></h4>
                        <p><strong><i class="far fa-calendar"></i>&nbsp;<?=$inicia?> 
                                / 
                                <i class="far fa-calendar"></i>&nbsp;<?=$termina?></strong></p>
                    </div>
               </div>

               <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="table table-responsive">
                            <table class="table table-bordered table-stripped table-hover">
                                <thead class="bg-segundo" style="text-align:center;">
                                    <tr>
                                        <th>DIA</th>
                                        <th>HORA</th>
                                        <th>ASIGNATURA</th>
                                        <th>RINCÓN/ÁMBITO</th>
                                        <th>DESTREZAS</th>
                                        <th>ACTIVIDADES</th>
                                        <th>TAREAS/EVALUACION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align:center; background-color:#898b8d" rowspan="<?=count($arrayDias[0]['horas'])?>">
                                            <?=$arrayDias[0]['nombre']?>
                                        </td>
                                    <?php
                                        for ($i=0; $i < count($arrayDias) ; $i++) { 
                                            for ($j=0; $j < count($arrayDias[$i]['horas']) ; $j++) { 
                                              ?>
                                              <td style="background-color:#65b2e8">°<?=$arrayDias[$i]['horas'][$j]['hora']?></td>
                                                <td style="text-align:center">
                                                    <?=Html::a(
                                                       $arrayDias[$i]['horas'][$j]['materia'].
                                                        '<br><small class="my-text-small">'.$arrayDias[$i]['horas'][$j]['curso'].'</small>',
                                                        ['kids-plan-semanal-hora-clase/index1',
                                                        'plan_semanal_id' => $kidsPlanSemanal->id,
                                                        'clase_id' => $arrayDias[$i]['horas'][$j]['clase_id'],
                                                        'detalle_id' => $arrayDias[$i]['horas'][$j]['detalle_id']
                                                    ],
                                                    [
                                                        'class' => 'link'
                                                    ]
                                                    )?>
                                                </td>
                                                <td style="text-align:center">ambito</td>
                                                <td style="text-align:center">destreza</td>
                                                <td style="text-align:center">act</td>
                                                <td style="text-align:center">tarea</td> 
                                                </tr>
                                              <?php  
                                            }
                                            ?>
                                            <tr>
                                                <?php
                                                if($i+1 < count($arrayDias)){
                                                    ?>
                                                    <td style="text-align:center; background-color:#898b8d" rowspan="<?=count($arrayDias[$i+1]['horas'])?>">
                                                        <?=$arrayDias[$i+1]['nombre']?>
                                                    </td>
                                                    <?php
                                                }
                                        }
                                    ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
               </div>

                <!--finaliza cuerpo de documento-->

            </div>

        </div>

    </div>
</div>