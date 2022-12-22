<?php

use yii\helpers\Html;
use yii\grid\GridView;
?>

<div style="background-color: #65b2e8; color: white; height: 30px; margin-bottom: 5px; padding: 5px;">
    Objetivos de grado / curso
</div>

<!-- inicio de modal -->
<p>
    <!-- Button trigger modal -->
    <a href="" type="" class="" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        <i class="fas fa-plus-square" style="color: green;"> Nuevo objetivo</i>
    </a>

    <!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Agregando objetivo de grado/curso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="codigo">Código:</label>
                    <input type="text" name="codigo" id="codigo" class="form-control" require>
                </div>

                <div class="form-group">
                    <label class="form-label" for="codigo">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" require></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="addObjective()" data-bs-dismiss="modal">Grabar</button>
            </div>
        </div>
    </div>
</div>
</p>
<!-- fin de modal -->


<div class="table-responsive">
    <table class="table table-hover table-condensed">
        <thead>
            <tr>
                <td>OBJETIVO</td>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach ($detalle as $det) {
            ?>
                <tr>
                    <td>
                        <a href="#" onclick="addPcaDetail('<?= $det['code'] ?>')">
                            <b><?= $det['code'] . '</b> ' . $det['description'] ?>
                        </a>
                        
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function addObjective() {
        let codigo = $('#codigo').val();
        let descripcion = $('#descripcion').val();
        let cabeceraId = '<?= $cabeceraId ?>';
        
        let url = '<?= yii\helpers\Url::to(['ajax-add-objective-grado']) ?>';

        var params = {
            codigo      : codigo,
            descripcion : descripcion,
            cabeceraId  : cabeceraId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function() {
                showObjGrados();                
            }
        });
    }

    function addPcaDetail(code) {        
        let cabeceraId = '<?= $cabeceraId ?>';
        
        let url = '<?= yii\helpers\Url::to(['ajax-add-detail']) ?>';

        var params = {
            code      : code,            
            cabecera_id  : cabeceraId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function() {
                showObjGrados();     
                showReporte();           
            }
        });
    }
    
</script>