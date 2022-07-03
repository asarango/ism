<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$sentencias = new backend\models\SentenciasSql();

$this->title = 'Duplicando actividad: '.$modelActividad->clase->ismAreaMateria->materia->nombre;
?>

<div class="scholaris-actividad-duplicar">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>                    
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?= 'Clase: ' . $modelActividad->clase->id . ' / ' .
        'Curso: ' . $modelActividad->clase->paralelo->course->name . ' "'.$modelActividad->clase->paralelo->name.' " /' .
        'Actividad: ' . $modelActividad->title . ' / ' .
        'Fecha: ' . $modelActividad->inicio; ?>
                    </small>

                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6"> 
                </div>
                <!-- fin de primeros botones -->

                <!--botones derecha-->
                <div class="col-lg-6 col-md-6" style="text-align: right;">                 
                    
                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>


            <!-- /****************************************************************************************************/  -->
            <!-- comienza cuerpo  -->
            <div class="row" style="margin-top: 15px; padding: 10px;">
                <?php
//        echo date('W', strtotime('2019-09-05'));
        $fechasAsi = toma_dias($modelActividad->inicio);
//        print_r($fechasAsi);


        foreach ($modelClases as $clase) {
            echo '<div class="table table-responsive">';
            echo '<table class="table table-hover table-striped table-bordered">';
            echo '<tr>';
            echo '<td colspan="4" align="center" bgcolor="CCCCCC">PARALELO: ' 
                . $clase['paralelo'] . ' | '
                . $clase['docente']
                . '</td>';
            echo '</tr>';

            $modelDias = toma_dias_horario($clase['id']);
            foreach ($modelDias as $dia) {



                echo '<tr>';
                echo '<td width="30%">' . $dia['dia'] . '</td>';


                for ($i = 0; $i < count($fechasAsi); $i++) {
                    if (get_numero_dia($fechasAsi[$i]) == $dia['num_dia']) {

                        echo '<td>' . $fechasAsi[$i] . '</td>';
                        echo '<td>' . $dia['num_dia'] . $dia['hora'] . '</td>';

                        $modelRes = $sentencias->get_actividad_duplicada($modelActividad->id, $clase['id']);

                        if ($modelRes) {
                            echo '<td>Actividad ya se encuentra duplicada</td>';
                        } else {
                            echo '<td>';
                            echo Html::a('clic aquí...', ['duplicaraqui',
                                "actividadId" => $modelActividad->id,
                                'clase' => $clase['id'],
                                'inicio' => $fechasAsi[$i],
                                'hora' => $dia['id']
                            ]);
                            echo '</td>';
                        }
                    }
                }





                echo '</tr>';
            }

            echo '</table>';
            echo '</div>';
        }

        ?>
            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>

<?php

function toma_dias($fecha) {

    $arregloFechas = array();

    $fechaComoEntero = strtotime($fecha);

    $year = date("Y", $fechaComoEntero);
    $month = date("m", $fechaComoEntero);
    $day = date("d", $fechaComoEntero);

# Obtenemos el día de la semana de la fecha dada
    $diaSemana = date("w", mktime(0, 0, 0, $month, $day, $year));

# el 0 equivale al domingo...
    if ($diaSemana == 0)
        $diaSemana = 7;


    for ($i = 1; $i <= 5; $i++) {
        array_push($arregloFechas, date("Y-m-d", mktime(0, 0, 0, $month, $day - $diaSemana + $i, $year)));
    }

    return $arregloFechas;
}

function toma_dias_horario($clase) {
    $con = Yii::$app->db;
    $query = "select 	dia.nombre as dia
                ,dia.numero as num_dia
		,ho.nombre as hora
		,ho.id
from 	scholaris_horariov2_horario h
		inner join scholaris_horariov2_detalle d on d.id = h.detalle_id
		inner join scholaris_horariov2_dia dia on dia.id = d.dia_id
		inner join scholaris_horariov2_hora ho on ho.id = d.hora_id
where	h.clase_id = $clase
order by dia.numero, ho.numero;";
    $res = $con->createCommand($query)->queryAll();
    return $res;
}

function get_numero_dia($fecha) {

    $fechaComoEntero = strtotime($fecha);

    $year = date("Y", $fechaComoEntero);
    $month = date("m", $fechaComoEntero);
    $day = date("d", $fechaComoEntero);

    $diaSemana = date("w", mktime(0, 0, 0, $month, $day, $year));

    return $diaSemana;
}
?>