<?php

use backend\models\PlanSemanalBitacora;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aprobador Plan Semanal';
$contador = 0;
$coordinador = Yii::$app->user->identity->usuario;
// echo '<pre>';
// print_r($bitacora);
// die();
?>

<style>
    .adv {
        animation: vibrar .5s ease-in-out;
    }

    .adv-aprov {
        animation: vibrar .5s ease-in-out;
    }

    .buscar {
        animation: vibrar 0.5s infinite;
        animation-delay: 4s;
    }

    .stop {
        animation: none;
    }

    @keyframes vibrar {
        0% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-2px);
        }

        50% {
            transform: translateX(2px);
        }

        75% {
            transform: translateX(-2px);
        }

        100% {
            transform: translateX(2px);
        }
    }

    .aler {
        animation: respirar .5s ease-in-out infinite;
    }

    @keyframes respirar {

        0%,
        100% {
            transform: scale(1.5);
        }

        50% {
            transform: scale(1.05);
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="scholaris-asistencia-profesor-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/calendario.png" width="34px" style="" class="img-thumbnail"></h4>
                </div>

                <div class="col-lg-2 col-md-2">

                    <h6 style="font-weight: bold; font-size: 1rem;">

                        <?= Html::encode($this->title) ?>

                    </h6>

                </div>

                <div class="col-lg-6 col-md-6">

                    <?php $form = ActiveForm::begin([
                        'method' => 'get',
                        // 'layout' => 'inline',
                    ]); ?>

                    <div style="display: flex;justify-content: space-between; align-items: center;">
                        <input type="hidden" name="template_id" value="<?= $template ?>" />


                        <select onchange="capturarValor(this)" style="text-align: center;" name="trimestre_defecto" class="form-control">

                            <option value="">Selecionar Trimestre</option>

                            <?php
                            foreach ($trimestres as $trim) {
                            ?>
                                <option value="<?= $trim->id ?>">
                                    <?= $trim->name ?>
                                </option>
                            <?php
                            }
                            ?>

                        </select>

                        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary buscar', 'style' => 'margin-left: 10px']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

                <!-- <div class="col-lg-4 col-md-4">
                    <div id="div-semanas"></div>
                </div> -->

                <!--botones derecha-->
                <div class="col-lg-3 col-md-3" style="text-align: right;">
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    );
                    ?>
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Aprobaciones</span>',
                        ['planificacion-aprobacion/index'],
                        ['class' => 'link']
                    );
                    ?>

                </div>
                <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
                <hr>
            </div>

            <div style="margin-top: -20px;">
                <div style="font-size: 20px;justify-content: center;font-weight: bold;">
                    <?php

                    echo $trimestre->name;

                    ?>
                </div>

                <!-- <div>
                    <p> -->
                <?php

                // echo $trimestres[0]->name;
                // echo $trimestres[1]->name;
                // echo $trimestres[2]->name;

                ?>
                <!-- </p>
                </div> -->


                <!-- tabla para presentar estados de planifiaciones del docente -->
                <table id="tablaTrimestre" class="table table-bordered table-striped" style="text-align: center;">

                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col" width="280px" style="font-size: 11px; text-align: center">Profesor </th>
                            <?php foreach ($semanas as $semana) { ?>
                                <?php

                                // echo"<pre>";
                                // print_r($semana);
                                // die();
                                ?>
                                <th scope="col" style="font-size: 11px;">
                                    <?= $semana->nombre_semana ?>
                                </th>
                            <?php } ?>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        foreach ($docentes as $contador => $docente) {

                        ?>
                            <tr>
                                <th style="font-size: 12px;">
                                    <?= $contador + 1 ?>
                                </th>
                                <td style="text-align: left; font-size: 10px;">
                                    <?= $docente['docente'] ?>
                                </td>
                                <?php
                                foreach ($semanas as $sem) {

                                ?>
                                    <td style="font-size: 10px;">

                                        <?php
                                        $estado = consulta_estado($sem->id, $docente['login'], $coordinador);
                                        // echo '<pre>';
                                        // print_r($estado);
                                        // die();
                                        // $valorSeleccionado = '';
                                        // print_r($valorSeleccionado);

                                        if ($estado === 'COORDINADOR') {
                                            echo '<a id="ver-plan-link" href="aprobar-plan-semanal?semana_id=' . $sem->id . '&docentes=' . $docente['login'] . '&trimestre_name=' . $trimestre->name .  '"><svg xmlns="http://www.w3.org/2000/svg" class="adv icon icon-tabler icon-tabler-eye-check" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6f32be" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path d="M11.102 17.957c-3.204 -.307 -5.904 -2.294 -8.102 -5.957c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6a19.5 19.5 0 0 1 -.663 1.032" />
                                                    <path d="M15 19l2 2l4 -4" />
                                                </svg></a>';
                                        } elseif ($estado === 'DEVUELTO') {
                                            echo '<a id="ver-plan-link" href="aprobar-plan-semanal?semana_id=' . $sem->id . '&docentes=' . $docente['login'] . '&trimestre_name=' .  $trimestre->name . '"><svg xmlns="http://www.w3.org/2000/svg" class="aler icon icon-tabler icon-tabler-clipboard-off" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff2825" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M5.575 5.597a2 2 0 0 0 -.575 1.403v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2m0 -4v-8a2 2 0 0 0 -2 -2h-2" />
                                                    <path d="M9 5a2 2 0 0 1 2 -2h2a2 2 0 1 1 0 4h-2" />
                                                    <path d="M3 3l18 18" />
                                                </svg></a>';
                                        } elseif ($estado === 'APROBADO') {

                                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-certificate" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                            <path d="M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5" />
                                            <path d="M6 14m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                            <path d="M4.5 17l-1.5 5l3 -1.5l3 1.5l-1.5 -5" />
                                          </svg>';
                                        } else {
                                            echo
                                            '<svg xmlns="http://www.w3.org/2000/svg" class="adv icon icon-tabler icon-tabler-alert-triangle" title="El profesor no ha enviado su panificación" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffbf00" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M12 9v4" />
                                                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                                    <path d="M12 16h.01" />
                                                </svg>';
                                            echo '<span title="El profesor no ha enviado su panificación"></span>';
                                        }
                                        ?>

                                    </td>
                                <?php } ?>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>

                <!-- fin tabla para presentar estados de planifiaciones del docente -->

            </div>

        </div>
    </div>
</div>

<?php

function consulta_estado($semanaId, $docente, $coordinador)
{

    $bitacora = PlanSemanalBitacora::find()
        ->where([
            'semana_id' => $semanaId,
            'docente_usuario' => $docente,
            // 'usuario_recibe' => $coordinador,
            // 'trimestre' => $trimesmtres
        ])
        ->orderBy(['id' => SORT_DESC])
        ->one();
    if ($bitacora) {
        return $bitacora->estado;
    } else {
        return 0;
    }
}
?>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $(".buscar").addClass("stop");
        }, 7000);
    });
</script>

<script>
    var valorSeleccionado = '';

    function capturarValor(selectElement) {
        valorSeleccionado = selectElement.value;
        console.log("Trimestre: " + valorSeleccionado);
        // document.getElementById("trimestre").value = valorSeleccionado;
    }
</script>