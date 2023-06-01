<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueActividad */

$this->title = 'Bloque de Unidad - Parciales';
$this->params['breadcrumbs'][] = ['label' => 'Parciales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// echo"<pre>";
// print($modelComoCalifica);
// die();

?>
<div class="scholaris-bloque-actividad-create" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8 co-ms1-8 col-xs-">
            <div class="row" style="margin-top: 10px;">
                <div class="col-lg-1 col-md-1 col-ms-1 col-xs-1">
                    <h1><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h1>
                </div>
                <div class="col-lg-7" style="margin-top: 10px;">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <!-- botones (Bloques/Inicio) -->
                <div class="col-lg-4 col-md-4 col-ms-4 col-xs-4" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8">
                            <i class="fas fa-home"></i> Bloques
                        </span>',
                            ['index'],
                            ['class' => 'link']
                        );
                    ?>
                    |
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                        );
                    ?>
                </div>
                <hr>
                <!-- comienzo formulario -->
                <font size="2">
                    <div class="table-container">

                        <?= $this->render('_form', [
                            'model' => $model,
                            'instituto' => $instituto,
                            'modelComoCalifica' => $modelComoCalifica
                        ]) ?>

                    </div>
                </font>
            </div>
        </div>
    </div>
</div>