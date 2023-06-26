<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TocPlanUnidadDetalle */

$this->title = 'Plan de Unidad TOC-Actualización';
$this->params['breadcrumbs'][] = ['label' => 'Toc Plan Unidad Detalles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

// echo "<pre>";
// print_r($model);
// die();
?>

<script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script>


<style>

</style>


<div class="planificacion-toc-update">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-9 col-md-9" style="text-align: left;">
                    <h3><?= Html::encode($this->title) ?></h3>
                    <p>
                    <?=
                        '<small>' . $model->tocPlanUnidad->clase->ismAreaMateria->materia->nombre .
                        ' - (' .$model->tocPlanUnidad->bloque->name.') - '.
                        'Clase #:' . $model->tocPlanUnidad->clase->id .
                        ' - ' .
                        $model->tocPlanUnidad->clase->paralelo->course->name . ' - ' . $model->tocPlanUnidad->clase->paralelo->name . ' / ' .
                        $model->tocPlanUnidad->clase->profesor->last_name . ' ' . $model->tocPlanUnidad->clase->profesor->x_first_name .
                        '</small>';
                    ?>
                </div>
                <!-- INICIO BOTONES DERECHA -->
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9.00002 15.3802H13.92C15.62 15.3802 17 14.0002 17 12.3002C17 10.6002 15.62 9.22021 13.92 9.22021H7.15002" stroke="#ffffff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8.57 10.7701L7 9.19012L8.57 7.62012" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g>
                            </svg> Plan de unidad TOC</span>',
                            ['toc-plan-unidad-detalle/index1', 'id' => $model['id']],
                            ['class' => '', 'title' => 'Plan de unidad TOC']
                        );
                    ?>
                    <!-- FIN BOTONES DERECHA -->

                </div>
                <hr>
            </div>
            
            <!-- Form en yii2 con ck editor -->
            <div> 
                <div style="text-align: center;"><h4>
                    Modificando "<?= cabeceras($bandera) ?>"
                </h4></div>
                <div style="margin: 10px;">
                    <?= Html::beginForm(['update'], 'post') ?>
                    <input type="hidden" name="id" value="<?= $model->toc_plan_unidad_id; ?>">
                    <input type="hidden" name="bandera" value="<?=$bandera; ?>">
                    <input type="hidden" name="bandera_seccion" value="<?=$banderaSeccion; ?>">
                    <textarea name="<?=$bandera?>" id="editor"><?=$model->$bandera ?></textarea>
                    <br />

                    <script>
                        ClassicEditor
                            .create(document.querySelector('#editor'))
                            .catch(error => {
                                console.error(error);
                            });
                    </script>
                    <div>
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?= Html::endForm() ?>
                </div>
            </div>
            <!-- Fin Form en yii2 con ck editor -->
        </div>
    </div>
</div>




<!-- <div class="toc-plan-unidad-detalle-update">

    <h1>
        <?php //Html::encode($this->title) ?>
    </h1>

    <?php //$this->render('_form', [
    //'model' => $model,
    //]) ?>

</div> -->
<?php
function cabeceras($bandera){
if($bandera=='descripcion_unidad'){
    return 'DESCRIPCIÓN DE LA UNIDAD';
}elseif($bandera=='preguntas_conocimiento'){
    return'PREGUNTAS DE CONOCIMIENTO UNIDAD';
}elseif($bandera=='conocimientos_esenciales'){
    return'CONOCIMIENTOS ESENCIALES';
}elseif($bandera=='actividades_principales'){
    return'ACTIVIDADES PRINCIPALES';
}elseif($bandera=='evaluacion_pd'){
    return 'EVALUACIÓN';
}elseif($bandera==''){
    return 'DIFERENCIACIÓN';
}elseif($bandera=='enfoques_aprendizaje'){
    return 'ENFOQUES DEL APRENDIZAJE';  
}elseif($bandera=='funciono_bien'){
    return'LO QUE FUNCIONÓ BIEN';
}elseif($bandera=='no_funciono_bien'){
    return'LO QUE NO FUNCIONÓ BIEN';
}elseif($bandera=='observaciones'){
    return'OBSERVACIONES';
}
}

?>