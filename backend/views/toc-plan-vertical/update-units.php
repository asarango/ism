<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

// echo "<pre>";
// print_r($unidad);
// die();
?>

<script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script>

<div class="planificacion-toc-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8 col-sm-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1 col-ms-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-7 col-md-7 col-ms-7">
                    <h3><b>Modificar plan de unidad</b></h3>
                    <p>2DO. DE BACHILLERATO " B " / <b><?= $unidad->bloque->name; ?></b></p>
                </div>
                <div class="col-lg-4 col-md-4 col-ms-4" style="text-align: right;">
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
                    |
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"><svg xmlns="http://www.w3.org/2000/svg" 
                            class="icon icon-tabler icon-tabler-list-details" width="20" height="20" viewBox="0 0 24 24" 
                            stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M13 5h8" />
                            <path d="M13 9h5" />
                            <path d="M13 15h8" />
                            <path d="M13 19h5" />
                            <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                            <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        </svg></span>',
                            ['toc-plan-unidad-detalle/index1', 'id' => $unidad['id']],
                            ['class' => '', 'title' => 'Planificacion de Unidad TOC']
                        );
                    ?>
                </div>
                <hr>
            </div>

            <!-- INICIO TITULO CON BOTON -->

            <!-- FIN TITULO CON BOTON -->

            <div class="card" style="margin-top:-8; margin-right: 1rem; margin-bottom: 1rem; margin-left: 1rem;">
                <div style="margin: 1rem;">
                    <!-- inicio formulario -->

                    <?= Html::beginForm(['update-units'], 'post') ?>
                    <input type="hidden" name="id" value="<?= $unidad['id']; ?>">
                    <div>
                        <label for="editor1">
                            <h6>Título:</h6>
                        </label>
                        <textarea name="titulo" id="editor1"><?= $unidad['titulo']; ?></textarea>
                    </div>
                    <hr>
                    <div>
                        <label for="editor2">
                            <h6>Objetivos: </h6>
                        </label>
                        <textarea name="objetivos" id="editor2"><?= $unidad['objetivos']; ?></textarea>
                    </div>
                    <hr>
                    <div>
                        <label for="editor3">
                            <h6>Conceptos Clave: </h6>
                        </label>
                        <textarea name="conceptos_clave" id="editor3"><?= $unidad['conceptos_clave']; ?></textarea>
                    </div>
                    <hr>
                    <div>
                        <label for="editor4">
                            <h6>Contenido:</h6>
                        </label>
                        <textarea name="contenido" id="editor4"><?= $unidad['contenido']; ?></textarea>
                    </div>
                    <hr>
                    <div>
                        <label for="editor5">
                            <h6>Evaluación PD:</h6>
                        </label>
                        <textarea name="evaluacion_pd" id="editor5"><?= $unidad['evaluacion_pd']; ?></textarea>
                    </div>

                    <script>
                        for (let i = 1; i <= 5; i++) {
                            ClassicEditor
                                .create(document.querySelector(`#editor${i}`), {
                                    placeholder: 'Añadir una nota...'
                                })
                                .catch(error => {
                                    console.error(error);
                                });
                        }
                    </script>

                    <div class="form-group" style="margin-top:1rem; margin-bottom:1rem;">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success'] ); ?>                     
                    </div>

                    <?= Html::endForm() ?>
                    <!-- // fin del formulario -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 
<div style="margin-top: 1rem;">
    <div id="ck"></div>
    <script>
        ClassicEditor
            .create(document.querySelector('#ck'), {
                placeholder: 'Añadir una nota...'
            })
            .catch(error => {
                console.error(error);
            });
    </script>
</div> -->