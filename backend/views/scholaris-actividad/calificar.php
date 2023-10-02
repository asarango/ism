<?php

use app\models\ScholarisActividad;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actividad #: ' . $modelActividad->id . ' | ' . $modelActividad->title;

// echo "<pre>";
// print_r($modelActividad);
// die();


?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row " style="margin-top: 10px;">
                <div class="col-lg-1 col-md-1 col-ms-1 col-xs-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h4>
                </div>
                <?php
                if ($modelActividad->calificado == true) {
                    $calificado = '<i class="fas fa-check-square fa-md" style="color: #3bb073;"></i>';
                } else {
                    $calificado = '<i class="fas fa-times-circle fa-lg" style="color: #c1331a;"></i>';
                }
                ?>
                <div class="col-lg-9 col-md-9">
                    <h5>
                        <?= Html::encode($this->title) ?>
                    </h5>
                    <p>(
                        <?= '<small>' . $modelActividad->clase->ismAreaMateria->materia->nombre .
                            ' - ' .
                            'Clase #:' . $modelActividad->clase->id .
                            ' - ' .
                            $modelActividad->clase->paralelo->course->name . ' - ' . $modelActividad->clase->paralelo->name . ' / ' .
                            //$modelActividad->clase->paralelo->name . ' / ' .
                            $modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name . ' / ' .
                            'Es calificado: ' . $calificado . ' / ' .
                            'Tipo de actividad: ' . getActividad($modelActividad->tipo_actividad_id) . '</small>';
                        ?>

                        )
                    </p>
                </div>

                <!-- boton de la derecha fila 1 -->
                <div class="col-lg-2 col-md-2 col-ms-2 col-xs-2" style="text-align: right;">

                    <?php echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #800080"><i class="fas fa-chart-line"></i> Detalle - Actividad</span>',
                        ['actividad', "actividad" => $modelActividad->id],
                        ['class' => '', 'title' => ' Detalle - Actividad']
                    ); ?>
                </div>
                <hr>
            </div>

            <!-- comienza cuerpo  -->

            <div class="row">

                <div class="col-lg-4 col-md-4" style="background-color: #ccc">
                    <div id="div-estadistica"></div>
                </div>


                <div class="col-lg-8 col-md-8" style="overflow-y: scroll; height: 400px;">
                    <div class="table table-responsive">
                        <font size="2">
                            <table class="table table-condensed table-striped table-hover table-bordered">
                                <tr>
                                    <th>#</th>
                                    <?php $cont = 1; ?>
                                    <th>Estudiantes</th>
                                    <th class="text-center"><b></b></th>
                                    <?php
                                    if (isset($modelCriterios)) {
                                        foreach ($modelCriterios as $criterio) {
                                            $modelCriterio = \backend\models\IsmCriterio::findOne($criterio['id_criterio']);
                                            echo '<th class="text-center">' . $modelCriterio->nombre . '</th>';
                                        }
                                    } else {
                                        echo '<th class="text-center">NOTA</th>';
                                    }
                                    ?>
                                    <th class="text-center">TOTAL ARCHIVOS</th>
                                </tr>

                                <?php
                                foreach ($modelGrupo as $grupo) {
                                    $grupoId = $grupo['grupo_id'];
                                    $modelArchSubidos = \backend\models\ScholarisActividadDeber::find()
                                        ->where([
                                            //                                      'alumno_id' => $grupo->estudiante_id,
                                            'alumno_id' => $grupo['alumno_id'],
                                            'actividad_id' => $modelActividad->id
                                        ])
                                        ->all();

                                    echo '<tr>';
                                    echo '<td>' . $cont . '</td>';
                                    $cont = $cont + 1; //contador de numeros de alumnos
                                    echo '<td>';
                                    echo Html::a($grupo['last_name'] . ' ' . $grupo['first_name'] . ' ' . $grupo['middle_name'], [
                                        'calificacion/index1',
                                        'actividad_id' => $modelActividad->id,
                                        'grupo_id' => $grupoId
                                    ]);
                                    echo '</td>';


                                    foreach ($modelCalificaciones as $notas) {
                                        if ($grupo['alumno_id'] == $notas->idalumno) {

                                            ////para colocar semaforo de calificacion menor de 70
                                            echo '<td align="center">';
                                            echo semaforo_menor_70($notas->calificacion);
                                            ////para colocar semaforo de calificacion menor de 70

                                            echo '</td>';

                                            if ($estado == 'abierto') {
                                                /// SI EL BLOQUE ESTA ABIERTO
                                                /// REALIZA EL INPUT
                                                echo '<td align="center" width="10%">' //NOTAS
                                                    . '<input   class="input text-white" type="text " id="al' . $notas->id . '" value="' . $notas->calificacion .
                                                    '" onchange="cambiarNota(' . $notas->id . ',' . $grupoId . ');" onkeypress="return NumCheck(event, this)" '
                                                    . 'style="width : 60px; text-align: center; border: none;  background-color: #929292; "; >'
                                                    . '</td>'; //FIN NOTAS

                                            } else {
                                                /// SI EL BLOQUE ES CERRADO SOLO MUESTRA LA CALIFICACION SIN OPCION A MODIFICAR
                                                echo '<td>' . $notas->calificacion . '</td>';
                                            }

                                            //                                   echo '<td>' . substr($notas->observacion, 0, 20).' ... ' . '</td>';
                                        }
                                    }
                                    echo '<td class="text-center">' . count($modelArchSubidos) . ' archivos subidos</td>';

                                    echo '</tr>';
                                }
                                ?>
                            </table>
                        </font>
                    </div>
                </div>
            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.input').keyup(function(e) {
            if (e.keyCode == 38) //38 para arriba
                mover(e, -1);
            if (e.keyCode == 40) //40 para abajo
                mover(e, 1);
        });
    });


    function mover(event, to) {
        let list = $('input');
        let index = list.index($(event.target));
        index = Math.max(0, index + to);
        list.eq(index).focus();
    }
