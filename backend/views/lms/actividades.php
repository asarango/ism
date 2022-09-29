<?php

use yii\helpers\Url;
use yii\helpers\Html;

//$listaTipoActividadNac = \yii\helpers\ArrayHelper::map($tipoActividadNac, 'id', 'nombre_nacional');
//$listaTipoActividadPai = \yii\helpers\ArrayHelper::map($tipoActividadPai, 'id', 'nombre_nacional');
?>
<ul style="display: flex">                

    <b style="color: #ab0a3d">
        Actividades configuradas
    </b>
    <!-- inicio para crear actividades-->                                                
    <li style="margin-left: 10px">
        <!-- Button trigger modal -->
        <a type="button" class="" data-bs-toggle="modal" data-bs-target="#nueva-actividad">
            <i class="fas fa-building" style="color: #ff9e18"> Agregar actividad</i>
        </a>


        <!-- Modal -->
        <div class="modal fade" id="nueva-actividad" data-bs-backdrop="static" data-bs-keyboard="false" 
             tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Nueva actividad:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <?= Html::beginForm(['acciones'], 'post', ['enctype' => 'multipart/form-data']) ?>
                    <div class="modal-body">


                        <input type="hidden" name="clase_id" value="<?= $claseId ?>">
                        <input type="hidden" name="lms_id" value="<?= $lms->id ?>">
                        <input type="hidden" name="campo" value="actividad">
                        <input type="hidden" name="nombre_semana" value="<?= $nombreSemana ?>">
                        <input type="hidden" name="semana_numero" value="<?= $numeroSemana ?>">

                        <div class="form-group">
                            <label for="tipoActividad" class="form-label">TIPO ACTIVIDAD</label>
                            <select name="tipo_actividad" required="" 
                                    class="form-control"
                                    onchange="selecciona_tipo_insumo(this)">
                                <option>Seleccione tipo actividad...</option>
                                <option value="P">PAI</option>
                                <option value="N">Nacional</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-top: 5px">
                            <label for="tipoInsumo" class="form-label">INSUMO</label>

                            <select name="tipo_actividad_nac_id" 
                                    class="form-control"
                                    id="select-nacional" 
                                    style="display: none">
                                <option value="0">Seleccione Insumo</option>
                                <?php
                                foreach ($tipoActividadNac as $nac) {
                                    echo '<option value="' . $nac->id . '">' . $nac->nombre_nacional . '</option>';
                                }
                                ?>
                            </select>                                                        

                            <select name="tipo_actividad_pai_id" 
                                    class="form-control"
                                    id="select-pai" 
                                    style="display: none">
                                <option value="0">Seleccione Insumo</option>
                                <?php
                                foreach ($tipoActividadPai as $pai) {
                                    echo '<option value="' . $pai->id . '">' . $pai->nombre_nacional . '</option>';
                                }
                                ?>
                            </select>
                        </div>       



                        <div class="form-group" style="margin-top: 10px">
                            <label for="titulo" class="form-label">TÍTULO</label>
                            <input type="titulo" name="titulo" class="form-control"><!-- comment -->
                        </div>

                        <div class="form-group" style="margin-top: 10px">
                            <label for="descripcion" class="form-label">DESCRIPCIÓN</label>
                            <textarea name="descripcion"></textarea>

                            <script>
                                CKEDITOR.replace('descripcion');
                            </script>
                        </div>

                        <div class="form-group" style="margin-top: 10px">
                            <label for="tarea" class="form-label">TAREA</label>
                            <textarea name="tarea"></textarea>

                            <script>
                                CKEDITOR.replace('tarea');
                            </script>
                        </div>


                        <div class="form-group" style="margin-top: 10px">
                            <label for="esCalificado" class="form-label">ES CALIFICADO?</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="es_calificado" type="checkbox" id="flexSwitchCheckChecked">
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 10px">
                            <label for="esPublicado" class="form-label">ES PUBLICADO?</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="es_publicado" type="checkbox" id="flexSwitchCheckChecked">
                            </div>
                        </div>


                        <div class="form-group" style="margin-top: 10px">
                            <label for="tarea" class="form-label">MATERIAL DE APOYO: (Opcional)</label>
                            <textarea name="material_apoyo"></textarea>

                            <script>
                                CKEDITOR.replace('material_apoyo');
                            </script>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Grabar</button>
                    </div>
                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
    </li>
    <!--fin para crear actividades -->




</ul>   




<script>
    function selecciona_tipo_insumo(obj) {
        var tipoActividad = obj.value;

        if (tipoActividad == 'N') {
            $('#select-nacional').show();
            $('#select-pai').hide();
        } else if (tipoActividad == 'P') {
            $('#select-pai').show();
            $('#select-naciona').hide();
        }
    }
</script>