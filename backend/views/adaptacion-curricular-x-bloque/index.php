<?php

use backend\models\CurriculoMecBloque;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdaptacionCurricularXBloqueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Adaptacion Curricular X Bloques';
$this->params['breadcrumbs'][] = $this->title;

//llamamos la bloque

$bloque = CurriculoMecBloque::find()->All();
$arrayBloque = ArrayHelper::map($bloque,'id','last_name');

// echo '<pre>';
// print_r($arrayBloque);
// die();


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<div class="adaptacion-curricular-xbloque-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-8">
                    <h1><?= Html::encode($this->title) ?></h1>    
                </div>               

                <hr>
                <div>
                    <select id="id_select" onchange="showAsignaturas()" class="form-select" aria-label="Default select example">
                        <option selected>Seleccione Bloque</option>
                        <?php
                            foreach ($bloque as $b) 
                            {
                        ?>
                            <option value="<?= $b->id?>"><?= $b->last_name?></option>                        
                        <?php                    
                            }                        
                        ?>
                    </select>
                </div>
                <div class="row" style="margin-top: 25px;">
                    <select name="niveles" onchange="showAsignaturas()" id="nivel" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                        <option selected="selected" value="">Escoja un curso...</option>
                        <?php
                        foreach ($cursos as $nivel) {
                            echo '<option value="' . $nivel['id'] . '">' . $nivel['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <hr>
                <div class="row">
                    <div id="div_detalle" class="table table-responsive">
                     
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- SCRIPT PARA MOSTRAR MATERIAS POR CURSO ESCOGIDO -->
<script>
    
    function showAsignaturas() {
        var nivel = $('#nivel').val();
        var id_bloque = $('#id_select').val();   
        var url = '<?= Url::to(['list-materias']) ?>';
        var params = {
            curso_id: nivel,
            id_bloque:id_bloque,
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                $("#div_detalle").html(response);
                //console.log(response);
            }
        });
    }
    function guardar_nee_x_clase(idNeeXClass)
    {
        var url = '<?= Url::to(['guardar-adaptacion']) ?>';
        var idNeeXClase = idNeeXClass;
        var id_adaptacion = $('#adaptacion_clase_'+idNeeXClase).val();
        var id_bloque = $('#id_select').val();        
        
        
        var params = {
            idNeeXClase: idNeeXClase,
            id_adaptacion:id_adaptacion,
            id_bloque:id_bloque,
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function() {},
            success: function(response) {
                //$("#div_detalle").html(response);
                //console.log("Datos Guardados de forma correcta !!");
                alert("Datos Guardados de forma correcta !!");
            }
        });

    }
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