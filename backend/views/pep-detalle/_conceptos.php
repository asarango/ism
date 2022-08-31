<div class="row">
    <div class="col-lg-4 col-md-4">
        <p class="text-primero"><b><u><i class="fas fa-cog"> Conceptos clave</i></u></b></p>

        <?php
        foreach ($registros as $reg) {
            if ($reg->tipo == 'concepto_clave') {
                $reg->contenido_opcion ? $check = 'checked' : $check = '';
                ?>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?= $check ?>
                        onclick="update(<?= $reg->id ?>)">
                        <label class="form-check-label" for=""><?= $reg->contenido_texto ?></label>
                      </div>
                <?php
            }
        }
        ?>


    </div>
    <div class="col-lg-5 col-md-5">
        <p class="text-primero"><b><u><i class="fas fa-cogs"> Conceptos relacionados</i></u></b></p>
        <?php
        foreach ($registros as $reg) {
            if ($reg->tipo == 'concepto_relacionado') {
                $reg->contenido_opcion ? $check = 'checked' : $check = '';
                $claveModel = \backend\models\PepOpciones::find()->where(['contenido_es' => $reg->contenido_texto])->one();
                ?>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?= $check ?>
                        onclick="update(<?= $reg->id ?>)">
                        <div class="row">
                            <div class="col-lg-5 col-md-5" style="font-size: 10px"><?= $claveModel->categoria_principal_es ?>:</div>
                            <div class="col-lg-7 col-md-7"><label class="form-check-label" for=""><b><?= $reg->contenido_texto ?></b></label></div>
                            
                        </div>
                        
                      </div>
                <?php
            }
        }
        ?>
        
    </div>
    <!--fin de conceptos relacionados-->
    
    
    <!--inicio de atributos de perfil-->
    <div class="col-lg-3 col-md-3">
        <p class="text-primero"><b><u><i class="fas fa-cogs"> Atributos del perfil</i></u></b></p>
        <?php
        foreach ($registros as $reg) {
            if ($reg->tipo == 'atributos_perfil') {
                $reg->contenido_opcion ? $check = 'checked' : $check = '';
                ?>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" <?= $check ?>
                        onclick="update(<?= $reg->id ?>)">
                        <label class="form-check-label" for=""><?= $reg->contenido_texto ?></label>
                      </div>
                <?php
            }
        }
        ?>
    </div>
    <!--fin de atributos de perfil-->
    
</div>


<script>
    function update(id){
        var url = '<?= yii\helpers\Url::to(['update-selection']) ?>';

        params = {
          id : id      
        };

        $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (resp) {   
                    respuesta = JSON.parse(resp);
                    var estado = respuesta.status;

                    if(estado == 'ok'){
                        //alert('Actualizado correctamente!');
                    }else{
                        alert('El registro no se actualizó!');
                    }
                }
            });
    }
</script>