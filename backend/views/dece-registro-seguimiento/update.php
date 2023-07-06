<?php

use yii\helpers\Html;
use backend\models\ScholarisAsistenciaComportamientoDetalle;
use backend\models\ScholarisAsistenciaProfesor;
use backend\models\ScholarisGrupoAlumnoClase;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroSeguimiento */

$this->title = 'Actualización Acompañamiento - No.: ' . $model->numero_seguimiento;
$this->params['breadcrumbs'][] = ['label' => 'Dece Registro Seguimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';


if ($model->id_clase > 0) //si es mayor a cero, biene de leccionario
{
    //llamamos al grupo
    $modelGrupo = ScholarisGrupoAlumnoClase::find()
        ->where(['estudiante_id' => $model->id_estudiante])
        ->andWhere(['clase_id' => $model->id_clase])
        ->one();
    //buscamos el leccionario, a travez de la ultima clase, tomada del grupo
    $modelAsistProfesor = new ScholarisAsistenciaProfesor();
    $modelAsistProfesor->id = 0;
    if ($modelGrupo) {
        $modelAsistProfesor = ScholarisAsistenciaProfesor::find()
            ->where(['clase_id' => $modelGrupo->clase->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }
}
?>
<div class="dece-registro-seguimiento-update">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-10 col-md-10">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h4>
                </div>

                <div class="col-lg-8 col-md-9">
                    <h4>
                        <?= Html::encode($this->title) ?>
                    </h4>
                    <small>
                        <?= 'Asociado al Número de Caso: ' . $model->caso->numero_caso ?>
                    </small>
                </div>
                <div class="col-lg-3 col-md-3 col-ms-6" style="text-align: right;">
                    <div class="row align-items-center p-2">
                        <p>
                            |
                            <?=
                                Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Regresar Caso</span>',
                                    ['dece-casos/update', 'id' => $model->id_caso],
                                    ['class' => 'link']
                                );
                            ?>
                            |
                            <?=
                                Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #C70039 "><i class="fa fa-briefcase" aria-hidden="true"></i> PDF</span>',
                                    ['pdf', 'id' => $model->id],
                                    [
                                        'class' => 'link',
                                        'target' => '_blank'
                                    ]
                                );
                            ?>
                            <?php
                            if ($model->id_clase > 0) //si es mayor a cero, biene de leccionario
                            {
                                ?>
                                |
                                <?=
                                    Html::a(
                                        '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Regresar - Mi Clase</span>',
                                        ['comportamiento/index', 'id' => $modelAsistProfesor->id],
                                        ['class' => 'link']
                                    );
                                ?>
                                <?php
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <?= $this->render('_form', [
                'model' => $model,
                'resUser' => $resUser,
            ]) ?>


        </div>

    </div>

</div>