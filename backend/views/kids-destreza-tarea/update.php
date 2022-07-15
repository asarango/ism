<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\KidsPlanSemanalHoraClaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kids Plan Semanal Hora Clases';
$this->params['breadcrumbs'][] = $this->title;
 
// echo '<pre>';
// print_r($model->planDestreza->horaClase);
// die();

?>
<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>

<div class="kids-destreza-tarea-update">

    <div class="" style="padding-left: 40px; padding-right: 40px">

        <div class="m-0 vh-50 row justify-content-center align-items-center">
            <div class="card shadow col-lg-12 col-md-12">

                <!-- comienza encabezado -->
                <div class="row" style="background-color: #ccc; font-size: 12px">
                    <div class="col-md-6 col-sm-6">
                        <p style="color:white">
                            |                                
                            <?=
                            Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                    ['site/index'], ['class' => 'link']);
                            ?>                
                            |
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i>Hora Clase</span>',
                                    [
                                        'kids-plan-semanal-hora-clase/index1',
                                        'plan_semanal_id' => $model->planDestreza->horaClase->plan_semanal_id,
                                        'clase_id' => $model->planDestreza->horaClase->clase_id,
                                        'detalle_id' => $model->planDestreza->horaClase->detalle_id
                                    ]
                            );
                            ?>    
                            |
                           
                        </p>
                    </div>

                    <div class="col-md-6 col-sm-6" style="text-align:end">
                        |                                
                        <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar Tarea</span>',
                            [
                                'kids-destreza-tarea/eliminar',
                                'id' => $model->id
                            ]
                        );
                        ?>        
                        |
                    </div>

                   
                </div>
                <!-- Fin de encabezado -->

                <!--comienza cuerpo de documento-->
                <div style="background-color:#fff">
                    <div class="row">

                        <div class="col-md-12 col-sm-12">
                        <?= $this->render('_form', [
                            'model' => $model,
                            'archivos' => $archivos,
                            'path' => $path
                        ]) ?>
                        </div>

                    </div>
                </div>
                

                <!--finaliza cuerpo de documento-->

            </div>

        </div>

    </div>
</div>

