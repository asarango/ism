<?php
use yii\helpers\Html;
use yii\helpers\Url;

// echo "<pre>";
// print_r($vertical);
// die();
?>

<div class="col-lg-12 col-md-12 col-sm-12">
    <h5 id="datos" style="margin-top: 1rem;"><b>DATOS INFORMATIVOS</b></h5>
    <table width="100%" class="table table-secondary table-bordered; table table-bordered" style="font-size:10px;">
        <tbody>
            <tr>
                <th width="150px" class="fondo-campos">PROFESOR(ES):</th>
                <td colspan="3" class="estilo-link-modal">
                    <?php buscar_respuesta($vertical, 'PROFESORES'); ?>
                </td>
                <th width="300px" colspan="2" class="fondo-campos">GRUPO DE ASIGNATURAS, CURSO Y NIVEL:
                </th>
                <td colspan="2" class="estilo-link-modal">
                    <?php buscar_respuesta($vertical, 'GRUPO_ASIGNATURA'); ?>
                </td>
                <th class="fondo-campos">AÑO DEL PD:</th>
                <td class="estilo-link-modal">
                    <?php buscar_respuesta($vertical, 'ANIO'); ?>
                </td>
            </tr>
            <tr>
                <th class="fondo-campos">CARGA HORARIA SEMANAL:</th>
                <td class="estilo-link-modal">
                    <?php buscar_respuesta($vertical, 'CARGA_HORARIA'); ?>
                </td>
                <th class="fondo-campos">NRO. SEMANAS DE TRABAJO:</th>
                <td class="estilo-link-modal">
                    <?php buscar_respuesta($vertical, 'NUM_SEMANAS'); ?>
                </td>
                <th class="fondo-campos">TOTAL DE SEMANAS DE CLASE:</th>
                <td class="estilo-link-modal">
                    <?php buscar_respuesta($vertical, 'TOTAL_SEMANAS'); ?>
                </td>
                <th class="fondo-campos">EVALUACIÓN DEL APRENDIZAJE E IMPREVISTOS:</th>
                <td class="estilo-link-modal">
                    <?php buscar_respuesta($vertical, 'IMPREVISTOS'); ?>
                </td>
                <th class="fondo-campos">CANTIDAD DE UNIDADES:</th>
                <td class="estilo-link-modal">
                    <?php buscar_respuesta($vertical, 'CANT_UNIDADES'); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- fin datos informativos -->
</div>

<?php
function buscar_respuesta($modelVertical, $campo)
{
    foreach ($modelVertical as $plan) {
        if ($plan->opcion_descripcion == $campo) {
            ?>
            <!-- Button trigger modal -->
            <a data-bs-toggle="modal" data-bs-target="#modal<?= $plan->id ?>">
                <?php
                echo $plan->contenido;
                ?>
            </a>

            <!-- Modal -->
            <div class="modal fade" id="modal<?= $plan->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modificando Campo</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?= Html::beginForm(['update-field'], 'post') ?>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $plan->id ?>">
                            <div class="form-group">
                                <label class="form-label"><b>Contenido</b></label>
                                <textarea name="contenido" class="form-control"><?= $plan->contenido ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary'] ) ?>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                </div>
            </div>
            <?php

        }
    }
}
?>