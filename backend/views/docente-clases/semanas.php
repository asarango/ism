<?php

use yii\helpers\Url;
use yii\helpers\Html;

if (isset($modelSemanas[0]['bloque'])) {
    ?>

    <h5><b>
    <?= $modelSemanas[0]['bloque'] ?>        
        </b>
    </h5>


    <div class="row row-cols-1 row-cols-md-3 g-4">
        
    <?php
    foreach ($modelSemanas as $semana) {
        ?>
        
            <div class="col">
                <div class="card">
                    <div class="card-header" style="background-color: #65b2e8; color: white"><?= $semana['semana_numero'] ?></div>
                    <!--<img src="..." class="card-img-top" alt="...">-->
                    <div class="card-body">
                        <h5 class="card-title"><?= $semana['nombre_semana'] ?></h5>
                        <p class="card-text"><b>Total de horas a planificar: </b><?= $modelClase->ismAreaMateria->total_horas_semana ?></p>
                        <p class="card-text"><b>Total de horas planificadas: </b><?= $semana['total_horas'] ?></p>
                    </div>
                    
                    <div class="card-footer">
                        <?php
                            
                            if(trim($usuarioLog) == ($responsable)){
                                echo Html::a('<i class="fas fa-user-cog"></i>',['lms/index1',
                                    'semana_numero' => $semana['semana_numero'],
                                    'nombre_semana' => $semana['nombre_semana'],
                                    'clase_id' => $modelClase->id
                                ],['style' => "color: #ab0a3d", 'title' => 'Configurar plan semanal']);
                                
                            }

                            echo Html::a('<i class="fas fa-calendar-week"></i>',['lms-docente/index1',
                                    'semana_numero' => $semana['semana_numero'],
                                    'nombre_semana' => $semana['nombre_semana'],
                                    'clase_id' => $modelClase->id
                                ]
                                ,['style' => "color: #0a1f8f; margin-left:10px", 
                                    'title' => 'Plan semanal',
                                    'target' => '_blank'    
                                ]
                                ,['class' => 'zoom']
                            
                            );
                                
                        ?>
                    </div>
                </div>
            </div>                                
        <?php
    }
    ?>

</div>



    <?php
} else {
    echo 'no';
}
?>