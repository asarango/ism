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
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row " style="margin-top: 10px;">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h4>
                </div>
                <div class="col-lg-7 col-md-7">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                    <p>(
                        <?=
                            ' <small>' . $modelActividad->clase->ismAreaMateria->materia->nombre .
                            ' - ' .
                            'Clase #:' . $modelActividad->clase->id .
                            ' - ' .
                            $modelActividad->clase->paralelo->course->name . ' - ' . $modelActividad->clase->paralelo->name . ' / ' .
                            //$modelActividad->clase->paralelo->name . ' / ' .
                            $modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name . ' / ' .
                            'Es calificado: ' . $modelActividad->calificado . ' / ' .
                            'Tipo de actividad: ' . $modelActividad->tipo_calificacion .
                            '</small>';
                        ?>
                        )
                    </p>
                </div>

                <!-- botones de la derecha fila 1 -->
                <div class="col-lg-4 col-md-4" style="text-align: right;">

                    <?php echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fas fa-chart-line"></i>  DETALLE - ACTIVIDAD</span>',
                        ['actividad', "actividad" => $modelActividad->id],
                        ['class' => '', 'title' => 'DETALLE -ACTIVIDAD']
                    ); ?>
                    |
                    <?php echo Html::a(
                        '<span class="badge rounded-pill bg-cuarto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Calificación Detallada</span>',
                        ['calificacion/index1', "actividad_id" => $modelActividad->id],
                        ['class' => '', 'title' => 'Calificación Detallada']
                    ); ?>
                </div>
                <hr>
            </div>

            <!-- comienza cuerpo  -->
            <div class="table table-responsive">
                <font size="2">
                    <table class="table table-condensed table-striped table-hover table-bordered">
                        <tr>
                            <th>#</th>
                            <?php $cont = 1; ?>
                            <th>Estudiantes</th>
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
                            echo '<td>' . $grupo['last_name'] . ' ' . $grupo['first_name'] . ' ' . $grupo['middle_name'] . '</td>';

                            foreach ($modelCalificaciones as $notas) {
                                if ($grupo['alumno_id'] == $notas->idalumno) {
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

            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.input').keyup(function (e) {
            if (e.keyCode == 38)//38 para arriba
                mover(e, -1);
            if (e.keyCode == 40)//40 para abajo
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
                url,
                { nota: nota, notaId: id, grupo_id: grupoId },
                function (result) {
                    $("#res").html(result);
                }
            );
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