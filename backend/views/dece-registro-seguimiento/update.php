<?php
use yii\helpers\Html;
use backend\models\ScholarisAsistenciaComportamientoDetalle;
use backend\models\ScholarisAsistenciaProfesor;
use backend\models\ScholarisGrupoAlumnoClase;

/* @var $this yii\web\View */
/* @var $model app\models\DeceRegistroSeguimiento */

$this->title = 'Actualización Acompañamiento - No° ' . $model->numero_seguimiento;
$this->params['breadcrumbs'][] = ['label' => 'Dece Registro Seguimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

if ($model->id_clase > 0) //si es mayor a cero, viene de leccionario
{
    //llamamos al grupo
    $modelGrupo = ScholarisGrupoAlumnoClase::find()
        ->where(['estudiante_id' => $model->id_estudiante])
        ->andWhere(['clase_id' => $model->id_clase])
        ->one();
    //buscamos el leccionario, a través de la última clase, tomada del grupo
    $modelAsistProfesor = new ScholarisAsistenciaProfesor();
    $modelAsistProfesor->id = 0;
    if ($modelGrupo) {
        $modelAsistProfesor = ScholarisAsistenciaProfesor::find()
            ->where(['clase_id' => $modelGrupo->clase->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }
}

// echo"<pre>";
// print_r($model);
// die();

?>

<style>
    .header-row {
        display: flex;
        align-items: center;
    }

    .header-logo {
        margin-right: 20px;
    }

    .header-title {
        font-size: 1.5rem;
    }

    .header-links {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .badge-link {
        margin-right: 10px;
    }
</style>

<div class="dece-registro-seguimiento-update">
    <div class="container col-lg-12">
        <div class="card shadow">
            <div class="header-row p-3">
                <div class="header-logo">
                    <img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                </div>
                <div class="header-title">
                    <?= Html::encode($this->title) ?>
                </div>
                <hr>
                <div class="header-links flex-grow-1 text-end">
                    <p>
                        <!-- /* quitar background de necesitarse */ -->

                        <?=
                            Html::a(
                                '<span class="badge badge-primary badge-link" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Regresar Caso</span>',
                                ['dece-casos/update', 'id' => $model->id_caso],
                                ['class' => 'link']
                            );
                        ?>
                        <!-- //quitar background de necesitarse// -->
                        <?=
                            Html::a(
                                '<span class="badge badge-danger badge-link" style="background-color: #ab0a3d"><i class="fa fa-briefcase" aria-hidden="true"></i> PDF</span>',
                                ['pdf', 'id' => $model->id],
                                [
                                    'class' => 'link',
                                    'target' => '_blank'
                                ]
                            );
                        ?>
                        <?php
                        if ($model->id_clase > 0) //si es mayor a cero, viene de leccionario
                        {
                            ?>
                            <?=
                                Html::a(
                                    '<span class="badge badge-primary badge-link"><i class="fa fa-briefcase" aria-hidden="true"></i> Regresar - Mi Clase</span>',
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

            <div class="card-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'resUser' => $resUser,
                ]) ?>
            </div>
        </div>
    </div>
</div>