</script>

<script>
    document.getElementById("calificar").focus();

    function cambiarNota(id, grupoId) {
        var idx = '#al' + id;
        var nota = $(idx).val();

        var minima = <?= $modelMinimo->valor ?>;
        var maxima = <?= $modelMaximo->valor ?>;
        console.log(id);

        if (nota == '' || (nota >= minima && nota <= maxima)) {
            var url = "<?= Url::to(['registra']) ?>";
            $.post(
                url, {
                    nota: nota,
                    notaId: id,
                    grupo_id: grupoId
                },
                function(result) {
                    $("#res").html(result);                    
                }
            );
            show_estadisticas();
        } else {
            alert("La calificación debe estar ente " + minima + " y " + maxima);
            alert($(idx).val(''));
            $(idx).focus();
            //location.reload();
        }
    }

    function NumCheck(e, field) {
        key = e.keyCode ? e.keyCode : e.which

        // backspace
        if (key == 8)
            return true

        // 0-9
        if (key > 47 && key < 58) {
            if (field.value == "")
                return true

            regexp = /.[0-9]{2}$/
            return !(regexp.test(field.value))
        }

        // .

        if (key == 46) {
            if (field.value == "")
                return false
            regexp = /^[0-9]+$/
            return regexp.test(field.value)
        }
        // other key

        if (key == 9)
            return true

        return false
    }
</script>

<!-- inicio para presentar estadisticas -->
<script>
    show_estadisticas();

    function show_estadisticas() {
        let actividadId = '<?= $modelActividad->id ?>';

        var url = "<?= Url::to(['estadisticas']) ?>";

        $.ajax({
            url: url,
            data: {
                actividad_id: actividadId
            },
            method: 'get',
            success: function(result) {
                $("#div-estadistica").html(result);
            }
        })
    }
</script>
<!-- fin para presentar estadisticas -->

<!-- fin de funcion scrip -->



<!-- funciones php -->
<?php
function getActividad($tipo_actividad_id)
{
    $actividadTextos = array(
        1 => 'Lecciones de revisión(1)',
        3 => 'Pruebas de base estructuradas(3)',
        4 => 'Tareas en clase(4)',
        5 => 'Proyectos y/o investigaciones(5)',
        6 => 'Proyectos y/o investigaciones(6)',
        7 => 'Exposiciones foros(7)',
        9 => 'Talleres(9)',
        2 => 'Desarrollo de productos(2)',
        8 => 'Se aplica metodología(8)',
        10 => 'Evaluación de base estructurada(10)'
    );

    if (isset($actividadTextos[$tipo_actividad_id])) {
        return $actividadTextos[$tipo_actividad_id];
    } else {
        return 'Actividad desconocida';
    }
}
?>

<?php
function obtenerIcono($calificacion)
{
    if ($calificacion < 70) {
        return '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mood-nervous" width="40" height="40" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
            <path d="M9 10h.01" />
            <path d="M15 10h.01" />
            <path d="M8 16l2 -2l2 2l2 -2l2 2" />
        </svg>';
    } elseif ($calificacion >= 71 && $calificacion <= 85) {
        return '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mood-smile-beam" width="40" height="40" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 21a9 9 0 1 1 0 -18a9 9 0 0 1 0 18z" />
            <path d="M10 10c-.5 -1 -2.5 -1 -3 0" />
            <path d="M17 10c-.5 -1 -2.5 -1 -3 0" />
            <path d="M14.5 15a3.5 3.5 0 0 1 -5 0" />
        </svg>';
    } else {
        return '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mood-check" width="40" height="40" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M20.925 13.163a8.998 8.998 0 0 0 -8.925 -10.163a9 9 0 0 0 0 18" />
            <path d="M9 10h.01" />
            <path d="M15 10h.01" />
            <path d="M9.5 15c.658 .64 1.56 1 2.5 1s1.842 -.36 2.5 -1" />
            <path d="M15 19l2 2l4 -4" />
        </svg>';
    }
}


function semaforo_menor_70($nota)
{

    if ($nota < 70) {
        $estado = '<i class="fas fa-spinner fa-spin" style="color: red;"></i>';
    } elseif ($nota >= 70 && $nota < 95) {
        $estado = '<i class="fas fa-circle" style="color: blue;"></i>';
    } else {
        $estado = '<i class="fas fa-smile-wink" style="color: green;"></i>';
    }
    return $estado;
}

?>