<?php
use yii\helpers\Html;
use yii\helpers\Url;

// echo "<pre>";
// print_r($unidades);
// die();
?>

<div>
    <h5 id="datos"><b>Datos Informativos</b></h5>
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