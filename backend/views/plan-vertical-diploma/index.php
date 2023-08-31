<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$this->title = 'Planificación Vertical Diploma';

$idioma = $cabecera->ismAreaMateria->idioma;

// echo "<pre>";
// print_r($_GET);
// die();

?>

<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
<style>
    a.current {
        background-color: yellow;
    }

    .border-t {
        border-top: solid 1px #ccc;
    }

    .border-b {
        border-bottom: solid 1px #ccc;
    }

    .border-r {
        border-right: solid 1px #ccc;
    }

    .border-l {
        border-left: solid 1px #ccc;
    }

    .background-gray {
        background-color: #eee;
        color: #000;
        font-size: 10px;
    }

    .response {
        font-size: 10px;
        border: solid 1px #ccc;
    }

    .class-input {
        font-size: 10px;
        border: none;
    }

    .ancho-boton {
        border-bottom: solid 1px #ccc;
        height: 30px;
        margin-top: 3px;
    }

    .Aprobado {
        animation: rotate-scale-up-horizontal 0.6s linear both;
    }

    @keyframes rotate-scale-up-horizontal {
        0% {
            transform: scale(1) rotateX(0)
        }

        50% {
            transform: scale(2) rotateX(-180deg)
        }

        100% {
            transform: scale(1) rotateX(-360deg)
        }
    }
</style>

