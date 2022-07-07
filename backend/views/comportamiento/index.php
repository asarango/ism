<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\ScholarisAsistenciaAlumnosNovedades;
use yii\helpers\Url;
use backend\models\PlanificacionOpciones;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registro de comportamiento estudiantil';

//extraccion parametros para faltas automaticas
$modelOp = PlanificacionOpciones::find()->where([
    'tipo' => 'FALTA_A_CLASES'
])->asArray()->all();
$idFalta = $modelOp[0]['opcion']; //id
$obsFalta = $modelOp[1]['opcion']; //obs

//revision de numero de observaciones, por materia
$total = ScholarisAsistenciaAlumnosNovedades::find()
    ->where([
        'asistencia_profesor_id' => $modelAsistencia->id
    ])
    ->all();

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
$numEstSinAsistir = ScholarisAsistenciaAlumnosNovedades::find()
    ->select(["grupo_id"])
    ->distinct()
    ->where([
        'asistencia_profesor_id' => $modelAsistencia->id,
        'comportamiento_detalle_id' => $idFalta //id, para contar alumnos con no asistencia
    ])
    ->asArray()->all();

// echo '<pre>';
// print_r($modelGrupo);
// die();
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comportamiento-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-11 col-md-11">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
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
                <div class="col-lg-5 col-md-5">
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
                                                        <label class="btn-nn"  style="background-color: #ab0a3d; border:3px solid brown;border-radius: 100px;">
                                                            <span style="color:white;"><?= count($modelNeeXClase) ?> </span>
                                                        </label>
                                                    </a>
                                                    <a href="#" class="btn-n" onclick="hide_obs_nee()">
                                                        <label class="btn-n"  style="background-color: #ab0a3d; border:3px solid brown;border-radius: 100px;">
                                                            <span style="color:white;"><?= count($modelNeeXClase) ?> </span>
                                                        </label>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                     </th>
                                    <th>Estudiantes <span>(<?= count($modelGrupo);?>)</span></th>
                                    <th>Novedades
                                        <table>
                                            <tr>
                                                <td >#:</td>
                                                <td ><?php echo count($numNovedades ). 'N de ' . count($numEstNovedades).'E ' ?></td>
                                            </tr>
                                        </table>
                                    </th>
                                    <th>Asisten / Faltan
                                        <?php $estAsisten = count($modelGrupo)-count($numEstSinAsistir) ;
                                              $estFaltan = count($numEstSinAsistir) ;
                                        ?>
                                        <table>
                                            <tr>
                                                <td>#:</td>
                                                <td><?php echo  "$estAsisten /  $estFaltan "?></td>
                                            </tr>
                                        </table>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php                              
                                $num=0;  
   
                                foreach ($modelGrupo as $alumno) {
                                    $num++;
                                    $numObsAlumno = 0;
                                    $color = '';

                                    //******** NUMERO  *****************//
                                    echo '<tr>';
                                    echo '<td>' . $num . '</td>';
                                    
                                    if($modelNeeXClase){
                                            //******** COLOR N.E.E  *****************// 
                                    foreach($modelNeeXClase as $nee)
                                    {
                                        if($alumno['estudiante_id'] == $nee['student_id']) 
                                        {
                                            if($nee['grado_nee']==1){$color = 'green';}
                                            if($nee['grado_nee']==2){$color = 'orange';}
                                            if($nee['grado_nee']==3){$color = 'red';} 
                                            echo '<td class="text-center"><i class="fas fa-circle btn-n" data-bs-toggle="modal" data-bs-target="#exampleModal" style="font-size:15px;color:' . $color . '; display:none" title="Grado: '.$nee['grado_nee'].'/ Fecha: '.$nee['fecha_inicia'].'/ Det: '.$nee['diagnostico_inicia'].'"></i></td>';  
                                           
                                            echo '<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >';
                                            echo '<div class="modal-dialog modal-dialog-centered" >';
                                                echo '<div class="modal-content" style="background:#FFFFFF" role="dialog">';
                                                echo '<div class="modal-header">';
                                                    echo '<h5 class="modal-title" id="exampleModalLabel">'.$alumno['last_name'] . ' ' . $alumno['first_name'].'</h5>';
                                                    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                                echo '</div>';
                                                echo '<div class="modal-body">';
                                                    echo 'Grado: '.$nee['grado_nee'];
                                                    echo '<br/>';
                                                    echo 'Fecha: '.$nee['fecha_inicia'];
                                                    echo '<br/>';
                                                    echo 'Detalle: '.$nee['diagnostico_inicia'];   
                                                    echo '<br/>';                                                 
                                                echo '</div>';
                                                // echo '<div class="modal-footer">';
                                                //     echo '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>';                                                    
                                                // echo '</div>';
                                                echo '</div>';
                                            echo '</div>';
                                            echo '</div>';                                          
                                        }
                                        else {
                                            echo '<td></td>';
                                        }
                                    }
                                    }else{
                                        echo '<td></td>';
                                    }                                 

                                    //******** ESTUDIANTES  *****************//
                                    echo '<td>' . $alumno['last_name'] . ' ' . $alumno['first_name'] . '</td>';
                                    $numObsAlumno = consulta_num_falta_por_alumno($total, $alumno['id']);

                                    //******** NOVEDADES  *****************//
                                    echo '<td class="text-center">';
                                    echo Html::a(
                                        $numObsAlumno, //count($total),
                                        ['detalle', "alumnoId" => $alumno['estudiante_id'], 'asistenciaId' => $modelAsistencia->id],
                                        ['class' => 'link']
                                    );
                                    echo '</td>';

                                    //******** ASISTENCIA  *****************//
                                    //verifica si existe un registro con falta.
                                    $resp = consulta_falta_automatica($total, $idFalta, $alumno['id']);
                                    if ($resp) {
                                        echo '<td class="text-center"><a href="#" onclick="borrar_falta_a_clases_estudiante(' . $alumno['estudiante_id'] . ',' . $modelAsistencia->id . ')"><span id="\'' . $alumno['estudiante_id'] . '\'" class="fas fa-user-times" style="color:red"></span></a></td>';
                                    } else {
                                        echo '<td class="text-center"><a href="#" onclick="falta_a_clases_estudiante(' . $alumno['estudiante_id'] . ',' . $modelAsistencia->id . ')"><span id="\'' . $alumno['estudiante_id'] . '\'" class="fas fa-user-check" style="color:green"></span></a></td>';
                                    }
                                    echo '</tr>';
                                } //FIN FOREACH
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7">
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

