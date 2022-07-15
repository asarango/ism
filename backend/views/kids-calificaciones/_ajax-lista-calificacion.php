<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
// echo '<pre>';
// print_r($escalas);
// echo '<hr>';
// print_r($calificaciones);
?>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="table table-responsive">
            <table class="table table-hover table-stripped table-bordered table-condensed">
                <thead class="bg-quinto">
                    <tr>
                        <th valign="middle" rowspan="2">#</th>
                        <th valign="middle" rowspan="2">ESTUDIANTE</th>
                        <th style="text-align:center" colspan="4">
                            CALIFICACIÓN
                        </th>
                        <th class="text-center" valign="middle" rowspan="2">ACTIVO</th>
                    </tr>
                    <tr class="text-center">
                        <td>N/E</td>
                        <td>I</td>
                        <td>EP</td>
                        <td>A</td>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $cont = 1;
                    foreach ($calificaciones as $key => $estudiante):
                        ?>
                        <tr>
                            <td><?=$cont?></td>
                            <td><?=$estudiante['estudiante']?></td>
                            
                                <?php 
                                foreach ($escalas as $keyEsc => $escala) {
                                    ?>
                                    <td class="text-center">
                                    <a onclick="califica(<?=$escala['id']?>,<?=$estudiante['id']?>)" type="button">
                                        <?php 
                                        if($estudiante['escala'] == $escala['escala']){
                                            ?>
                                            <i class="<?=$escala['icono_font_awesome']?>" title="<?=$escala['significado']?>" style="font-size:18px; color:#9e28b5" ></i>&nbsp;
                                            <?php
                                        }else{
                                            ?>
                                            <i class="<?=$escala['icono_font_awesome']?>" title="<?=$escala['significado']?>" style="font-size:18px; color:#898b8d" ></i>&nbsp;
                                            <?php
                                        }
                                        ?>
                                    </a>
                                    <?php
                                }
                                ?>
                                </td>
                            
                            <td class="text-center">
                                <?=$activo = ($estudiante['es_activo'] == 1) 
                                ? '<i class="fas fa-check-circle" style="font-size:18px; color:green"></i>' 
                                : '<i class="fas fa-cross" style="font-size:18px;color:red"></i>' ?>
                            </td>
                        </tr>
                        <?php
                        $cont ++;
                    endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    //Función para agregar calificación al estudiante
    function califica(escalaId, calificacionId){
        var url = '<?=Url::to(["update-calificacion"])?>';
        var params = {
            id: escalaId,
            calificacion_id: calificacionId
        };

        $.ajax({
            url: url,
            data: params,
            type: 'POST',
            success: function(){
                muestra_estudiantes();
            }
        });
    }
</script>