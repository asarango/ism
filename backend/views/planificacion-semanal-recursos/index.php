<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Recursos de la clase';
$this->params['breadcrumbs'][] = $this->title;

// echo"<pre>";
// print_r($planBloqueId);
// die();

?>

<style>
    .tablarecursos {
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        border-radius: 10px;
        justify-content: center;
    }

    .card {
        border: none;
    }

    .card h5 {
        font-weight: bold;
    }

    .card p {
        color: #555;
    }


    .mb-3 {
        margin-bottom: 1rem;
    }

    .p-3 {
        padding: 5px;
    }

    .peq-btn {
        margin: 1rem;
    }

    .plnsemanal {
        margin-top: -2rem;
    }

    .bordes {
        border: 1px solid #ab0a3d;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://www.youtube.com/iframe_api"></script>

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
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ff9e18"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up"
                        width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" 
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                       <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                       <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                       <path d="M19 22v-6" />
                       <path d="M22 19l-3 -3l-3 3" />
                       </svg>Regresar</span>',
                        [
                            'planificacion-semanal/index1',
                            'clase_id' => $planificacionSemanal->clase_id,
                            'bloque_id' => $bloqueId,
                            'semana_defecto' => $planificacionSemanal->semana_id,
                            'pud_origen' => 'normal',
                            'plan_bloque_unidad_id' => $planBloqueId
                        ]
                    ) 
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
                        ['create', 
                            'planificacion_semanal_id' => $planificacionSemanal->id,
                            'plan_bloque_unidad_id' => $planBloqueId
                        ]
                    ) ?>
                </div>
                <hr>
                <!-- FIN BOTONES -->
            </div>

            <!-- COMIENZO CUERPO -->
            <div class="row" style="margin-top: -0.5rem; align-items: stretch;">
                <div class="col-lg-3 d-flex justify-content-center" style="margin-bottom: 15px;">
                    <div class="card col-lg-12" style="padding: 1rem;background-color: #eee;">

                        <h4 class="card mb-3" style="text-align: center;border-radius: 1rem;">
                            <?php echo $planificacionSemanal->hora->nombre ?>
                        </h4>

                        <div class="p-3 mb-3" style="text-align: center;">
                            <h6 class="card" style="border-radius: 1rem;"><b>Tema</b></h6>
                            <p>
                                <?php echo $planificacionSemanal->tema ?>
                            </p>
                        </div>

                        <div class="p-3 mb-3 plnsemanal" style="text-align: justify;">
                            <h6 class="card" style="text-align: center;border-radius: 1rem;">
                                <b>Actividades</b>
                            </h6>
                            <p>
                                <?php
                                $actividades = $planificacionSemanal->actividades;
                                echo $actividades;
                                ?>
                            </p>
                        </div>

                        <div class="p-3 plnsemanal" style="text-align: justify;">
                            <h6 class="card" style="text-align: center;border-radius: 1rem;"><b>Diferenciación NEE</b>
                            </h6>
                            <p>
                                <?php echo $planificacionSemanal->diferenciacion_nee ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 d-flex justify-content-center">
                    <div class="card col-lg-12" style="padding: 1rem; margin-bottom: 1rem;">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="100px" style="text-align: center;">Tema</th>
                                <th width="200px" colspan="" style="text-align: center;">Tipo de Recurso
                                </th>
                                <th colspan="2" style="text-align: center;"> Acción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php 
                            $contador = 1;
                            foreach ($recursos as $recurso): ?>
                            <tr>
                                <td>
                                    <b>
                                        <?= $contador++ ?>
                                    </b>
                                </td>

                                <td style="text-align: center;">
                                    <?= Html::a(
                                        $recurso->tema,
                                        $recurso->url_recurso,
                                        [
                                            'style' => 'text-decoration: none; cursor: pointer; 
                                                        transition: transform 0.3s; display: inline-block;',
                                            'onmouseover' => 'this.style.transform = "scale(1.3)"',
                                            'onmouseout' => 'this.style.transform = "scale(1)"',
                                            'target' => '_blank'
                                        ]
                                    ); ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php
                                    switch ($recurso->tipo_recurso) {
                                        case 'file':
                                            echo iconoFile($recurso->id);
                                            break;
                                        case 'link':
                                            echo iconolink($recurso->id);
                                            break;
                                        case 'video-conferencia':
                                            echo iconovideo($recurso->id);
                                            break;
                                        case 'texto':
                                            echo iconotexto($recurso->id);
                                            break;
                                        default:
                                            echo $recurso->tipo_recurso;
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?= iconoVisualizar($recurso->id); ?>

                                </td>
                                <td>
                                    <?= iconoeditar($recurso->id); ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= iconoEstado($recurso->id, $recurso->estado); ?>
                                </td>


                            </tr>

                        <?php endforeach; ?>

                        </tbody>
                    </table>



                        
                    </div>
                </div>
                <div class="col-lg-3 d-flex justify-content-center">
                    <div class="card p-3 mb-3 bordes" style="text-align: center;background-color: #eee;">
                        <?php foreach ($insumos as $contar => $insumo): ?>
                            <table class="table table-striped">
                                <thead class="rounded">
                                    <tr>
                                        <th>#</th>
                                        <th>
                                            Tema:
                                        </th>
                                        <th>
                                            Tipo de insumo:
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>N°
                                                <?= $contar + 1 ?>
                                            </b>
                                        </td>
                                        <td>
                                            <?php echo $insumo->title ?>
                                        </td>
                                        <td>
                                            <?php echo obtenerTipoActividad($insumo->tipo_actividad_id); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- FIN DE CUERPO -->
        </div>
    </div>
</div>


<?php
function obtenerYouTubeVideoId($url)
{
    $parts = parse_url($url);
    if (isset($parts['query'])) {
        parse_str($parts['query'], $query);
        if (isset($query['v'])) {
            return $query['v'];
        } elseif (isset($query['vi'])) {
            return $query['vi'];
        }
    }
    if (isset($parts['path'])) {
        $path = explode('/', trim($parts['path'], '/'));
        return end($path);
    }
    return false;
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mostrarPdfBtns = document.querySelectorAll(".mostrarpdf-btn");
        const ocultarPdfBtns = document.querySelectorAll(".ocultarpdf-btn");

        mostrarPdfBtns.forEach(function (btn) {
            btn.addEventListener("click", function () {
                const targetId = this.getAttribute("data-target");
                const pdfEmbed = document.querySelector(`#pdfEmbed${targetId}`);
                pdfEmbed.style.display = "block";
            });
        });

        ocultarPdfBtns.forEach(function (btn) {
            btn.addEventListener("click", function () {
                const targetId = this.getAttribute("data-target");
                const pdfEmbed = document.querySelector(`#pdfEmbed${targetId}`);
                pdfEmbed.style.display = "none";
            });
        });
    });
