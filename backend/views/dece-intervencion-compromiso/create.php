<?php

use backend\models\DeceIntervencionCompromiso;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceIntervencionCompromiso */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Dece Intervencion Compromisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

 //llamamos al compromiso, acorde lo seleccionado
$tipoConsulta = $tipo;

$modelCompromisos= DeceIntervencionCompromiso::find()
//->select(['comp_estudiante' , 'esaprobado'])
->where(['id_dece_intervencion'=>2])
->all();
?>

<div class="dece-intervencion-compromiso-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tipoConsulta'=>$tipoConsulta,
        'id_intervencion'=>$id_intervencion,
    ]) ?>
    <br>
    <!-- SE MOSTRARA UNA TABLA ACORDE EL TIPO DE INFORMACION A LLENAR -->
    <h4>Compromisos Registrados</h4>
    <h6>Estudiantes</h6>
    <table class='table table-striped table-info'>
        <tr>
            <td><b>COMPROMISO</b></td>
            <td><b>FECHA MÁXIMO COMPROMISO</b></td>
            <td><b>SE REALIZÓ</b></td>
        </tr>
        <?php
          foreach($modelCompromisos as $reg)
          {             
        ?>
            <tr>
                <td><?= $reg->comp_estudiante?></td>
                <td><?= $reg->fecha_max_cumplimiento?></td>
                <td style="align-text: center;"><input type="checkbox" id="cbox1" value="first_checkbox" checked="<?= $reg->esaprobado?>"> </td>
            </tr>
        <?php
          }
        ?>
    </table>
    <h6>Representantes</h6>
    <table class='table table-striped table-light'>
        <tr>
            <td><b>COMPROMISO</b></td>
            <td><b>FECHA MÁXIMO COMPROMISO</b></td>
            <td><b>SE REALIZÓ</b></td>
        </tr>
        <?php
          foreach($modelCompromisos as $reg)
          {             
        ?>
            <tr>
                <td><?= $reg->comp_representante?></td>
                <td><?= $reg->fecha_max_cumplimiento?></td>
                <td style="align-text: center;"><input type="checkbox" id="cbox1" value="first_checkbox" checked="<?= $reg->esaprobado?>"> </td>
            </tr>
        <?php
          }
        ?>
    </table>
    <h6>Docentes</h6>
    <table class='table table-striped table-warning'>
        <tr>
            <td><b>COMPROMISO</b></td>
            <td><b>FECHA MÁXIMO COMPROMISO</b></td>
            <td><b>SE REALIZÓ</b></td>
        </tr>
        <?php 
          foreach($modelCompromisos as $reg)
          {                  
        ?>
            <tr>
                <td><?= $reg->comp_docente?></td>
                <td><?= $reg->fecha_max_cumplimiento?></td>
                <td style="align-text: center;"><input type="checkbox" id="cbox1" value="first_checkbox" checked="<?= $reg->esaprobado?>"> </td>
            </tr>
        <?php
          }
        ?>
    </table>
    <h6>Dece</h6>
    <table class='table table-striped table-success'>
        <tr>
            <td><b>COMPROMISO</b></td>
            <td><b>FECHA MÁXIMO COMPROMISO</b></td>
            <td><b>SE REALIZÓ</b></td>
        </tr>
        <?php
          foreach($modelCompromisos as $reg)
          {             
        ?>
            <tr>
                <td><?= $reg->comp_dece?></td>
                <td><?= $reg->fecha_max_cumplimiento?></td>
                <td style="align-text: center;"><input type="checkbox" id="cbox1" value="first_checkbox" checked="<?= $reg->esaprobado?>"> </td>
            </tr>
        <?php
          }
        ?>
    </table>
</div>
