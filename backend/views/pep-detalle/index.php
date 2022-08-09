<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación PEP Detalle';
$this->params['breadcrumbs'][] = $this->title;

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<div class="pep-detalle-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Pantalla Principal</span>',
                            ['planificacion-desagregacion-cabecera/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fa fa-briefcase" aria-hidden="true"></i> Generar PDF</span>',
                            ['pdf', 'planificacion_id' => $tema->id],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->        

            <div class="row p-2" style="margin-top: 20px; background-color: #65b2e8"> <!-- ROW INFORMACION GENERAL -->
                <p>
                    <a class="" data-bs-toggle="collapse" href="#collapseExample" 
                       role="button" aria-expanded="false" 
                       aria-controls="collapseExample"
                       style="color: #000">
                        <i class="fas fa-plus-square"></i> Información General
                    </a>

                </p>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body">
                        <!-- Para el tema de la unidad -->            
                        <?= $this->render('_info-general', ['tema' => $tema]); ?> 
                        <hr>

                        <!--inicio para idea central-->
                        <?= $this->render('_idea-central', ['tema' => $tema, 'registros' => $registros]); ?> 
                        <!--fin para idea central-->

                        <hr>
                        <!--inicio para lineas de indagacion-->
                        <?= $this->render('_linea-indagacion', ['tema' => $tema, 'registros' => $registros]); ?> 

                        <hr>
                        <!--inicio para conceptos y atributos-->
                        <?= $this->render('_conceptos', ['tema' => $tema, 'registros' => $registros]); ?> 


                        <hr>
                        <!--inicio para enfoques de aprendizaje-->
                        <?= $this->render('_enfoques', ['tema' => $tema, 'registros' => $registros]); ?> 

                        <hr>
                        <!--inicio para accion-->
                        <?= $this->render('_accion', ['tema' => $tema, 'registros' => $registros]); ?> 

                    </div> <!-- Fin de card-body -->
                </div>
            </div><!-- fin de row de información general -->


            <div class="row p-2" style="margin-top: 20px; background-color: #65b2e8"> <!-- ROW REFELXION Y PLANIFICACION -->
                <p>
                    <a class="" data-bs-toggle="collapse" href="#reflexion" 
                       role="button" aria-expanded="false" 
                       aria-controls="reflexion"
                       style="color: #000">
                        <i class="fas fa-plus-square"></i> Reflexión y planificación
                    </a>

                </p>
                <div class="collapse" id="reflexion">
                    <div class="card card-body">
                        <!-- Para el tema de la unidad -->            
                        <?= $this->render('_reflexion-planificacion', ['tema' => $tema, 'registros' => $registros]); ?> 
                        <hr>                        

                    </div> <!-- Fin de card-body -->
                </div>
            </div><!-- fin de row de reflexion y planificacion-->
            
            
            <!-- ROW DISEÑO E IMPLEMENTACIÓN -->
            <div class="row p-2" style="margin-top: 20px; background-color: #ff9e18"> 
                <p>
                    <a class="" data-bs-toggle="collapse" href="#diseno" 
                       role="button" aria-expanded="false" 
                       aria-controls="diseno"
                       style="color: #000">
                        <i class="fas fa-plus-square"></i> Diseño e implementación
                    </a>

                </p>
                <div class="collapse" id="diseno">
                    <div class="card card-body">
                        <!-- Para el tema de la unidad -->            
                        <?= $this->render('_diseno-implementacion', [
                                'tema' => $tema, 
                                'registros' => $registros,
                                'planesSemanales' => $planesSemanales
                            ]); ?> 
                        <hr>                        

                    </div> <!-- Fin de card-body -->
                </div>
            </div><!-- fin de row de diseño e implementación-->

            
            <!-- ROW REFLEXIÓN -->
            <div class="row p-2" style="margin-top: 20px; background-color: #c0cdbc"> 
                <p>
                    <a class="" data-bs-toggle="collapse" href="#reflexionc" 
                       role="button" aria-expanded="false" 
                       aria-controls="reflexionc"
                       style="color: #000">
                        <i class="fas fa-plus-square"></i> Relflexión
                    </a>

                </p>
                <div class="collapse" id="reflexionc">
                    <div class="card card-body">
                        <!-- Para el tema de la unidad -->            
                        <?= $this->render('_reflexion', ['tema' => $tema, 'registros' => $registros]); ?> 
                        <hr>                        

                    </div> <!-- Fin de card-body -->
                </div>
            </div><!-- fin de row de REFLEXIÓN -->
            
            
            <!-- ROW DESTREZAS DEL MINISTERIO DE EDUCACION -->
            <div class="row p-2" style="margin-top: 20px; margin-bottom: 20px ;"> 
                <p class="zoom">
                    <?= Html::a('<i class="fas fa-hand-point-right" style="color: #ab0a3d"> Ir a criterios y destrezas - Ministerio de Educación</i>', ['desagregacion',
                        'tema_id' => $tema->id
                    ]) ?>
                </p>
            </div><!-- fin de row de Destrezas del ministerio de educacion -->

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