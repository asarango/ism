<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Plan de Unidad TOC';
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// echo "<pre>";
// print_r($unidad);
// die();

?>
<style>
    .menuizquierda {
        font-size: 12px;
        font-weight: bold;
    }

    .colorfuente {
        color: gray;
        font-size: 11px;
        font-weight: lighter;

    }

    .colorfuente1 {
        color: #9e28b5;
        font-size: 13px;
        font-weight: bold;

    }

    .colorfuente2 {
        color: black;
        font-size: 11px;
        font-weight: normal;

    }

    .cajastexto {
        border: 1px solid #ccc;
        padding: 5px;
        background-color: #f9f9f9;
        color: #333;
        font-family: "Helvetica", sans-serif;
        font-size: 12px;
        text-align: left;
        font-weight: normal;
    }
</style>



<div class="planificacion-toc-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1 col-md-1">
                    <h4><img src="../ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"
                            class="img-thumbnail"></h4>
                </div>
                <?php
                $fecha_desde = $unidad->bloque->desde;
                $fecha_desde_form = strtotime($fecha_desde);
                $fecha_desde_n = date('Y-m-d', $fecha_desde_form);
                $fecha_hasta = $unidad->bloque->hasta;
                $fecha_hasta_form = strtotime($fecha_hasta);
                $fecha_hasta_n = date('Y-m-d', $fecha_hasta_form);
                ?>
                <div class="col-lg-9 col-md-9" style="text-align: left;">
                    <h3>
                        <?= Html::encode($this->title) ?>
                    </h3>
                    <p>
                        <?=
                            '<small>' . $unidad->clase->ismAreaMateria->materia->nombre .
                            ' - (' .$unidad->bloque->name.') - '.
                            'Clase #:' . $unidad->clase->id .
                            ' - ' .
                            $unidad->clase->paralelo->course->name . ' - ' . $unidad->clase->paralelo->name . ' / ' .
                            $unidad->clase->profesor->last_name . ' ' . $unidad->clase->profesor->x_first_name .
                            '<br>Desde: ' . $fecha_desde_n . ' - Hasta: '
                            . $fecha_hasta_n .
                            '</small>';
                        ?>

                    </p>
                </div>
                <!-- INICIO BOTONES DERECHA -->
                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <?=
                        Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9.00002 15.3802H13.92C15.62 15.3802 17 14.0002 17 12.3002C17 10.6002 15.62 9.22021 13.92 9.22021H7.15002" stroke="#ffffff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8.57 10.7701L7 9.19012L8.57 7.62012" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g>
                            </svg> Planificación Vertical TOC</span>',
                            ['toc-plan-vertical/index1', 'clase_id' => $unidad['clase_id']],
                            ['class' => '', 'title' => 'Planificación Vertical TOC']
                        );
                    ?>
                    <!-- FIN BOTONES DERECHA -->

                </div>
                <hr>
            </div>
            <!-- Inicio Cuerpo -->
            <div class="row">
                <!-- Menu Navegacion Derecha -->
                <div class="col-lg-2 col-md-2" style="height: 60vh; background-color: #eee; overflow-y: scroll; 
                    font-size: 10px;margin-top:-20px;">

                    <div class=" row ancho-boton zoom" style="border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#datos" class="menuizquierda">DATOS INFORMATIVOS</a>
                    </div>
                    <div class=" row ancho-boton zoom" style="border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#DescripcionUnidad1" class="colorfuente">- Descripción General</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#EvaluacionPD" class="colorfuente">- Evaluación PD</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#indagacion" class="menuizquierda">INDAGACIÓN</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#DescripcionUnidad2" class="colorfuente">- Descripción de la unidad</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#PreguntasConocimiento" class="colorfuente">- Preguntas conocimiento</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#accion" class="menuizquierda">ACCIÓN</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#ConocimientoEscencial" class="colorfuente">- Conocimientos esenciales</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#ActividadPrincipal" class="colorfuente">- Actividades Principales</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#ProcesoAprendizaje" class="colorfuente">- Proceso de aprendizaje</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#evaluacion" class="colorfuente">- Evaluación</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#diferenciacion" class="colorfuente">- Diferenciación</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#EnfoqueAprendizaje" class="colorfuente">- Enfoques del apredizaje</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#reflexion" class="menuizquierda">REFLEXIÓN</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#loqueFunciono" class="colorfuente">- Lo que funcionó bien</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#loqueNoFunciono" class="colorfuente">- Lo que no funcionó bien</a>
                    </div>
                    <div class="row ancho-boton zoom" style=" border-bottom:solid 1px #ccc;margin-top:5px;">
                        <a href="#Observaciones" class="colorfuente">- Observaciones</a>
                    </div>
                </div>

                <!-- Fin Menu Navegacion Derecha -->
                <div class="col-lg-10 col-md-10" style="overflow-y: scroll; height: 60vh;margin-top:-20px;">
                    <!-- Datos informativos -->
                    <div>
                        <?=
                            $this->render('//toc-plan-vertical/_datosinformativos', ['vertical' => $vertical])
                            ?>
                    </div>
                    <hr>
                    <!--Fin Datos Informativos -->
                    <div class="row">
                        <div class="col-lg-6 col-md-6" style="align-items: center; border-right: 1px solid #ccc;margin-bottom: 10px ">
                            <div class="row" style="align-items: center">
                                <div id="DescripcionUnidad1" class="col-lg-12 col-md-12 menuizquierda"
                                    style="text-align: CENTER;  ">- DESCRIPCIÓN
                                    GENERAL
                                </div>
                            </div>
                            <div class="row" style="margin: 0.5rem;">
                                <div class="cajastexto">
                                    <?= $unidad->titulo ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="row" style="align-items: center">
                                <div id="EvaluacionPD" style=" text-align: CENTER;"
                                    class="col-lg-12 col-md-12 menuizquierda">-
                                    EVALUACIÓN DEL PD PARA LA UNIDAD

                                </div>
                            </div>
                            <div class="row" style="margin: 0.5rem;">
                                <div class="cajastexto">
                                    <?= $unidad['evaluacion_pd'] ?>
                                </div>
                            </div>

                        </div>
                        <hr>
                    </div>

                    <!-- Indagacion -->
                    <div class="row">
                        <div id="indagación" style="margin-top: 0.5rem" class="colorfuente1">INDAGACIÓN:establecimiento
                            del propósito de la unidad
                        </div>
                        <hr>
                    </div>

                    <div class="row" style="align-items: center;">
                        <div class="col-lg-1 col-md-1" style=" text-align: center; ">
                            <?=
                                boton_update($unidad['id'], 'descripcion_unidad', 'DescripcionUnidad')
                                ?>
                        </div>
                        <div id="DescripcionUnidad2" style=" text-align: left;"
                            class="col-lg-11 col-md-11 menuizquierda">-
                            DESCRIPCIÓN DE LA UNIDAD*
                        </div>
                        <div class="row colorfuente2" style="margin-left:0.5rem ;">
                            <div class="col-lg-10 col-md-10"
                                style="text-align: justify; ;color: blue; border: 1px solid blue; padding: 5px; margin: 0.5rem;">
                                * Establezca de uno a tres objetivos generales, amplios y a largo plazo para la
                                unidad.
                                Los objetivos de transferencia son los objetivos
                                principales que requieren que los alumnos “transfieran” o apliquen sus
                                conocimientos,
                                habilidades y conceptos al final de la unidad, en
                                circunstancias nuevas o diferentes, de manera independiente y sin contar con un
                                andamiaje proporcionado por el profesor.
                            </div>
                        </div>
                        <div class="row" style="margin: 0.5rem;">
                            <div class="col-lg-11 col-md-11 cajastexto">
                                <?= $pud['evaluacion_pd'] ?>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="row" style="align-items: center;">
                        <div class="col-lg-1 col-md-1" style=" text-align: center; ">
                            <?=
                                boton_update($unidad['id'], 'preguntas_conocimiento', 'PreguntasConocimiento')
                                ?>
                        </div>
                        <div class="col-lg-11 col-md-11 menuizquierda" id="PreguntasConocimiento"
                            style="margin-top: 0.5rem">
                            - PREGUNTAS DE CONOCIMIENTO UNIDAD
                        </div>
                        <div class="row" style="margin: 0.5rem;">
                            <div class="col-lg-11 col-md-11 cajastexto">
                                <?= $pud['preguntas_conocimiento'] ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <!-- Fin Indagacion / Inicio ACCIÓN -->
                    <div class="row" style="align-items: center;">
                        <div class="colorfuente1" id="accion" style="margin-top: 0.5rem; align-items: center">
                            ACCIÓN: enseñanza y aprendizaje a través de la indagación
                        </div>
                        <hr>
                    </div>
                    <div class="row" style="align-items: center;">
                        <div class="col-lg-1 col-md-1" style=" text-align: center; ">
                            <?=
                                boton_update($unidad['id'], 'conocimientos_esenciales', 'ConocimientoEsencial')
                                ?>
                        </div>
                        <div class="col-lg-11 col-md-11 menuizquierda" id="ConocimientoEsencial"
                            style="margin-top: 0.5rem">
                            - CONOCIMIENTOS ESENCIALES
                        </div>
                        <div class="row" style="margin: 0.5rem;">
                            <div class="col-lg-11 col-md-11 cajastexto">
                                <?= $pud['conocimientos_esenciales'] ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="row" style="align-items: center;">
                        <div class="col-lg-1 col-md-1" style=" text-align: center; ">
                            <?=
                                boton_update($unidad['id'], 'actividades_principales', 'ActividadPrincipal')
                                ?>
                        </div>
                        <div class="col-lg-11 col-md-11 menuizquierda" id="ActividadPrincipal"
                            style="margin-top: 0.5rem">
                            - ACTIVIDADES PRINCIPALES
                        </div>
                        <div class="row" style="margin: 0.5rem;">
                            <div class="col-lg-11 col-md-11 cajastexto">
                                <?= $pud['actividades_principales'] ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="row" style="align-items: center;">
                        <div class="menuizquierda" id="ProcesoAprendizaje" style="margin-top: 0.5rem">
                            - PROCESO DE APRENDIZAJE
                        </div>
                        <div style="margin: 0.5rem">
                            <?=
                                Html::a(
                                    '<button class="btn btn-primary" type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-click" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 12l3 0" />
                                    <path d="M12 3l0 3" />
                                    <path d="M7.8 7.8l-2.2 -2.2" />
                                    <path d="M16.2 7.8l2.2 -2.2" />
                                    <path d="M7.8 16.2l-2.2 2.2" />
                                    <path d="M12 12l9 3l-4 2l-2 4l-3 -9" />
                                  </svg> Seleccionar procesos</button>',
                                    ['aprendizaje', 'toc_plan_unidad_id' => $unidad['id']],
                                    ['class' => '', 'title' => 'Agregar o Crear nuevos Procesos de Aprendizaje']
                                )
                                ?>
                        </div>
                        <?php
                        $contador = 0;
                        ?>
                        <table class="table-secondary table table-bordered" style="border-color: black;">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div style="text-align: center;">
                                            <h6>PROCESO DE APRENDIZAJE SELECCIONADOS</h6>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                                foreach ($aprendizajes as $aprendizaje) {
                                    $contador++;
                                    echo '<tr class="fondo-campos">
                                            <th style="text-align: center;font-size: 13px;">                                                
                                                    ' . $contador . '                                                    
                                            </th>
                                            <td style="font-size: 12px;">' .
                                            $aprendizaje->tocOpcion->descripcion . '
                                            </td>
                                           
                                        </tr> ';
                                    }
                                
                                ?>
                            </tbody>
                        </table>

                        <hr>
                    </div>
                    <div class="row" style="align-items: center;">
                        <div class="col-lg-1 col-md-1" style=" text-align: center; ">
                            <?=
                                boton_update($unidad['id'], 'evaluacion_pd', 'evaluacion') //cambiar encabezado
                                ?>
                        </div>
                        <div class="col-lg-11 col-md-11 menuizquierda" id="evaluacion" style="margin-top: 0.5rem">
                            - EVALUACIÓN
                        </div>
                        <div class="row" style="margin: 0.5rem;">
                            <div class="col-lg-11 col-md-11 cajastexto">
                                <?= $pud['evaluacion_pd'] ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="row" style="align-items: center;">
                        <div class="col-lg-1 col-md-1" style=" text-align: center; ">
                            <?=
                                boton_update($unidad['id'], 'diferenciacion', 'diferenciacion') //cambiar encabezado --revizar para navegar hacia arriba
                                ?>
                        </div>
                        <div class="col-lg-11 col-md-11 menuizquierda" id="diferenciacion" style="margin-top: 0.5rem">
                            - DIFERENCIACIÓN
                        </div>
                        <div class="row" style="margin: 0.5rem;">
                            <div class="col-lg-11 col-md-11 cajastexto">
                                <?= $pud['diferenciacion'] ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="row" style="align-items: center;">
                        <div class="col-lg-1 col-md-1" style=" text-align: center; ">
                            <?=
                                boton_update($unidad['id'], 'enfoques_aprendizaje', 'EnfoqueAprendizaje')
                                ?>
                        </div>
                        <div class="col-lg-11 col-md-11 menuizquierda" id="EnfoqueAprendizaje"
                            style="margin-top: 0.5rem">
                            - ENFOQUES DEL APRENDIZAJE
                        </div>
                        <div class="col-lg-9 col-md-9 colorfuente2"
                            style="text-align: justify;border: 1px solid blue;color: blue; padding: 3px; margin:0.5rem ;">
                            * Marque
                            las casillas de las conexiones explícitas con los enfoques del aprendizaje
                            establecidas en la unidad. Para obtener más información sobre los enfoques del
                            aprendizaje,<br>
                            <b>
                                <i>
                                    <u>
                                        <a href="https://xmltwo.ibo.org/publications/DP/Group0/d_0_dpatl_gui_1502_1/static/dpatl//es/guide.html"
                                            target="_blank">Consulte esta guía.
                                        </a>
                                    </u>
                                </i>
                            </b>

                        </div>
                        <!-- habilidades -->
                        <div class="row" style="margin: 0.5rem;">
                            <table class="table-secondary table table-bordered" style="border-color: black;">
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <div style="text-align: center;">
                                                <h6>ENFOQUES DEL APRENDIZAJE (HABILIDADES IB)</h6>
                                            </div>
                                        </td>
                                    </tr>

                                    <?php
                                    foreach ($habilidades as $habilidad) {
                                        if ($habilidad->toc_plan_unidad_id == $unidad->id and $habilidad->is_active == true) {
                                            echo '<tr class="fondo-campos">
                                            <th style="text-align: center;font-size: 13px;">                                                
                                                    ' . $habilidad->tocOpciones->opcion . '                                                    
                                            </th>
                                            <td style="font-size: 12px;">' .
                                                $habilidad->tocOpciones->descripcion . '
                                            </td>
                                           
                                        </tr> ';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                        <div class="row" style="margin: 0.5rem;">
                            <div class="col-lg-11 col-md-11 cajastexto">
                                <?= $pud['enfoques_aprendizaje'] ?>
                            </div>
                        </div>
                        <div class="col-lg-11 col-md-11 colorfuente2"
                            style="text-align: justify;border: 1px solid blue;color: blue; padding: 3px; margin:0.5rem ;">
                            Pedir a los alumnos que proporcionen un argumento razonado para respaldar su opinión:<br>
                            • Implicar a los alumnos en tareas que amplíen conocimientos previos específicos<br>
                            • Exponer a los alumnos a ejemplos y materiales en una variedad de medios, incluidas
                            fuentes visuales, discursos, entrevistas y mapas<br>
                            • Desarrollar la alfabetización crítica, a fin de que los alumnos analicen y realicen
                            críticas
                            de textos en busca de suposiciones, sesgos o distorsiones, invitándolos a considerar
                            preguntas
                            como las siguientes: ¿Cuál es el propósito del texto?, ¿Qué suposiciones hacen los autores
                            sobre
                            las perspectivas, valores, los conocimientos y las creencias?, ¿Qué se ha omitido y por
                            qué?,
                            ¿el lenguaje oculta la intención?, ¿Cuáles son las estructuras de poder subyacentes que
                            crearon
                            el contexto del texto? - Animar a todos los alumnos a participar en las discusiones de TdC
                        </div>
                    </div>
                    <hr>

                    <!--Fin Indagacion/Inicio Reflexion-->
                    <div class="row" style="align-items: center;">
                        <div class="colorfuente1" id="reflexion" style="margin-top: 0.5rem">
                            REFLEXIÓN: consideración de la planificación, el proceso y el impacto de la indagación
                        </div>
                        <hr>
                    </div>
                    <div class="row" style="align-items: center;">
                        <div class="col-lg-1 col-md-1" style=" text-align: center; ">
                            <?=
                                boton_update($unidad['id'], 'funciono_bien', 'loqueFunciono')
                                ?>
                        </div>
                        <div class="col-lg-11 col-md-11 menuizquierda" id="loqueFunciono" style="margin-top: 0.5rem">
                            - LO QUE FUNCIONÓ BIEN
                        </div>
                        <div class="row" style="margin: 0.5rem;">
                            <div class="col-lg-11 col-md-11 cajastexto">
                                <?= $pud['funciono_bien'] ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="row" style="align-items: center;">
                        <div class="col-lg-1 col-md-1" style=" text-align: center; ">
                            <?=
                                boton_update($unidad['id'], 'no_funciono_bien', 'loqueNoFunciono')
                                ?>
                        </div>
                        <div class="col-lg-11 col-md-11 menuizquierda" id="loqueNoFunciono" style="margin-top: 0.5rem">
                            - LO QUE NO FUNCIONÓ BIEN
                        </div>
                        <div class="row" style="margin: 0.5rem;">
                            <div class="col-lg-11 col-md-11 cajastexto">
                                <?= $pud['no_funciono_bien'] ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="row" style="align-items: center;">
                        <div class="col-lg-1 col-md-1" style=" text-align: center; ">
                            <?=
                                boton_update($unidad['id'], 'observaciones', 'Observaciones')
                                ?>
                        </div>
                        <div class="col-lg-11 col-md-11 menuizquierda" id="Observaciones" style="margin-top: 0.5rem">
                            - OBSERVACIONES
                        </div>
                        <div class="row" style="margin: 0.5rem;">
                            <div class="col-lg-11 col-md-11 cajastexto">
                                <?= $pud['observaciones'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Cuerpo -->
        </div>
    </div>
</div>
</div>

<?php
function boton_update($id, $bandera, $banderaSeccion)
{
    return Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checklist"
                    width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="#00b341"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" />
                    <path d="M14 19l2 2l4 -4" />
                    <path d="M9 8h4" />
                    <path d="M9 12h2" /></svg>',
        ['update', 'id' => $id, 'bandera' => $bandera, 'bandera_seccion' => $banderaSeccion]
    );
}
?>