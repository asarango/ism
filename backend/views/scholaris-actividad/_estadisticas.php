<?php

use yii\helpers\Html;

?>

<div style="background-color: #fff; margin: 10px;" 
     class="text-center">
    <b>RESULTADOS DE CALIFICACIONES</b>
</div>



<div style="background-color: #fff; margin: 10px;"
     class="text-center">
     <b>Mayores o iguales a 95</b><br>
    <progress id="file" max="<?= $totalCalificaciones ?>" value="<?= $sobresalientes ?>" style="width: 300px;">
        <?= $sobresalientes ?>
    </progress>
    <br>
    <i class="fas fa-smile-wink" style="color: green;"></i> <?= $sobresalientes ?> de sobresalientes
</div>

<div style="background-color: #fff; margin: 10px;"
     class="text-center">
     <b>Calificaciones buenas</b><br>
    <progress id="file" max="<?= $totalCalificaciones ?>" value="<?= $regulares ?>" style="width: 300px;">
        <?= $sobresalientes ?>
    </progress>
    <br>
    <i class="fas fa-circle" style="color: blue;"></i> <?= $regulares ?> de buenos
</div>


<div style="background-color: #fff; margin: 10px;"
     class="text-center">
     <b>Calificaciones bajas</b><br>
    <progress id="file" max="<?= $totalCalificaciones ?>" value="<?= $bajos ?>" style="width: 300px;">
        <?= $sobresalientes ?>
    </progress>
    <br>
    <i class="fas fa-spinner fa-spin" style="color: red;"></i> <?= $bajos ?> de bajos

    <hr>

    <progress id="file" max="<?= $bajos ?>" value="<?= $reportadosPadres ?>" style="width: 300px;">
        <?= $reportadosPadres ?>
    </progress>
    <br>
    <i class="fas fa-bullhorn"></i> <?= $reportadosPadres ?> reportados a padre de familia

    <hr>

    <?php 
        if($bajos == $reportadosPadres){            
            //nada que hacer
        }else{
            echo Html::a(
                'Notificar padres',
                ['notificar-padres', 'actividadId' => $actividadId]
            );
        }
    ?>


</div>