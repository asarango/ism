<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceDeteccion */

$this->title = 'Creación - Detección';
$this->params['breadcrumbs'][] = ['label' => 'Dece Deteccions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="dece-deteccion-create">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class="row  p-1">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/derivacion2.png" width="100px" class="img-thumbnail"></h4>
                </div>

                <div class="col-lg-9 col-md-9">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>
                    <small>
                        <?= 'Asociado al Número de Caso: ' . $model->caso->numero_caso ?>
                    </small>
                </div>
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <div class=" row  ">
                        <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i>Regresar Casos</span>',
                                ['dece-casos/update', 'id' => $model->id_caso, 'id_clase' => 0],
                                ['class' => 'link']
                            );
                        ?>
                    </div>
                </div>
                <hr>
            </div>
           

            <?= $this->render('_form', [
                'model' => $model,
                'resUser' => $resUser,
                'array_datos_estudiante' => $array_datos_estudiante,
            ]) ?>

        </div>
    </div>
</div>