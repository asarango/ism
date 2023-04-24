<?php

use yii\helpers\Url;

?>



<div class="row">
    <div class="col-lg-12 col-md-12">
        <table class="table table-bordered table-hover" style="margin: 10px; font-size: 10px;">
            <tr>
                <td>
                    <b><?= $modelClase->ismAreaMateria->materia->nombre ?></b>

                    <small style="margin-left: 15px;">
                        <?= $modelClase->paralelo->course->name . ' - ' . $modelClase->paralelo->name ?>
                    </small>
                </td>
                <?php
                foreach ($bloques as $bloque) {
                    if ($bloque['estado'] == 'cerrado') {
                        $color = "#ab0a3d";
                        $title = 'CERRADO';
                    } else {
                        $color = "#65b2e8";
                        $title = 'ABIERTO';
                    }
                ?>
                    <td class="text-center">
                        <a class="p-2 zoom" href="#" 
                            style="border: solid 1px #ccc; border-radius: 50%; background-color: <?= $color ?>; color: #fff" 
                            title="<?= $title ?>" onclick="muestra_semanas(<?= $modelClase->id ?>, <?= $bloque['bloque_id'] ?>,'informacion')">
                            <?= $bloque['abreviatura'] ?>
                        </a>
                    </td>
                <?php
                }
                ?>
            </tr>
        </table>
    </div>
</div>






<script>
    function muestra_semanas(claseId, bloqueId) {
        var url = '<?= Url::to(['semanas']) ?>';
        var params = {
            clase_id: claseId,
            bloque_id: bloqueId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                $("#div-semanas").show();
                $("#div-semanas").html(response);
            }
        });
    }
</script>