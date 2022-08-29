<?php



use backend\models\DeceCasos;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\DeceMotivos;
use backend\models\DeceRegistroSeguimiento;
use backend\models\PlanificacionOpciones;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceCasos */
/* @var $form yii\widgets\ActiveForm */
//*** motivos */ 

$arrayEstado = cargaArreglo("estado_seg");
$arrayMotivo = cargaArreglo("motivo");
function cargaArreglo($campo)
{
    $consulta = DeceMotivos::find()
        ->select([$campo,])
        ->distinct()
        ->where(['not', [$campo => null]])
        ->asArray()
        ->all();

    $array = array();
    //recorremos arreglo
    foreach ($consulta as $dato) {
        $array[$dato[$campo]] = $dato[$campo];
    }
    return $array;
}
//busca todos los casos del estudiante
$modelCasosHist = DeceCasos::find()
    ->where(['id_estudiante' => $model->id_estudiante])
    ->andWhere(['id_periodo' => $model->id_periodo])
    ->all();

//buscamos el numero de seguimientos que tiene el alumno
$modelRegSeguimiento = DeceRegistroSeguimiento::find()
    ->where(['id_estudiante' => $model->id_estudiante])
    ->orderBy(['estado' => SORT_DESC, 'fecha_inicio' => SORT_ASC])
    ->all();

//busca el path de los archivos donde se guardan los datos de dece
$modelPathArchivo = PlanificacionOpciones::find()
->where(['tipo'=>'VER_ARCHIVO'])
->one();

?>
<script src="//cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>

