<?php

use backend\models\PlanSemanalBitacora;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan Semanal';
$contador = 0;

// echo '<pre>';
// print_r($template);
// die();
?>

<style>
    .adv {
        animation: vibrar .5s ease-in-out;
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

<div class="scholaris-asistencia-profesor-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/calendario.png" width="34px" style="" class="img-thumbnail"></h4>
                </div>

                <div class="col-lg-2 col-md-2">
                    <h5>
                        <?= Html::encode($this->title) ?>
                    </h5>
                </div>

                <div class="col-lg-6 col-md-6">

                    <?php $form = ActiveForm::begin(); ?>

                    <input type="hidden" name="template_id" value="<?= $template ?>" style="display: none;">


                    <select style="text-align: center;" name="" class="form-control">
                        <option value="">Seleccione Trimestre</option>

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

                    <div class="form-group d-grid gap-2" style="text-align: center; margin-top: 5px;margin-bottom: 5px">
                        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                    </div>

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
            <div>
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
                                        $coordinador = Yii::$app->user->identity->usuario;
                                        $estado = consulta_estado($sem->id, $docente['login']);


                                        if ($estado === 'COORDINADOR') {
                                            echo '<a id="ver-plan-link" href="aprobar-plan-semanal?semana_id=' . $sem->id . '&docentes=' . $docente['login'] .  '"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-check" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path d="M11.102 17.957c-3.204 -.307 -5.904 -2.294 -8.102 -5.957c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6a19.5 19.5 0 0 1 -.663 1.032" />
                                            <path d="M15 19l2 2l4 -4" />
                                          </svg></a>';
                                        } elseif ($estado === 'DEVUELTO') {
                                            echo '<a id="ver-plan-link" href="aprobar-plan-semanal?semana_id=' . $sem->id . '&docentes=' . $docente['login'] . '"><svg xmlns="http://www.w3.org/2000/svg" class="aler icon icon-tabler icon-tabler-clipboard-off" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff2825" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5.575 5.597a2 2 0 0 0 -.575 1.403v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2m0 -4v-8a2 2 0 0 0 -2 -2h-2" />
                                            <path d="M9 5a2 2 0 0 1 2 -2h2a2 2 0 1 1 0 4h-2" />
                                            <path d="M3 3l18 18" />
                                          </svg></a>';
                                        } else {
                                            echo
                                            '<a id="ver-plan-link" href="aprobar-plan-semanal?semana_id=' . $sem->id . '&docentes=' . $docente['login'] . '"><svg xmlns="http://www.w3.org/2000/svg" class="adv icon icon-tabler icon-tabler-alert-triangle" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffbf00" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 9v4" />
                                                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                                <path d="M12 16h.01" />
                                                </svg></a>';
                                        }
                                        ?>

                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                </table>
                </tbody>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>



<?php
function consulta_estado($semanaId, $docente)
{
    $coordinador = Yii::$app->user->identity->usuario;


    $bitacora = PlanSemanalBitacora::find()
        ->where([
            'semana_id' => $semanaId,
            'docente_usuario' => $docente,
            'usuario_recibe' => $coordinador
        ])
        ->one();
    if ($bitacora) {
        return $bitacora->estado;
    } else {
        return 0;
    }
}
?>