<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="../ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail">
                    </h4>
                </div>
                <div class="col-lg-6 col-md-6">
                    <h4>
                        <?= Html::encode($this->title) ?><br>
                        <small>
                            <?= $cabecera->ismAreaMateria->materia->nombre ?>
                        </small>
                    </h4>

                </div>

                <!--botones derecha-->
                <div class="col-lg-5 col-md-5" style="text-align: right;">

                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fa fa-plus-circle" aria-hidden="true"></i> Inicio</span>',
                        ['site/index']
                    );
                    ?>
                    |
                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fas fa-users-class"></i> Plan Unidades</span>',
                        ['planificacion-bloques-unidad/index1', 'id' => $cabecera->id]
                    );
                    ?>
                    |
                    <?php
                    echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fas fa-file-pdf"></i> Convertir a PDF</span>',
                        ['pdf', 'cabecera_id' => $cabecera->id],
                        ['target' => '_blank']
                    );
                    ?>
                    |

                    <!-- condicion de aprobacion -->
                    <?php

                    if ($cabecera['estado'] === 'INICIANDO') {
                        echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: blue"><i class="fa fa-briefcase" aria-hidden="true"></i>Enviar Aprobación</span>',
                            ['plan-vertical-diploma/enviar-coordinador', 'cabecera_id' => $cabecera->id],
                            ['class' => 'link']
                        ); /* enviado */
                    } elseif ($cabecera['estado'] === 'EN_COORDINACION') {
                        echo '<span class="badge rounded-pill" style="background-color: orange"><i class="fa fa-briefcase" aria-hidden="true"></i>Esperando Respuesta</span>';
                    } elseif ($cabecera['estado'] === 'DEVUELTO') {
                        echo 'Planificación no aprobada';
                        echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: purple"><i class="fa fa-briefcase" aria-hidden="true"></i>Reenviar</span>',
                            [
                                'plan-vertical-diploma/enviar-coordinador',
                                'cabecera_id' => $cabecera->id

                            ],
                            ['class' => 'link']
                        );
                    } elseif ($cabecera['estado'] === 'APROBADO') {
                        echo '<span class="badge rounded-pill Aprobado" style="background-color: green"><i class="fa fa-briefcase" aria-hidden="true"></i>Aprobado</span>';
                    }

                    ?>
                    <!-- condicion de aprobacion -->

                </div>
            </div>

            <!-- /****************************************************************************************************/  -->
            <!-- comienza cuerpo  -->

            <!-- inicia menu -->
            <div class="row">
                <div class="col-lg-2 col-md-2" style="height: 80vh; background-color: #eee; font-size: 10px;">

                    <div class="row align-middle ancho-boton zoom">
                        <a href="#datos" style="height: 30px; border-bottom: solid 1px #ccc;">DATOS INFORMATIVOS</a>
                    </div>

                    <div class="row ancho-boton zoom">
                        <a href="#esquemas">ESQUEMAS DEL CURSO</a>
                    </div>

                    <div class="row ancho-boton zoom">
                        <a href="#componentes">EVALUACIÓN INTERNA Y EXTERNA</a>
                    </div>

                    <div class="row ancho-boton zoom">
                        <a href="#vinculos">VÍNCULOS CON TEORÍA DEL CONOCIMIENTO</a>
                    </div>

                    <div class="row ancho-boton zoom">
                        <a href="#enfoques">ENFOQUES DE APRENDIZAJE</a>
                    </div>


                    <div class="row ancho-boton zoom">
                        <a href="#mentalidad">MENTALIDAD INTERNACIONAL</a>
                    </div>

                    <div class="row ancho-boton zoom">
                        <a href="#perfil">DESARROLLO DEL PERFIL</a>
                    </div>

                    <div class="row ancho-boton zoom">
                        <a href="#equipos">INSTALACIONES Y EQUIPOS</a>
                    </div>

                    <div class="row ancho-boton zoom">
                        <a href="#otros">OTROS RECURSOS</a>
                    </div>

                    <div class="row ancho-boton zoom">
                        <a href="#bibliografia">BIBLIOGRAFÍA</a>
                    </div>

                </div>

                <!-- Finaliza menú -->

                <div class="col-lg-10 col-md-10" style="height: 70vh; overflow-y: scroll;">

                    <!-- inicio de la sección datos informativos -->
                    <div id="Datos informativos">

                        <h6 id="datos" class=""><b>Datos informativos</b></h6>

                        <!-- inicia nombres y codigos del colegio -->
                        <div class="row">
                            <div class="col-lg-3 col-md-3 background-gray border-t border-l">
                                Nombre de la asignatura del Programa del Diploma (indique la lengua)
                            </div>

                            <div class="col-lg-5 col-md-5 response">
                                <?php
                                $asignatura = search_data_text($plan, 'datos', 'asignatura');
                                echo $asignatura['contenido'];
                                ?>
                            </div>

                            <div class="col-lg-2 col-md-2 background-gray border-t">
                                Código del colegio:
                            </div>

                            <div class="col-lg-2 col-md-2 response">
                                <?php
                                // $asignatura = search_data_text($plan, 'datos', 'colegio');
                                // echo $asignatura['contenido'];
                                echo $codigo
                                    ?>
                            </div>
                        </div>
                        <!-- Fin nombres y codigos del colegio -->

                        <!-- inicia Nivel -->
                        <div class="row background-gray border-t border-l border-r">
                            <div class="col-lg-3 col-md-3 border-r">Nivel</div>
                            <div class="col-lg-2 col-md-2 border-r">Superior</div>
                            <div class="col-lg-1 col-md-1 border-r">
                                <?php
                                $data = search_data_select($plan, 'datos', 'nivel', 'Superior');
                                $checked = $data['seleccion'] ? 'checked' : '';
                                ?>
                                <input class="select-cerrado" type="checkbox" name="datos_superior" <?= $checked ?>
                                    onclick="change_select(<?= $data['id'] ?>)">
                            </div>

                            <div class="col-lg-2 col-md-2 border-r">Medio completado en dos años</div>
                            <div class="col-lg-1 col-md-1 border-r">
                                <?php
                                $data = search_data_select($plan, 'datos', 'nivel', 'Medio completado en dos años');
                                $checked = $data['seleccion'] ? 'checked' : '';
                                ?>
                                <input class="select-cerrado" type="checkbox" name="datos_superior" <?= $checked ?>
                                    onclick="change_select(<?= $data['id'] ?>)">
                            </div>

                            <div class="col-lg-2 col-md-2">Medio completado en un año *</div>
                            <div class="col-lg-1 col-md-1">
                                <?php
                                $data = search_data_select($plan, 'datos', 'nivel', 'Medio completado en un año *');
                                $checked = $data['seleccion'] ? 'checked' : '';
                                ?>
                                <input class="select-cerrado" type="checkbox" name="datos_superior" <?= $checked ?>
                                    onclick="change_select(<?= $data['id'] ?>)">
                            </div>
                        </div>
                        <!-- Fin Nivel -->

                        <!-- inicia Completar artes -->
                        <?php
                        if ($area == 'ARTES') {
                            ?>
                            <div class="row background-gray border-t border-b">
                                <div class="col-lg-3 col-md-3 border-l border-r">Completar solo para artes</div>
                                <div class="col-lg-5 col-md-5 border-r">
                                    <form-group>
                                        <label for="artes-visuales" class="form-label">(Indique la opción o las opciones de
                                            Artes Visuales)</label>
                                        <?php
                                        $data = search_data_text($plan, 'datos', 'artes_visuales');

                                        ?>
                                        <textarea class="form-control text-cerrado class-input" name="artes-visuales"
                                            id="artes-visuales"
                                            onchange="update_text(<?= $data['id'] ?>, 'artes-visuales');"><?= $data['contenido']; ?></textarea>
                                    </form-group>
                                </div>

                                <div class="col-lg-4 col-md-4 border-r">
                                    <form-group>
                                        <label for="artes-visuales" class="form-label">(Indique la opción o las opciones de
                                            Música)</label>
                                        <?php
                                        $data = search_data_text($plan, 'datos', 'artes_musica');
                                        ?>
                                        <textarea class="form-control text-cerrado class-input" id="artes-musica"
                                            onchange="update_text(<?= $data['id'] ?>, 'artes-musica');"
                                            name="artes-musica"><?= $data['contenido']; ?></textarea>
                                    </form-group>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <!-- inicia Completar artes -->


                        <!-- nombre de profesor y fecha de capacitación IB -->
                        <div class="row">
                            <div class="col-lg-3 col-md-3 background-gray border-b border-l">
                                Nombre del profesor que completó el esquema
                            </div>

                            <div class="col-lg-3 col-md-5 response">
                                <?php
                                $data = search_data_text($plan, 'datos', 'profesor');
                                echo $data['contenido'];
                                ?>
                            </div>

                            <div class="col-lg-3 col-md-2 background-gray border-t">
                                Fecha de capacitación del IB:
                            </div>

                            <div class="col-lg-3 col-md-2 response">
                                <?php $data = search_data_text($plan, 'datos', 'fecha_cap'); ?>
                                <input class="form-control text-cerrado class-input" type="date" name="fecha_cap"
                                    id="fecha_cap" value="<?= $data['contenido'] ?>"
                                    onchange="update_text(<?= $data['id'] ?>, 'fecha_cap');">
                            </div>
                        </div>
                        <!-- FIN nombre de profesor y fecha de capacitación IB -->



                        <!-- fecha que se completpo el esquema -->
                        <div class="row">
                            <div class="col-lg-3 col-md-3 background-gray border-b border-l">
                                Fecha en que se completó el esquema
                            </div>

                            <div class="col-lg-3 col-md-3 response">
                                <?php $data = search_data_text($plan, 'datos', 'fecha_completo'); ?>
                                <input class="form-control text-cerrado class-input" type="date" name="fecha_completo"
                                    id="fecha_completo" value="<?= $data['contenido'] ?>"
                                    onchange="update_text(<?= $data['id'] ?>, 'fecha_completo');">
                            </div>

                            <div class="col-lg-3 col-md-3 background-gray border-t">
                                Nombre del talle ( Indique nombre de la asignatura y categoría del taller ):
                            </div>

                            <div class="col-lg-3 col-md-3 response">
                                <?php $data = search_data_text($plan, 'datos', 'taller'); ?>
                                <input class="form-control text-cerrado class-input" type="text" name="taller"
                                    id="taller" value="<?= $data['contenido'] ?>"
                                    onchange="update_text(<?= $data['id'] ?>, 'taller');">
                            </div>
                        </div>
                        <!-- FIN fecha que se completpo el esquem -->


                        <!-- una clase dura y en una semana hay -->
                        <div class="row">
                            <div class="col-lg-3 col-md-3 background-gray border-b border-l">
                                Una clase dura
                            </div>

                            <div class="col-lg-3 col-md-3 response">
                                <?php $data = search_data_text($plan, 'datos', 'clase_dura'); ?>
                                <input class="form-control text-cerrado class-input" type="text" name="clase_dura"
                                    id="clase_dura" value="<?= $data['contenido'] ?>"
                                    onchange="update_text(<?= $data['id'] ?>, 'clase_dura');">
                            </div>

                            <div class="col-lg-3 col-md-3 background-gray border-t">
                                En una semana hay
                            </div>

                            <div class="col-lg-3 col-md-3 response">
                                <?php $data = search_data_text($plan, 'datos', 'semana_hay'); ?>
                                <input class="form-control text-cerrado class-input" type="text" name="semana_hay"
                                    id="semana_hay" value="<?= $data['contenido'] ?>"
                                    onchange="update_text(<?= $data['id'] ?>, 'semana_hay');">
                            </div>
                        </div>
                        <!-- una clase dura y en una semana hay -->


                        <!-- una clase dura y en una semana hay -->
                        <div class="row">
                            <div class="col-lg-3 col-md-3 background-gray border-b border-l">
                                Imprevistos
                            </div>

                            <div class="col-lg-3 col-md-3 response">
                                <?php $data = search_data_text($plan, 'datos', 'imprevisto'); ?>
                                <input class="form-control text-cerrado class-input" type="text" name="imprevisto"
                                    id="imprevisto" value="<?= $data['contenido'] ?>"
                                    onchange="update_text(<?= $data['id'] ?>, 'imprevisto');">
                            </div>

                            <div class="col-lg-3 col-md-3 background-gray border-t">
                                Total semanas clase
                            </div>

                            <div class="col-lg-3 col-md-3 response">
                                <?php $data = search_data_text($plan, 'datos', 'semanas_total'); ?>
                                <input class="form-control text-cerrado class-input" type="text" name="semanas_total"
                                    id="semanas_total" value="<?= $data['contenido'] ?>"
                                    onchange="update_text(<?= $data['id'] ?>, 'semanas_total');">
                            </div>
                        </div>
                        <!-- una clase dura y en una semana hay -->


                        <!-- Ejes trasversales -->
                        <div class="row">
                            <div class="col-lg-3 col-md-3 background-gray border-b border-l">
                                Ejes transversales
                            </div>

                            <div class="col-lg-9 col-md-9 response">
                                <?php $data = search_data_text($plan, 'datos', 'ejes'); ?>
                                <input class="form-control text-cerrado class-input" type="text" name="ejes" id="ejes"
                                    value="<?= $data['contenido'] ?>"
                                    onchange="update_text(<?= $data['id'] ?>, 'ejes');">
                            </div>
                        </div>
                        <!-- Fin Ejes trasversales -->

                    </div>
                    <!-- fin de la sección datos informativos -->

                    <!-- inicio de la sección 1) esquemas del curso -->
                    <div id="esquemas" style="margin-top: 10px; scroll-behavior: smooth;">
                        <?=
                            $this->render('_unidades', [
                                'unidades' => $unidades
                            ])
                            ?>
                    </div>
                    <!-- fin de la sección 1) esquemas del curso -->


                    <!-- inicio de componentes internos y externos -->
                    <div id="componentes" style="margin-top: 10px; scroll-behavior: smooth;">
                        <?=
                            $this->render('_componentes', [
                                'componentes' => $componentes,
                                'cabecera_id' => $cabecera->id
                            ])
                            ?>
                    </div>
                    <!-- fin de componentes internos y externos -->



                    <!-- inicio de vínculos con Teoría del conocimiento -->
                    <div id="vinculos" style="margin-top: 10px; scroll-behavior: smooth;">
                        <?=
                            $this->render('_vinculo', [
                                'plan' => $plan,
                                'cabecera_id' => $cabecera->id
                            ])
                            ?>
                    </div>
                    <!-- fin de vínculos con Teoría del conocimiento -->


                    <!-- 4.- inicio enfoques de aprendizaje -->
                    <div id="enfoques" style="margin-top: 10px; scroll-behavior: smooth;">
                        <?=
                            $this->render('_enfoques', [
                                'plan' => $plan,
                                'cabecera_id' => $cabecera->id
                            ])
                            ?>
                    </div>
                    <!-- 4.- Fin enfoques de aprendizaje -->



                    <!-- 5.- inicio mentalidad internacional -->
                    <div id="mentalidad" style="margin-top: 10px; scroll-behavior: smooth;">
                        <?=
                            $this->render('_mentalidad', [
                                'plan' => $plan,
                                'cabecera_id' => $cabecera->id
                            ])
                            ?>
                    </div>
                    <!-- 5.- Fin mentalidad internacional -->

                    <!-- 6.- Desarrollo perfil de la comunidad -->
                    <div id="perfil" style="margin-top: 10px; scroll-behavior: smooth;">
                        <?=
                            $this->render('_perfil', [
                                'plan' => $plan,
                                'cabecera_id' => $cabecera->id
                            ])
                            ?>
                    </div>
                    <!-- 6.- Fin Desarrollo perfil de la comunidad -->

                    <!-- 7.- Instalaciones y equipos -->
                    <div id="equipos" style="margin-top: 10px; scroll-behavior: smooth;">
                        <?=
                            $this->render('_equipos', [
                                'plan' => $plan,
                                'cabecera_id' => $cabecera->id
                            ])
                            ?>
                    </div>
                    <!-- 7.- Fin Instalaciones y equipos -->


                    <!-- 8.- Otros recursos -->
                    <div id="otros" style="margin-top: 10px; scroll-behavior: smooth;">
                        <?=
                            $this->render('_otros', [
                                'plan' => $plan,
                                'cabecera_id' => $cabecera->id
                            ])
                            ?>
                    </div>
                    <!-- 8.- Fin Otros recursos -->


                    <!-- 9.- Bibliografía -->
                    <div id="bibliografia" style="margin-top: 10px; scroll-behavior: smooth;">
                        <?=
                            $this->render('_bibliografia', [
                                'plan' => $plan,
                                'cabecera_id' => $cabecera->id
                            ])
                            ?>
                    </div>
                    <!-- 9.- Fin Bibliografía -->

                </div>
            </div>



            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>