function consulta_falta_automatica($total, $idFalta, $idAlumno)
{
    //consulta si existe una falta, con el codigo 1ch, por cada alumno, para cambiar el icono de asistencia
    $resp = false;
    foreach ($total as $fila) {
        if (($fila->grupo_id) == $idAlumno and ($fila->comportamiento_detalle_id) == $idFalta) {
            $resp = true;
        }
    }
    return $resp;
}
function consulta_num_falta_por_alumno($total, $idAlumno)
{
    //consulta el numero de faltas por estudiante    
    $resp = 0;
    foreach ($total as $fila) {
        if (($fila->grupo_id) == $idAlumno) {
            $resp = $resp + 1;
        }
    }
    return $resp;
}

?>

<!--/********************************************************************************************************************* */-->
<!--PROGRAMACION JAVASCRIPT-->
<script>  
    window.onload = function () {
        hide_obs_nee() 
    }
    function show_obs_nee() 
    {        
        $(".btn-n").show(); 
        $(".btn-nn").hide();          
    }
    function hide_obs_nee() 
    {        
        $(".btn-n").hide();    
        $(".btn-nn").show();          
    }

    function falta_a_clases_estudiante(alumnoId, claseId) {
        var url = '<?= Url::to(['comportamiento/falta-auto-estudiante']) ?>';

        params = {
            idAlumno: alumnoId,
            idClase: claseId
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

    function borrar_falta_a_clases_estudiante(alumnoId, claseId) {
        var url = '<?= Url::to(['comportamiento/borrar-falta-auto-estudiante']) ?>';
        params = {
            idAlumno: alumnoId,
            idClase: claseId
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