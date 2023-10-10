<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ViewActividadCrearSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reporte Notas Profesor';
$this->params['breadcrumbs'][] = $this->title;

// echo "<pre>";
// print_r($trimestre);
// die();

?>

<style>
    * {
        font-weight: bold;
    }

    td {
        padding: 8px;
        text-align: center;
        border: 1px solid black;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:first-child {
        background-color: #ab0a3d;
        color: #ff9e18;
        text-align: center;
    }

    tr:nth-child(2n) {
        background-color: #f5f5f5;
    }

    tr:first-child:hover {
        background-color: #ab0a3d;
    }

    tr:hover {
        background-color: #ccc;
    }

    td:first-child {
        font-weight: bold;
    }

    td:hover {
        transform: scale(1.05);
    }

    /* muestra de mejora manera en dispositivos moviles */

    @media (max-width: 768px) {

        .table th,
        .table td {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }
    }

    /* estilos para enlaces */

    .trimestre {
        margin: 5px 10px;
        font-size: 1.3rem;
    }

    .trimestre a {
        text-decoration: none;
        padding: 10px 50px;
        background-color: #ab0a3d;
        color: #ff9e18;
        border-radius: 10px;
        transition: background-color 0.3s;
    }

    .trimestre a:hover {
        background-color: blue;
    }

    /* al pasar el mouse el texto se expande y cambia a un color azul */
    /* td:hover {
        transform: scale(1.2);
        color: #0a1f8f;
    } */
</style>

<!-- para los botones de navegacion de los trimestres -->
<div style="display: flex; justify-content: space-evenly;margin-bottom: 10px">
    <?php foreach ($trimestres as $tri): ?>
        <div class="trimestre" style="margin-right: 10px;">
            <?php
            echo Html::a($tri['trimestre'], [
                'index1',
                'trimestre_defecto' => $tri['bloque_id'],
                'clase_id' => $clase->id
            ]);
            ?>
        </div>
    <?php endforeach; ?>
</div>


<div style="margin-bottom: 5px">
    <?php

    echo $this->render('_gauz', [
        'trimestre' => $trimestre,
        'notasFinales' => $notasProfesor->promediosFinales
    ]);
    ?>
</div>


<!-- fin para los botones de navegacion de los trimestres -->

<table class="table" border="1" cellpading="1" cellspacing="1">
    <tr>
        <td>#</td>
        <td>Estudiante</td>
        <?php
        foreach ($notasProfesor->cabecera as $key => $cabecera) {
            echo "<td>" . $cabecera['title'] . "</td>";
        }
        ?>
    </tr>

    <?php
    $i = 0;
    foreach ($notasProfesor->notas as $key => $estudiante) {
        $i++;
        echo "<tr>";
        echo "<td>" . $i . "</td>";
        echo "<td>" . $estudiante['estudiante'] . "</td>";

        foreach ($estudiante['notas'] as $notas) {
            echo "<td>" . $notas['nota'] . "</td>";
        }
        echo '</tr>';
    }

    ?>
</table>

<?php
function consulta_notas($arrayNotas)
{
}

?>