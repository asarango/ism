<div class="row">


    <p class="text-primero"><b><u><i class="fas fa-cogs"> Enfoques de aprendizaje</i></u></b></p>

    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th>Acción</th>
                <th>Principal</th>
                <th>Secundaria</th>
            </tr>
        </thead>
        <tbody>

            <?php
            foreach ($registros as $reg) {
                if ($reg->tipo == 'enfoques_aprendizaje') {
                    $reg->contenido_opcion ? $check = 'checked' : $check = '';
                    ?>
            <tr>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?= $check ?>
                           onclick="update(<?= $reg->id ?>)">
                    
                    </div>
                </td>
                <td><label class="form-check-label" for=""><?= $reg->referencia ?></label></td>
                <td><label class="form-check-label" for=""><?= $reg->contenido_texto ?></label></td>
                
            </tr>   
                <?php
            }
        }
        ?>
        </tbody>
    </table>




    <!--fin de conceptos relacionados-->


    <!--inicio de atributos de perfil-->

    <!--fin de atributos de perfil-->

</div>


<script>
    function update(id) {
        var url = '<?= yii\helpers\Url::to(['update-selection']) ?>';

        params = {
            id: id
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (resp) {
                respuesta = JSON.parse(resp);
                var estado = respuesta.status;

                if (estado == 'ok') {
                    //alert('Actualizado correctamente!');
                } else {
                    alert('El registro no se actualizó!');
                }
            }
        });
    }
</script>