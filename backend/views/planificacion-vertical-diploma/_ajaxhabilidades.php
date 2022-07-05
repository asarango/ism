
<div class="table table-responsive">
    <table class="table table-hover table-condensed table-bordered table-striped">
        <thead>
            <tr>
                <th colspan="4">ENFOQUES DE APRENDIZAJE (HABILIDADES IB)</th>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach ($habilidades as $habilidad) {
                ?>
                <tr>
                    <?php
                    if ($habilidad['elegido_id']) {
                        ?>
                    <td><a href="#"
                           style="color: green"
                           onclick="eliminar(<?= $habilidad['elegido_id'] ?>)"
                           ><i class="fas fa-check-circle"></i></a></td>
                        <?php
                    }else{
                        ?>
                    <td><a href="#" 
                           style="color: #ab0a3d"
                           onclick="agregar(<?= $habilidad['opcion_id'] ?>, <?= $planVerticalId ?>)"><i class="fas fa-ban"></i></a>
                    </td>
                        <?php
                    }
                    ?>
                    
                    <td><?= $habilidad['habilidad'] ?></td>
                    <td><?= $habilidad['sub_habilidad'] ?></td>
                    <td><?= $habilidad['nombre'] ?></td>
                    
                </tr>

                <?php
            }
            ?>
        </tbody>

    </table>
</div>

<script>
    function eliminar(elegidoId) {
        var url = '<?= \yii\helpers\Url::to(['ajax-habilidades-accion']) ?>';

        var params = {
            elegido_id: elegidoId,
            accion: 'eliminar'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                show_habilidades();
                show_habilidades_seleccionadas();
            }
        });
    }


    function agregar(opcionId, planVerticalId) {

        var url = '<?= \yii\helpers\Url::to(['ajax-habilidades-accion']) ?>';

        var params = {
            opcion_id: opcionId,
            plan_vertical_id: planVerticalId,
            accion: 'agregar'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                show_habilidades();
                show_habilidades_seleccionadas();
            }
        });
    }
</script>