<?php
use yii\helpers\Url;
use yii\helpers\Html;
// echo '<pre>';
// print_r($planSemanal);
// echo $experienciaId;
// die();
?>
<div class="table table-responsive">
    <table class="table table-hover table-stripped" id="table-exp" >
        <thead>
            <tr>
                <th scope="col" style="text-align:center">ACCIÓN</th>
                <th scope="col">SEMANA</th>
                <th scope="col">ESTADO</th>
                <th scope="col">EXPERIENCIA</th>
            </tr>
        </thead>
        <tbody>
                        
    <?php 
        foreach($planSemanal as $i => $plan){
        ?>
            <tr>
                <td style="width:40px;text-align:center">        
                <?php
                    $semanaId = $plan['id'];
                    if($experienciaId == 0 && $plan['kids_unidad_micro_id'] != 0){
                        echo Html::a(
                            '<i class="fas fa-eye"></i>',
                            ['detalle',
                             'kids_plan_semanal_id' => $plan['plan_semanal_id']
                            ]
                        ); 
                    }elseif($experienciaId == 0 && !$plan['kids_unidad_micro_id']){
                        echo '<i class="fas fa-gift" title="Disponible"></i>';
                    }elseif($experienciaId != 0 && $plan['kids_unidad_micro_id'] != 0){
                        // $button = 'asignado';
                        echo Html::a(
                            '<i class="fas fa-eye"></i>',
                            ['detalle',
                             'kids_plan_semanal_id' => $plan['plan_semanal_id']
                            ]
                        ); 
                    }elseif(!$plan['kids_unidad_micro_id']){
                        // $button = 'asignar';
                        echo '<a class="btn btn-info" id="btn-add" onclick="agregar('.$semanaId.')" >
                                <i class="fas fa-plus-square" title="Agregar"></i>
                              </a>';
                    }
                ?>
                        
                </td>
                <td><?=$plan['nombre_semana']?></td>
                <td><?=$plan['estado']?></td>
                <td><?=$plan['experiencia']?></td>
            </tr>   
    <?php
        }
    ?>
        </tbody>
    </table>
</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

<script>
function agregar(semanaId){
var experienciaId = '<?=$experienciaId?>';
// alert(experienciaId);
// alert(semanaId);
    if(experienciaId == 0){
        alert('Debe seleccionar una experiencia!!!');
        return false;
    }

    var url = '<?=Url::to(['ajax-insert-experiencia'])?>';
    var params = {
        experiencia_id: experienciaId,
        semana_id: semanaId
    };

    $.ajax({
        url: url,
        data: params,
        type: 'POST',
        success: function(){
            semanas(experienciaId);
        }
    });

}
</script>
