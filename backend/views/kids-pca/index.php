<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Kids Pca';
$this->params['breadcrumbs'][] = $this->title;

$curso = $modelPca->opCourse->name;
// echo '<pre>';
// print_r();
// die();

?>
<div class="kids-pca-index">

    <div class="scholaris-asistencia-profesor-index" style="padding-left: 40px; padding-right: 40px">

        <div class="m-0 vh-50 row justify-content-center align-items-center">
            <div class="card shadow col-lg-9 col-md-9 " style="background-color: #ccc; font-size: 12px">

                <!-- comienza encabezado -->
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <p style="color:white">
                            |                                
                            <?=
                            Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                    ['site/index'], ['class' => 'link']);
                            ?>                
                            |
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificaciones</span>',
                                    [
                                        'kids-menu/index1'
                                    ]
                            );
                            ?>    
                            |
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fas fa-file-pdf"></i> PDF</span>',
                                    [
                                        'pdf', 'id' => $modelPca->id
                                    ],
                                    [
                                        'target' => '_blank'
                                    ]
                            );
                            ?>    
                            |
                        </p>
                    </div>                    

                </div>
                
                <div class="row">
                    <div class="col-md-9 col-sm-9">
                        <h5 style="color:white"><?= $this->title ?></h5>
                            <strong>CURSO: <?=$curso?></strong>
                    </div>
                    
                    <div class="col-lg-2 col-md-2">
                        <b>Código:</b> ISMR20-17 <br>
                        <b>Versión:</b> 5.0 <br>
                        <b>Fecha:</b> 23/10/2022 <br>
                    </div>
                    
                    <div class="col-lg-1 col-md-1">
                        <img src="../imagenes/iso/iso.png" class="img-thumbnail">
                    </div>
                </div>
                <!-- Fin de encabezado -->

                <!--comienza cuerpo de documento-->
                <div class="row" style="background-color: #fff; margin-top: 5px;">
                    <div class="col-md-12 col-sm-12 scroll-200">
                        <?=
                        $this->render('_form', [
                            'modelPca' => $modelPca,                            
                            'userLog' => $userLog,
                            'today' => $today
                        ]);
                        ?>
                    </div>
                </div>


                <!--Divs controlados por JavaScript-->

                <div class="row" id="titulo-microcurricular" style="background-color: #ccc; margin-top: 20px;">
                    <div class="col-md-12 col-sm-12">
                        <p style="color:white"><b>Unidades Microcurriculares</b></p>
                    </div>
                </div>
                <div class="row" id="body-microcurricular" style="background-color:#fff" >
                    <div class="col-md-12 col-sm-12">
                        <?php
                        echo $this->render('microcurriculares', [
                            'microcurriculares' => $microcurriculares,
                            'modelMicro' => $modelMicro,
                            'pcaId' => $modelPca->id,
                            'userLog' => $userLog,
                            'today' => $today
                        ]);
                        ?>
                    </div>
                </div>
                <!--finaliza cuerpo de documento-->

            </div>

        </div>

    </div>
</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script>

muestra_curricular();

function muestra_curricular() {

    if ('<?= $modelPca->isNewRecord ?>') {
        $("#titulo-microcurricular").hide();
        $("#body-microcurricular").hide();
    } else {
//            alert('Ahora puede agregar contenido "Unidades Microcurriculares"');
        $("#titulo-microcurricular").show();
        $("#body-microcurricular").show();

//            Scroll automatico
        $("html, body").animate({
            scrollTop: "300px"
        });

    }

}

</script>
