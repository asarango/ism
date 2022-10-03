<?php
use yii\helpers\Url;

?>


<h3><b>
        <?= $modelClase->ismAreaMateria->materia->nombre ?>
        <small>
            <?= $modelClase->paralelo->course->name . ' - ' . $modelClase->paralelo->name ?>
        </small>
    </b>
</h3>


 <table style="margin: 10px">        
     <tr>
         <?php
        foreach ($bloques as $bloque){
            if($bloque['estado'] == 'cerrado'){
                $color = "#ab0a3d";
                $title = 'CERRADO';
            }else{
                $color = "#65b2e8";
                $title = 'ABIERTO';
            }
            ?>
            <td class="text-center">
                    <a class="p-2" href="#" 
                       style="border: solid 1px #ccc; border-radius: 50%; background-color: <?= $color ?>; color: #fff"
                       title="<?= $title ?>"
                       onclick="muestra_semanas(<?= $modelClase->id ?>, <?= $bloque['bloque_id']?>,'informacion')">
                        <?= $bloque['abreviatura'] ?>
                    </a>
            </td>
            <?php
        }     
        ?>
     </tr>
</table>


<script>
    function muestra_semanas(claseId, bloqueId){
        var url = '<?= Url::to(['semanas']) ?>';
        var params = {
            clase_id : claseId,
            bloque_id : bloqueId
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function (){},
            success: function (response) {                                
                $("#div-semanas").show();
                $("#div-semanas").html(response);
            }
        });
    }
</script>