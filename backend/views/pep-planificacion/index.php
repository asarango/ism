<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación PEP';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<div class="planificacion-desagregacion-cabecera-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?= $course->name ?>
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Pantalla Principal</span>',
                            ['planificacion-desagregacion-cabecera/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->
            
            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 20px">
                <b><h5><u>Temas de la planificación PEP</u></h5></b>
                
                
                <div id="div-detalle-temas"></div>
                
            </div>
            <!-- finaliza cuerpo de card -->            
            
        </div>
    </div>
</div>

<!-- SCRIPT PARA MOSTRAR MATERIAS POR CURSO ESCOGIDO -->
<script>
    
    showTemas();
    
    function showTemas() {
        var url = '<?= Url::to(['ajax-get']) ?>';
        var opCourseId = '<?= $course->id ?>';
      
        
        var params = {
            op_course_id: opCourseId,
            accion: 'temas'
        };
        //alert(url);

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (response) {
                $("#div-detalle-temas").html(response);
                //console.log(response);
            }
        });
    }
</script>


<!-- SCRIPT PARA SELECT2 -->
<!--<script>
    buscador();
    function buscador() {
        $('.select2').select2({
            closeOnSelect: true
        });
    }

</script>-->