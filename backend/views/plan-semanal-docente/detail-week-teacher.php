<?php

use yii\helpers\Url;

?>


<div class="row">
    <div class="col-lg-10 col-md-10">
        <iframe width="100%" height="600" src="<?= Url::toRoute([
                                                    'acciones',
                                                    'action' => 'pdf',
                                                    'week_id' => $weekId
                                                ]) ?>">
        </iframe>
    </div>
    <div class="col-lg-2 col-md-2">
        ESTADOS
        <hr>

        <?php
        foreach ($coordinadores as $coordinador) {
            if(!isset($coordinador['estado'])){
                echo 'Iniciado'.'<br>';
               
                ?>
                    <a href="#" onclick="enviar_coordinador('<?=$coordinador['usuario']?>', <?=$weekId?>)">hacer</a>
                <?php

            }elseif(isset($coordinador['estado']) == 'coordinador'){
                echo 'coordinador';
            }elseif(isset($coordinador['estado']) == 'devuelto'){
                echo 'devuelto';
            }else{
                echo 'aprobado';
            }
            
        }

        ?>
    </div>
</div>

<script>
   function enviar_coordinador(coordinador, semanaId){
    let url = '<?= Url::to(['acciones']) ?>';

    params = {
        week_id: semanaId,
        action: 'enviar',
        coordinador: coordinador
    };

    $.ajax({
        data: params,
        url: url,
        type: 'GET',
        beforeSend: function () { },
        success: function (response) {
            // $("#div-detail-week").html(response);
            refresh(semanaId);
        }
    });
   } 


   function refresh(semanaId) {
    let weekId = semanaId;
        let url = '<?= Url::to(['acciones']) ?>';

        params = {
            week_id: weekId,
            action: 'detail-week'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () { },
            success: function (response) {
                $("#div-detail-week").html(response);
            }
        });
   }
</script>

