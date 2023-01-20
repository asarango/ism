<?php

use backend\models\ScholarisAsistenciaComportamientoDetalle;
use backend\models\ScholarisAsistenciaProfesor;
use backend\models\ScholarisGrupoAlumnoClase;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroSeguimiento */

$this->title = 'Creación - Acompañamiento';
$this->params['breadcrumbs'][] = ['label' => 'Dece Registro Seguimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//$id_grupo llega desde el controllador con -1, quiere decir que es directo del modulo DECE



//llamamos al grupo
$modelGrupo = ScholarisGrupoAlumnoClase::find()
    ->where(['estudiante_id' => $model->id_estudiante])
    ->andWhere(['clase_id' => $model->id_clase])
    ->one();

if ($modelGrupo) // si el grupo existe viene de leccionario
{
    //buscamos el leccionario, a travez de la ultima clase, tomada del grupo
    $modelAsistProfesor = ScholarisAsistenciaProfesor::find()
        ->where(['clase_id' => $modelGrupo->clase_id])
        ->orderBy(['id' => SORT_DESC])
        ->one();
}

?>
<div class="dece-registro-seguimiento-create">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-10 col-md-10">
            <div class="row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="100px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-2">
                        <div class=" row align-items-center p-2">                
                            <?php
                            if ($modelGrupo) // si el grupo existe viene de leccionario
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
                            }else{?>
                                <?=
                                Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i>Regresar Caso</span>',
                                    ['dece-casos/update', 'id' => $model->id_caso],
                                    ['class' => 'link']
                                );
                                ?>                        
                            
                                <?php
                            }?>
                        </div>
                </div>
                <div class="col-lg-9">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <h3><?= 'Asociado al Número de Caso: ' . $model->caso->numero_caso ?></h3>
                </div>
            </div>
            <?= $this->render('_form', [
                'model' => $model
            ]) ?>
        </div>

    </div>

</div>