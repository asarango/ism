<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>5. Mentalidad internacional</b></h6>
        <p>Todas las asignaturas del IB deben contribuir al desarrollo de una mentalidad internacional en los alumnos.
            Para dar un ejemplo de cómo lo haría, elija un tema del esquema del curso que permita a
            los alumnos analizarlo desde distintas perspectivas culturales.
            Explique brevemente por qué elige ese tema y qué recursos utilizaría para alcanzar este objetivo.</p>

    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-md-3"><b>Tema</b></div>
            <div class="col-lg-9 col-md-9">
                Contribución al desarrollo de una mentalidad internacional
                (incluidos los recursos que utilizaría)
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3">
                <form-group>
                    <?php $data = search_data_text($plan, 'datos', 'tema_menta'); ?>
                    <textarea class="form-control text-cerrado " id="tema-menta" 
                            onchange="update_text(<?= $data['id'] ?>, 'tema-menta');" 
                            name="tema-menta"><?= $data['contenido']; ?></textarea>
                </form-group>

            </div>


            <div class="col-lg-9 col-md-9">
                <form-group>
                    <?php $data = search_data_text($plan, 'datos', 'contibu_menta'); ?>

                    <textarea class="form-control text-cerrado " id="contibu-menta" 
                            onchange="update_text(<?= $data['id'] ?>, 'contibu-menta')" 
                            name="tema-tdc"><?= $data['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>