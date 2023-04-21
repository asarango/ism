<?php

use yii\helpers\Html;
use backend\models\ScholarisAsistenciaComportamientoDetalle;
use backend\models\ScholarisAsistenciaProfesor;
use backend\models\ScholarisGrupoAlumnoClase;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceCasos */

$this->title = 'Registro General de Acompañamiento';
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
<script type="css">
    .table {
        display: table;
    }

    .title {
        display: table-caption;
        text-align: center;
        font-weight: bold;
        font-size: larger;
    }
    .heading {
        display: table-row;
        font-weight: bold;
        text-align: center;
    }
    .row {
        display: table-row;
    }

    .cell {
        display: table-cell;
        border: solid;
        border-width: thin;
        padding-left: 5px;
        padding-right: 5px;
    }
    .texto-vertical-2 {
        writing-mode: vertical-lr;
        transform: rotate(180deg);
    }
</script>

<div class="dece-casos-reg-gen-acompaniamiento" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <img src="../ISM/main/images/submenu/firma-electronica.png" width="" class="img-thumbnail">
                </div>

                <div class="col-lg-11">
                    <h3>REGISTRO GENERAL DE ACOMPAÑAMIENTO</h3>
                </div>

                <!-- FIN DE CABECERA -->

                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color:#9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Dece - Casos</span>',
                    ['dece-casos/historico', 'id' => '1'],
                    ['class' => 'link']
                );
                ?>
                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color:#9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Registro General Acompañamiento</span>',
                    ['reg-gen-acompaniamiento'],
                    ['class' => 'link']
                );
                ?>
                <hr>

            </div>
            <div class="table">
                <div class="row">
                    <div class="col">
                        PSICÓLOGO DEL NIVEL:
                    </div>
                    <div class="col">
                        <select class="form-select" aria-label="Default select example" id="usuario">
                            <option >Seleccione Opción</option>
                            <option value="quitodece2@ism.edu.ec">quitodece2@ism.edu.ec</option>                            
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Fecha de Inicio:
                    </div>
                    <div class="col">
                        <input type="date" id="fecha_inicio"/>
                    </div>
                    <div class="col">
                        Fecha de Tèrmino:
                    </div>
                    <div class="col">
                        <input type="date" id="fecha_fin"/>
                    </div>
                    <div class="col">
                        <button type="submit"  onclick="mostrar_reporte_general()">Buscar</button>
                    </div>
                </div>
            </div>
            <div id="div_reporte">

            </div>
        </div>
    </div>
</div>
<script>

function mostrar_reporte_general()
{
    var url = '<?= Url::to(['mostrar-reporte-general'])?>';
    var usuario =$("#usuario").val();
    var fecha_inicio =$("#fecha_inicio").val();
    var fecha_fin =$("#fecha_fin").val();

    var params ={
        usuario: usuario,
        fecha_inicio:fecha_inicio,
        fecha_fin:fecha_fin,
    };

    
    $.ajax({
        data: params,
        url:url,
        type: 'POST',
        beforeSend: function(){},
        success:function(respuesta){
            //alert("holaaa");
            $("#div_reporte").html(respuesta);

        }
    });
} 
</script>