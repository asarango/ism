<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación Semanal';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($model);
// die();
?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="planificacion-vertical-pai-criterios-index">
    <!-- CABECERA -->
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"  class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?php
                        echo $model->pepPlanificacion->opCourseTemplate->name . ' | ' .
                        $model->pepPlanificacion->temaTransdisciplinar->categoria_principal_es;
                        ?>
                    </small>
                </div>
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
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Plan semanal</span>',
                            ['index1'],
                            ['class' => 'link']
                    );
                    ?>


                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->                    

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->


            <hr>

            <!-- inicia cuerpo de card -->
            <div class="row p-3" style="margin-top: 10px; margin-left:1px;margin-right:1px; margin-bottom:5px; background-color: #EEE">

                <div class="col-lg-8 col-md-8">

                    <div class="card p-2" style="">
                        <div class="form-group">
                            <label class="label"><b>Diseñar experiencias de aprendizaje interesantes:</b></label>
                            <div id="editor-experiencia">
                                <?= $model->experiencias_aprendizaje ?>
                            </div>                   
                        </div>
                    </div>


                    <hr>
                    <br>

                    <div class="card p-2" style="">
                        <div class="form-group">
                            <label class="label"><b>Evaluación continua:</b></label>
                            <div id="editor-evaluacion">
                                <?= $model->evaluacion_continua ?>
                            </div>                   
                        </div>                        
                    </div>           

                    <button type="submit" class="btn btn-outline-success" 
                            style="margin-top: 10px"
                            onclick="grabar(<?= $model->id ?>)">
                        Guardar
                    </button>
                </div>


                <div class="col-lg-4 col-md-4" style="color: #0a1f8f">
                    <b><u>Detalles de la planificación</u></b>
                    
                    <div class="card p-2" style="background-color: #898b8d; color: white">
                        
                        <ul>
                            <?php
                                if($model->es_aprobado){
                                    echo '<li><i class="fas fa-check" style=""> Está aprobada</i></li>';
                                    echo '<li><i class="fas fa-check" style=""> </i> Por '.$model->quien_aprueba.'</li>';
                                    echo '<li><i class="fas fa-check" style=""> </i> El '.$model->fecha_aprobacion.'</li>';
                                }else{
                                    echo '<li><i class="fas fa-thumbs-down" style=""> No está aprobada</i></li>';
                                }                        
                            ?>
                        </ul>                                                
                    </div>

                    <div class="card p-2" style="background-color: #65b2e8; color: white; margin-top: 10px">   
                        <b>Retroalimentación</b>
                        <?= $model->retroalimentacion ?>
                    </div>
                    
                    <div class="card p-2" style="background-color: #ff9e18; color: white; margin-top: 10px">   
                        <b>Creado por: </b> <?= $model->created ?>
                        el <?= $model->created_at ?>
                        <hr>
                        <b>Actualizado por: </b> <?= $model->updated ?>
                        el <?= $model->updated_at ?>
                    </div>
                    
                </div>
            </div>

            <!-- fin cuerpo de card -->
        </div>
    </div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'], // toggled buttons
        ['blockquote', 'code-block'],

        [{'header': 1}, {'header': 2}], // custom button values
        [{'list': 'ordered'}, {'list': 'bullet'}],
        [{'script': 'sub'}, {'script': 'super'}], // superscript/subscript
        [{'indent': '-1'}, {'indent': '+1'}], // outdent/indent
        [{'direction': 'rtl'}], // text direction

        [{'size': ['small', false, 'large', 'huge']}], // custom dropdown
        [{'header': [1, 2, 3, 4, 5, 6, false]}],

        [{'color': []}, {'background': []}], // dropdown with defaults from theme
        [{'font': []}],
        [{'align': []}],

        ['clean'], // remove formatting button
        ['video']                                         // remove formatting button
    ];

    var quillExp = new Quill('#editor-experiencia', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    var quillEval = new Quill('#editor-evaluacion', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });


    function grabar(planSemanalId) {
        let editorExperiencia = quillExp.container.firstChild.innerHTML;
        let editorEvaluacion  = quillEval.container.firstChild.innerHTML;                
        
        let url = '<?= yii\helpers\Url::to(['update']) ?>';

        params = {
            experiencia: editorExperiencia,
            evaluacion: editorEvaluacion,
            plan_semanal_id: planSemanalId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (resp) {
                var resultado = JSON.parse(resp);
                var estado = resultado.status;
                if (estado == 'ok') {
                    alert('Actualizado correctamente!');
                } else {
                    alert('El registro no se actualizó correctamente!');
                }
            }
        });
    }

</script>