<?php
use backend\models\TocPlanUnidadHabilidad;
use Mpdf\Tag\Span;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Planificación Semanal';
$this->params['breadcrumbs'][] = $this->title;

// echo "<pre>";
// print_r($semanas);
// die();
?>

<div class="planificacion-semanal-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card  shadow col-lg-10 col-md-10 col-sm-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/plan.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-9 col-md-9">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                    <p>
                        CURSOS/PARALELOS
                    </p>
                </div>
                <!-- INICIO BOTONES DERECHA -->
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5">
                            <i class="fa fa-briefcase" aria-hidden="true"></i> Regresar</span>',
                            ['toc-plan-vertical/index1', ['clase_id']],
                            ['class' => '', 'title' => 'Planificación Vertical TOC']

                        );
                    ?>
                    <!-- FIN BOTONES DERECHA -->
                </div>
                <hr>
                <!-- Datos informativos -->
            </div>
            <!-- INICIO DATOS INFORMATIVOS -->
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h5 id="datos" style="margin-top: 1rem;"><b>DATOS INFORMATIVOS</b></h5>
                <div style="margin-left:10rem:">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M12 11l0 6" />
                            <path d="M9 14l6 0" />
                          </svg>
                             Regresar</span>',
                            ['toc-plan-vertical/index1', ['clase_id']],
                            ['class' => '', 'title' => 'Planificación Vertical TOC']

                        );
                    ?>
                </div>
                <table width="100%" class="table table-secondary table-bordered; table table-bordered"
                    style="font-size:10px;">
                    <tbody>
                        <tr>
                            <th width="150px" class="fondo-campos">PROFESOR:</th>


                            <th width="150px" class="fondo-campos">Nivel: </th>


                            <th width="150px" class="fondo-campos">Mes: </th>


                            <th width="150px" class="fondo-campos">Unidad:</th>


                            <th width="150px" class="fondo-campos">Semana desde hasta</th>


                            <th width="150px" class="fondo-campos">Semana</th>

                        </tr>
                        <tr>

                            <th class="fondo-campos">
                                <?= $clase->idprofesor; ?>
                            </th>

                            <th class="fondo-campos"></th>

                            <th class="fondo-campos"> </th>

                            <th class="fondo-campos">
                                <?= $bloque->name; ?>
                            </th>

                            <th class="fondo-campos"> </th>

                            <th class="fondo-campos"> </th>

                        </tr>
                    </tbody>
                </table>
                <!-- fin datos informativos -->
            </div>
            <!-- INICIO LISTADO DE SEMANAS -->
            <div class="" style="text-align: center;">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>NOMBRE DE SEMANA
                                <?php
                                foreach ($semanas as $sem) {
                                    echo "<th colspan='2' class='fondo-campos'>" . $sem['nombre_semana'] . "</th>";

                                }
                                ?>
                            </th>
                        </tr>
                        <tr>
                        <tr>
                            <th>Numero de semanas
                                <?php
                                foreach ($semanas as $sem) {
                                    echo "<th colspan='2' class='fondo-campos'>" . '#' . $sem['semana_numero'] . "</th>";
                                }
                                ?>
                            </th>
                        </tr>
                        </tr>




                    </tbody>
                </table>
            </div>
            <!-- FIN LISTADO DE SEMANAS -->
            <!-- INICIO HORARIOS -->
            <div>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="150px" class="fondo-campos">Fecha</th>

                            <th width="" rowspan="" class="fondo-campos">Dia / Day/
                                JourHora/Hour
                            </th>
                            <th class="fondo-campos">horario de clase</th>

                            <th class="fondo-campos">Tema de la clase</th>

                            <th class="fondo-campos">Actividades / Activities
                                / Activités</th>

                            <th class="fondo-campos">Diferenciación NEE</th>

                            <th class="fondo-campos">Recursos
                                Integrados</th>

                        </tr>
                        <tr>
                        <tr>
                            <th rowspan="" class="fondo-campos"></th>


                            </td>
                            <th rowspan="" class="fondo-campos"></th>


                            </td>
                            <th rowspan="" class="fondo-campos"></th>


                            </td>
                            <th rowspan="" class="fondo-campos"></th>


                            </td>
                            <th rowspan="" class="fondo-campos"></th>


                            </td>
                            <th rowspan="" class="fondo-campos"></th>


                            </td>
                            <th rowspan="" class="fondo-campos"></th>

                            </td>
                        </tr>
                        </tr>
                    </tbody>

                </table>
            </div>
            <!-- FIN HORARIOS -->

        </div>
    </div>
</div>

<?php
function print_semana($sem)
{
    if ($sem == 1) {
        echo "Lunes";
    } elseif ($sem == 2) {
        echo "Martes";
    } elseif ($sem == 3) {
        echo "Miercoles";
    } elseif ($sem == 4) {
        echo "Jueves";
    } elseif ($sem == 5) {
        echo "Viernes";
    }
}

?>