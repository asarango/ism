<?php

use backend\models\PlanificacionVerticalDiplomaHabilidades;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use yii\helpers\Html;

//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opciones del plan Vertical Diploma';

// echo '<pre>';
// print_r($arrayConexionesTdc);
// die();
?>

<!--ckeditor-->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>

<div class="planificacion-vertical-pai-criterios-index">
    <!-- CABECERA -->
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small><b>BLOQUE Nº: </b> 
                            <?= $modelPlanVertical->planificacionBloqueUnidad->curriculoBloque->last_name ?><b> | 
                        <?= $modelPlanVertical->planificacionBloqueUnidad->unit_title ?></b> | 
<?= $modelPlanVertical->planificacionBloqueUnidad->planCabecera->ismAreaMateria->materia->nombre ?></b>
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

                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificación Vertical</span>',
                            ['planificacion-vertical-diploma/index1', 'unidad_id' => $modelPlanVertical->planificacion_bloque_unidad_id],
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
            <hr>
           
            <p style="margin-top: 10px;"><b>CONEXIONES CON TDC</b></p> 
            
            <!--incio de conocimientos-->
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <?php
                        $tipoArea = 'Conocimiento y actor del conocimiento';
                        echo genera_formulario($arrayConexionesTdc, $tipoArea);                           
                    ?>                    

                </div>
            </div>
            <!--fin de conocmientos-->
            <hr>
            <!--incio de Areas de conocimiento-->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <p><b>Áreas de conocimiento</b></p>
                    <?php
                        $tipoArea = 'Áreas de conocimiento';
                        echo genera_opciones($arrayConexionesTdc, $tipoArea);                           
                    ?>                    

                </div>
            </div>
            <!--fin de areas de conocimiento-->
            <hr>
            <!--incio de áreas de conocimientos-->
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <?php
                        $tipoArea = 'Preguntas de conocimiento';
                        echo genera_formulario_preguntas($arrayConexionesTdc, $tipoArea);                           
                    ?>                    

                </div>
            </div>
            <!--fin de áreas conocmientos-->
            <hr>
            
            <!--incio de marcos de conocimientos-->
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <p>Marcos de conocimiento</p>
                    <?php
                        $tipoArea = 'Marcos de conocimiento';
                        echo genera_opciones($arrayConexionesTdc, $tipoArea);                           
                    ?>                    

                </div>
            </div>
            <!--fin de marcos de conocmientos-->
            <hr>
            <!--incio de conceptos-->
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <p>Conceptos</p>
                    <?php
                        $tipoArea = 'Conceptos';
                        echo genera_opciones($arrayConexionesTdc, $tipoArea);                           
                    ?>                    

                </div>
            </div>
            <!--fin de conceptos-->
            <hr>
            <!-- fin cuerpo de card -->
        </div>
    </div>
</div>

<script>
    function update(id){
        var url = '<?= \yii\helpers\Url::to(['update-tdc-register']) ?>';
        var params = {
          tdc_id: id,
          tipo: 'opcion'
        };
        
         $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                        //$('#div-habilidades').html(resp);
                    }
        });
    }
</script>

<script>
    CKEDITOR.replace( 'contenido',{
    customConfig: '/ckeditor_settings/config.js'                                
    } );
</script>

<script>
    CKEDITOR.replace( 'preguntas',{
    customConfig: '/ckeditor_settings/config.js'                                
    } );
</script>

<?php
    function genera_formulario($arrayConexionesTdc, $tipoArea){
        
        $html = '';
        foreach ($arrayConexionesTdc as $tdc) {                        
            if($tdc['tipo_area'] == $tipoArea){                
                $tdcId = $tdc['id'];
                $contenido = $tdc['contenido'];
                $html .= Html::beginForm(['update-tdc-register'], 'post');
                $html .= '<input type="hidden" name="tdc_id" value="'.$tdcId.'">';
                $html .= '<input type="hidden" name="tipo" value="formulario">';
                $html .= '<label for="'.$tipoArea.'" class="form-label">'.$tipoArea.'Conocimiento y actor del conocimiento</label>';
                $html .= '<textarea name="contenido" require="" class="form-control">'.$contenido.'</textarea><br>';
                $html .= '<button type="submit" class="btn btn-success" >Actualizar</button>';
                $html .= Html::endForm();
            }            
        }
        return $html;
    }
    
    function genera_formulario_preguntas($arrayConexionesTdc, $tipoArea){
        
        $html = '';
        foreach ($arrayConexionesTdc as $tdc) {                        
            if($tdc['tipo_area'] == $tipoArea){                
                $tdcId = $tdc['id'];
                $contenido = $tdc['contenido'];
                $html .= Html::beginForm(['update-tdc-register'], 'post');
                $html .= '<input type="hidden" name="tdc_id" value="'.$tdcId.'">';
                $html .= '<input type="hidden" name="tipo" value="formulario">';
                $html .= '<label for="'.$tipoArea.'" class="form-label">'.$tipoArea.'Conocimiento y actor del conocimiento</label>';
                $html .= '<textarea name="preguntas" require="" class="form-control">'.$contenido.'</textarea><br>';
                $html .= '<button type="submit" class="btn btn-success" >Actualizar</button>';
                $html .= Html::endForm();
            }            
        }
        return $html;
    }
    
    function genera_opciones($arrayConexionesTdc, $tipoArea){
        $html = '';
        $html .= '<ul>';
        foreach ($arrayConexionesTdc as $tdc) {                        
            if($tdc['tipo_area'] == $tipoArea){   
                $tdc['es_activo'] ? $check = 'checked' : $check = '';
                
                $tdcId = $tdc['id'];
                $contenido = $tdc['opcion'];
                $html.= '<li>';
                $html.= '<div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" '.$check.' onclick="update('.$tdcId.')">
                            <label class="form-check-label" for="flexSwitchCheckChecked">'.$contenido.'</label>
                          </div>';                
                $html.= '</li>';
            }            
        }
        $html .= '</ul>';
        return $html;
    }
?>
