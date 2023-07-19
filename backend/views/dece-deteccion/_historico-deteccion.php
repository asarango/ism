<?php
use backend\models\DeceDeteccion;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\helpers\HelperGeneral;
use backend\models\OpParent;
use backend\models\OpStudent;
use backend\models\ResPartner;
?>
<h4 style="color:red">Histórico Detección</h4>
                <div style="overflow-x:scroll;overflow-y:scroll;">
                    <table class="table table-success table-striped table-bordered my-text-small">
                        <tr class="table-primary">
                            <td>No.</td>
                            <td>Fecha Creación</td>
                            <td>Editar</td>
                            <td>Ver</td>
                        </tr>
                        <?php if ($modelRegDeteccion) {
                            foreach ($modelRegDeteccion as $modelReg) {
                        ?>
                                <tr>
                                    <td><?= $modelReg->numero_deteccion ?></td>
                                    <td><?= $modelReg->fecha_reporte ?></td>

                                    <td>
                                        <?=
                                        Html::a(
                                            '<i class="fa fa-edit" aria-hidden="true"></i>',
                                            ['dece-deteccion/update', 'id' => $modelReg->id],
                                            ['class' => 'link']
                                        );
                                        ?>
                                    </td>
                                    <td>
                                        <!--boton VER  boton llama modal -->
                                        <button type="button" class="rounded-pill" data-bs-toggle="modal" data-bs-target="<?php echo "#staticBackdrop$modelReg->id"; ?>">
                                            <i class="fas fa-glasses" style="color:blueviolet;"></i>
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="<?php echo "staticBackdrop$modelReg->id"; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel"><b>Derivación No: <?= $modelReg->id ?></b></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped table-hover" style="font-size:15px;">
                                                            <tr>
                                                                <td><b>Fecha Creación: </b></td>
                                                                <td><?= $modelReg->fecha_reporte ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Nombre Quien Reporta: </b></td>
                                                                <td><?= $modelReg->nombre_quien_reporta ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Hora Aproximada: </b></td>
                                                                <td><?= $modelReg->hora_aproximada ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Descripción del Hecho: </b></td>
                                                                <td><?= $modelReg->descripcion_del_hecho ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Acciones Realizadas: </b></td>
                                                                <td><?= $modelReg->acciones_realizadas ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Listar Evidencias: </b></td>
                                                                <td><?= $modelReg->lista_evidencias ?></td>
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
                        } //fin if
                        ?>
                    </table>
                </div>
            </div>