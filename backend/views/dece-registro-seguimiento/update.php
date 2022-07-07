<?php

use yii\helpers\Html;
use backend\models\ScholarisAsistenciaComportamientoDetalle;
use backend\models\ScholarisAsistenciaProfesor;
use backend\models\ScholarisGrupoAlumnoClase;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroSeguimiento */

$this->title = 'Dece Seguimiento - Ficha Número: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dece Registro Seguimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';


if($model->id_clase>0)//si es mayor a cero, biene de leccionario
{
//llamamos al grupo
$modelGrupo = ScholarisGrupoAlumnoClase::find()
->where(['estudiante_id'=>$model->id_estudiante])
->andWhere(['clase_id'=>$model->id_clase])
->one();
//buscamos el leccionario, a travez de la ultima clase, tomada del grupo
$modelAsistProfesor = ScholarisAsistenciaProfesor::find()
    ->where(['clase_id' => $modelGrupo->clase->id])
    ->orderBy(['id' => SORT_DESC])
    ->one();
}

?>
<div class="dece-registro-seguimiento-update">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-10 col-md-10">
            
            <div class=" row align-items-center p-2">
                <div class="col-lg-2">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px"  class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-10">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <h3><?= 'Asociado al Número de Caso: '.$model->id_caso?></h5>
                </div>
            </div>
            <div class=" row align-items-center p-2">
                    <p>
                 
                        |
                    <?php
                       if($model->id_clase>0)//si es mayor a cero, biene de leccionario
                        { 
                        ?>
                        <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i>Regresar - Mi Clase</span>',
                            ['comportamiento/index', 'id' => $modelAsistProfesor->id],
                            ['class' => 'link']
                        );
                        ?>
                        <?php
                        }
                        ?>
                    </p>
            </div>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>

    </div>

</div>