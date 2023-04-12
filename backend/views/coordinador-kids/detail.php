<?php

use yii\helpers\Html;

?>

<div class="row">
    <div class="col-lg-12 col-md-12"><b>Detalle de semana: </b><?= $week->nombre_semana ?></div>
    <hr>

    <div class="table table-responsive" style="height: 60vh;">
        <table class="table table-hover table-condensed table-striped" 
                style="font-size: 10px; overflow-y: scroll;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>DOCENTE</th>
                    <th>USUARIO</th>
                    <th>ESTADO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $i=0;
                foreach($details as $detail){
                    $i++;
                    ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $detail['docente'] ?></td>
                        <td><?= $detail['login'] ?></td>
                        <td><?= $detail['estado'] ?></td>
                        <td class="text-center">
                        <?php
                            if($detail['estado'] == 'ENVIO_COORDINACION' || $detail['estado'] == 'DEVUELTO' || $detail['estado'] == 'APROBADO'){                             
                                echo Html::a('<i class="fas fa-eye"></i>',['view',
                                        'week_id' => $week->id,
                                        'user_teacher' => $detail['login']
                                    ],['target' => '_blank']);                             
                            }else{
                                echo '--';
                            }
                        ?>
                        </td>
                    </tr>
                    <?php
                }                
            ?>
            </tbody>
        </table>
    </div>    
</div>