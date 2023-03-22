<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClase */
/* @var $form yii\widgets\ActiveForm */

// echo '<pre>';
// print_r($model);
// die();
//$this->title = 'Ingresar Alumno a la clase: ' .   
// $model->id . ' / ' .
// $model->ismAreaMateria->materia->nombre . ' / ' .
// $model->profesor->last_name . ' ' . $model->profesor->x_first_name . ' / ' .
// $model->paralelo->course->name . ' - ' . $model->paralelo->name . ' / '
//        'Malla: ' . $modelMalla->malla->nombre_malla
//        'Malla: ' . $mallaNombre
//;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Clases', 'url' => ['scholaris-clase/index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="scholaris-clase-unitario">

    <div class="container">

        <div class="card shadow">

            <?php echo Html::encode($this->title); ?>
            <?php echo Html::beginForm(['unitario', 'post']); ?>

            <div class="card-header">

                <div class="row ">
                    <div class="col-lg-2">
                        <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
                    </div>
                    <div class="col-lg-10">
                        <h1>Seleccion de Alumnos</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <h4>Clase: <?= $model->id ?></h4>
                    </div>
                    <div class="col-lg-2">
                        <h4>Materia: <?= $model->ismAreaMateria->materia->nombre ?></h4>
                    </div>
                    <div class="col-lg-8">
                        <h4>Profesor: <?= $model->profesor->last_name . ' ' . $model->profesor->x_first_name ?></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <h4>Curso: <?= $model->paralelo->course->name ?></h4>
                    </div>
                    <div class="col-lg-4">
                        <h4>Fase: <?= $model->paralelo->name ?></h4>
                    </div>
                </div>
            </div>


            <div class="card-body">
                <?php
                // echo '<pre>';
                // print_r($modelAlumnos);
                // die();

                $listData = ArrayHelper::map($modelAlumnos, 'id', 'nombre');

                echo '<h5>Estudiantes:'.count($listData).'</h5>';
                echo Select2::widget([
                    'name' => 'alumno',
                    //                        'value' => $model->tipo_usu_bloque,
                    'data' => $listData,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione Estudiante',
                        //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);

                echo '<input type="hidden" name="id" class="form-control" value="' . $model->id . '">';
                ?>

                <br><br>
                <div class="form-group">
                    <?= Html::submitButton('Agregar', ['class' => 'btn btn-success']) ?>
                </div>
                <?php echo Html::endForm(); ?>

            </div>




        </div>
    </div>
</div>