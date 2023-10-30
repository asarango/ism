<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VisitaAulica */

$this->title = 'Crear visita áulica';
$this->params['breadcrumbs'][] = ['label' => 'Visita Aulicas', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;

// echo "<pre>";
// print_r($trimestre);
// die();
?>


<div class="m-0 vh-50 row justify-content-center align-items-center">
    <div class="card shadow col-lg-12 col-md-12">
        <div class=" row align-items-center p-2">
            <div class="col-lg-8" style="font-weight: bold;">
                <h4><img src="../ISM/main/images/submenu/files.png" width="64px" style="" class="img-thumbnail">
                    <?= Html::encode($this->title) ?>
                </h4>
                <p>
                    <?= ''
                        . 'Coordinador: '
                        . $clase->paralelo->dece_nombre . ' - '
                        . $clase->paralelo->course->name . ' - ' . ' " '
                        . $clase->paralelo->name . ' " ' . 'Materia: '
                        . $clase->ismAreaMateria->materia->nombre . ' '
                        . '(Clase: '
                        . $clase->id . ')'

                    ?>
                </p>
            </div>

            <div class="col-lg-4" style="text-align: right;font-size: 13px">
                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color: #ab0a3d;color: #fff"><i class="fa fa-briefcase" aria-hidden="true"></i> Regresar</span>',
                    [
                        'view',
                        'clase_id' => $clase->id,
                        'bloque_id' => $trimestre->id
                    ],
                    ['class' => 'link']
                );
                ?>
            </div>
            <hr>
        </div>
        <div class="row">
            <div class="visita-aulica-create">

                <?= $this->render('_form', [
                    'model' => $model,
                    'clase' => $clase,
                    'trimestre' => $trimestre
                ]) ?>

            </div>
        </div>
    </div>
</div>