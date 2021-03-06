<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Crear clase';
?>

<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>


<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>

                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6"> |
                    <?php
                    echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fa fa-plus-circle" aria-hidden="true"></i> Inicio</span>',
                            ['site/index']
                    );
                    ?>                    
                    |
                </div>
                <!-- fin de primeros botones -->

                <!--botones derecha-->
                <div class="col-lg-6 col-md-6" style="text-align: right;">                 
                    |
                    <?php
                    echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nueva clase</span>',
                            ['create']
                    );
                    ?>                    
                    |
                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>


            <!-- /****************************************************************************************************/  -->
            <!-- comienza cuerpo  -->
            
            <?= $this->render('_form', [
                'modelCursos' => $modelCursos
            ]) ?>
            
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>