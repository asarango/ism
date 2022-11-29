<?php

// echo '<pre>';
// print_r($model);
// die();

use yii\helpers\Html;

?>

<div class="row" style="border: solid 1px #eee; margin: 10px; padding: 10px;">
    <!-- inicio de modal de grupos -->
    <div class="col-lg-6 col-md-6">
        <!-- Button trigger modal -->
        <?php
            if(count($enviados) == 0){
                ?>
            <a type="button" class="" data-bs-toggle="modal" data-bs-target="#staticBackdropGroup">
                <i class="fas fa-users" style="color: #65b2e8"> Add Grupo</i>
            </a>
                <?php
            }

        ?>        

        <!-- Modal -->
        <div class="modal fade" id="staticBackdropGroup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Agregar grupo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <div class="form-group">
                                <label class="form-label" for="BusquedaGrupo">Buscar por grupo: ...</label>
                                <input type="text" name="input_buscar" class="form-control" placeholder="POR GRUPO ..." onkeyup="search_group(this);">
                            </div>

                            <div class="" id="div-respuesta-grupos" style="border: solid 1px #eee; margin-top: 10px; padding: 10px;">

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- fin de modal de grupos -->

    <!-- inicio de modal de individuales -->
    <div class="col-lg-6 col-md-6" style="text-align: right;">
        <!-- Button trigger modal -->
        <?php
            if(count($enviados) == 0){
                ?>
                <a type="button" class="" data-bs-toggle="modal" data-bs-target="#staticBackdropUser">
                    <i class="fas fa-user" style="color: #ff9e18;"> Add Usuario</i>
                </a>
                <?php
            }
        ?>        
        <!-- Modal -->
        <div class="modal fade" id="staticBackdropUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Agregar remitente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <div class="form-group">
                                <label class="form-label" for="BusquedaUser">Buscar por remitente: ...</label>
                                <input type="text" name="input_buscar_user" class="form-control" placeholder="BUSCAR ..." onkeyup="search_user(this);">
                            </div>

                            <div class="" id="div-respuesta-users" style="border: solid 1px #eee; margin-top: 10px; padding: 10px;">

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- inicio de modal de individuales -->
</div>


<!-- inicia detalle de grupos  -->
<div class="row" style="border: solid 1px #65b2e8; margin: 10px; padding: 10px;">
    <div class="col-lg-12 col-md-12">
        <b>A :</b>
        <?php   
            foreach ($to as $t) {
                ?>
                    <span class="badge rounded-pill" style="background-color: #65b2e8;">
                        <?= $t['nombre'] ?>
                        <?php 
                            if(count($enviados) == 0){
                                echo Html::a(' <i class="fas fa-trash zoom" style="color: #eee"></i>', [
                                'acciones',
                                'message_header_id' => $model->id,
                                'tipo_busqueda' => 'delete_group',
                                'word' => 'd',
                                'group_id' => $t['grupo_id']
                                ]);
                            }                         
                        ?>
                    </span>
                <?php
                }
                foreach ($toUsers as $tu) {
                ?>
                    <span class="badge rounded-pill" style="background-color: #ff9e18;">
                        <?= $tu['name'] ?>
                        <?php
                            if(count($enviados) == 0){ 
                                echo Html::a(' <i class="fas fa-trash zoom" style="color: #eee"></i>', [
                                'acciones',
                                'message_header_id' => $model->id,
                                'tipo_busqueda' => 'delete_para',
                                'word' => 'd',
                                'para_id' => $tu['id']
                                ]);
                            }
                        ?>
                    </span>
                <?php
                }              
        ?>
    </div>
</div>
<!-- fin detalle de grupos  -->


<!-- inicio de totales -->
<div class="row" style="border: solid 1px #9e28b5; margin: 10px; padding: 10px; color: #9e28b5">
    <div class="col-lg-12 col-md-12">
        <b>Total de remitentes:</b>
        <h1><?= count($para) ?></h1>
    </div>
</div>
<!-- fin de totales -->


<!-- inicio de boton de envío -->
<div class="row" style="border: solid 1px #898b8d; margin: 10px; padding: 10px; color: #9e28b5">
    <div class="col-lg-12 col-md-12">
        <?php

        if (count($totalEnviado) > 0) {
            echo Html::a(
                '<i class="fas fa-plane-departure"> Enviar notificación</i>',
                [
                    'acciones',
                    'message_header_id' => $model->id,
                    'tipo_busqueda' => 'enviar_para',
                    'word' => ''
                ],
                ['class' => 'btn btn-outline-success']
            );
        }else if(count($enviados) > 0){
            echo '<b style="color: green">
                    <img src="ISM/main/images/actions/feliz.gif" width="100px">
                    Su notificación fue enviada con éxito!!!
                </b>';
        }else{
            echo '<b style="color: #898b8d">Debe ingresar remitentes para poder enviar el mensaje</b>';
        }
        ?>
    </div>
</div>
<!-- fin de boton de envío -->

<script>
    var messageId = '<?= $model->id ?>';

    function search_group(obj) {

        word = obj.value;
        var url = '<?= \yii\helpers\Url::to(['acciones']) ?>';

        var params = {
            'message_header_id': messageId,
            'tipo_busqueda': 'group',
            'word': word
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function() {},
            success: function(resp) {
                //console.log(resp);
                $('#div-respuesta-grupos').html(resp);
            }
        });
    }


    function search_user(obj) {

        word = obj.value;
        var url = '<?= \yii\helpers\Url::to(['acciones']) ?>';

        var params = {
            'message_header_id': messageId,
            'tipo_busqueda': 'search_user',
            'word': word
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function() {},
            success: function(resp) {
                //console.log(resp);
                $('#div-respuesta-users').html(resp);
            }
        });
    }
</script>