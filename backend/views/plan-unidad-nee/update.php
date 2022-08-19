<?php

use backend\models\CurriculoMec;
use backend\models\CurriculoMecBloque;
use yii\helpers\Html;
use backend\models\NeeXClase;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanUnidadNee */

$this->title = 'Registro Información  ' ;
$this->params['breadcrumbs'][] = ['label' => 'Plan Unidad Nees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';



//extraccion de datos nee de estudiante
$modelNee = NeeXClase::findOne($model->nee_x_unidad_id);
$modelBloque = CurriculoMecBloque::findOne($model->curriculo_bloque_unidad_id);

?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<div class="materias-pai-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class="plan-unidad-nee-update">
                <div class=" row align-items-center p-2">
                    <div class="col-lg-1">
                        <h4><img src="ISM/main/images/submenu/libros.png" width="64px" class="img-thumbnail"></h4>
                    </div>
                    <div class="col-lg-11">
                        <h4><?= Html::encode($this->title) ?></h4>
                        <?php
                            $nombreAlumnno  = $modelNee->nee->student->last_name . ' ' . $modelNee->nee->student->middle_name . ' ' . $modelNee->nee->student->first_name;
                        ?>
                        <h6>Estudiante: <?=$nombreAlumnno?></h6>
                        <h6>Bloque: <?=$modelBloque->shot_name;?></h6>
                    </div>
                </div><!-- FIN DE CABECERA -->
               
                <?=  Html::a(
                            '<span class="badge  rounded-pill" style="background-color:#9e28b5;">Planificacion Nee</span>',
                            ['index'],
                            ['class' => 'link']
                ); ?>
                <hr>
               
                <table class="table table-striped table-hover ">
                    <tr>
                        <td><b>Estudiante</b></td>
                        <td><b>Grado</b></td>
                        <td><b>Diagnóstico</b></td>
                        <td><b>Recomendación</b></td>
                    </tr>
                    <tr>
                        <td><?= $nombreAlumnno ?></td>
                        <td><?= $modelNee->grado_nee ?></td>
                        <td><?= $modelNee->diagnostico_inicia?></td>
                        <td><?= $modelNee->recomendacion_clase ?></td>
                    </td>
                </table>
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>

            </div>
        </div>
    </div>
</div>