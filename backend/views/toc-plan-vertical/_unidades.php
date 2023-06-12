<?php
use yii\helpers\Html;
use yii\helpers\Url;

// echo "<pre>";
// print_r($unidades);
// die();
?>
<h4 class="plan-uni">PLAN DE UNIDADES</h4>
<?php
foreach ($unidades as $unidad) {

    ?>
    <!-- Inicio Unidades -->
    <hr>
    <div class="col-lg-12 col-md-12">
        <div class="" style="margin-top:-5px;">
            <div class="row">
                <p>
                    <a class="btn btn segunda_tabla boton text-white" data-bs-toggle="collapse"
                        href="#detalle<?= $unidad->id ?>" role="button" aria-expanded="false"
                        aria-controls="collapseExample">
                        "<?php echo $unidad->bloque->name ?>"
                    </a>
                    <a style="margin-right: 400px ;">
                        <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #ff9e18"><i 
                            class="fa fa-briefcase" aria-hidden="true"></i> Modificar plan</span>',
                                ['site/index'],
                                ['class' => 'link']
                            );
                        ?>
                        |
                        <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i 
                            class="fa fa-briefcase" aria-hidden="true"></i> Ir a detalles </span>',
                                ['site/index'],
                                ['class' => 'link']
                            );
                        ?>
                        |
                        <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #9e28b5"><i 
                            class="fa fa-briefcase" aria-hidden="true"></i> Habilidades IB </span>',
                                ['site/index'],
                                ['class' => 'link']
                            );
                        ?>
                    </a>
                </p>
            </div>
            <div class="collapse" id="detalle<?= $unidad->id ?>">
                <div class="card card-body">
                    <div class="segunda_tabla">
                        <h6 class="plan-uni">
                            <?= $unidad['titulo']; ?>

                        </h6>

                    </div>

                    <table class="table align-middle">
                        <thead style="text-align: center; margin-left: 400px">
                        </thead>
                        <thead>
                            <td width="210px" class="segunda_tabla lin_tabla" style="text-align: center;">
                                OBJETIVOS DE LA
                                UNIDAD</td>
                            <td width="160px" class="align-top segunda_tabla lin_tabla" style="text-align: center;">
                                CONCEPTOS CLAVE</td>
                            <td class=" segunda_tabla lin_tabla" style="text-align: center;">CONTENIDO
                            </td>
                            <td width="190px" <?= $unidad['conceptos_clave']; ?> class="segunda_tabla lin_tabla"
                                style="text-align: center;">
                                HABILIDADES IB
                            </td>
                            <td width="190px" class="segunda_tabla lin_tabla" style="text-align: center;">
                                EVALUACIÓN PD
                            </td>
                            </tr>
                        </thead>
                        <tbody>
                            <td class="vertical-align: middle; links" style="text-align: center;">

                                <?= $unidad['objetivos']; ?>
                            </td>
                            <td class="vertical-align: middle; links" style="text-align: center;">
                                <?= $unidad['conceptos_clave']; ?>
                            </td>
                            <td class="vertical-align: middle; links" style="text-align: center;">
                                <?= $unidad['contenido']; ?>
                            </td>
                            <td class="vertical-align: middle; links" style="text-align: center;">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. </td>
                            <td class="vertical-align: middle; links" style="text-align: center;">
                                <?= $unidad['evaluacion_pd']; ?>
                            </td>
                        </tbody>
                        <thead>
                            <!-- <td class="segunda_tabla lin_tabla" style="text-align: center;">TÍTULO DE LA UNIDAD
                                    </td>
                                    <td class="align-top segunda_tabla lin_tabla" style="text-align: center;">CONCEPTOS
                                        CLAVE</td>
                                    <td class=" segunda_tabla lin_tabla" style="text-align: center;">CONTENIDO</td>
                                    <td class="segunda_tabla lin_tabla" style="text-align: center;">HABILIDADES IB</td>
                                    <td class="segunda_tabla lin_tabla" style="text-align: center;">EVALUACIÓN PD</td> -->
                            </tr>
                        </thead>
                    </table>
                    <!-- <a type="button" class="btn btn-success segunda_tabla boton">Guardar</a>  -->
                </div>
            </div>
        </div>
    </div>
    <!-- FIN DE UNIDADES -->
    <?php
}
?>