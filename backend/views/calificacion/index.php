<?php

use backend\models\OpStudent;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actividad #: ' . $modelActividad->id . ' | ' . $modelActividad->title;

// echo "<pre>";
// print_r($group);
// die();

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row " style="margin-top: 10px;">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" style=""
                            class="img-thumbnail"></h4>
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
                        <?=
                            ' <small>' . $modelActividad->clase->ismAreaMateria->materia->nombre .
                            ' - ' .
                            'Clase #:' . $modelActividad->clase->id .
                            ' - ' .
                            $modelActividad->clase->paralelo->course->name . ' - ' . $modelActividad->clase->paralelo->name . ' / ' .
                            $modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name . ' / ' .
                            'Es calificado: ' . $calificado . ' / ' .
                            'Tipo de actividad: ' . $modelActividad->tipo_calificacion .
                            '</small>';
                        ?>
                        )
                    </p>

                </div>
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <?php echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #800080"><i class="fas fa-chart-line"></i> Detalle - Actividad</span>',
                        ['scholaris-actividad/calificar', "id" => $modelActividad->id],
                        ['class' => '', 'title' => ' Detalle - Actividad']
                    ); ?>
                    <!-- |
                    <?php echo Html::a(
                        '<span class="badge rounded-pill bg-cuarto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Calificación Detallada</span>',
                        ['calificacion/index1', "actividad_id" => $modelActividad->id],
                        ['class' => '', 'title' => '']
                    ); ?> -->


                </div>
                <hr>
            </div>
            <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->

            <!-- comienza cuerpo  -->
            <div class="row" style="margin-top: 0px;">

                <div class="col-lg-12 col-md-12 text-quinto" style="text-align: center;">
                    <?=
                        '<h5>' . $group->alumno->last_name . " " . $group->alumno->first_name . " " .
                        $group->alumno->middle_name . '</h5>';
                    ?>
                </div>
            </div>
            <div class="row" style="margin-top: 0px;">

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

    function cambiarNota(id) {
        var idx = '#al' + id;
        var nota = $(idx).val();

        var minima = <?= $modelMinimo->valor ?>;
        var maxima = <?= $modelMaximo->valor ?>;

        if (nota >= minima && nota <= maxima) {
            var url = "<?= Url::to(['registra']) ?>";

            $.post(
                url,
                { nota: nota, notaId: id },
                function (result) {
                    $("#res").html(result);
                }
            );
        } else {
            alert("La calificación debe estar ente " + minima + " y " + maxima);
            location.reload();
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