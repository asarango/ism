<?php

    // echo '<pre>';
    // print_r($dates);
    // print_r($hours);

?>

<hr>

<div class="" style="margin-left: 30px; margin-bottom: 30px;">
    <?php
        //inicio de foreach principal de dias y fechas
        foreach($dates as $date){
            ?>
            
            <!-- inicio de dia y fecha -->
            <div class="row" style="margin-top: 30px;">
                <div class="col-lg-12 col-md-12" style="color: #0a1f8f;">
                    <i class="fas fa-clock"></i>
                    <?= $date['nombre'].' '.$date['fecha'] ?>
                    <hr>
                </div>
            </div>
            <!-- fin de dia y fecha -->

            <?php
                //inicio de horas
                foreach($hours as $hour){
                    if($date['numero'] == $hour['dia_numero']){

                        if($hour['responsable_planificacion'] == $user){
                            $color = '#ff9e18';
                        }else{
                            $color = '#9e28b5';
                        }

                        ?>
                        <div class="row" style="margin-left: 30px; color: <?= $color ?>">
                            <div class="col-lg-3 col-md-3"><u><?= $hour['hora'] ?></u></div>
                            <div class="col-lg-3 col-md-3"><u><?= $hour['curso'].' '.$hour['paralelo'] ?></u></div>
                            <div class="col-lg-3 col-md-3"><u><?= $hour['materia'] ?></u></div>
                            <div class="col-lg-3 col-md-3"><u><?= $hour['responsable_planificacion'] ?></u></div>
                        </div>
                        <?php
                    }


                    


                }
                //inde horas
            ?>


        <?php //fin de foreach principal de dias y fechas
        }
    ?>
</div>