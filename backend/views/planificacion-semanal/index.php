<?php
use backend\models\ScholarisActividad;
use backend\models\TocPlanUnidadHabilidad;
use Mpdf\Tag\Small;
use Mpdf\Tag\Span;
use yii\helpers\Html;
use yii\helpers\Url;
use function Symfony\Component\String\s;

$this->title = 'Planificación Semanal';
$this->params['breadcrumbs'][] = $this->title;

// echo "<pre>";
// print_r($planSemanal);
// die();

?>


<div class="planificacion-semanal-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card  shadow col-lg-10 col-md-10 col-sm-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/plan.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8 col-md-8">
                    <h4>
                        <?= Html::encode($this->title)
                            ?>
                    </h4>
                    <p>
                        <?=
                            '<b><small>' . $clase->paralelo->course->name . ' - ' . ' "' . $clase->paralelo->name .
                            '"' . ' ' . '-' . ' ' . '(' . $bloque->name . ')' . ' ' . 'Clase #:' . $clase->id .
                            ' / ' . $clase->profesor->last_name . ' ' . $clase->profesor->x_first_name . '</small></b>'
                            ?>
                    </p>
                </div>
                <!-- INICIO BOTONES DERECHA -->
                <div class="col-lg-3 col-md-3" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up"
                             width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" 
                             fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                            <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                            </svg> Regresar</span>',
                            ['toc-plan-vertical/index1', 'clase_id' => $clase->id],
                            ['class' => '', 'title' => 'Planificación Vertical TOC']
                        );
                    ?>
                    <!-- FIN BOTONES DERECHA -->
                </div>
                <hr>
            </div>
            <!-- INICIO MENU IZQUIERDA -->
            <div class="row " style="margin-top: -0.3rem; text-align: center;">
                <div class="col-lg-2 col-md-2" style="height: 60vh; background-color: #eee; overflow-y: auto; font-size: 16px; 
                margin-top: -20px;">
                    <b>
                        <?php
                        foreach ($semanas as $sem1) {
                            ?>
                            <div class="row ancho-boton zoom menuizquierda"
                                style="border-bottom:solid 1px #ccc;margin-top:5px;">
                                <?php
                                echo Html::a(
                                    $sem1->nombre_semana,
                                    [
                                        'planificacion-semanal/index1',
                                        'bloque_id' => $sem1->bloque_id,
                                        'clase_id' => $clase->id,
                                        'semana_defecto' => $sem1->id
                                    ],
                                );
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </b>
                </div>
                <!-- FIN DE MENU IZQUIERDA -->

                <!-- INICIO INFO DE SEMANA -->
                <div class="col-lg-10 col-md-10" style="overflow-y: scroll; height: 60vh">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div style="text-align: left;margin-bottom: 0.5rem;">
                                <h5><b>
                                        <?= $semana->nombre_semana ?>
                                    </b></h5>
                                <div style="text-align: right;margin-top: -2rem;">
                                    <b>Desde:
                                        <?= $semana->fecha_inicio . ' / ' . 'Hasta: ' . $semana->fecha_finaliza ?>
                                    </b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="table table-responsive">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th width="100px">FECHA</th>
                                            <th width="100px">DÍA</th>
                                            <th width="120px">HORA</th>
                                            <th width="100px">TEMA</th>
                                            <th width="100px">ACTIVIDADES</th>
                                            <th width="100px">DIF. NEE</th>
                                            <th width="100px">TAREAS</th>
                                            <th width="100px">RECURSOS</th>
                                            <th width="100px">EDITAR</th>
                                            <th width="150px">CREAR TAREA</th>
                                        </tr>
                                        <?php
                                        foreach ($planSemanal as $semTotal) {
                                            echo '<tr>';
                                            echo '<td>' . $semTotal->fecha . '</td>';
                                            echo '<td>' . obtenerDiaFecha($semTotal->fecha) . '</td>';
                                            echo '<td>' . $semTotal->hora->nombre . '</td>';
                                            echo '<td>' . obtenerIcono($semTotal, 'tema') . '</td>';
                                            echo '<td>' . obtenerIcono($semTotal, 'actividades') . '</td>';
                                            echo '<td>' . obtenerIcono($semTotal, 'diferenciacion_nee') . '</td>';
                                            echo '<td>' . obtener_total_insumos($semTotal->id, $semTotal->hora_id).'</td>';
                                            echo '<td>' . obtenerIcono($semTotal, 'recursos') . '</td>';
                                            echo '<td>' . generarBotonEdicion('update', $semTotal->id) . '</td>';
                                            echo '<td>' . BotonTarea($semTotal->id) . '</td>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- FIN INFO DE SEMANA -->
            </div>

        </div>
    </div>
</div>

<?php
function print_semanas($modelSemanal, $campo)
{
    foreach ($modelSemanal as $plan) {
        if ($plan->opcion_descripcion == $campo) {
            ?>
            <!-- Button trigger modal -->
            <a data-bs-toggle="modal" data-bs-target="#modal<?= $plan->id ?>">
                <?php
                echo $plan->contenido;
                ?>
            </a>

            <!-- Modal -->
            <div class="modal fade" id="modal<?= $plan->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modificando Campo</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?= Html::beginForm(['update-field'], 'post') ?>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $plan->id ?>">
                            <div class="form-group">
                                <label class="form-label"><b>Contenido</b></label>
                                <textarea name="contenido" class="form-control"><?= $plan->contenido ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>

<?php
function generarBotonEdicion($url, $id)
{
    return Html::a('<svg xmlns="http://www.w3.org/2000/svg"
                    class="icon icon-tabler icon-tabler-edit" width="20" height="20"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#0a1f8f" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                    <path d="M16 5l3 3" />
                    </svg>',
        ['update', 'id' => $id],
        ['class' => '', 'title' => 'Editar plan semanal']
    );
}
?>
<?php
function generarBotonChecklist($url, $options = [])
{
    $defaultOptions = [
        'class' => '',
        'title' => '',
    ];
    $mergedOptions = array_merge($defaultOptions, $options);
    $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checklist"
                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00b341" 
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" />
                <path d="M14 19l2 2l4 -4" />
                <path d="M9 8h4" />
                <path d="M9 12h2" />
            </svg>';

    return Html::a($icon, $url, $mergedOptions);
}
?>

<?php
function obtenerDiaFecha($fecha)
{
    $diasSemana = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $numeroDia = date('w', strtotime($fecha));
    return $diasSemana[$numeroDia];
}
?>

<?php
function obtenerIcono($semTotal, $campo)
{
    $titulo = '';

    switch ($campo) {
        case 'tema':
            $titulo = strip_tags($semTotal->tema);
            break;
        case 'actividades':
            $titulo = strip_tags($semTotal->actividades);
            break;
        case 'diferenciacion_nee':
            $titulo = strip_tags($semTotal->diferenciacion_nee);
            break;
        case 'recursos':
            $titulo = strip_tags($semTotal->recursos);
            break;
    }

    if ($semTotal->$campo == '' || $semTotal->$campo == 'none' || $semTotal->$campo == '<p>none</p>') {
        return '<i class="fas fa-circle" style="color: #ab0a3d;" title="' . $titulo . '"></i>';
    } else {
        return '<i class="fas fa-circle" style="color: #00b341;" title="' . $titulo . '"></i>';
    }
}
?>

<?php
function BotonTarea($id) {
    return Html::a(
        '<span class="badge rounded-pill" style="background-color: #ab0a3d; font-size: 12px;"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
            <path d="M10 14h4" />
            <path d="M12 12v4" />
        </svg></span>',
        ['tasks', 'id' => $id],
        ['class' => 'Descripción del botón']
    );
}
?>

<?php
function contarTipoActividades($tipoActividades) {
    return count($tipoActividades);
}

function imprimirCantidadTipoActividades($cantidad) {
    echo 'La cantidad de datos inyectados en $tipoActividades es: ' . $cantidad;
}
?>
<?php
function obtener_total_insumos($planSemanalId,$horaId) {
    $actividad = ScholarisActividad::find()
    ->where(['plan_semanal_id' => $planSemanalId,
    'hora_id' => $horaId
    ])
    ->all();
    return count($actividad);
}
?>