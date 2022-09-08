<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Criterios y Destrezas Seleccionados';
$this->params['breadcrumbs'][] = $this->title;


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<div class="pep-detalle-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4> <?= Html::encode($this->title) ?></h4>
                    <small>
                    <h3>Tema: <?= $tema->temaTransdisciplinar->categoria_principal_es ?></h3>
                    </small>
                </div>
            </div><!-- FIN DE CABECERA -->
            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8">'
                            . '<i class="fa fa-briefcase" aria-hidden="true"></i> Regresar Selección Destrezas</span>',
                            ['desagregacion', 
                                'tema_id' => $tema->id
                            ],
                            ['class' => 'link']
                    );
                    ?>

                     
                    
                   
                    </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->        

            <div>
                    <br>
                    <h4>Números de Registros :<?=count($destrezasSeleccionadas)?></h4>                    
                   
                    <table class="table table-striped table-Warning" > 
                    <thead>
                    <tr>
                        <th rowspan="2" class="text-center border tamano10" style="background-color: #65B0E8;">ASIGNATURA</th>
                        <th colspan="2" class="text-center border tamano10" style="background-color: #65B0E8;">CRITERIO DE EVALUACIÓN</th>
                        <th colspan="2" class="text-center border tamano10" style="background-color: #65B0E8;">DESTREZAS</th>
                    </tr>                    
                    <tr>
                        <th colspan="" class="text-center border tamano10" style="background-color: #65B0E8;">Código</th>
                        <th colspan="" class="text-center border tamano10" style="background-color: #65B0E8;">Descripción</th>
                        <th colspan="" class="text-center border tamano10" style="background-color: #65B0E8;">Código</th>
                        <th colspan="" class="text-center border tamano10" style="background-color: #65B0E8;">Descripción</th>
                    </tr>
                    </thead>
                    
                    <tbody>
                    <?php                        
                        foreach ($destrezasSeleccionadas as $destreza){
                    ?>
                        <tr style="font-size:10px;">
                            <td  class="border"><?=$destreza['asignatura']?></td>
                            <td class="centrarTexto  border"><?=$destreza['criterio_evaluacion_code']?></td>
                            <td  class=" border"><?=$destreza['criterio_evaluacion']?></td>
                            <td class="centrarTexto  border"><?=$destreza['destreza_code']?></td>
                            <td  class=" border text-center"><?=$destreza['destreza']?></td>
                        </tr>
                    <?php
                       }
                    ?>
                            
                    </tbody>
                    </table>
                   
            </div>
            
            <!-- finaliza cuerpo de card -->            

        </div>
    </div>
</div>
