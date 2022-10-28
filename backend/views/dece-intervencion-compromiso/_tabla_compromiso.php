
<?php
    use yii\helpers\Url;

    // echo '<pre>';
    // print_r($modelCompromisos);
    // die();
?>
<div>
<h6>Estudiantes</h6>
    <table class='table table-striped table-info'>
        <tr>
            <td><b>Bloque</b></td>
            <td><b>Compromiso</b></td>
            <td><b>Fecha Max Compromiso </b></td>
            <td><b>Se realizó</b></td>
            <td><b>Revisión Cumplimiento</b></td>
        </tr>
        <?php
          foreach($modelCompromisos as $reg)
          {
            if(strlen($reg->comp_estudiante)>0)
            {
        ?>
            <tr>
                <td><?= $reg->bloque?></td>
                <td><?= $reg->comp_estudiante?></td>
                <td><?= $reg->fecha_max_cumplimiento?></td>
                <td >
                    <?php
                        if($reg->esaprobado)
                        {
                    ?>
                        <input type="checkbox" id="cbox1_<?= $reg->id?>" name="cbox1_<?= $reg->id?>"  checked>
                    <?php
                        }else{
                    ?>
                        <input type="checkbox" id="cbox1_<?= $reg->id?>" name="cbox1_<?= $reg->id?>"  >
                    <?php
                        }
                    ?>
                </td>
                <td>
                    <div class="input-group mb-3">
                        <input id="btn_revision_compromiso1_<?= $reg->id?>" type="text" class="form-control" placeholder="Pendiente Revisión"
                        aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?= $reg->revision_compromiso?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="guardarCompromiso('estudiante',<?= $reg->id?>)"><i class="fas fa-save"></i></button>
                            <button class="btn btn-outline-secondary" type="button" onclick="eliminarCompromiso(<?= $reg->id?>)"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
            }
          }
        ?>
    </table>

    <h6>Representantes</h6>
    
    <table class='table table-striped table-light'>
    <tr>
            <td><b>Bloque</b></td>
            <td><b>Compromiso</b></td>
            <td><b>Fecha Max Compromiso </b></td>
            <td><b>Se realizó</b></td>
            <td><b>Revisión Cumplimiento</b></td>
        </tr>
        <?php
          foreach($modelCompromisos as $reg)
          {
            if(strlen($reg->comp_representante)>0)
            {
        ?>
            <tr>
                <td><?= $reg->bloque?></td>
                <td><?= $reg->comp_representante?></td>
                <td><?= $reg->fecha_max_cumplimiento?></td>
                <td >
                    <?php
                        if($reg->esaprobado)
                        {
                    ?>
                        <input type="checkbox" id="cbox2_<?= $reg->id?>" name="cbox2_<?= $reg->id?>"  checked>
                    <?php
                        }else{
                    ?>
                        <input type="checkbox" id="cbox2_<?= $reg->id?>" name="cbox2_<?= $reg->id?>"  >
                    <?php
                        }
                    ?>
                </td>
                <td>
                    <div class="input-group mb-3">
                        <input id="btn_revision_compromiso2_<?= $reg->id?>" type="text" class="form-control" placeholder="Pendiente Revisión"
                        aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?= $reg->revision_comp_representante?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="guardarCompromiso('representante',<?= $reg->id?>)"><i class="fas fa-save"></i></button>
                            <button class="btn btn-outline-secondary" type="button" onclick="eliminarCompromiso(<?= $reg->id?>)"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
            }
          }
        ?>
    </table>
     
    <h6>Docentes</h6>
    <table class='table table-striped table-success'>
    <tr>
            <td><b>Bloque</b></td>
            <td><b>Compromiso</b></td>
            <td><b>Fecha Max Compromiso </b></td>
            <td><b>Se realizó</b></td>
            <td><b>Revisión Cumplimiento</b></td>
        </tr>
        <?php
          foreach($modelCompromisos as $reg)
          {
            if(strlen($reg->comp_docente)>0)
            {
        ?>
            <tr>
                <td><?= $reg->bloque?></td>
                <td><?= $reg->comp_docente?></td>
                <td><?= $reg->fecha_max_cumplimiento?></td>
                <td >
                    <?php
                        if($reg->esaprobado)
                        {
                    ?>
                        <input type="checkbox" id="cbox3_<?= $reg->id?>" name="cbox3_<?= $reg->id?>"  checked>
                    <?php
                        }else{
                    ?>
                        <input type="checkbox" id="cbox3_<?= $reg->id?>" name="cbox3_<?= $reg->id?>"  >
                    <?php
                        }
                    ?>
                </td>
                <td>
                    <div class="input-group mb-3">
                        <input id="btn_revision_compromiso3_<?= $reg->id?>" type="text" class="form-control" placeholder="Pendiente Revisión"
                        aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?= $reg->revision_comp_docente?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="guardarCompromiso('docente',<?= $reg->id?>)"><i class="fas fa-save"></i></button>
                            <button class="btn btn-outline-secondary" type="button" onclick="eliminarCompromiso(<?= $reg->id?>)"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
            }
          }
        ?>
    </table>
     
    <h6>Dece</h6>
    <table class='table table-striped table-warning'>
    <tr>
            <td><b>Bloque</b></td>
            <td><b>Compromiso</b></td>
            <td><b>Fecha Max Compromiso </b></td>
            <td><b>Se realizó</b></td>
            <td><b>Revisión Cumplimiento</b></td>
        </tr>
        <?php
          foreach($modelCompromisos as $reg)
          {
            if(strlen($reg->comp_dece)>0)
            {
        ?>
            <tr>
                <td><?= $reg->bloque?></td>
                <td><?= $reg->comp_dece?></td>
                <td><?= $reg->fecha_max_cumplimiento?></td>
                <td >
                    <?php
                        if($reg->esaprobado)
                        {
                    ?>
                        <input type="checkbox" id="cbox4_<?= $reg->id?>" name="cbox4_<?= $reg->id?>"  checked>
                    <?php
                        }else{
                    ?>
                        <input type="checkbox" id="cbox4_<?= $reg->id?>" name="cbox4_<?= $reg->id?>"  >
                    <?php
                        }
                    ?>
                </td>
                <td>
                    <div class="input-group mb-3">
                        <input id="btn_revision_compromiso4_<?= $reg->id?>" type="text" class="form-control" placeholder="Pendiente Revisión"
                        aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?= $reg->revision_comp_dece?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="guardarCompromiso('dece',<?= $reg->id?>)"><i class="fas fa-save"></i></button>
                            <button class="btn btn-outline-secondary" type="button" onclick="eliminarCompromiso(<?= $reg->id?>)"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
            }
          }
        ?>
    </table>
    
    </div>
    <script>
        function guardarCompromiso(tipo_compromiso,id_inter_compromiso)
        {
            var url = '<?= Url::to(['dece-intervencion-compromiso/guardar-compromiso']) ?>';  
            
            var id_intervencion_compromiso = id_inter_compromiso;
            var revision_compromiso = "";            
            var esChequeado = false;

            switch(tipo_compromiso){
                case 'estudiante':
                    revision_compromiso = $('#btn_revision_compromiso1_'+id_inter_compromiso).val(); 
                    esChequeado = $('#cbox1_'+id_inter_compromiso).prop('checked');
                    break;
                case 'representante':
                    revision_compromiso = $('#btn_revision_compromiso2_'+id_inter_compromiso).val(); 
                    esChequeado = $('#cbox2_'+id_inter_compromiso).prop('checked');
                    break;
                case 'docente':
                    revision_compromiso = $('#btn_revision_compromiso3_'+id_inter_compromiso).val(); 
                    esChequeado = $('#cbox3_'+id_inter_compromiso).prop('checked');                   
                    break;
                case 'dece':                    
                    revision_compromiso = $('#btn_revision_compromiso4_'+id_inter_compromiso).val(); 
                    esChequeado = $('#cbox4_'+id_inter_compromiso).prop('checked');
                    break;
            }
           

            var params = {
                revision_compromiso: revision_compromiso,
                id_intervencion_compromiso : id_intervencion_compromiso,
                tipo_compromiso:tipo_compromiso,   
                esChequeado:esChequeado,            
            };           

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (response) {
                    muestraTablaCompromiso();
                    alert(response);

                }
            });
        }
        function eliminarCompromiso(id_inter_compromiso)
        {
            var url = '<?= Url::to(['dece-intervencion-compromiso/eliminar-compromiso']) ?>';  
            var id_intervencion_compromiso = id_inter_compromiso;          
            var params = {             
                id_intervencion_compromiso : id_intervencion_compromiso,          
            };         

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (response) {
                    muestraTablaCompromiso();
                    alert(response);

                }
            });
        }

    </script>
   