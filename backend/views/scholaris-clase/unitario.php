<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClase */
/* @var $form yii\widgets\ActiveForm */

// echo '<pre>';
// print_r($modelAlumnos);
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

<style>
    .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #e1e8ed;
        padding: 15px 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .card-header h1 {
        font-size: 20px;
        margin: 0;
        color: #1877f2;
        /* Color azul de Facebook */
    }

    .card-header h4 {
        font-size: 14px;
        margin: 0;
        color: #606770;
        /* Color de texto en Facebook */
    }

    .card-header img.img-thumbnail {
        border: none;
        padding: 0;
        max-width: 64px;
        border-radius: 50%;
    }

    .row {
        margin-bottom: 10px;
    }


    /* estilos para select2 */

    .select2-small {
        width: 50% !important;
        font-size: 15px;
        padding: 15px;
        /* border: 1px solid #ced4da; */
        border-radius: 4px;
    }

    /* estilos para la tabla*/
    .table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .table td {
        padding: 10px;
        border-bottom: 1px solid #e1e8ed;
    }

    .table h4 {
        font-size: 14px;
        margin: 0;
        color: #606770;
    }

    .table img.img-thumbnail {
        max-width: 64px;
        border-radius: 50%;
    }
</style>


<div class="scholaris-clase-unitario">

    <div class="container">

        <div class="card shadow">

            <?php echo Html::encode($this->title); ?>
            <?php echo Html::beginForm(['unitario', 'post']); ?>

            <div class="card-header">
                <div class="row">
                    <div class="col-lg-2">
                        <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px"
                                class="img-thumbnail"></h4>
                    </div>
                    <div class="col-lg-10">
                        <h1>Seleccion de Alumnos</h1>
                    </div>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <td class="col-lg-2">
                            <h4>Clase:
                                <?= $model->id ?>
                            </h4>
                        </td>
                        <td class="col-lg-2">
                            <h4>Materia:
                                <?= $model->ismAreaMateria->materia->nombre ?>
                            </h4>
                        </td>
                        <td class="col-lg-8">
                            <h4>Profesor:
                                <?= $model->profesor->last_name . ' ' . $model->profesor->x_first_name ?>
                            </h4>
                        </td>
                    </tr>
                    <tr>
                        <td class="col-lg-4">
                            <h4>Curso:
                                <?= $model->paralelo->course->name ?>
                            </h4>
                        </td>
                        <td class="col-lg-4">
                            <h4>Fase:
                                <?= $model->paralelo->name ?>
                            </h4>
                        </td>
                    </tr>
                </table>

            </div>


            <div class="card-body">
                <?php
                
                $listData = ArrayHelper::map($modelAlumnos, 'id', 'nombre');

                echo '<h5>Estudiantes:' . count($listData) . '</h5>';
                echo Select2::widget([
                    'name' => 'alumno',
                    //                        'value' => $model->tipo_usu_bloque,
                    'data' => $listData,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione Estudiante',
                        'class' => 'select2-small',
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