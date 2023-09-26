<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aprobación de Planificaciones DPL';
$this->params['breadcrumbs'][] = $this->title;

// echo"<pre>";
// print_r($detalle);
// die();

?>

<style>
    body {
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .course-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .course-info {
        flex: 1;
        padding-right: 20px;
    }

    .course-title {
        font-size: 1.2rem;
        color: #ab0a3d;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        background-color: #f2f2f2;
        color: #333;
        font-weight: bold;
        border-radius: 5px;
    }

    .action-links {
        display: flex;
        align-items: center;
    }

    .action-link {
        text-decoration: none;
        padding: 8px 12px;
        background-color: #0a1f8f;
        color: #fff;
        border-radius: 5px;
        transition: background-color 0.2s ease;
        margin-right: 5px;
    }

    .action-link:hover {
        background-color: #eee;
    }
</style>

<div class="planificacion-aprobacion-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/files.png" width="64px" style="" class="img-thumbnail">
                    </h4>
                </div>
                <div class="col-lg-10">
                    <h4>
                        <b>
                            <?= Html::encode($this->title) ?>
                        </b>
                    </h4>

                </div>
                <div class="col-lg-1">
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5;color: #fff"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    );
                    ?>
                </div>
                <hr>
            </div><!-- FIN DE CABECERA -->

            <!-- inicia cuerpo de card -->

            <div class="container">
                <?php

                $mostrarDlp = ['0', '1'];

                foreach ($mostrarDlp as $index) {
                    $curso = $detalle[$index];

                ?>
                    <div class="course-card">

                        <div class="course-info">
                            <div class="course-title">
                                <?= $curso['curso'] . ' - ' . $curso['code'] ?>
                            </div>
                            <div class="badge">
                                Total Asignaturas:
                                <?= $curso['total_materias'] ?>
                            </div>
                        </div>

                        <div class="action-links">
                            <?=
                            Html::a('Planificación Vertical', [
                                'planificacion-aprobacion/asignaturas',
                                'template_id' => $curso['x_template_id']
                            ], [
                                'class' => 'action-link',
                                'title' => 'Ver planificacion Vertical'
                            ]);
                            ?>
                            <?=
                            Html::a('Plan de Unidades', [
                                'pud-aprobacion/index1',
                                'template_id' => $curso['x_template_id']
                            ], [
                                'class' => 'action-link',
                                'title' => 'Ver plan de Unidades'
                            ]);
                            ?>
                            <?=
                            Html::a('Planificación Semanal', [
                                'aprobacion-plan-semanal-diploma/index1',
                                'template_id' => $curso['x_template_id']
                            ], [
                                'class' => 'action-link',
                                'title' => 'Ver plan semanal'
                            ]);
                            ?>
                            <?php if ($curso['code'] == 'PAI') : ?>
                                <?=
                                Html::a('Mapa de Enfoques', ['materias-pai/lista-asignaturas'], ['class' => 'action-link']);
                                ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>


            <!-- fin cuerpo de card -->

        </div>
    </div>

</div>