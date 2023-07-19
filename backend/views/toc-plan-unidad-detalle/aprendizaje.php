<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Procesos de Aprendizaje';
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// echo "<pre>";
// print_r($habilidades);
// die();

?>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<div class="planificacion-toc-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-9 col-md-9" style="text-align: left;">
                    <h3> <?= Html::encode($this->title)?> </h3>
                    <p>
                        <?=
                             '<small>' . $unidad->clase->ismAreaMateria->materia->nombre .
                            ' - ' .
                            'Clase #:' . $unidad->clase->id .
                            ' - ' .
                            $unidad->clase->paralelo->course->name . ' - ' . $unidad->clase->paralelo->name . ' / ' .
                            $unidad->clase->profesor->last_name . ' ' . $unidad->clase->profesor->x_first_name .
                            '</small>';
                        ?>
                        
                    </p>

                </div>
                <!-- INICIO BOTONES DERECHA -->
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9.00002 15.3802H13.92C15.62 15.3802 17 14.0002 17 12.3002C17 10.6002 15.62 9.22021 13.92 9.22021H7.15002" stroke="#ffffff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8.57 10.7701L7 9.19012L8.57 7.62012" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g>
                            </svg> Plan Unidad Detalle</span>',
                            ['index1', 'id' => $unidad['id']],
                            ['class' => '', 'title' => 'Plan Unidad Detalle']
                        );
                    ?>
                    <!-- FIN BOTONES DERECHA -->
                </div>
                <hr>
            </div>

            <!-- inicio Cuerpo -->

            <body>
                <div class="row" style="margin:10px;">
                    <!--tabla disponibles-boton modal -->
                    <div class="col-lg-6 col-md-6">
                        <div class="text-center">
                            <h5>Procesos Disponibles</h5><br>
                        </div>
                        <div id="BotonAgregarModoAprendizaje">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalAgregarModoAprendizaje"><svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-circle-plus" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M9 12l6 0" />
                                    <path d="M12 9l0 6" />
                                </svg>Agregar Procesos</button>
                        </div>

                        <div class="modal fade" id="modalAgregarModoAprendizaje" tabindex="-1"
                            aria-labelledby="modalAgregarModoAprendizajeLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalAgregarModoAprendizajeLabel">Nuevo proceso de
                                            aprendizaje</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <?= Html::beginForm(['accion-aprendizaje'], 'get') ?>
                                        <input type="hidden" name="toc_plan_unidad_id" value="<?= $unidad->id; ?>">
                                        <input type="hidden" name="bandera" value="nuevo">
                                        <textarea name="descripcion" class="form-control" rows="5"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary"
                                            onclick="guardarModoAprendizaje()">Guardar</button>
                                    </div>
                                    <?= Html::endForm() ?>
                                </div>
                            </div>
                        </div>

                        <script>
                            function guardarModoAprendizaje() {
                                // var textoModoAprendizaje = document.getElementById("modoAprendizajeTextarea").value;
                                // Aquí puedes realizar la lógica para guardar el texto del modo de aprendizaje, como enviarlo a través de AJAX o almacenarlo en una variable, según tus necesidades
                                //console.log("Texto del modo de aprendizaje guardado: " + textoModoAprendizaje);

                                // Cerrar el modal después de guardar los cambios
                                var modal = bootstrap.Modal.getInstance(document.getElementById("modalAgregarModoAprendizaje"));
                                modal.hide();
                            }
                        </script>
                        <!--tabla disponibles-DataTabla -->
                        <div class="table table-responsive p-1">
                            <table id="example" class="table table-striped table-hover">
                                <thead>
                                    <tr bgcolor="#0a1f8f" ; style="color:white">
                                        <th class="text-center">ID</th>
                                        <th>PERFIL</th>
                                        <th class="text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <?php
                                $contador1=0
                                ?>
                                <tbody>
                                    <?php
                                    foreach ($disponibles as $disponible) {
                                        $contador1++;
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $contador1 ?>
                                            </td>
                                            <td>
                                                <?= $disponible['descripcion'] ?>
                                            </td>
                                            <td class="text-center">
                                                <div>
                                                    <?= Html::a(
                                                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checkbox" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#7bc62d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M9 11l3 3l8 -8" />
                                                                <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                                                              </svg>',
                                                        [
                                                            'accion-aprendizaje',
                                                            'toc_plan_unidad_id' => $unidad['id'],
                                                            'toc_opciones_id' => $disponible['id'],
                                                            'bandera' => 'agregar'
                                                        ]

                                                    )
                                                        ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr bgcolor="#0a1f8f" ; style="color:white">
                                        <th class="text-center">ID</th>
                                        <th>PERFIL</th>
                                        <th class="text-center">ACCIONES</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!--fin tabla disponibles -->
                    <?php
                    $contador2=0;
                    
                    ?>
                    <!--inicio tabla seleccionados -->
                    <div class="col-lg-6 col-md-6 align-items-center" style="border-left:1px solid #BE2929;">
                        <div class="text-center">
                            <h5>Procesos Seleccionados</h5><br><br>
                        </div>
                        <div class="table table-responsive p-2" style="margin-top: 1.75rem">
                            <table id="example1" class="table table-striped table-hover">
                                <thead>
                                    <tr bgcolor="#0a1f8f" style="color:#ff9e18">
                                        <th class="text-center">ID</th>
                                        <th>PERFIL</th>
                                        <th class="text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($seleccionadas as $seleccionada) {
                                        $contador2++;
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $contador2 ?>
                                            </td>
                                            <td>
                                                <?= $seleccionada->tocOpcion->descripcion ?>
                                            </td>
                                            <td class="text-center">
                                                <div>
                                                    <?= Html::a(
                                                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#fd0061" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M4 7l16 0" />
                                                        <path d="M10 11l0 6" />
                                                        <path d="M14 11l0 6" />
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                      </svg>',
                                                        [
                                                            'accion-aprendizaje',
                                                            'id' => $seleccionada['id'],
                                                            'toc_plan_unidad_id' => $unidad->id,
                                                            'bandera' => 'eliminar'
                                                        ],
                                                        [
                                                            'class' => 'dropdown-item',
                                                            'style' => 'font-size:10px'
                                                        ]
                                                    )
                                                        ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Fin tabla seleccionados -->
                </div>
        </div>
        </body>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
    crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
    $('#example').DataTable();
</script>

<script>
    function cambia_opcion(id) {
        var url = '<?= Url::to(['change-habilidad']) ?>';
        var params = {
            id: id
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () { },
            success: function (response) {
                // $("#table-body").html(response);
                //console.log(response);
            }
        });
    }
</script>