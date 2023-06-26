<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Planificación Teoría del Conocimiento';
$this->params['breadcrumbs'][] = $this->title;
// echo "<pre>";
// print_r($classes);
// die();
?>

<div class="planificacion-toc-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-6 col-md-6 col-sm-6">
            <div class=" row align-items-center p-2" style="text-align: center;">
                <div class="col-lg-2">
                    <h4><img src="../ISM/main/images/submenu/plan.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <!-- INICIO BOTONES DERECHA -->
                <div class="col-lg-2 col-md-2 col-sm-2" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5">
                            <i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => '', 'title' => 'Inicio']
                        );
                    ?>
                </div>
                <!-- FIN BOTONES DERECHA -->
                <hr>
            </div>
            <!-- FIN DE CABECERA -->

            <!-- inicia cuerpo de card -->
            <div style="margin-top: -9rem;">
                <table class="table table-bordered border-dark" style="text-align: center;margin-bottom: 2rem">
                    <thead>
                        <tr>
                            <th scope="col">CURSOS: 2.DO DE BACHILLERATO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">
                                <p>
                                <ul>
                                    <h6>
                                        <?php
                                        foreach ($classes as $clase) {
                                            echo Html::a('<li>' . $clase['curso'] . ' - ' . $clase['paralelo'] . '<hr>' . '</li>', ['toc-plan-vertical/index1', 'clase_id' => $clase['clase_id']]);
                                        }
                                        ?>
                                    </h6>
                                </ul>
                                </p>
                            </th>
                        </tr>
            </div>

            <!-- fin cuerpo de card -->
        </div>
        <!-- Termina shadow principal -->
    </div>
</div>