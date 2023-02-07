<?php

//echo '<pre>';
// print_r($dates);
//print_r($hours);

use backend\models\Lms;
use backend\models\LmsActividad;
use backend\models\LmsDocenteNee;
use backend\models\ScholarisActividad;

?>

<hr>

<div class="" style="margin-left: 30px; margin-bottom: 30px;">
    <?php
    //inicio de foreach principal de dias y fechas
    foreach ($dates as $date) {
    ?>

        <!-- inicio de dia y fecha -->
        <div class="row" style="margin-top: 30px;">
            <div class="col-lg-12 col-md-12" style="color: #0a1f8f;">
                <i class="fas fa-clock"></i>
                <?= $date['nombre'] . ' ' . $date['fecha'] ?>
                <hr>
            </div>
        </div>
        <!-- fin de dia y fecha -->

        <?php
        //inicio de horas
        foreach ($hours as $hour) {
            if ($date['numero'] == $hour['dia_numero']) {

                if ($hour['responsable_planificacion'] == $user) {
                    $color = '#ff9e18';
                } else {
                    $color = '#9e28b5';
                }

        ?>


                <div class="row" style="margin: 0px 50px 50px 50px; 
                                                            color: <?= $color ?>; 
                                                            border: solid 1px <?= $color ?>; 
                                                            ">

                    <nav aria-label="breadcrumb" style="background-color: <?= $color ?>;">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page" style="color: white;"><?= $hour['hora'] ?></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: white;"><?= $hour['curso'] ?></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: white;"><?= $hour['materia'] ?></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: white;"><?= $hour['responsable_planificacion'] ?></li>
                        </ol>
                    </nav>


                    <?php
                    $detail = get_planification_by_hour($hour['detalle_id'], $hour['clase_id']);


                    if ($detail) {
                        if ($detail['fecha'] == $date['fecha']) {
                            if ($detail['titulo'] == 'NO CONFIGURADO') {
                                echo '<div class="col">';
                                echo '<p>';
                                echo '<img src="../ISM/main/images/actions/trabajando.gif" width="70px" style="" class="">';
                                echo '<b>' . $detail['titulo'] . '</b>';
                                echo '</p>';
                                echo '</div>';
                            } else {
                                echo '<div class="col" style="overflow-y: scroll; height: 400px">';
                                echo '<p><b>TÍTULO: </b>' . $detail['titulo'] . '</p>';

                                echo '<div class="row">';
                                echo '<div class="col-lg-3">';

                                echo '</div>';
                                echo '</div>';

                                echo '<p><b>ACTIVIDADES</b>' . $detail['descripcion_actividades'] . '</p>';

                                echo '<p style="margin-top:10px">';
                                echo '<b>TAREAS / EVALUACIONES</b><br>';

                                $homeWork = LmsActividad::find()->where(['lms_id' => $detail['lms_id']])->all();
                                
                                foreach ($homeWork as $hw) {
                                    echo '<span class="badge rounded-pill" style="background-color: #0a1f8f; margin-right:3px;">
                                        <i class="fas fa-book-reader"> '.$hw->titulo.'</i>' 
                                        . '</span>';
                                }
                                echo '</p>';

                                echo '<p style="margin-top:10px">';
                                echo '<b>ADAPATACIONES CURRICULARES</b><br>';
                                $adaptaciones = LmsDocenteNee::find()->where(['lms_docente_id' => $detail['id']])->all();
                                echo '<ul style="font-size: 10px">';
                                foreach ($adaptaciones as $adaptacion) {
                                    echo '<li style="margin-bottom: 10px"><i class="fas fa-child"></i> '
                                        . '<b><u>' . $adaptacion->neeXClase->nee->student->first_name . ' ' . $adaptacion->neeXClase->nee->student->last_name . '</u></b>'
                                        . '<br>' . $adaptacion->adaptacion_curricular
                                        . '</li>';
                                }

                                echo '</ul>';

                                echo '</p>';
                                echo '</div>';
                            }
                        }
                    } else {
                        echo '<div class="col"><img src="../ISM/main/images/actions/no.gif" 
                                                        width="50px" style="" class="">Sin planificar</div>';
                    }

                    ?>
                </div>

        <?php
            }
        }
        //inde horas
        ?>


    <?php //fin de foreach principal de dias y fechas
    }
    ?>
</div>

<?php

function get_planification_by_hour($detalleHorarioId, $claseId)
{
    $con = Yii::$app->db;
    $query = "select 	ld.id 
                    ,ld.fecha 
                    ,lm.titulo 
                    ,lm.descripcion_actividades 
                    ,lm.id as lms_id
                    ,ld.observaciones
                from 	lms_docente ld
                    left join lms lm on lm.id = ld.lms_id 
                where 	ld.horario_detalle_id = $detalleHorarioId
                    and ld.clase_id = $claseId;";

    $res = $con->createCommand($query)->queryOne();
    return $res;
}


?>