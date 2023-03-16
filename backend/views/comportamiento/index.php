<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\ScholarisAsistenciaAlumnosNovedades;
use yii\helpers\Url;
use backend\models\PlanificacionOpciones;
use backend\models\ScholarisAsistenciaComportamientoDetalle;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registro de comportamiento estudiantil';

//revision de numero de observaciones especificas por clase
$numNovedadesPorMateria = $listaNovedadesTodas;
$numNovedadesEspecificasPorMateria = $listaNovedadesEspecificas;

//conteo de alumnos con novedades
$numEstNovedades = ScholarisAsistenciaAlumnosNovedades::find()
    ->select(["grupo_id"])
    ->distinct()
    ->where([
        'asistencia_profesor_id' => $modelAsistencia->id
    ])
    ->asArray()->all();
//conteo de novedades
$numNovedades = ScholarisAsistenciaAlumnosNovedades::find()
    ->select(["grupo_id"])
    ->where([
        'asistencia_profesor_id' => $modelAsistencia->id
    ])
    ->asArray()->all();

//conteo de alumnos sin asistencia
$conteoNovedadesEspecificas = conteo_estudiante_por_novedad($modelAsistencia->id);

$numEstFaltaJustificada = conteo_novedades_especiale($conteoNovedadesEspecificas,'1b');
$numEstFaltaInjustificada = conteo_novedades_especiale($conteoNovedadesEspecificas,'1c');
$numEstAtrasoJustificada = conteo_novedades_especiale($conteoNovedadesEspecificas,'1a');
$numEstAtrasoInjustificada = conteo_novedades_especiale($conteoNovedadesEspecificas,'1d');

// echo '<pre>';
// print_r($numEstFaltaJustificada);
// die();

function conteo_estudiante_por_novedad($id_asistencia_profesor)
{
    $con = Yii::$app->db;
    $query = "select a1.codigo,a1.nombre ,count(*) as conteo 
            from scholaris_asistencia_comportamiento_detalle a1, 
            scholaris_asistencia_alumnos_novedades a2
            where a1.id = a2.comportamiento_detalle_id 
            and a2.asistencia_profesor_id  = '$id_asistencia_profesor'
            and a1.codigo in ('1a','1b','1c','1d')
            group by a1.codigo,a1.nombre ;";
    $resp = $con->createCommand($query)->queryAll();
    
    return  $resp;
}
function conteo_novedades_especiale($array,$codigo)
{
    foreach($array as $dato)
    {
        if($dato['codigo']==$codigo)
        {
            return $dato['conteo'];
        }
    }  
    return 0; 
}

?>

