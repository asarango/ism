<?php

use yii\helpers\Html;
use backend\models\ScholarisAsistenciaComportamientoDetalle;
use backend\models\ScholarisAsistenciaProfesor;
use backend\models\ScholarisGrupoAlumnoClase;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceCasos */

$this->title = 'Dece Casos - Creación';
$this->params['breadcrumbs'][] = ['label' => 'Dece Casos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$ahora = date('Y-m-d H:i:s');

// echo '<pre>';
// print_r($model);
// print_r($model->estudiante);
// die();

?>
<!--Scripts para que funcionen AJAX de select 2 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<div class="dece-casos-create" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-1">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/firma-electronica.png" width="64px" class="img-thumbnail"></h4>                    
                </div>

                <div class="col-lg-8 col-md-8">
                    <h4>CREACIÓN - CASOS</h4>
                    <p><small>
                    <?php $nombreEstudiante = $model->estudiante->last_name .' '.$model->estudiante->middle_name . ' ' . $model->estudiante->first_name ?>                           
                    <b>Estudiante:</b> <span style="color:red"><?=$nombreEstudiante?></span><br>
                    <b>Nuevo Caso:</b> <span style="color:red"><?=$model->numero_caso?></span>
                    </p></small>
                    
                </div>
              
                <!-- FIN DE CABECERA -->
                <div class="col-lg-3 col-md-3" style="text-align: right;">
                <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color:#9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Dece - Casos</span>',
                                ['dece-casos/historico','id'=>$model->id_estudiante],
                                ['class' => 'link']
                            );
                            ?>
                </div>

                                            
                <hr>
                
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>