<?php

use yii\helpers\Url;
use yii\helpers\Html;

//$listaTipoActividadNac = \yii\helpers\ArrayHelper::map($tipoActividadNac, 'id', 'nombre_nacional');
//$listaTipoActividadPai = \yii\helpers\ArrayHelper::map($tipoActividadPai, 'id', 'nombre_nacional');
?>

<?php
foreach ($actividades as $actividad) {
    ?>
    <div class="row" style="background-color: white; margin-bottom: 5px">    

        <div class="col-lg-12 col-md-12" style="margin-top: 10px">
            <div class="row">
                <div class="col-lg-9 col-md-9"><h4><b><?php echo $actividad->titulo ?></b></h4></div>
                <div class="col-lg-3 col-md-3" style="text-align: right">
                    <?=
                    Html::a('<i class="fas fa-edit"></i>', ['acciones-get',
                        'lms_id' => $actividad->lms_id,
                        'campo' => 'update-actividad',
                        'actividad_id' => $actividad->id,
                        'claseId' => $claseId,
                        'nombreSemana' => $nombreSemana,
                        'numeroSemana' => $numeroSemana
                    ],['title' => 'Actualizar'])
                    ?>
                </div>  
            </div>
            
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <?= $actividad->tipoActividad->nombre_nacional ?> |
                    <?php 
                        echo '<b>Es calificado: </b>';
                        echo $actividad->es_calificado ?  '<i class="fas fa-check-circle" style="color: green"></i> | ' 
                                                       : '<i class="fas fa-times-circle"></i> |';
                        
                        echo '<b>Es publicado: </b>';
                        echo $actividad->es_publicado ? '<i class="fas fa-check-circle" style="color: green"></i> | ' 
                                                      : '<i class="fas fa-times-circle" style="color: red"></i> |';
                        
                        echo '<b>Es aprobado: </b>';
                        echo $actividad->es_aprobado ?  '<i class="fas fa-check-circle" style="color: green"></i> | ' 
                                                     : '<i class="fas fa-times-circle" style="color: red"></i> |';
                                             
                        echo '<b>Tipo: </b>';
                        echo $actividad->tipoActividad->tipo == 'N' ?  '<i style="color: green">Nacional</i> | ' 
                                                     : '<i style="color: red"> Pai</i> |';
                                             
                    ?> 
                    
                </div>                
            </div>
            <hr>
            
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <b>Descripción: </b>
                    <?= $actividad->descripcion ?>
                </div>
            </div>            
            <hr>
            
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <b>Tareas: </b>
                    <?= $actividad->tarea ?>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-lg-12 col-md-12">

                        <b>Material de apoyo: </b>
                        <?php 
                            
                            if($actividad->material_apoyo){
                                echo 'Si tiene materia de apoyo';
                            }else{
                                echo 'No tiene materia de apoyo';
                            }
                             
                        ?>

                </div>
            </div>
            
            
        </div>        
    </div>

    <?php
}
?>


<?php

function consulta_material_apoyo($actividadId){
    
}

?>