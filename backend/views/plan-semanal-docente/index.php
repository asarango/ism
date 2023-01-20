<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan semanal';
?>

<div class="scholaris-asistencia-profesor-index" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/calendario.png" width="34px" style="" class="img-thumbnail"></h4>
                </div>

                <div class="col-lg-4 col-md-4">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>

                <div class="col-lg-3 col-md-3">
                    <select name="" id="select-bloques" onchange="showWeeks(this)" class="form-control">
                        <option value="">Seleccione bloque...</option>
                        <?php
                            foreach($blocks as $block){
                                ?>
                                <option value="<?= $block['id'] ?>"><?= $block['bloque'] ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </div>

                <div class="col-lg-2 col-md-2">
                    <div id="div-semanas"></div>
                </div>

                <!--botones derecha-->
                <div class="col-lg-2 col-md-2" style="text-align: right;">                 
                    |
                    <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                    ['site/index'],
                    ['class' => 'link']
                );
                ?>                   
                    |
                </div> 
                <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->

            </div>
            

            <!--comienza cuerpo de documento-->            
                <div id="div-detail-week"></div>
            <!--finaliza cuerpo de documento-->


        </div>
    </div>

</div>

<script>
    function showWeeks(obj){
        let blockId = obj.value;
        let url = '<?= Url::to(['acciones']) ?>';      

        params = {
            block_id: blockId,
            action : 'weeks'   
        };        

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $("#div-semanas").html(response);
            }
        });
    }


    function showWeek(obj){
        let weekId = obj.value;
        let url = '<?= Url::to(['acciones']) ?>';      

        params = {
            week_id: weekId,
            action : 'detail-week'   
        };        

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $("#div-detail-week").html(response);
            }
        });
    }
</script>