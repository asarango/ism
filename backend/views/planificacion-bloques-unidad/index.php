<?php

use backend\models\IsmGrupoMateriaPlanInterdiciplinar;
use backend\models\IsmGrupoPlanInterdiciplinar;
use backend\models\OpCourse;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PudAprobacionBitacora;
use backend\models\ScholarisBloqueActividad;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Planificación ';
$this->params['breadcrumbs'][] = $this->title;

//consulta para extraer el porcentaje de avance del PUD DIPLOMA
function pud_dip_porcentaje_avance($planVertDiplId, $planBloqueUniId)
{
    $pud_dip_porc_avance = 0;
    //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados    
    $obj2 = new backend\models\helpers\Scripts();
    $pud_dip_porc_avance = $obj2->pud_dip_porcentaje_avance($planVertDiplId, $planBloqueUniId);

    return $pud_dip_porc_avance;
}
//busqueda de las materias para ver si son planificación interdisciplinar
/*
  1.- buscamos por ism area materia
2.- extraermos todos los opcourse que tengan el id del opcourse template
3.- buscamos el id del opcourse , en la lista del punto 2
4.- buscamos por referencia textal, al bloque al que pertenece
5.- Agregamos la I, para identificar
 */
//1.-

$existeCurso = false;
$grupoMateria = mostrar_datos_materia($cabecera);
//si existe la materia, podemos hacer el resto del proceso
if ($grupoMateria) {
    $existeCurso = verifica_curso($cabecera, $grupoMateria);
}
/********************************************************************* */
function mostrar_datos_materia($cabecera)
{
    $con = Yii::$app->db;
    //buscamos el periodo
    $periodo_id = Yii::$app->user->identity->periodo_id;
    //ism area materia
    $id_ism_area_materia = $cabecera->ismAreaMateria->id;
    //devuelve los datos de la materias interdisciplinar
    $query = "select i.id ,i.id_ism_area_materia,i2.id as idGrupoInter ,i2.id_bloque ,i2.id_op_course,i3.id,i3.abreviatura    
            from ism_grupo_materia_plan_interdiciplinar i,
            ism_grupo_plan_interdiciplinar i2,scholaris_bloque_actividad i3
            where i.id_grupo_plan_inter =i2.id 
            and i2.id_bloque = i3.id
            and i.id_ism_area_materia =$id_ism_area_materia
            and i2.id_periodo =$periodo_id;";
        
    $resp = $con->createCommand($query)->queryOne();
    return $resp;
}
/********************************************************************* */
function verifica_curso($cabecera, $grupoMateria)
{
    //R1: VERIFICAR LA UTILIDAD DE ESTE METODO
    $resp = false;
    $idCoursoGrupo = $grupoMateria['id_op_course'];
    $modelOpCourse = OpCourse::find()
        ->select(['id', 'code', 'name', 'x_template_id', 'x_institute', 'abreviatura', 'period_id', 'section'])
        //->where(['x_template_id'=>$cabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->id])
        ->where(['x_template_id' => 3])
        ->all();
   
    

    //buscamos si existe el id del curso
    foreach ($modelOpCourse as $curso) {
        if ($curso->id == $idCoursoGrupo) {
            $resp = true;
        }
    }
    return $resp;
}

?>