</script>



<?php
function iconoeditar($id)
{
    return Html::a(
        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#0a1f8f" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
            <path d="M16 5l3 3" />
        </svg>',
        ['view', 'id' => $id],
        [
            'class' => '',
            'title' => 'Editar Recurso',
            'style' => 'cursor: pointer; transition: transform 0.3s; display: inline-block;',
            'onmouseover' => 'this.style.transform = "scale(1.4)"',
            'onmouseout' => 'this.style.transform = "scale(1.2)"',
        ]
    );
}

function iconoVisualizar($id)
{
    return Html::a(
        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#9e28b5" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
  </svg>',
        null,
        ['class' => 'visualizar-btn', 'data-toggle' => 'modal', 'data-target' => '#visualizarModal', 'style' => 'cursor: pointer;']
    );
}

function iconoFile($id)
{
    return Html::a(
        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-description" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
        <path d="M9 17h6" />
        <path d="M9 13h6" />
      </svg>'
    );
}

function iconolink($id)
{
    return Html::a(
        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world-share" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M20.94 13.045a9 9 0 1 0 -8.953 7.955" />
        <path d="M3.6 9h16.8" />
        <path d="M3.6 15h9.4" />
        <path d="M11.5 3a17 17 0 0 0 0 18" />
        <path d="M12.5 3a16.991 16.991 0 0 1 2.529 10.294" />
        <path d="M16 22l5 -5" />
        <path d="M21 21.5v-4.5h-4.5" />
        </svg>'
    );
}

function iconovideo($id)
{
    return Html::a(
        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-video-plus" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z" />
        <path d="M3 6m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
        <path d="M7 12l4 0" />
        <path d="M9 10l0 4" />
        </svg>'
    );
}

function iconotexto($id)
{
    return Html::a(
        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-text-size" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M3 7v-2h13v2" />
        <path d="M10 5v14" />
        <path d="M12 19h-4" />
        <path d="M15 13v-1h6v1" />
        <path d="M18 12v7" />
        <path d="M17 19h2" />
        </svg>'
    );
}
?>

<?php
function iconoEstado($id, $estado)
{
    $isActive = $estado === 1;
    $options = [

    ];

    if ($isActive) {
        $options['class'] .= ' active-link';
    }
    return Html::a(
        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-check" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
        <path d="M9 12l2 2l4 -4" />
      </svg>',
        null,
        $options
    );
}
?>

<?php
function obtenerTipoActividad($tipo_actividad_id)
{
    $tipos = [
        1 => 'Lecciones de revisión',
        3 => 'Pruebas de base estructuradas',
        4 => 'Tareas en clase',
        5 => 'Proyectos y/o investigaciones',
        6 => 'Proyectos y/o investigaciones',
        7 => 'Exposiciones foros',
        9 => 'Talleres',
        2 => 'Desarrollo de productos',
        8 => 'Se aplica metodología',
        10 => 'Evaluación de base estructurada'


    ];

    return isset($tipos[$tipo_actividad_id]) ? $tipos[$tipo_actividad_id] : 'Tipo de actividad desconocido';
}

?>