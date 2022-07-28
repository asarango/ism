<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Criterios y destrezas Ministerio de Educación';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<div class="pep-detalle-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?= $tema->temaTransdisciplinar->categoria_principal_es ?>
                    </small>
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
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Detalle</span>',
                            ['index1', 'tema_id' => $tema->id],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->        

            <div>
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        /** INICIO BOTONES DE ACCION * */
                        [
                            'class' => 'yii\grid\ActionColumn',
//                    'width' => '150px',
                            'template' => '{update}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    if($model->detalle_id){
                                        return Html::a('<i class="fas fa-check" style="color: green"></i>', $url, [
                                            'title' => 'Quitar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                        ]);
                                    }else{
                                        return Html::a('<i class="fas fa-ban" style="color: #ab0a3d"></i>', $url, [
                                            'title' => 'Agregar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                        ]);
                                    }                                                                        
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key) use($temaId){
                                
                                if($model->detalle_id){
                                    if ($action === 'update') {
                                        return \yii\helpers\Url::to(['quitar', 'id' => $model->detalle_id]);
                                    }
                                }else{
                                    if ($action === 'update') {
                                        return \yii\helpers\Url::to(['agregar', 'destreza_id' => $model->destreza_id, 'tema_id' => $temaId]);
                                    }
                                }                                
                            }
                        ],
                        /** FIN BOTONES DE ACCION * */
                        'curso',
                        'materia',
                        'criterio_eval_codigo',
                        'criterio_eval_descripcion',
                        'destreza_codigo',
                        'destreza',
                        'detalle_id',
//                        [
//                            'attribute' => 'ism_area_materia_id',
//                            'format' => 'raw',
//                            'value' => function ($model) {
//                                return $model->ismAreaMateria->materia->nombre;
//                            },
//                            'filter' => $listaM,
//                            'filterInputOptions' => [
//                                'class' => 'form-control',
//                                'prompt' => 'Seleccione asignatura...'
//                            ],
//                        ],

                       
                        //'idcurso',
                                   
                    ],
                ]);
                ?>
            </div>
            
            <!-- finaliza cuerpo de card -->            

        </div>
    </div>
</div>

<!-- SCRIPT PARA MOSTRAR MATERIAS POR CURSO ESCOGIDO -->
<script>


</script>


<!-- SCRIPT PARA SELECT2 -->
<!--<script>
    buscador();
    function buscador() {
        $('.select2').select2({
            closeOnSelect: true
        });
    }

</script>-->