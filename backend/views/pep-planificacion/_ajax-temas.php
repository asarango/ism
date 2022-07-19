<?php

use yii\helpers\Html;

foreach ($temas as $tema) {
    ?>
        <div class="card card-body shadow" style="margin: 30px;">
            <p style="color: #0a1f8f; font-size: 18px;">
                <b><u><?= $tema->temaTransdisciplinar->categoria_principal_es ?></u></b>
            </p>
            <?= $tema->temaTransdisciplinar->contenido_es ?>
            <hr>
            <div class="row">
                <?php
                if ($tema->bloque_id) {
                    ?>
                    <div class="col-lg-3 col-md-3"><b>Creado el </b><?= $tema->created_at ?></div>
                    <div class="col-lg-3 col-md-3"><b>Bloque </b><?= $tema->created_at ?></div>
                    <?php
                } else {
                    ?>
                    <p style="color: #ab0a3d">
                        <b>¡Este tema no se encuentra planificado!</b>
                        <!-- Button trigger modal -->
                        <a href="#" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $tema->id ?>">
                            ¿Quieres asignar este tema a un bloque?
                        </a>

                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop<?= $tema->id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel"><?= $tema->temaTransdisciplinar->categoria_principal_es ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?= Html::beginForm(['update'], 'post', ['enctype' => 'multipart/form-data']) ?>
                                        
                                        <input type="text" name="op_course_template_id" value="<?= $tema['op_course_template_id'] ?>">
                                        <input type="text" name="scholaris_periodo_id" value="<?= $tema['scholaris_periodo_id'] ?>">
                                        
                                        <?= Html::submitButton('Submit', ['class' => 'submit']) ?>
                                        <?= Html::endForm() ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Understood</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </p>
                    <?php
                }
                ?>

            </div>
        </div>


    <?php
}

function bloque($temaId) {
    
}
