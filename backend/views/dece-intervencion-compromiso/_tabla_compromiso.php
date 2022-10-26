
<?php
use yii\helpers\Url;
?>
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
                        <input type="checkbox" id="cbox1" name="cbox1" value="<?= $reg->esaprobado?>" checked>
                    <?php
                        }else{ 
                    ?>
                    <input type="checkbox" id="cbox1" name="cbox1" value="<?= $reg->esaprobado?>" >

                    <?php
                        }    
                    ?>           
                </td>
                <td>
                    <div class="input-group mb-3">
                        <input id="btn_revision_compromiso<?= $reg->id?>"type="text" class="form-control" placeholder="Pendiente Revisión" 
                        aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?= $reg->revision_compromiso?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick=guardarCompromiso()><i class="fas fa-save"></i></button>
                            <button class="btn btn-outline-secondary" type="button" onclick=eliminarCompromiso()><i class="fas fa-trash-alt"></i></button>
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
                        <input type="checkbox" id="cbox1" name="cbox1" value="<?= $reg->esaprobado?>" checked>
                    <?php
                        }else{ 
                    ?>
                    <input type="checkbox" id="cbox1" name="cbox1" value="<?= $reg->esaprobado?>" >

                    <?php
                        }    
                    ?>           
                </td>
                <td>
                    <div class="input-group mb-3">
                        <input id="btn_revision_compromiso<?= $reg->id?>"type="text" class="form-control" placeholder="Pendiente Revisión" 
                        aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?= $reg->revision_comp_representante?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button">Guardar</button>
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
                        <input type="checkbox" id="cbox1" name="cbox1" value="<?= $reg->esaprobado?>" checked>
                    <?php
                        }else{ 
                    ?>
                    <input type="checkbox" id="cbox1" name="cbox1" value="<?= $reg->esaprobado?>" >

                    <?php
                        }    
                    ?>           
                </td>
                <td>
                    <div class="input-group mb-3">
                        <input id="btn_revision_compromiso<?= $reg->id?>"type="text" class="form-control" placeholder="Pendiente Revisión" 
                        aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?= $reg->revision_comp_docente?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button">Guardar</button>
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
                        <input type="checkbox" id="cbox1" name="cbox1" value="<?= $reg->esaprobado?>" checked>
                    <?php
                        }else{ 
                    ?>
                    <input type="checkbox" id="cbox1" name="cbox1" value="<?= $reg->esaprobado?>" >

                    <?php
                        }    
                    ?>           
                </td>
                <td>
                    <div class="input-group mb-3">
                        <input id="btn_revision_compromiso<?= $reg->id?>"type="text" class="form-control" placeholder="Pendiente Revisión" 
                        aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?= $reg->revision_comp_dece?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button">Guardar</button>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
          }
        }
        ?>
    </table>

    <script>
        function guardarCompromiso()
        {
            var url = '<?= Url::to(['dece-intervencion-compromiso/mostrar-tabla']) ?>';
            var id_intervencion = '<?=$model->id?>';
            var params = {
                id_intervencion: id_intervencion
            };
            
            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (response) {
                    $('#tabla_compromisos').html(response);

                }
            });
        }
        function eliminarCompromiso()
        {
            var url = '<?= Url::to(['dece-intervencion-compromiso/mostrar-tabla']) ?>';
            var id_intervencion = '<?=$model->id?>';
            var params = {
                id_intervencion: id_intervencion
            };
            
            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (response) {
                    $('#tabla_compromisos').html(response);

                }
            });
            
        }
    </script>