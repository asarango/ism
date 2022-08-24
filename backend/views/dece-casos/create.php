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

?>
<!--Scripts para que funcionen AJAX de select 2 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<div class="dece-casos-create" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-7 col-md-7">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h3><img src="ISM/main/images/submenu/curriculum-vitae-y-cv.png" width="64px" class="img-thumbnail"></h3>
                </div>

                <div class="col-lg-11">
                    <h3>CREACIÓN - CASOS</h3>
                </div>
              
                <!-- FIN DE CABECERA -->
                <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color:#ff9e18"><i class="fa fa-briefcase" aria-hidden="true"></i> Dece - Casos</span>',
                                ['dece-casos/historico','id'=>0],
                                ['class' => 'link']
                            );
                            ?>
                <hr>
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>