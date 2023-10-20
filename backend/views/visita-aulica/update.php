<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VisitaAulica */

$this->title = 'Visita áulica -' . ' Visita ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Visita Aulicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

// echo "<pre>";
// print_r($model);
// die();
?>


<style>
    .custom-table {
        border-collapse: collapse;
        width: 100%;
        /* border-radius: 10px; */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        color: black;
        /* font-weight: bold; */
        font-size: 11px;
    }

    .custom-table th,
    .custom-table td {
        padding: 15px;
        /* text-align: center; */
        /* border: 1px solid #333; */

    }

    .custom-table th {
        background-color: #ab0a3d;
        color: white;
    }

    .custom-table tr:nth-child(even) {
        /* background-color: #eee; */
        color: black;
    }

    .custom-table th:first-child,
    .custom-table td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        text-align: left;
        color: black;
    }

    .custom-table th:last-child,
    .custom-table td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        text-align: right;
        color: black;
    }
</style>


<div class="m-0 vh-50 row justify-content-center align-items-center scroll">
    <div class="card shadow col-lg-10 overflow-auto">
        <div class="row align-items-center p-2">
            <!-- INICIO ENCABEZADO -->
            <div class="row">
                <div class="col-lg-1">
                    <h3><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h3>
                </div>
                <div class="col-lg-9">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <p>
                        <?= ''
                            . 'Coordinador: '
                            . $clase->paralelo->dece_nombre . ' - '
                            . $clase->paralelo->course->name . ' - ' . ' " '
                            . $clase->paralelo->name . ' " ' . 'Materia: '
                            . $clase->ismAreaMateria->materia->nombre . ' '
                            . '(Clase: #'
                            . $clase->id . ')'

                        ?>
                    </p>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-share" width="15" height="15" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.247 0 .484 .045 .702 .127" />
                        <path d="M19 12h2l-9 -9l-9 9h2v7a2 2 0 0 0 2 2h5" />
                        <path d="M16 22l5 -5" />
                        <path d="M21 21.5v-4.5h-4.5" />
                        </svg> Regresar
                            </span>',
                        [
                            'view',
                            'clase_id' => $clase->id,
                            'bloque_id' => $trimestre->id
                        ]
                    );
                    ?>
                </div>
            </div>

            <!-- FIN ENCABEZADO -->

            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6 visita-aulica-update overflow-auto" style="height: 700px;">
                    <!-- RENDER FORM -->
                    <div class="card" style="padding: 10px">
                        <?= $this->render('_form', [
                            'model' => $model,
                            'clase' => $clase,
                            'trimestre' => $trimestre,
                        ]) ?>
                    </div>
                    <!-- RENDER FORM -->

                    <!-- CONDICIONAL PARA MOSTRAR TABLAS GRUPALES O INDIVIDUAL 1-->

                    <div class="card" style="margin-top: 10px;padding: 10px">
                        <?php
                        if ($model['aplica_grupal'] > 0) {
                        ?>
                            <!-- GRUPALES -->
                            <h3 style="text-align: center">GRUPAL</h3>
                            <h6 style="text-align: center">Observación al docente</h6>
                            <table class="table custom-table table-hover table-responsive">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th style="border-bottom: none;color: white;">Descripción</th>
                                        <th style="border-bottom: 1px solid #ab0a3d;">SI</th>
                                        <th style="border-bottom: 1px solid #ab0a3d;">NO</th>
                                        <th style="border-bottom: none;color: white;">Comentarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align: center;">
                                        <!-- foreach -->
                                        <td></td>
                                        <td><input type="checkbox"></td>
                                        <td><input type="checkbox"></td>
                                        <td><textarea style="border: none; width: 100%;" rows="4" cols="30"></textarea></td>
                                    </tr>
                                    <tr style="text-align: center;">
                                        <th colspan="4" style="color: white;text-align: center">Observación para el docente</th>
                                    </tr>
                                    <tr style="text-align: center;">
                                        <td colspan="4"><textarea style="border: none; width: 100%;" rows="4" cols="30"></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- GRUPALES -->
                        <?php
                        } else {
                        ?>
                            <!-- INDIVIDUALES -->
                            <h3 style="text-align: center">INDIVIDUAL</h3>
                            <h6 style="text-align: center">Observación al docente</h6>
                            <table class="table custom-table table-hover table-responsive">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th style="border-bottom: none;color: white;">Descripción</th>
                                        <th style="border-bottom: 1px solid #ab0a3d;">SI</th>
                                        <th style="border-bottom: 1px solid #ab0a3d;">NO</th>
                                        <th style="border-bottom: none;color: white;text-align: center;">Comentarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align: center;">
                                        <!-- foreach -->
                                        <td></td>
                                        <td><input type="checkbox"></td>
                                        <td><input type="checkbox"></td>
                                        <td><textarea style="border: none; width: 100%;" rows="4" cols="30"></textarea></td>
                                    </tr>
                                    <tr style="text-align: center;">
                                        <th colspan="4" style="color: white;text-align: center">Observación para el docente</th>
                                    </tr>
                                    <tr style="text-align: center;">
                                        <td colspan="4"><textarea style="border: none; width: 100%;" rows="4" cols="30"></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- INDIVIDUALES -->
                        <?php
                        }
                        ?>
                    </div>

                </div>

                <!-- FIN CONDICIONAL PARA MOSTRAR TABLAS GRUPALES O INDIVIDUAL 1-->


                <!-- CONDICIONAL PARA GRUPAL E INDIVIDUAL 2 -->

                <div class="card col-lg-6 col-md-6 visita-aulica-update overflow-auto" style="height: 500px;">
                    <?php
                    if ($model['aplica_grupal'] > 0) {
                    ?>
                        <!-- GRUPAL -->
                        <div class="visita-aulica-update">

                            <h6 style="text-align: center">Observación a estudiantes</h6>
                            <table class="table custom-table table-hover table-responsive">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th style="border-bottom: none;color: white;">Nómina completa</th>
                                        <th colspan="2" style="border-bottom: 1px solid #ab0a3d;">Asiste a clase</th>
                                        <th style="border-bottom: none;color: white;">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align: center;">
                                        <!-- foreach -->
                                        <td></td>
                                        <td>SI <input type="checkbox"></td>
                                        <td>NO <input type="checkbox"></td>
                                        <td><textarea style="border: none; width: 100%;" rows="4" cols="30"></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- GRUPAL -->
                    <?php
                    } else {
                    ?>
                        <!-- INDIVIDUAL -->
                        <div class=" visita-aulica-update">

                            <h6 style="text-align: center">Observación a estudiantes</h6>
                            <table class="table custom-table table-hover table-responsive">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th style="border-bottom: none;color: white;text-align: center">Descripción</th>
                                        <th style="border-bottom: 1px solid #ab0a3d;" colspan="2">Asiste a clase</th>
                                        <th style="border-bottom: none;color: white;text-align: center;">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align: center;">
                                        <!-- foreach -->
                                        <td></td>
                                        <td>SI <input type="checkbox"></td>
                                        <td>NO <input type="checkbox"></td>
                                        <td><textarea style="border: none; width: 100%;" rows="4" cols="30"></textarea></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <!-- INDIVIDUALES -->

                    <?php
                    }
                    ?>


                </div>
            </div>
            <!-- FIN CONDICIONAL PARA GRUPAL E INDIVIDUAL 2-->

        </div>


    </div>

</div>