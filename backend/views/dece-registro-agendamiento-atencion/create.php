<?php

use backend\models\DeceRegistroSeguimiento;
use backend\models\ScholarisAsistenciaProfesor;
use backend\models\ScholarisGrupoAlumnoClase;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroAgendamientoAtencion */

$this->title = 'Crear Dece Agendamiento Atencion';
$this->params['breadcrumbs'][] = ['label' => 'Dece Registro Agendamiento Atencions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//buscamos id clase, id estudiante, con id seguimiento
$modelRegSeguimiento = DeceRegistroSeguimiento::findOne($idSeguimiento);
//buscamos el leccionario, a travez de la ultima clase, tomada del grupo
$modelAsistProfesor = ScholarisAsistenciaProfesor::find()
    ->where(['clase_id' => $modelRegSeguimiento->id_clase])
    ->orderBy(['id' => SORT_DESC])
    ->one();


?>
<div class="dece-registro-agendamiento-atencion-create">
    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-10 col-md-10">

            <div class=" row align-items-center p-2">
                <div class="col-lg-2">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-10">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
            </div>
            <div class=" row align-items-center p-2">
                <p>
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i>Regresar - Mi Clase</span>',
                        ['comportamiento/index', 'id' => $modelAsistProfesor->id],
                        ['class' => 'link']
                    );
                    ?>
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color:blueviolet"><i class="fa fa-briefcase" aria-hidden="true"></i>Dece - Seguimiento</span>',
                        ['dece-registro-seguimiento/update', 'id' => $idSeguimiento],
                        ['class' => 'link']
                    );
                 
                    ?>
                </p>

            </div>
            <?= $this->render('_form', [
                'model' => $model,
                'idRegSeguimiento' => $idSeguimiento,
            ]) ?>

        </div>
    </div>
</div>