<?php
function search_data_text($plan, $section, $typeField)
{
    foreach ($plan as $p) {
        if ($p->tipo_campo == $typeField && $p->tipo_seccion == $section) {
            return array(
                'id' => $p->id,
                'contenido' => $p->opcion_texto
            );
        }
    }
}

function search_data_select($plan, $section, $typeField, $option)
{
    foreach ($plan as $p) {
        if ($p->tipo_campo == $typeField && $p->tipo_seccion == $section && $p->opcion_texto == $option) {
            return array(
                'id' => $p->id,
                'seleccion' => $p->opcion_seleccion
            );
        }
    }
}

?>

<script>
    window.addEventListener('scroll', function () {
        let currentSection = '';
        document.querySelectorAll('section').forEach(function (section) {
            const sectionTop = section.offsetTop;
            if (scrollY >= sectionTop - 100) {
                currentSection = section.getAttribute('id');
            }
        });

        document.querySelectorAll('a').forEach(function (link) {
            link.classList.remove('current');
            if (link.getAttribute('href').substring(1) === currentSection) {
                link.classList.add('current');
            }
        });
    });


    function change_select(planId) {
        var url = '<?= Url::to(['update']) ?>';
        params = {
            plan_id: planId,
            field: 'seleccion'
        }
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () { },
            success: function (response) {
                //location.reload();
            }
        });
    }


    function update_text(planId, elementId) {
        let content = $('#' + elementId).val();

        var url = '<?= Url::to(['update']) ?>';
        params = {
            plan_id: planId,
            field: 'texto',
            content: content
        }
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () { },
            success: function (response) {
                //location.reload();
            }
        });
    }
</script>