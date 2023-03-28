<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card">
    <div class="card-header">
        <h6 id=""><b>6.	Desarrollo del perfil de la comunidad de aprendizaje del IB</b></h6>
        <p>También se espera que, mediante las asignaturas, los alumnos desarrollen los atributos del perfil de la 
            comunidad de aprendizaje del IB. Para dar un ejemplo de cómo lo haría, 
            elija un tema del esquema del curso y explique de qué manera los contenidos y las 
            habilidades relacionadas fomentarían el desarrollo de los atributos del perfil de la 
            comunidad de aprendizaje del IB que usted decida.</p>

    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-md-3"><b>Tema</b></div>
            <div class="col-lg-9 col-md-9">
                Contribución al desarrollo de los atributos del perfil de la comunidad de aprendizaje del IB
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3">
                <form-group>
                    <?php $data = search_data_text($plan, 'datos', 'tema_perfil'); ?>
                    <textarea class="form-control text-cerrado " id="tema-perfil" 
                            onchange="update_text(<?= $data['id'] ?>, 'tema-perfil');" 
                            name="tema-perfil"><?= $data['contenido']; ?></textarea>
                </form-group>

            </div>


            <div class="col-lg-9 col-md-9">
                <form-group>
                    <?php $data = search_data_text($plan, 'datos', 'contibu_perfil'); ?>

                    <textarea class="form-control text-cerrado " id="contibu-perfil" 
                            onchange="update_text(<?= $data['id'] ?>, 'contibu-perfil')" 
                            name="contibu-perfil"><?= $data['contenido']; ?></textarea>
                </form-group>

            </div>

        </div>
    </div>
</div>