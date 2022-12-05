<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueSemanas */

$this->title = 'Creando Semanas';
$this->params['breadcrumbs'][] = ['label' => 'Semanas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-bloque-semanas-create" style="padding-left: 50px; padding-right: 50px">


    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
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

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #65b2e8">
                            <i class="fas fa-plus"></i> Crear Semana
                        </span>',
                        ['create'],
                        ['class' => 'link']
                    );
                    ?>

                    |
                    <!-- <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-plus-square" aria-hidden="true"></i> Crear notificación</span>',
                                ['create'],
                                ['class' => 'link']
                            );
                            ?>

                    | -->
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                'model' => $model,
                'modelBloques' => $modelBloques
            ]) ?>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>