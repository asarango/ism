<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceDerivacion */

$this->title = 'Actualizacíon - Derivación: ' . $model->numero_derivacion;
$this->params['breadcrumbs'][] = ['label' => 'Dece Derivacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="dece-derivacion-update">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-10 col-md-10">

            <div class="row align-items-center p-2">
                <div class="col-lg-2">
                    <h4><img src="../ISM/main/images/submenu/derivacion2.png" width="100px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-2">
                        <div class=" row align-items-center p-2">
                             <?=
                                Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i>Regresar Casos</span>',
                                    ['dece-casos/update', 'id' => $model->id_casos, 'id_clase' => 0],
                                    ['class' => 'link']
                                );
                            ?>
                            |    
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #C70039 "><i class="fa fa-briefcase" aria-hidden="true"></i>PDF</span>',
                                    ['pdf', 'id' => $model->id],
                                    ['class' => 'link',
                                     'target'=>'_blank']
                                );
                                ?> 
                        </div>
                </div>
                <div class="col-lg-8">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <h3><?= 'Asociado al Número de Caso: '.$model->casos->numero_caso ?></h5>
                </div>
            </div>

            <?= $this->render('_form', [
                'model' => $model,
                'resUser'=>$resUser,
                'arrayInstExtUpdate' => $arrayInstExtUpdate
            ]) ?>

        </div>
    </div>
</div>
