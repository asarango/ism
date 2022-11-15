<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IsmCriterioDescriptorArea */

$this->title = 'Actualizar criterio y descriptor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ism Criterio Descriptor Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="ism-criterio-descriptor-area-update">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div><!-- FIN DE CABECERA -->
            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <!-- inicio de menu derecha -->
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                            <i class="fa fa-briefcase" aria-hidden="true"></i> Eliminar</span>',
                        ['eliminar', 'id' => $model->id],
                        ['class' => 'link']
                    );
                    ?>
                    |
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->

            <div class="">
                <?= $this->render('_form', [
                    'model'                 => $model,
                    'descriptores'          => $descriptores,
                    'descriptoresLiteral'   => $descriptoresLiteral,
                    'criteriosLiteral'      => $criteriosLiteral,
                    'criterios'             => $criterios
                ]) ?>
            </div>

            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>