<div class="dece-casos-create" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h3><img src="ISM/main/images/submenu/autismo.png" width="64px" class="img-thumbnail"></h3>                    
                </div>
                
                <div class="col-lg-11">
                    <h3>DECE - CASOS</h3>
                </div>
                <!-- FIN DE CABECERA -->
                <!-- inicia menu  -->
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <!-- menu izquierda -->
                        |
                        <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                        ?>
                        <?php
                        if ($model->id_clase == 0) //si es mayor a cero, biene de leccionario
                        {
                        ?>
                            |
                            <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: blue"><i class="fa fa-briefcase" aria-hidden="true"></i>Regresar Dece Casos</span>',
                                ['dece-casos/index'],
                                ['class' => 'link']
                            );
                            ?>
                        <?php
                        }
                        ?>
                        |
                        <?php
                        if ($model->id_clase > 0) //si es mayor a cero, biene de leccionario
                        {
                        ?>
                            <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i>Regresar - Mi Clase</span>',
                                ['comportamiento/index', 'id' => $modelAsistProfesor->id],
                                ['class' => 'link']
                            );
                            ?>
                        <?php
                        }
                        ?>


                    </div> <!-- fin de menu izquierda -->

                    <div class="col-lg-6 col-md-6" style="text-align: right;">
                        <!-- inicio de menu derecha -->

                    </div><!-- fin de menu derecha -->
                </div>
                <!-- finaliza menu menu  -->
                <hr>
                <div class="row">
                    <div class="col">
                        <span><b>Estudiante: </b>
                            <?php $nombreEstudiante = $model->estudiante->last_name.' '.$model->estudiante->middle_name . ' ' . $model->estudiante->first_name?>
                           <span style="color:red"><?= $nombreEstudiante ?><span>
                        </span>
                    </div>
                    <div class="col">
                        <span><b>Periodo: </b>
                            <?php $periodo = $model->periodo->nombre ?>
                            <span style="color:red"><?= $periodo ?><span>
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="dece-casos-form col-lg-12 col-ms-12">
                        <div class="row">
                            <table>
                                <tr>
                                    <td>
                                        <h5 style="color:red">Casos</h5>
                                    </td>
                                    <td>
                                        <?= Html::a(
                                            '<span class="badge  rounded-pill" style="background-color:blue;">Crear Casos</span>',
                                            ['create','idEstudiante'=>$model->id_estudiante],
                                            ['class' => 'link']
                                        ); ?>
                                    </td>
                                </tr>
                            </table>


                            <div style="overflow:scroll;">
                                <table class="table table-success table-striped table-bordered my-text-small">
                                    <tr class="table-primary">
                                        <td><b>Caso</b></td>
                                        <td><b>Fecha Creación</b></td>
                                        <td><b>Última Modificación</b></td>
                                        <td><b>Estado</b></td>
                                        <td><b>Motivo</b></td>
                                        <td><b>Editar</b></td>
                                        <td><b>Ver</b></td>
                                    </tr>
                                    <?php
                                    foreach ($modelCasosHist as $modelReg) {

                                    ?>
                                        <tr>
                                            <td><?= $modelReg->numero_caso ?></td>
                                            <td><?= $modelReg->fecha_inicio ?></td>
                                            <td><?= $modelReg->fecha_fin ?></td>
                                            <td><?= $modelReg->estado ?></td>
                                            <td><?= $modelReg->motivo ?></td>
                                            <td>
                                                <?=
                                                Html::a(
                                                    '<i class="fa fa-edit" aria-hidden="true"></i>',
                                                    ['dece-casos/update', 'id' => $modelReg->id],
                                                    ['class' => 'link']
                                                );
                                                ?>
                                            </td>

                                            <td>
                                                <!--boton VER  boton llama modal -->
                                                <button type="button" class="rounded-pill" data-bs-toggle="modal" data-bs-target="<?php echo "#staticBackdrop_caso_$modelReg->id"; ?>">
                                                    <i class="fas fa-glasses" style="color:blueviolet;"></i>
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="<?php echo "staticBackdrop_caso_$modelReg->id"; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="staticBackdropLabel"><b>Dece Caso : <?= $modelReg->numero_caso ;?></b></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-striped table-hover">
                                                                    <!-- <tr>
                                                                        <td><b>Fecha Creación: </b></td>
                                                                        <td><?= substr($modelReg->fecha_inicio, 0, 10) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Última Modificación: </b></td>
                                                                        <td><?= substr($modelReg->fecha_fin, 0, 10)  ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Estado: </b></td>
                                                                        <td><?= $modelReg->estado ?></td>
                                                                    </tr> -->
                                                                    <tr>
                                                                        <td><b>Motivo: </b></td>
                                                                        <td><?= $modelReg->motivo ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Detalle: </b></td>
                                                                        <td><?= $modelReg->detalle ?></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    } //fin for
                                    ?>
                                </table>
                            </div>
                        </div>   <!-- fin dif casos -->
                        
                        <div>
                            <hr>
                            <h6>Ejes de Acción</h6>
                            <hr>
                        </div>
                        <div class="row">
                            <h6 style="color:red">Acompañamiento</h6>
                            <div style="overflow-x:scroll;overflow-y:scroll;">
                                <table class="table table-success table-striped table-bordered my-text-small">
                                    <tr class="table-primary">
                                        <td><b>Caso</b></td>
                                        <td><b>No. Seg.</b></td>
                                        <td><b>Fecha Creación</b></td>
                                        <td><b>Última Modificación</b></td>
                                        <td><b>Estado</b></td>
                                        <td><b>Motivo</b></td>
                                        <td><b>Editar</b></td>
                                        <td><b>Ver</b></td>
                                    </tr>
                                    <?php
                                    foreach ($modelRegSeguimiento as $modelReg) {

                                    ?>
                                        <tr>
                                            <td><?= $modelReg->caso->numero_caso ?></td>
                                            <td><?= $modelReg->numero_seguimiento ?></td>
                                            <td><?= $modelReg->fecha_inicio?></td>
                                            <td><?= $modelReg->fecha_fin?></td>
                                            <td><?= $modelReg->estado ?></td>
                                            <td><?= $modelReg->motivo ?></td>
                                            <td>
                                                <?=
                                                Html::a(
                                                    '<i class="fa fa-edit" aria-hidden="true"></i>',
                                                    ['dece-registro-seguimiento/update', 'id' => $modelReg->id],
                                                    ['class' => 'link']
                                                );
                                                ?>
                                            </td>

                                            <td>
                                                <!--boton VER  boton llama modal -->
                                                <button type="button" class="rounded-pill" data-bs-toggle="modal" data-bs-target="<?php echo "#staticBackdrop_seg_$modelReg->id"; ?>">
                                                    <i class="fas fa-glasses" style="color:blueviolet;"></i>
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="<?php echo "staticBackdrop_seg_$modelReg->id"; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="staticBackdropLabe2"><b>Seguimiento : <?= $modelReg->numero_seguimiento; ?></b></h5>                                                            

                                                                <h6 class="modal-title" id="staticBackdropLabe3"><b>Caso : <?= $modelReg->id_caso; ?></b></h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <table class="table table-striped table-hover">                                                                    
                                                                    <!-- <tr>
                                                                        <td><b>Fecha Creación: </b></td>
                                                                        <td><?= $modelReg->fecha_inicio ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Última Modificación: </b></td>
                                                                        <td><?= $modelReg->fecha_fin ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Estado: </b></td>
                                                                        <td><?= $modelReg->estado ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Motivo: </b></td>
                                                                        <td><?= $modelReg->motivo ?></td>
                                                                    </tr> -->
                                                                    <tr>
                                                                        <td><b>Pronunciamiento: </b></td>
                                                                        <td><?= $modelReg->pronunciamiento ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Acuerdo y Compromiso: </b></td>
                                                                        <td><?= $modelReg->acuerdo_y_compromiso ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Evidencia: </b></td>
                                                                        <td><?= $modelReg->eviencia ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Atendido Por: </b></td>
                                                                        <td><?= $modelReg->atendido_por ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Responsable Seguimiento:</b></td>
                                                                        <td><?= $modelReg->responsable_seguimiento ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <?php $arrayArchivo = array("", "");
                                                                        if (strlen($modelReg->path_archivo) > 0) {
                                                                            $arrayArchivo = explode("##", $modelReg->path_archivo);
                                                                        }
                                                                        ?>
                                                                        <td><b>Archivo: </b></td>
                                                                        <td><a target="_blank" href="<?= $modelPathArchivo->opcion . $arrayArchivo[0] . '/' . $arrayArchivo[1] ?>">
                                                                                <?= $arrayArchivo[1] ?>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    } //fin for
                                    ?>
                                </table>
                            </div>

                        </div> <!-- fin acompaniamiento -->
                        <div class="row">
                            ************************************************************************************
                            <h6 style="color:red"> Detección</h6>
                            <div style="overflow-x:scroll;overflow-y:scroll;">
                                <table class="table table-success table-striped table-bordered my-text-small">
                                    <tr class="table-primary">
                                        <td><b>Caso</b></td>
                                        <td><b>No. Seg.</b></td>
                                        <td><b>Fecha Creación</b></td>
                                        <td><b>Última Modificación</b></td>
                                        <td><b>Estado</b></td>
                                        <td><b>Motivo</b></td>
                                        <td><b>Editar</b></td>
                                        <td><b>Ver</b></td>
                                    </tr>
                                    <?php
                                    foreach ($modelRegDeteccion as $modelReg) {
                                    ?>

                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div> <!-- fin Deteccion -->
                        <div class="row">
                            ************************************************************************************
                            <h6 style="color:red"> Derivación</h6>
                            <div style="overflow-x:scroll;overflow-y:scroll;">
                                <table class="table table-success table-striped table-bordered my-text-small">
                                    <tr class="table-primary">
                                        <td><b>Caso</b></td>
                                        <td><b>No. Seg.</b></td>
                                        <td><b>Fecha Creación</b></td>
                                        <td><b>Última Modificación</b></td>
                                        <td><b>Estado</b></td>
                                        <td><b>Motivo</b></td>
                                        <td><b>Editar</b></td>
                                        <td><b>Ver</b></td>
                                    </tr>
                                    <?php
                                    foreach ($modelRegDerivacion as $modelReg) {
                                    ?>

                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div> <!-- fin Derivacion -->
                        <div class="row">
                            ************************************************************************************
                            <h6 style="color:red"> Intervención</h6>
                            <div style="overflow-x:scroll;overflow-y:scroll;">
                                <table class="table table-success table-striped table-bordered my-text-small">
                                    <tr class="table-primary">
                                        <td><b>Caso</b></td>
                                        <td><b>No. Seg.</b></td>
                                        <td><b>Fecha Creación</b></td>
                                        <td><b>Última Modificación</b></td>
                                        <td><b>Estado</b></td>
                                        <td><b>Motivo</b></td>
                                        <td><b>Editar</b></td>
                                        <td><b>Ver</b></td>
                                    </tr>
                                    <?php
                                    foreach ($modelRegIntervencion as $modelReg) {
                                    ?>

                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div> <!-- fin Intervencio -->
                    </div>
                </div>
            </div>
        </div>
    </div>