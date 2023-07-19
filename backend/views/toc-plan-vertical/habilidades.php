<?php
use Mpdf\Tag\Span;
use yii\helpers\Html;
use yii\helpers\Url;

// echo "<pre>";
// print_r($habilidades);
// die();
?>


<!-- INICIO ENCABEZADO -->
<div class="m-0 vh-50 row justify-content-center align-items-center">
    <div class="card shadow col-lg-11 col-md-11">
        <div class=" row align-items-center p-2">
            <div class="col-lg-1 col-md-1 col-sm-1">
                <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                        class="img-thumbnail">
                </h4>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-7"> 
                <h3>Habilidades IB</h3>
                <p>2DO. DE BACHILLERATO " B "</p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4" style="text-align: right;">
                <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-up" 
                            width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" 
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2c.641 0 1.212 .302 1.578 .771" />
                            <path d="M20.136 11.136l-8.136 -8.136l-9 9h2v7a2 2 0 0 0 2 2h6.344" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                          </svg>Planificación Vertical</span>',
                        ['toc-plan-vertical/index1', 'clase_id' => $unidad['clase_id']],
                        ['class' => '', 'title' => 'Planificación TOC']
                    );
                ?>
            </div>
            <hr>
        </div>
        <!-- FIN ENCABEZADO -->

        <!-- INICIO FORMULARIO -->
        <div class="card col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 1rem">
            <div style=" margin: 2rem 1.5rem 0.5rem 1.5rem">
                <div style="margin-left: -2rem; margin-top:-2rem ; margin-bottom: 1rem;">
                    <?= Html::beginForm(['unidad'], 'post') ?>

                    <table class="table table-secondary table-bordered; table table-bordered col-lg-12 col-md-12 col-sm-12"
                        style="font-size:10px;margin: 1rem; border-spacing: 0 1rem;border-color: black;">
                        <tbody>
                            <tr>
                                <td colspan="4">
                                    <div style="margin-left: 1rem;">
                                        <h5>ENFOQUES DEL APRENDIZAJE (HABILIDADES IB)</h5>
                                    </div>
                                </td>
                            </tr>

                            <?php
                            foreach ($habilidades as $habilidad) {
                                if ($habilidad->is_active == true) {
                                    $check = "checked ";
                                } else {
                                    $check = "";
                                }

                                ?>
                                <tr class="fondo-campos">
                                    <th style="text-align: center;">
                                        <h6>
                                            <?= $habilidad->tocOpciones->opcion ?>
                                            <h6>
                                    </th>
                                    <td style="font-size: 12px;">
                                        <?= $habilidad->tocOpciones->descripcion ?>
                                    </td>
                                    <td><input onclick="cambia_opcion(<?= $habilidad->id ?>)" type="checkbox" <?= $check ?>
                                            name="opcion1" value="opcion1"></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- FIN FORMULARIO -->
    </div>
</div>

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