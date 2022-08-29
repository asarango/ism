<?php

use backend\models\PlanificacionVerticalDiplomaHabilidades;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Aprobaciones de plan semanal | ';
$this->params['breadcrumbs'][] = $this->title;

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<div class="planificacion-vertical-pai-criterios-index">
    <!-- CABECERA -->
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"  class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>

                    </small>

                </div>
            </div>
            <!-- FIN DE CABECERA -->

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


                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->


                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->
            <hr>
            <!-- inicia cuerpo de card -->

            <div class="row" style="padding-bottom: 20px">

                <div class="col-lg-3 col-md-3">     
                    
                    <div class="row" id="div-select">
                        <select name="niveles" onchange="showDocentes()" id="select-semana" 
                                class="form-control select2 select2-hidden-accessible" 
                                style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option selected="selected" value="" >Escoja una semana...</option>
                            <?php
                            foreach ($semanas as $nivel) {
                                echo '<option value="' . $nivel['id'] . '">' . $nivel['nombre_semana'] . '</option>';
                            }
                            ?>
                        </select> 
                    </div> <!-- /.form-group -->    

                    <div class="row" style="padding-left: 20px; display: none; margin-top: 20px" id="div-docentes">
                        <ul class="list-group">           
                            <?php
                            foreach ($docentes as $docente) {
                                ?>
                                    <li class="list-group-item">
                                        <a href="#" onclick="showDetail(<?= $docente['id'] ?>);">
                                            <?= $docente['docente'] ?>
                                        </a>                                                                            
                                    </li>
                                <?php
                            }
                            ?>                                                        
                        </ul>
                    </div>

                </div>


                <div class="col-lg-9 col-md-9">

                    <div class="table table-responsive" id="div-detalle">
                        
                    </div>
                    
                </div>

            </div>

            <!-- fin cuerpo de card -->
        </div>
    </div>
</div>

<script>
    $('#single-select-field').select2({
        theme: "bootstrap-5",
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder')
    });
</script>

<!-- SCRIPT PARA SELECT2 -->
<script>
    buscador();
    function buscador() {
        $('.select2').select2({
            closeOnSelect: true
        });
    }

</script>

<script>
    function showDocentes(){
        $("#div-docentes").show();
    }
    
    function showDetail(facId){
        let semanaId = document.getElementById("select-semana").value;
        let url = '<?= Url::to(['ajax-detalle']) ?>';
        
        var params = {
            fac_id: facId,
            semana_id: semanaId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                $("#div-detalle").html(response);
            }
        });
        
    }
</script>