<div class="comportamiento-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-12 col-md-12">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <p>
                        <?php
                        echo $modelClase->ismAreaMateria->materia->nombre .
                            ' / ' . $modelClase->paralelo->course->name . '"' . $modelClase->paralelo->name .
                            '" / ' . $modelClase->profesor->last_name .
                            ' ' . $modelClase->profesor->x_first_name;
                        ?>
                    </p>
                </div>
            </div>
            <hr>

            <p>
                |
                <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>
                |
                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="far fa-file"></i> Mis clases de hoy</span>',
                    ['scholaris-asistencia-profesor/index'],
                    ['class' => 'link']
                );
                ?>
                |
                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Mis asignaturas</span>',
                    ['profesor-inicio/index'],
                    ['class' => 'link']
                );
                ?>
                |
            </p>

            <div class="row">
                <div class="col-lg-7 col-md-7">
                    <div class="table table-responsive" style="height: 500px; overflow-y: scroll">
                        <table class="table table-responsive table-bordered table-striped ">
                            <thead class="table-success">
                                <tr style=" text-align: center;color: #ab0a3d;">
                                    <th>#</th>
                                    <th>
                                        <table>
                                            <tr>
                                                <td>
                                                    <a href="#" class="btn-nn" onclick="show_obs_nee()">
                                                        <label class="btn-nn" style="background-color: #ab0a3d; border:3px solid brown;border-radius: 100px;">
                                                            <span style="color:white;"><?= count($modelNeeXClase) ?> </span>
                                                        </label>
                                                    </a>
                                                    <a href="#" class="btn-n" onclick="hide_obs_nee()">
                                                        <label class="btn-n" style="background-color: #ab0a3d; border:3px solid brown;border-radius: 100px;">
                                                            <span style="color:white;"><?= count($modelNeeXClase) ?> </span>
                                                        </label>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </th>
                                    <th>NAT</th>
                                    <th>Estudiantes <span>(<?= count($modelGrupo); ?>)</span></th>
                                    <th>Novedades
                                        <table>
                                            <tr>
                                                <td>#:</td>
                                                <td><?php echo count($numNovedades) . 'N de ' . count($numEstNovedades) . 'E ' ?></td>
                                            </tr>
                                        </table>
                                    </th>
                                    <th>Asisten / Faltan
                                        <?php $estAsisten = count($modelGrupo) - $numEstFaltaInjustificada;
                                        $estFaltan = $numEstFaltaInjustificada;
                                        ?>
                                        <table>
                                            <tr>
                                                <td>#:</td>
                                                <td><?php echo  "$estAsisten /  $estFaltan " ?></td>
                                            </tr>
                                        </table>
                                    </th>
                                    <th>
                                        F.I.: <?=$numEstFaltaInjustificada?>
                                    </th>
                                    <th>
                                        F.J.: <?=$numEstFaltaJustificada?>
                                    </th>
                                    <th>
                                        A.I.: <?=$numEstAtrasoJustificada?>
                                    </th>
                                    <th>
                                        A.J.: <?=$numEstAtrasoInjustificada?>
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $num = 0;

                                foreach ($modelGrupo as $alumno) {
                                    $num++;
                                    $numObsAlumno = 0;
                                    $color = '';

                                    //******** NUMERO  *****************//
                                    echo '<tr>';
                                    echo '<td>' . $num . '</td>';

                                    echo '<td class="text-center">';

                                    if ($modelNeeXClase) {
                                        //******** COLOR N.E.E  *****************// 
                                        foreach ($modelNeeXClase as $nee) {
                                            if ($alumno['estudiante_id'] == $nee['student_id']) {
                                                if ($nee['grado_nee'] == 1) {
                                                    $color = 'green';
                                                }
                                                if ($nee['grado_nee'] == 2) {
                                                    $color = 'orange';
                                                }
                                                if ($nee['grado_nee'] == 3) {
                                                    $color = 'red';
                                                }
                                                // echo '<td class="text-center"><i class="fas fa-circle btn-n" data-bs-toggle="modal" data-bs-target="#exampleModal'.$nee['id'].'" style="font-size:15px;color:' . $color . '; display:none" title="Grado: '.$nee['grado_nee'].'/ Fecha: '.$nee['fecha_inicia'].'/ Det: '.$nee['diagnostico_inicia'].'"></i></td>'; 
                                                echo '<i class="fas fa-circle btn-n" data-bs-toggle="modal" data-bs-target="#exampleModal' . $nee['id'] . '" style="font-size:15px;color:' . $color . '; display:none" title="Grado: ' . $nee['grado_nee'] . '/ Fecha: ' . $nee['fecha_inicia'] . '/ Det: ' . $nee['diagnostico_inicia'] . '"></i>';

                                                echo '<div class="modal fade" id="exampleModal' . $nee['id'] . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >';
                                                echo '<div class="modal-dialog modal-dialog-centered" >';
                                                echo '<div class="modal-content" style="background:#FFFFFF" role="dialog">';
                                                echo '<div class="modal-header">';
                                                echo '<h5 class="modal-title" id="exampleModalLabel">' . $alumno['last_name'] . ' ' . $alumno['first_name'] . '</h5>';
                                                echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                                echo '</div>';
                                                echo '<div class="modal-body">';
                                                echo 'Grado: ' . $nee['grado_nee'];
                                                echo '<br/>';
                                                echo 'Fecha: ' . $nee['fecha_inicia'];
                                                echo '<br/>';
                                                echo 'Detalle: ' . $nee['diagnostico_inicia'];
                                                echo '<br/>';
                                                echo 'Recomendaciones: ' . $nee['recomendacion_clase'];
                                                echo '<br/>';
                                                echo '</div>';
                                                // echo '<div class="modal-footer">';
                                                //     echo '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>';                                                    
                                                // echo '</div>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</div>';
                                            } else {
                                                // echo '<td></td>';
                                                echo '';
                                            }
                                        }
                                    } else {
                                        // echo '<td></td>';
                                        echo '';
                                    }

                                    echo '</td>';
                                    //******** N.A.T  *****************// 
                                    if ($alumno['student_state'] == 'N') {

                                        echo '<td class="text-center"><a data-bs-toggle="modal" data-bs-target="#exampleModalTr' . $alumno['id'] . '">N</a></td>';
                                        echo '<div class="modal fade" id="exampleModalTr' . $alumno['id'] . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >';
                                        echo '<div class="modal-dialog modal-dialog-centered" >';
                                        echo '<div class="modal-content" style="background:#FFFFFF" role="dialog">';
                                        echo '<div class="modal-header">';
                                        echo '<h5 class="modal-title" id="exampleModalLabel">' . $alumno['last_name'] . ' ' . $alumno['first_name'] . '</h5>';
                                        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                        echo '</div>';
                                        echo '<div class="modal-body">';
                                        echo 'Instituto procedencia: ' . $alumno['x_origin_institute'];
                                        echo '<br/>';
                                        echo 'Resultados: ';
                                        echo '<br/>';
                                        echo 'Observaciones: ';
                                        echo '<br/>';
                                        echo 'Recomendaciones: ';
                                        echo '<br/>';
                                        echo '</div>';
                                        // echo '<div class="modal-footer">';
                                        //     echo '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>';                                                    
                                        // echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                    } else {
                                        echo '<td class="text-center">' . $alumno['student_state'] . '</td>';
                                    }


                                    //******** ESTUDIANTES  *****************//
                                    echo '<td>' . $alumno['last_name'] . ' ' . $alumno['first_name'] . '</td>';                                    

                                    //******** NOVEDADES  *****************//
                                    $numObsAlumno = consulta_num_falta_por_alumno($numNovedadesPorMateria, $alumno['id']);
                                    echo '<td class="text-center">';
                                    echo Html::a(
                                        $numObsAlumno, //count($total),
                                        ['detalle', "alumnoId" => $alumno['estudiante_id'], 'asistenciaId' => $modelAsistencia->id],
                                        ['class' => 'link']
                                    );
                                    echo '</td>';

                                    //******** ASISTENCIA  *****************//
                                    //verifica si existe un registro con falta.
                                    $fi="1c";
                                    $fj='1b';
                                    $ai='1a';
                                    $aj='1d';
                                    $neutro ='';
                                    $colorIcono = 'green';

                                    $respFI = consulta_falta_automatica($numNovedadesEspecificasPorMateria, $fi, $alumno['id']);
                                    $respFJ = consulta_falta_automatica($numNovedadesEspecificasPorMateria, $fj, $alumno['id']);
                                    $respAI = consulta_falta_automatica($numNovedadesEspecificasPorMateria, $ai, $alumno['id']);
                                    $respAJ = consulta_falta_automatica($numNovedadesEspecificasPorMateria, $aj, $alumno['id']);

                                    if ($respFI) { $colorIcono="red"; }
                                    else if ($respFJ) {$colorIcono="blue";}
                                    else if ($respAI) {$colorIcono="brown";}
                                    else if ($respAJ) {$colorIcono="black";}
                                    else {$colorIcono="green";}       

                                    echo '<td class="text-center"><a href="#" onclick="borrar_falta_a_clases_estudiante(' . $alumno['estudiante_id'] . ',' . $modelAsistencia->id . ',\''.$neutro.'\')"><span class="fas fa-user" style="color:'.$colorIcono.'"></span></a></td>';
                                    echo '<td class="text-center"><a href="#" onclick="falta_a_clases_estudiante(' . $alumno['estudiante_id'] . ',' . $modelAsistencia->id . ',\''.$fi.'\')"><span class="badge rounded-pill" style="background-color: red ;color:white;font-size:14px;">F.I.</span></a></td>';
                                    echo '<td class="text-center"><a href="#" onclick="falta_a_clases_estudiante(' . $alumno['estudiante_id'] . ',' . $modelAsistencia->id . ',\''.$fj.'\')"><span class="badge rounded-pill" style="background-color: blue ;color:white;font-size:14px;">F.J.</span></a></td>';
                                    echo '<td class="text-center"><a href="#" onclick="falta_a_clases_estudiante(' . $alumno['estudiante_id'] . ',' . $modelAsistencia->id . ',\''.$ai.'\')"><span class="badge rounded-pill" style="background-color: brown ;color:white;font-size:14px;">A.I.</span></a></td>';
                                    echo '<td class="text-center"><a href="#" onclick="falta_a_clases_estudiante(' . $alumno['estudiante_id'] . ',' . $modelAsistencia->id . ',\''.$aj.'\')"><span class="badge rounded-pill" style="background-color: black ;color:white;font-size:14px;">A.J.</span></a></td>';
                                    

                                    echo '</tr>';
                                } //FIN FOREACH
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5">
                    <div class="row shadow" style="margin-right: 10px">
                        <p>
                            <b><u>
                                    <h4>Destreza / Actividades para la clase de hoy:</h4>
                                </u></b>
                        </p>
                        <div class="">
                            <table class="table table-responsive table-bordered table-striped ">
                                <thead class="table-success">
                                    <tr style="color:#ab0a3d">
                                        <th>TÍTULO</th>
                                        <th>DESCRIPCIÓN</th>
                                        <th>TAREAS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($modelActividades as $data) {
                                        //$modelHora = backend\models\ScholarisHorariov2Hora::findOne($data->hora_id);
                                        echo '<tr>';
                                        echo '<td>';
                                        echo '<p><strong> ■ ' . $data->title . '</strong></p>';
                                        echo '</td>';
                                        echo '<td>';
                                        echo '<p>' . $data->descripcion . '</p>';
                                        echo '</td>';
                                        echo '<td>';
                                        echo '<p>' . $data->tareas . '</p>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                <tbody>
                            </table>
                        </div>
                    </div>

                    <hr><!-- para los temas dictados en la clase de hoy -->
                    <div class="row shadow" style="margin-right: 10px">
                        <p>
                            <b><u>
                                    <h6>Temas Adicionales</h6>
                                </u></b>
                        </p>
                        <?php
                        echo Html::a(
                            '<i class="fas fa-file"></i> Adicionar Temas',
                            ['nuevotema', "asistenciaId" => $modelAsistencia->id],
                            ['class' => 'link']
                        );
                        ?>

                        <div class="table table-responsive">
                            <table class="table table-responsive table-bordered table-striped ">
                                <thead class="table-success">
                                    <tr style="color:#ab0a3d">
                                        <th>TEMA</th>
                                        <th>OBSERVACIÓN</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($modelTemas as $tema) {
                                        echo '<tr>';
                                        echo '<td>' . $tema->tema . '</td>';
                                        echo '<td>' . $tema->observacion . '</td>';
                                        echo '<td>';
                                        echo Html::a('<i class="fas fa-ban"></i> Eliminar', ['quitartema', "id" => $tema->id], ['class' => 'link']);
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--/********************************************************************************************************************* */-->
<!--PROGRAMACION PHP-->
<?php

function consulta_falta_automatica($numNovedadesEspecificasPorMateria, $codigo, $idAlumno)
{
    /*
        Creado Por: Santiago / Fecha Creacion: 
        Modificado Por: Santiago	/ Fecha Modificación: 2023-03-15
        Detalle: $numNovedadesPorMateria, contiene todas las novedades de la clase pero las especificas de codigo 1a,1b,1c,1d este metodo devuelve 
                por cada alumno, para asignar el color al icono
    */ 
    $resp = false;
    $modelNovedad = ScholarisAsistenciaComportamientoDetalle::find()
    ->where(['codigo'=>$codigo])
    ->one();   

    foreach ($numNovedadesEspecificasPorMateria as $fila) {
        if (($fila['grupo_id']) == $idAlumno and ($fila['comportamiento_detalle_id']) == $modelNovedad->id) {
            $resp = true;
        }
    }
    return $resp;
}

function consulta_num_falta_por_alumno($numNovedadesPorMateria, $idAlumno)
{
    /*
        Creado Por: Santiago / Fecha Creacion: 
        Modificado Por: Santiago	/ Fecha Modificación: 2023-03-15
        Detalle: $numNovedadesPorMateria, contiene todas las novedades de la clase, este metodo devuelve 
                por cada alumno, para asignar en cada fila el numero de novedades
    */

    $resp = 0;
    foreach ($numNovedadesPorMateria as $fila) {
        if (($fila['grupo_id']) == $idAlumno) {
            $resp = $resp + 1;
        }
    }
    return $resp;
}

?>

<!--/********************************************************************************************************************* */-->
<!--PROGRAMACION JAVASCRIPT-->
<script>
    window.onload = function() {
        hide_obs_nee()
    }

    function show_obs_nee() {
        $(".btn-n").show();
        $(".btn-nn").hide();
    }

    function hide_obs_nee() {
        $(".btn-n").hide();
        $(".btn-nn").show();
    }

    function falta_a_clases_estudiante(alumnoId, claseId,codigoNovedad) 
    {
        var url = '<?= Url::to(['comportamiento/falta-auto-estudiante']) ?>';  
        params = {
            idAlumno: alumnoId,
            idClase: claseId,
            codigoNovedad: codigoNovedad
        }

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function() {},
            success: function() {
                location.reload();
            }
        });
        
    }

    function borrar_falta_a_clases_estudiante(alumnoId, claseId,codigoNovedad) {
        var url = '<?= Url::to(['comportamiento/borrar-falta-auto-estudiante']) ?>';
        params = {
            idAlumno: alumnoId,
            idClase: claseId,
            codigoNovedad: codigoNovedad
        }
        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function() {},
            success: function() {
                    location.reload();
            }
        });
    }
</script>