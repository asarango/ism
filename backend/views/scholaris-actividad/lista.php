<?php

use backend\models\helpers\HelperGeneral;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insumos Calificados - ' . $modelClase->paralelo->course->name . 
' | '.$modelClase->paralelo->name.
' | '.$modelClase->ismAreaMateria->materia->nombre;


$helper = new HelperGeneral();


?>
<!--<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>-->
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

<link rel="stylesheet" href="estilo.css" />


<div class="lms-index">

    <div class="m-0 vh-50 row justify-content-center">

        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/aula.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?= '<b>BLOQUE: </b>'.$semanas[0]->bloque->name ?> | 
                        <?= '<b>SEMANA: </b>'.$semanas[0]->nombre_semana ?> | 

                    </small>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6 col-md-6"> |
                    <?php
                    echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fa fa-plus-circle" aria-hidden="true"></i> Mi plan semanal</span>',
                            ['lms-docente/index1',
                                'semana_numero' => $semana_numero,
                                'nombre_semana' => $nombre_semana,
                                'clase_id'      => $clase_id
                            ],
                            ['class' => '', 'title' => 'PLANIFICIONES SEMANALES']
                    );
                    ?>
                    |
                </div>
                <!-- fin de primeros botones -->

                <!--botones derecha-->
                <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>
            
                    <!--/************************************************************ */-->
            <!--incia cuerpo-->
            <div class="row" style="margin-top: 10px;">
                <div class="col-lg-3 col-md-3">
                    <b>TEMAS DE LA SEMANA:</b>
                    <ul style="margin-top: 15px;">
                    <?php
                        
                        foreach($temas as $tema){
                            echo Html::a('<li><i class="fa fa-clock zoom" style="color:#0a1f8f; margin-bottom: 10px"> '.$tema->titulo.'</i></li>',['lista',
                                'lms_id'        => $tema->id,
                                'clase_id'      => $clase_id,
                                'semana_numero' => $semana_numero,
                                'detalle_horario_id' => $detalle_horario_id                                
                            ]);

                            
                            
                        }
                    ?>
                    </ul>
                </div>
                <div class="col-lg-5 col-md-5 my-scroll" style="height: 65vh;">
                    <b>ACTIVIDADES PARA: </b><?= $lms->titulo ?>

                    <?php
                        foreach($actividades as $actividad){
                            ?>
                            <div class="card" style="margin-bottom: 20px;">
                            <div class="card-header" style="background: linear-gradient(#898b8d, #ab0a3d); color: white;">
                                <?php
                                    if($actividad->calificado == true){
                                        echo '<i class="fas fa-vote-yea"> SI CALIFICADO</i>';
                                    }else{
                                        echo '<i class="fas fa-vote-yea"> NO CALIFICADO</i>';
                                    }
                                    echo ' | ';
                                    if($actividad->tipo_calificacion == 'N'){
                                        echo '<i class="fas fa-vote-yea"> NACIONAL</i>';
                                    }else{
                                        echo '<i class="fas fa-vote-yea"> PAI</i>';
                                    }

                                    echo ' | ';

                                    echo $actividad->hora->sigla.'<b> HORA</b>';

                                    echo ' | ';
                                    if($actividad->es_heredado_lms == true){
                                        echo '<i class="fas fa-vote-yea"> SI HEREDA ('.$actividad->lms_actvidad_id.')</i>';
                                    }else{
                                        echo '<i class="fas fa-vote-yea"> PAI</i>';
                                    }


                                ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center"><u><?= $actividad->title ?></u></h5>
                                <b>Tarea:</b>
                                <p class="card-text"><?= $actividad->tareas ?></p>                                

                                <?=
                                    Html::a('<i class="fas fa-running zoom" style="color: #0a1f8f"> Ir a la actividad</i>',['actividad',
                                    'actividad' => $actividad->id
                                ]);
                                ?>
                            </div>
                            <div class="card-footer text-muted">
                                
                                <?php 
                                    echo '<b>Fecha de entrega: </b>'.$actividad->inicio; 
                                    echo ' | ';
                                    if($actividad->estado == true){
                                        echo '<i class="fas fa-vote-yea" style="color: green"> ESTÁS USANDO ('.$actividad->lms_actvidad_id.')</i>';
                                    }else{
                                        echo '<i class="fas fa-times" style="color: red"> NO ESTÁS USANDO</i>';
                                    }
                                 ?>
                                
                            </div>
                            </div>
                            <?php
                        }
                    ?>

                </div>
                <div class="col-lg-4 col-md-4">RECORDATORIO DEL RESTO DE ACTIVIDADES</div>
            </div>
            <!--fin de cuerpo-->

        </div>
    </div>
</div>