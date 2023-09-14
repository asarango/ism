<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>2. Componentes de evaluación interna y externa del Programa del Diploma que se deben completar
                durante el curso</b></h6>
    </div>
    <div class="card-body">
        <div class="table table-responsive" style="font-size: 10px;">
            <table class="table table-condensed table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle">EVALUACIÓN</th>
                        <th class="text-center align-middle">ACTIVIDAD</th>
                        <th class="text-center align-middle">FECHA</th>
                        <th class="text-center align-middle">REVISIÓN DE CUMPLIMIENTO</th>
                        <th class="text-center align-middle" colspan="2">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($componentes as $com) {

                        ?>
                        <tr>
                            <td>
                                <?= $com->evaluacion ?>
                            </td>
                            <td>
                                <?= $com->actividad ?>
                            </td>
                            <td>
                                <?= $com->fecha ?>
                            </td>
                            <td>
                                <?= $com->revision_cumplimiento ?>
                            </td>
                            <td class="text-center">
                                <!-- Button trigger modal para actualizar componente -->
                                <a type="button" class="" data-bs-toggle="modal" data-bs-target="#updatecomp"
                                    onclick="get_componente(<?= $com->id ?>)">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Modal para actualizar componente-->
                                <div class="modal fade" id="updatecomp" data-bs-backdrop="static" data-bs-keyboard="false"
                                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Actualizando componente
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <?= Html::beginForm(['accion-componente'], 'post') ?>
                                            <div class="modal-body">

                                                <div class="modal-body">

                                                    <input type="hidden" name="id" id="comp-id">
                                                    <input type="hidden" name="field" id="" value="update">

                                                    <div class="form-group" id="com-evaluacion">

                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label" for="Actividad">Actividad</label>
                                                        <textarea class="form-control" name="com-actividad"
                                                            id="com-actividad" cols="10"></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label" for="Fecha">Fecha</label>
                                                        <input type="date" name="com-fecha" id="com-fecha"
                                                            class="form-control">
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                                <?= Html::submitButton('Actualizar', ['class' => 'btn btn-outline-primary']) ?>
                                            </div>
                                            <?= Html::endForm() ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fin Modal para actualizar componente -->


                            </td>

                            <td class="text-center">
                                <?=

                                    Html::a(
                                        '<i class="fas fa-trash" style="color: #ab0a3d"></i>',
                                        ['accion-componente', 'id' => $com->id],
                                        [
                                            //'class' => 'btn btn-lg btn-primary',
                                            'data' => [
                                                'method' => 'post',
                                                'params' => ['id' => $com->id, 'field' => 'delete'],
                                                // <- extra level
                                            ],
                                        ]
                                    );
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <!-- modal para ingreso -->
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop5">
                Añadir
            </button>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop5" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" style="background-color: #fff;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Añadir evaluación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?= Html::beginForm(['componentes'], 'post') ?>
                        <div class="modal-body">

                            <input type="hidden" name="cabecera_id" value="<?= $cabecera_id ?>">

                            <div class="form-group">
                                <label class="form-label" for="Evaluacion">Evaluación</label>
                                <select name="evaluacion" class="form-control">
                                    <option value="INTERNA">INTERNA</option>
                                    <option value="EXTERNA">EXTERNA</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="Actividad">Actividad</label>
                                <textarea class="form-control" name="actividad" cols="10"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="Fecha">Fecha</label>
                                <input type="date" name="fecha" class="form-control">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cerrar</button>
                            <?= Html::submitButton('Agregar', ['class' => 'btn btn-outline-primary']) ?>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                </div>
            </div>

            <!-- FIN modal para ingreso -->
        </div>
    </div>

</div>

<script>
    function get_componente(id) {
        var url = '<?= Url::to(['accion-componente']) ?>';
        params = {
            id: id,
            field: 'consultar'
        }
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () { },
            success: function (response) {
                let content = JSON.parse(response);
                let evaluacion = content.evaluacion;
                let htmEvaluacion;
                htmEvaluacion = '';

                $('#comp-id').val(content.id);

                htmEvaluacion = `<div clas="form-group">
                    <label for="evaluacion" class="form-label">Evaluación</label>
                    <select name="evaluacion" class="form-control">
                        <option value="${evaluacion}">${evaluacion}</option>
                        <option value="INTERNA">INTERNA</option>
                        <option value="EXTERNA">EXTERNA</option>
                    </select>
                    </div>`;

                $('#com-evaluacion').html(htmEvaluacion);
                $('#com-actividad').val(content.actividad);
                $('#com-fecha').val(content.fecha);
                console.log(content);
            }
        });
    }
</script>

