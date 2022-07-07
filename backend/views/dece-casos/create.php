<?php

use yii\helpers\Html;
use backend\models\ScholarisAsistenciaComportamientoDetalle;
use backend\models\ScholarisAsistenciaProfesor;
use backend\models\ScholarisGrupoAlumnoClase;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceCasos */

$this->title = 'Dece Casos - Creación';
$this->params['breadcrumbs'][] = ['label' => 'Dece Casos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if($model->id_clase>0)//si es mayor a cero, biene de leccionario
{
//llamamos al grupo
$modelGrupo = ScholarisGrupoAlumnoClase::find()
->where(['estudiante_id'=>$model->id_estudiante])
->andWhere(['clase_id'=>$model->id_clase])
->one();
//buscamos el leccionario, a travez de la ultima clase, tomada del grupo
$modelAsistProfesor = ScholarisAsistenciaProfesor::find()
    ->where(['clase_id' => $modelGrupo->clase->id])
    ->orderBy(['id' => SORT_DESC])
    ->one();
}
?>
<!--Scripts para que funcionen AJAX de select 2 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<div class="dece-casos-create" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/menu.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
                <!-- FIN DE CABECERA -->
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
                           <?php
                            if ($model->id_clase == 0) //si es mayor a cero, biene de leccionario
                            {
                            ?>
                            |

                            <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: blue"><i class="fa fa-briefcase" aria-hidden="true"></i>Regresar Dece Casos</span>',
                                ['dece-casos/index'],
                                ['class' => 'link']
                            );
                            ?>
                              <?php
                            }
                            ?>
                             |
                             <?php
                            if ($model->id_clase > 0) //si es mayor a cero, biene de leccionario
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
                            }
                            ?>

                        
                    </div> <!-- fin de menu izquierda -->

                    <div class="col-lg-6 col-md-6" style="text-align: right;">
                        <!-- inicio de menu derecha -->

                    </div><!-- fin de menu derecha -->
                </div>
                <!-- finaliza menu menu  -->
                <hr>
               

                <?= $this->render('_form', [
                    'model' => $model
                ]) ?>

            </div>
        </div>
    </div>
</div>