<?php

use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisAsistenciaProfesor;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeceCasos */

$this->title = 'Actualización - Casos';
$this->params['breadcrumbs'][] = ['label' => 'Dece Casos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
//extraigo ID  de grupo, relacionado al estudiante, para luego cambiar el valor de la clase por cero

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
        <div class="card shadow col-lg-11 col-md-11">
            <div class=" row align-items-center p-2">                
                <div class="col-lg-1">
                    <img src="ISM/main/images/submenu/firma-electronica.png" width="" class="img-thumbnail">                    
                </div> 
                <div class="col-lg-3">   
                       |
                            <?=
                                Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                    ['site/index'],
                                    ['class' => 'link']
                                );
                            ?>                            
                            <?php
                            if ($model->id_clase == 0) //si es igual a cero, biene del dece
                            {
                            ?>                            
                                |
                                <?=
                                    Html::a(
                                        '<span class="badge rounded-pill" style="background-color: blue"><i class="fa fa-briefcase" aria-hidden="true"></i>Dece Casos</span>',
                                        ['dece-casos/historico','id'=>$model->id_estudiante],
                                        ['class' => 'link']
                                    );
                                ?>                            
                                |
                                <?=
                                    Html::a(
                                        '<span class="badge rounded-pill" style="background-color: grey"><i class="fa fa-briefcase" aria-hidden="true"></i>Crear Nuevo Caso</span>',
                                        ['dece-casos/create', 'id' => $model->id_estudiante,'id_clase' =>$model->id_clase ],
                                        ['class' => 'link']
                                    );
                                ?>                            
                                
                            <?php
                            }
                            ?>
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
                                       
                </div> 
                <div class="col-lg-8 text-left">
                    <h3>ACTUALIZACIÓN - CASOS</h3>
                    <?php $nombreEstudiante = $model->estudiante->last_name .' '.$model->estudiante->middle_name . ' ' . $model->estudiante->first_name ?>                           
                    <h6><b>Estudiante: </b><span style="color:red"><?=$nombreEstudiante?></span><br>
                    <b>Caso No.: </b><span style="color:red"><?=$model->numero_caso?></span></h6>
                </div>              
                <!-- FIN DE CABECERA -->
                <!-- inicia menu  -->               
                <div class="row">
                    <div class="col-lg-10 col-md-10">                       
                         
                            <div>
                                <span style="font-size:20px;">EJES DE ACCIÓN</span>
                                
                                <br>   
                                |                         
                                <?=
                                    Html::a(
                                        '<span class="badge rounded-pill" style="background-color: red"><i class="fa fa-briefcase" aria-hidden="true"></i>Acompañamiento</span>',
                                        ['dece-registro-seguimiento/create', 'id_estudiante' => $model->id_estudiante,'id_clase' =>$model->id_clase,'id_caso'=>$model->id ],
                                        ['class' => 'link']
                                    );
                                ?>
                                |                         
                                <?=
                                    Html::a(
                                        '<span class="badge rounded-pill" style="background-color: red"><i class="fa fa-briefcase" aria-hidden="true"></i>Detección</span>',
                                        ['dece-registro-seguimiento/create', 'id_estudiante' => $model->id_estudiante,'id_clase' =>$model->id_clase,'id_caso'=>$model->id ],
                                        ['class' => 'link']
                                    );
                                ?>
                                |                         
                                <?=
                                    Html::a(
                                        '<span class="badge rounded-pill" style="background-color: red"><i class="fa fa-briefcase" aria-hidden="true"></i>Derivación</span>',
                                        ['dece-derivacion/create', 'id_estudiante' => $model->id_estudiante,'id_clase' =>$model->id_clase,'id_caso'=>$model->id ],
                                        ['class' => 'link']
                                    );
                                ?>
                                |                         
                                <?=
                                    Html::a(
                                        '<span class="badge rounded-pill" style="background-color: red"><i class="fa fa-briefcase" aria-hidden="true"></i>Intervención</span>',
                                        ['dece-registro-seguimiento/create', 'id_estudiante' => $model->id_estudiante,'id_clase' =>$model->id_clase,'id_caso'=>$model->id ],
                                        ['class' => 'link']
                                    );
                                ?>
                                </div>  
                    </div> <!-- fin de menu izquierda -->

                    <div class="col-lg-2 col-md-2" style="text-align: right;">
                        <!-- inicio de menu derecha -->

                    </div><!-- fin de menu derecha -->
                </div>
                <hr>
                <span style="font-size:15px;">Datos Caso: <?=$model->numero_caso?></span>
                <!-- finaliza menu menu  -->
                <hr>

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>

            </div>
        </div>
    </div>
</div>