<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;


$tipoPai = $modelActividad->tipoActividad->tipo;

?>

<?php
    if($tipoPai == 'P'){
        ?>

<div class="card">
    <div class="card-header">
        OBJETIVOS PAI
    </div>
    
    <div class="card-body">
        
        <div class="table table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>Criterio</th>
                        <th>Descriptor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php                    
                        foreach ($criterios as $criterio){
                            echo '<tr>';
                            if($criterio['lms_criterio_id']){
                                echo '<td>';
                                echo Html::a('<i class="fas fa-check-circle" style="color: green"></i>',['quitar',
                                        'id' => $criterio['lms_criterio_id'],
                                        'clase_id'      => $clase_id,
                                        'actividadId'   => $id,
                                        'semana_numero' => $semana_numero,
                                        'nombre_semana' => $nombre_semana,
                                        'lms_id'        => $lms_id,
                                        'campo'         => 'update-actividad',
                                        'seccion'       => $seccion
                                    ]);
                                echo '</td>';
                            }else{
                                echo '<td>';
                                echo Html::a('<i class="fas fa-times-circle" style="color: red"></i>',['asignar',
                                        'actividadId'   => $id,
                                        'plan_vertical_descriptor_id' => $criterio['id'],
                                        'lms_id'        => $lms_id,
                                        'campo'         => 'update-actividad',
                                        'clase_id'      => $clase_id,
                                        'semana_numero' => $semana_numero,
                                        'nombre_semana' => $nombre_semana,
                                        'seccion'       => $seccion
                                    ]);
                                echo '</td>';
                            }
                            ?>
                    
                        <td><?= $criterio['criterio'] ?></td>
                        <td align="left"><?= $criterio['descripcion'] ?></td>
                    </tr>
                        <?php
                        }                    
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php
    }
?>