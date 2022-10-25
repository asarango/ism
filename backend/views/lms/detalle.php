
<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

?>



<div class="row" style="margin-left: 5px;">
    <div class="col-lg-12 col-md-12">

        <div class="row" style="background-color: white; padding: 10px 10px 10px 0px">
            
            <b style="color: #ab0a3d">
                <!-- Button trigger modal -->
                <a type="button" class="" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="fas fa-cogs" style="color: #ff9e18"> Cambiar tema</i>
                </a>
                <h4>TEMA: <small><?= $modelDetalleActivo->titulo ?></small></h4>                
            </b>
            <p><?= $modelDetalleActivo->hora_numero . '° Hora' ?></p>            

            <!--para cambiar el título de la hora a tratar tratar-->

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Cambiar tema a tratar en la hora:</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" id="titulo" class="form-control" value="<?= $modelDetalleActivo->titulo ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="grabar('titulo', <?= $modelDetalleActivo->id ?>)">Grabar</button>
                            </div>
                        </div>
                    </div>
                </div>

            <!--fin para cambiar el título de la hora a tratar tratar-->
            <hr /><!-- comment -->

            <!-- inicio cajas de texto para inyectar -->
            <div class="form-group">
                <label for="conceptos"></label>
                <textarea name="editorconceptos" id="id-conceptos"><?= $modelDetalleActivo->conceptos ?></textarea>
                 <script>
                    var editorconceptos = CKEDITOR.replace('editorconceptos');
                </script>
                <br>                               
            </div>

            <div class="form-group">
                <label for="indicaciones">ESTRATEGIA METODOLÓGICA:</label>
                <textarea name="editor1" id="id-indicaciones"><?= $modelDetalleActivo->indicaciones ?></textarea>
                 <script>
                    var editor1 = CKEDITOR.replace('editor1');
                </script>
                <br>                               
            </div>

            <div class="form-group">
                <label for="descripcion-actividades">DESCRIPCIÓN DE ACTIVIDADES:</label>
                <textarea name="editordescripcionactividades" id="id-desc-actividades"><?= $modelDetalleActivo->descripcion_actividades ?></textarea>
                 <script>
                    var editordescripcionactividades = CKEDITOR.replace('editordescripcionactividades');
                </script>
                <br>                               
            </div>

            <div class="form-group">
                <label for="tarea">TAREA:</label>
                <textarea name="editortarea" id="id-tarea"><?= $modelDetalleActivo->tarea ?></textarea>
                 <script>
                    var editortarea = CKEDITOR.replace('editortarea');
                </script>
                <br>                               
            </div>

            <div class="form-group">
                <label for="tarea">RECURSOS PARA MAESTROS:</label>
                <textarea name="editorrecursos" id="id-recursos"><?= $modelDetalleActivo->recursos ?></textarea>
                 <script>
                    var editorrecursos = CKEDITOR.replace('editorrecursos');
                </script>
                <br>                               
            </div>

            <button type="button" 
                onclick="grabar_text_indicaciones('indicaciones', <?= $modelDetalleActivo->id ?>);"
                class="btn btn-outline-primary" style="width: 20%; margin-left: 10px;">Grabar
            </button>

            <!-- fin cajas de texto para inyectar -->
            
        </div>
        <!--fin de cuadro de actualizar indicaciones-->


        <!--inicia menu de opciones de actividad-->
        <div class="row" style="background-color: white; margin-top: 10px; padding-top: 5px">
            <?php  
                
                echo $this->render('actividades',[
                    'lms' => $modelDetalleActivo,
                    'tipoActividadNac' => $tipoActividadNac,
                    'tipoActividadPai' => $tipoActividadPai,
                    'claseId' => $claseId,
                    'nombreSemana' => $nombreSemana,
                    'numeroSemana' => $modelDetalleActivo->semana_numero,
                    'seccion' => $seccion
            ]); ?>
        </div>
        
        
        
        
        
        <!--inicia menu de opciones de actividad-->

        <div class="row" style="margin-top: 5px; overflow-y: scroll; height: 450px; padding: 10px;">            
            <?php
            
                echo $this->render('lista-actividades',[
                    'actividades' => $actividades,
                    'claseId' => $claseId,
                    'nombreSemana' => $nombreSemana,
                    'numeroSemana' => $modelDetalleActivo->semana_numero,
                    'seccion' => $seccion
                ]);
            ?>
        </div>


    </div>

    <!--<div class="col-lg-4 col-md-4">dfsdf</div>-->
</div>


<script>

    function grabar_text_indicaciones(campoId, lmsId) {        
        var valor1              = editor1.getData();
        var conceptos           = editorconceptos.getData();
        var descActividades     = editordescripcionactividades.getData();
        var tarea               = editortarea.getData();
        var recursos            = editorrecursos.getData();

        var url = '<?= Url::to(['acciones']) ?>';
        //alert(campoId);
        var params = {
            lms_id: lmsId,
            campo: campoId,
            valor: valor1,
            conceptos: conceptos,
            descripcion_actividades: descActividades,
            tarea: tarea,
            recursos: recursos
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                alert('Guardado satisfactoriamente!!!');
                muestra_detalle(lmsId);
            }
        });
    }

    function grabar(campoId, lmsId) {
//        alert(editor1.getData());
        var claseId = '<?= $claseId ?>';
        var valor = $('#' + campoId).val();
        var url = '<?= Url::to(['acciones']) ?>';
        var params = {
            lms_id: lmsId,
            campo: campoId,
            valor: valor
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
//                $('#div-detalle').html(response);
                muestra_detalle(lmsId, claseId);
            }
        });

    }
</script>