<div class="planificacion-desagregacion-cabecera-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?= $cabecera->ismAreaMateria->materia->nombre ?>
                        (<?= $cabecera->ismAreaMateria->materia->id ?>)
                        -
                        <?= $cabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name ?>
                        (<?= $cabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->id ?>)
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
                        '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Selección de Niveles</span>',
                        ['planificacion-desagregacion-cabecera/index'],
                        ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    |
                    <?php
                    if ($seccion == 'PAI') {
                        echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18 "><i class="far fa-file-pdf" aria-hidden="true"></i> PDF Plan Vertical</span>',
                            ['pdf-pv-pai', 'cabecera_id' => $cabecera->id],
                            ['class' => 'link', 'target' => '_blank']
                        );
                        echo ' | ';

                        echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #F08080 "><i class="far fa-file-pdf" aria-hidden="true"></i> PDF Plan Horizontal</span>',
                            ['pdf-ph-pai', 'cabecera_id' => $cabecera->id],
                            ['class' => 'link', 'target' => '_blank']
                        );
                        echo ' | ';
                        echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #f9b900 "><i class="far fa-calendar" aria-hidden="true"></i> 5.-PCA</span>',
                            ['pca/index1', 'cabecera_id' => $cabecera->id],
                            ['class' => 'link']
                        );
                    }

                    if ($seccion == 'BAS') {
                        echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #f9b900 "><i class="far fa-calendar" aria-hidden="true"></i> 4.-PCA</span>',
                            ['pca/index1', 'cabecera_id' => $cabecera->id],
                            ['class' => 'link']
                        );
                    }

                    if ($seccion == 'DIPL') {

                        if ($ismAreaMateria->es_bi == true) {
                            echo Html::a(
                                '<span class="badge rounded-pill" style="background-color: #ff9e18 "><i class="far fa-file-pdf" aria-hidden="true"></i> PDF Plan Vertical</span>',
                                ['pdf-pv-dp', 'cabecera_id' => $cabecera->id],
                                ['class' => 'link', 'target' => '_blank']
                            );
                            echo ' | ';

                            echo Html::a(
                                '<span class="badge rounded-pill" style="background-color: #F08080 "><i class="far fa-file-pdf" aria-hidden="true"></i> PDF Plan Horizontal</span>',
                                ['pdf-ph-dp', 'cabecera_id' => $cabecera->id],
                                ['class' => 'link', 'target' => '_blank']
                            );
                            echo ' | ';
                        } else {
                            echo Html::a(
                                '<span class="badge rounded-pill" style="background-color: #f9b900 "><i class="far fa-calendar" aria-hidden="true"></i> 4.-PCA</span>',
                                ['pca/index1', 'cabecera_id' => $cabecera->id],
                                ['class' => 'link']
                            );
                        }
                    }
                    ?>

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 25px;">
                <div class="table table-responsive">
                    <table class="table table-condensed table-hover table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-center">BLOQUE</th>
                                <th class="text-center">TÍTULO</th>
                                <th class="text-center">PUD</th>
                                <th class="text-center">ESTADO CONFIGURACIÓN</th>
                                <th class="text-center">ES ABIERTO</th>
                                <th class="text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            foreach ($unidades as $unidad) {
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <?php
                                        if (/*$existeCurso*/$grupoMateria) {
                                            if ($unidad->curriculoBloque->shot_name == $grupoMateria['abreviatura']) {
                                                echo '<i class="fas fa-users" title="Interdisciplinar" style="color:#ab0a3d;"></i>';                                               
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center"><?= $unidad->curriculoBloque->last_name ?></td>
                                    <td class="text-center"><?= $unidad->unit_title ?></td>
                                    <!-- Columna del PUD -->
                                    <td class="text-center">
                                        <?php

                                        if (/*$existeCurso &&*/ $unidad->curriculoBloque->shot_name == $grupoMateria['abreviatura']) {
                                            $icono = '<i class="fas fa-times"  title="PUD PENDIENTE" ></i>';
                                            $style = 'color: red';
                                            echo 'Avance: ' . '10000' . '% | &nbsp';
                                            echo Html::a(
                                                $icono,
                                                ['ism-respuesta-plan-interdiciplinar/index1', 'plan_bloque_unidad_id' => $unidad->id,
                                                 'idgrupointer'=>$grupoMateria['idgrupointer']],
                                                ['style' => $style]
                                            );
                                        } else {
                                            if ($ismAreaMateria->es_bi) {
                                                $model = PlanificacionBloquesUnidad::findOne($unidad->id);
                                                $modelPudAprBit = PudAprobacionBitacora::find()
                                                    ->where(['unidad_id' => $model->id])
                                                    ->orderBy(['fecha_notifica' => SORT_DESC])
                                                    ->one();

                                                $icono = '<i class="fas fa-times"  title="PUD PENDIENTE" ></i>';
                                                $style = 'color: red';
                                                $actionController = '';

                                                echo 'Avance: ' . $model->avance_porcentaje . '% | &nbsp';

                                                if ($seccion == 'BAS') {
                                                    $actionController = 'pud-pep/index1';
                                                } elseif ($seccion == 'PAI') {
                                                    $actionController = 'pud-pai/index1';
                                                } elseif ($seccion == 'DIPL') {
                                                    $actionController = 'pud-dip/index1';
                                                }
                                                if ($modelPudAprBit) {

                                                    if ($modelPudAprBit->estado_jefe_coordinador == 'ENVIADO') {
                                                        $icono = '<i class="fas fa-user-clock"  title="PUD EN REVISION" ></i>';
                                                        $style = 'color: blue';
                                                    } elseif ($modelPudAprBit->estado_jefe_coordinador == 'DEVUELTO') {
                                                        $icono = '<i class="fas fa-user-clock"  title="PUD DEVUELTO" ></i>';
                                                        $style = 'color: red';
                                                    } elseif ($unidad->pud_status == 1) {
                                                        $icono = '<i class="fas fa-check"  title="PUD APROBADO" ></i>';
                                                        $style = 'color: green';
                                                    }
                                                }
                                                echo Html::a(
                                                    $icono,
                                                    [$actionController, 'plan_bloque_unidad_id' => $unidad->id],
                                                    ['style' => $style]
                                                );
                                            } else {
                                                echo Html::a(
                                                    '<i class="fas fa-fighter-jet"></i>',
                                                    ['pud-nacional', 'plan_bloque_unidad_id' => $unidad->id],
                                                    ['style' => 'color: #0a1f8f', 'title' => 'Pud Nacional']
                                                );
                                            }
                                        }
                                        ?>
                                    </td>
                                    <!-- fin Columna del PUD -->
                                    <td class="text-center"><?= $unidad->settings_status ?></td>
                                    <td class="text-center">
                                        <!--Si usuario es administrador puede mover "Es Abierto"-->
                                        <?php
                                        if ($perfil == 'Administrador') {
                                            if ($unidad->is_open == 1) {
                                        ?>
                                                <i class="fas fa-lock-open" style="color: green"></i>
                                                <!--                                                    <? Html::a(
                                                                                                            '<i class="fas fa-lock-open"></i>',
                                                                                                            ['abrir-bloque', 'plan_unidad_id' => $unidad->id],
                                                                                                            ['style' => 'color: green']
                                                                                                        ) ?>-->
                                            <?php
                                            } else {
                                            ?>
                                                <?=
                                                Html::a(
                                                    '<i class="fas fa-lock"></i>',
                                                    ['abrir-bloque', 'plan_unidad_id' => $unidad->id],
                                                    ['style' => 'color: #ab0a3d']
                                                )
                                                ?>
                                            <?php
                                            }
                                        } else {
                                            if ($unidad->is_open == 1) {
                                            ?>
                                                <i class="fas fa-lock-open" style="color: green"></i>
                                            <?php
                                            } else {
                                            ?>
                                                <i class="fas fa-lock" style="color: #ab0a3d"></i>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">

                                        <!-- Boton DropDownList -->
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 10px; border-radius: 0px">
                                                Acciones
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <?=
                                                    Html::a('<i class="fas fa-pencil-alt my-text-medium" style="color: #0a1f8f"> 1.-TITULO BLOQ.</i>', [
                                                        'update',
                                                        'unidad_id' => $unidad->id
                                                    ]);
                                                    ?>
                                                </li>
                                                <li>
                                                    <?=
                                                    Html::a('<i class="fas fa-list-ol my-text-medium" style="color: #65b2e8"> 2.-TEMARIO</i>', [
                                                        'contenido',
                                                        'unidad_id' => $unidad->id
                                                    ]);
                                                    ?>
                                                </li>
                                                <li>
                                                    <?php
                                                    $total = calcula_total_criterios($unidad->id);
                                                    echo Html::a(
                                                        '<i class="fas fa-cogs my-text-medium" style="color: #ff9e18"> 3.-MEC (' . count($total) . ')</i>',
                                                        [
                                                            'planificacion-desagregacion-cabecera/desagregacion',
                                                            'unidad_id' => $unidad->id
                                                        ]
                                                    );
                                                    ?>
                                                </li>
                                                <li>
                                                    <!-- Aqui muestro botones dependiendo de cada sección -->
                                                    <?php
                                                    if ($seccion == 'PAI') {
                                                        echo Html::a(
                                                            '<i class="far fa-copy my-text-medium" style="color: #ab0a3d"> 4.-PH-PV</i>',
                                                            [
                                                                'planificacion-vertical-pai-descriptores/index1',
                                                                'unidad_id' => $unidad->id
                                                            ]
                                                        );
                                                    } elseif ($seccion == 'DIPL') {
                                                        echo Html::a(
                                                            '<i class="far fa-copy my-text-medium" style="color: #ab0a3d"> 4.-PH-PV</i>',
                                                            [
                                                                'planificacion-vertical-diploma/index1',
                                                                'unidad_id' => $unidad->id
                                                            ]
                                                        );
                                                    }
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                            <?php
                                // Aqui aumento contador si los estados de bloques están "configurado"
                                if ($unidad['settings_status'] == 'configurado') {
                                    $contador = $contador + 1;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!--<div class="col-lg-6 col-md-6" style="text-align:center" >-->
                <!--<?
                    Html::a(
                        '<i class="fas fa-file-pdf"> Generar PDF - Vertical - Horizontal</i>',
                        ['pca-materia', 'cabecera_id' => $cabecera->id],
                        ['class' => 'link', 'style' => 'font-size:15px']
                    );
                    ?>-->
                <!--</div>-->
                <div class="col-lg-12 col-md-12" style="text-align:end; margin-bottom: 5px">
                    <?php
                    //                    echo $contador ;
                    //                    echo '<br>';
                    //                    echo count($unidades);
                    if (
                        $contador == count($unidades) && $cabecera->estado == 'INICIANDO' && $cabecera->estado != 'EN_COORDINACION' && $cabecera->estado != 'DEVUELTO' && $cabecera->estado != 'APROBADO'
                    ) {
                        echo Html::a(
                            '<i class="fas fa-hand-point-right">Enviar Planificaciones - Coordinador</i>',
                            ['envia-coordinador', 'cabecera_id' => $cabecera->id],
                            ['class' => 'btn btn-primary my-text-medium']
                        );
                    } else {
                        if ($contador < count($unidades)) {
                    ?>
                            <div class="alert alert-dark" role="alert" style="text-align: center">
                                <strong>Usted debe planificar todos los BLOQUES para enviar al Coordinador</strong>
                                <br>
                                <strong>ESTADO CONFIGURACIÓN - 'configurado'</strong>
                            </div>
                        <?php
                        }
                    }

                    if ($cabecera->estado == 'EN_COORDINACION') {
                        ?>
                        <div class="alert alert-primary" role="alert" style="text-align: center">
                            <strong>Se ha enviado Planificación al Coordinador</strong>
                        </div>
                    <?php
                    }
                    ?>

                    <?php
                    if ($cabecera->estado == 'DEVUELTO' && $contador == count($unidades)) {
                        echo Html::a(
                            '<i class="fas fa-hand-point-right">Enviar Planificaciones - Coordinador</i>',
                            ['envia-coordinador', 'cabecera_id' => $cabecera->id],
                            ['class' => 'btn btn-primary my-text-medium']
                        );
                    ?>
                        <div class="alert alert-danger" role="alert" style="text-align: start">
                            <b>
                                <h5>SE HA SOLICITADO REALIZAR LOS SIGUIENTES CAMBIOS:</h5>
                            </b>
                            <hr>
                            <?= $cabecera->revision_coordinacion_observaciones ?>
                        </div>
                        <?php
                    } else {
                        if ($cabecera->estado == 'DEVUELTO') {
                        ?>
                            <div class="alert alert-danger" role="alert" style="text-align: start">
                                <?= $cabecera->revision_coordinacion_observaciones ?>
                            </div>
                        <?php
                        }
                    }

                    if ($cabecera->estado == 'APROBADO') {
                        ?>
                        <div class="alert alert-success" role="alert" style="text-align: center">
                            <strong>
                                <u>
                                    <i class="far fa-thumbs-up" style="font-size: 20px"></i>
                                    Sus planificaciones han sido aprobadas por el coordinador.
                                </u>
                            </strong>

                            <hr><!-- separacion para las firmas -->

                            <b>Firmas</b><br>
                            <b>Revisado y aprobado por : </b><?= $firmaAprueba['firmado_por'] ?> el <?= $firmaAprueba['firmado_el'] ?>
                            <br />
                            <b>Elaborado por : </b><?= $firmaElaborado['firmado_por'] ?> el <?= $firmaElaborado['firmado_el'] ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>

            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>

<!-- funciones php -->
<?php
function calcula_total_criterios($unidadId)
{
    $model = PlanificacionDesagregacionCriteriosEvaluacion::find()
        ->where(['bloque_unidad_id' => $unidadId])
        ->all();
    return $model;
}
?>



<div id="google_translate_element" class="google"></div>

<script type="text/javascript">
    function googleTranslateElementInit() {
        // new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'ca,eu,gl,en,fr,it,pt,de', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, gaTrack: true}, 'google_translate_element');
        new google.translate.TranslateElement({
            pageLanguage: 'es',
            includedLanguages: 'en,fr,es',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            gaTrack: true
        }, 'google_translate_element');
    }
</script>

<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>