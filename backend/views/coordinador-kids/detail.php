<?php

use yii\helpers\Html;

?>

<div class="row">
    <div class="col-lg-12 col-md-12"><b>Detalle de semana: </b><?= $week->nombre_semana ?></div>
    <hr>

    <div class="table table-responsive" style="height: 60vh;">
        <table class="table table-hover table-condensed table-striped" id="table_coordinador_kids" 
                style="font-size: 10px; overflow-y: scroll;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>DOCENTE</th>
                    <th>USUARIO</th>
                    <th>CURSO</th>
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
                        <td><?= $detail['nivel'] ?></td>
                        <td><?= $detail['estado'] ?></td>
                        <td class="text-center">
                        <?php
                            if($detail['estado'] == 'ENVIO_COORDINACION' || $detail['estado'] == 'DEVUELTO' || $detail['estado'] == 'APROBADO'){                             
                                echo Html::a('<i class="fas fa-eye"></i>',['view',
                                        'week_id' => $week->id,
                                        'curso_id' => $detail['curso_id'],
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

<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>



<script>
    

    $('#table_coordinador_kids').DataTable({
        "search":"false"
    });
</script>