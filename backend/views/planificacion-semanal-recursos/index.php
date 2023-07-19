<?php

use yii\helpers\Html;
use yii\grid\GridView;

// echo "<pre>";
// print_r($recursos);
// die();

$this->title = 'Crear Recursos';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .tablarecursos {
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        border-radius: 10px;
        justify-content: center;
    }
</style>

<div class="planificacion-semanal-recursos-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-11 col-md-11 col-sm-11">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/actividad-fisica.png" width="64px" style=""
                            class="img-thumbnail"></h4>

                </div>
                <div class="col-lg-7">
                    <h2>
                        <?= Html::encode($this->title) ?>
                    </h2>
                    <p>
                        <?= '<b><small>' . $planificacionSemanal->clase->paralelo->course->name . ' ' . ' ' . '"' .
                            $planificacionSemanal->clase->paralelo->name . '"' . ' ' . '/' . ' Hora de clase:' . ' ' . $planificacionSemanal->hora->nombre . ' ' . ' '
                            . '/' . ' ' . 'Docente:' . ' ' . $planificacionSemanal->clase->profesor->last_name . ' '
                            . $planificacionSemanal->clase->profesor->x_first_name . '</small></b>' ?>
                    </p>
                </div>

                <!-- BOTONES -->
                <div class="col-lg-4 col-md-4" style="text-align: right">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up" 
                            width="12" height="12" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                            <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                          </svg> Regresar</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                    ?>
                    |
                    <?= Html::a(

                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-apps" width="12" height="12" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        <path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        <path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        <path d="M14 7l6 0" />
                        <path d="M17 4l0 6" />
                      </svg>  Nuevo Recurso</span>',
                        ['create', 'planificacion_semanal_id' => $planificacionSemanal->id]
                    ) ?>
                    |
                    <?= Html::a(

                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                      </svg>  Vista previa</span>',
                        [''],
                        ['class' => '', 'title' => 'Vista previa del Recurso']
                    ) ?>

                </div>
                <hr>
                <!-- FIN BOTONES -->
            </div>

            <!-- COMIENZO CUERPO -->
            <div class="row" style="padding: 1rem; margin-top: -1.5rem;align-items: center; overflow-x: auto">
                <div class="text-center"> <!-- Agregar el contenedor con la clase text-center -->
                    <!-- <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="400px">Tema</th>
                                <th width="300px">Tipo de Recurso</th>
                                <th>URL del Recurso</th>
                                <th> Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $referencia = $recursos[0]; ?>
                            <?php foreach ($recursos as $recurso) { ?>
                                <tr>
                                    <td>
                                        <?php echo $recurso->id; ?>
                                    </td>
                                    <td>
                                        <?php echo $recurso->tema; ?>
                                    </td>
                                    <td>
                                        <?php echo $recurso->tipo_recurso; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo $recurso->url_recurso; ?>"><?php echo $recurso->url_recurso; ?></a>
                                    </td>
                                    <td>
                                        <?php  echo Html::a('Ver', ['view', 'id' => $recurso->id], ['class' => 'btn btn-primary btn-sm']); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table> -->
                    





                    <div class="tablarecursos">
                    <table class="table table-hover table-striped ">
                        <thead>
                            <tr>
                                <th width="50px" >#</th>
                                <th width="200px">Tema</th>
                                <th width="400px">Tipo de Recurso</th>
                                <th width="400px" >URL del Recurso</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $referencia = $recursos[0]; ?>
                            <?php foreach ($recursos as $recurso) { ?>
                                <tr>
                                    <td>
                                        <?php echo $recurso->id; ?>
                                    </td>
                                    <td>
                                        <?php echo $recurso->tema; ?>
                                    </td>
                                    <td>
                                        <?php echo $recurso->tipo_recurso; ?>
                                    </td>
                                    <td>
                                        <?php echo $recurso->url_recurso; ?>
                                    </td>

                                    <td>
                                        <?php  echo Html::a('Ver', ['view', 'id' => $recurso->id], ['class' => 'btn btn-primary btn-sm']); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            <!-- FIN DE CUERPO -->


        </div>
    </div>
</div>


