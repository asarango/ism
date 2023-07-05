<?php
use backend\models\helpers\HelperGeneral;

//calcual la edad
$objHelperGeneral = new HelperGeneral();
$edad = $objHelperGeneral->obtener_edad_segun_fecha($modelEstudiante->birth_date);
//fin calculo edad
?>
<h6 style="color:blueviolet"><b>Datos Estudiante</b></h6>

<table class="table table-responsive">
    <tr>
        <td><b>No. Caso:</b></td>
        <td>
            <?= $model->caso->numero_caso ?>
        </td>
    </tr>
    <tr>
        <td><b>Alumno: </b></td>
        <td>
            <?= $modelEstudiante->first_name . ' ' . $modelEstudiante->middle_name . ' ' . $modelEstudiante->last_name ?>
        </td>
    </tr>
    <tr>
        <td><b>Fecha Nacimiento: </b></td>
        <td>
            <?= $modelEstudiante->birth_date . ' (' . $edad . ' años)' ?>
        </td>
    </tr>
    <tr>
        <td><b>Representante: </b></td>
        <td>
            <?= $modelRepresentante->name ?>
        </td>
    </tr>
    <tr>
        <td><b>Email Representante: </b></td>
        <td>
            <?= $modelRepresentante->email ?>
        </td>
    </tr>
    <tr>
        <td><b>Telèfono: </b></td>
        <td>
            <?= $modelRepresentante->phone . ' - ' . $modelRepresentante->mobile . ' - ' . $modelRepresentante->x_work_phone ?>
        </td>
    </tr>
</table>