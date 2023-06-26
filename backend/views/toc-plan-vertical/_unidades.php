<?php
use backend\models\TocPlanUnidadHabilidad;
use Mpdf\Tag\Span;
use yii\helpers\Html;
use yii\helpers\Url;


// echo "<pre>";
// print_r($claseId);
// die(); 

foreach ($unidades as $unidad) {
    
    ?>
    <!-- Inicio Unidades -->
    <hr>
    <div class="" style="margin-top:-5px;">
        <div class="">
            <p>
                <h5 style="margin-top: -1rem; margin-bottom: -0.5rem; text-align: left;">
                    <p class="col-lg-3 col-md-3 col-sm-3 col-lx-3" style="margin-bottom:-1rem"><b><?= $unidad->bloque->name; ?></b></p>
                    <!-- INICIO BOTONES TABLA -->             
                    <div class="col-lg-3 col-md-3 col-sm-3 col-lx-3" style="margin-left:9.6rem;margin-top: -1.5rem">
                        <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #ff9e18">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-description" 
                                width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" 
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M9 17h6" />
                                <path d="M9 13h6" />
                                </svg></span>',
                                ['toc-plan-vertical/update-units', 'id' => $unidad['id']],
                                ['class' => '', 'title' => 'Modificar Plan de Unidad']
                            );
                        ?>
                        |
                        <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #ab0a3d "><svg xmlns="http://www.w3.org/2000/svg" 
                                class="icon icon-tabler icon-tabler-brain" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" 
                                stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15.5 13a3.5 3.5 0 0 0 -3.5 3.5v1a3.5 3.5 0 0 0 7 0v-1.8" />
                                <path d="M8.5 13a3.5 3.5 0 0 1 3.5 3.5v1a3.5 3.5 0 0 1 -7 0v-1.8" />
                                <path d="M17.5 16a3.5 3.5 0 0 0 0 -7h-.5" />
                                <path d="M19 9.3v-2.8a3.5 3.5 0 0 0 -7 0" />
                                <path d="M6.5 16a3.5 3.5 0 0 1 0 -7h.5" />
                                <path d="M5 9.3v-2.8a3.5 3.5 0 0 1 7 0v10" />
                            </svg></span>', 
                            ['toc-plan-vertical/habilidades', 'id' => $unidad['id']], 
                            ['class' => '', 'title' => 'Habilidades IB'] 
                            );

                        ?>

                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-lx-3" style="margin-left: 48.5rem;margin-top: -1.5rem">
                    <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #9e28b5 "><svg xmlns="http://www.w3.org/2000/svg" 
                                                        class="icon icon-tabler icon-tabler-list-details" width="16" height="16" 
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" 
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M13 5h8" />
                                                        <path d="M13 9h5" />
                                                        <path d="M13 15h8" />
                                                        <path d="M13 19h5" />
                                                        <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                        <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                    </svg></span>',
                                ['toc-plan-unidad-detalle/index1', 'id' => $unidad['id']],
                                ['class' => '', 'title' => 'Planificacion de Unidad']
                            );

                        ?>
                        |
                        <?=
                            Html::a(
                                '<span class="badge rounded-pill" style="background-color: #65b2e8 "><svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="icon icon-tabler icon-tabler-calendar-event" 
                                width="16" height="16" viewBox="0 0 24 24" 
                                stroke-width="1.5" stroke="#ffffff" fill="none" 
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 
                                2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                <path d="M16 3l0 4" />
                                <path d="M8 3l0 4" />
                                <path d="M4 11l16 0" />
                                <path d="M8 15h2v2h-2z" />
                                </svg></span>',
                                ['planificacion-semanal/index1', 'clase_id' => $claseId, 'bloque_id' => $unidad->bloque_id ],
                                ['class' => '', 'title' => 'Plan Semanal']
                            );
                        ?>
                    
                    </div>
                    <!-- FIN BOTONES TABLA -->
                </h5>
            </p>
        </div>
        <div id="detalle<?= $unidad->id ?>">
            <div>
                <!-- CONDICIÓN DE ICONOS DE TABLA -->
                <?php
                if ($unidad->objetivos == '' || $unidad->objetivos == '<p>none</p>') {
                    $objetivos = Html::a(
                        '<span><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler 
                                icon-tabler-exclamation-circle" width="40" height="40"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="#ab0a3d" fill="none" 
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 9v4" />
                                <path d="M12 16v.01" />
                              </svg></span>',
                        ['toc-plan-vertical/update-units', 'id' => $unidad['id']]
                    );
                } else {
                    $objetivos = Html::a(
                        '<span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checklist"
                                width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="#00b341" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" />
                                <path d="M14 19l2 2l4 -4" />
                                <path d="M9 8h4" />
                                <path d="M9 12h2" />
                                </svg></span>',
                        ['toc-plan-vertical/update-units', 'id' => $unidad['id']]
                    );
                }
                if ($unidad->conceptos_clave == '' || $unidad->conceptos_clave == '<p>none</p>') {
                    $conceptos_clave = Html::a(
                        '<span><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler 
                                icon-tabler-exclamation-circle" width="40" height="40"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="#ab0a3d" fill="none" 
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 9v4" />
                                <path d="M12 16v.01" />
                              </svg></span>',
                        ['toc-plan-vertical/update-units', 'id' => $unidad['id']]
                    );
                } else {
                    $conceptos_clave = Html::a(
                        '<span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checklist"
                                width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="#00b341" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" />
                                <path d="M14 19l2 2l4 -4" />
                                <path d="M9 8h4" />
                                <path d="M9 12h2" />
                                </svg></span>',
                        ['toc-plan-vertical/update-units', 'id' => $unidad['id']]
                    );
                }

                if ($unidad->contenido == '' || $unidad->contenido == '<p>none</p>' ) {
                    $contenido = Html::a(
                        '<span><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler 
                                icon-tabler-exclamation-circle" width="40" height="40"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="#ab0a3d" fill="none" 
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 9v4" />
                                <path d="M12 16v.01" />
                              </svg></span>',
                        ['toc-plan-vertical/update-units', 'id' => $unidad['id']]
                    );
                } else {
                    $contenido = Html::a(
                        '<span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checklist"
                                width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="#00b341" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" />
                                <path d="M14 19l2 2l4 -4" />
                                <path d="M9 8h4" />
                                <path d="M9 12h2" />
                                </svg></span>',
                        ['toc-plan-vertical/update-units', 'id' => $unidad['id']]
                    );
                }

                if ($unidad->evaluacion_pd == '' || $unidad->evaluacion_pd == '<p>none</p>') {
                    $evaluacion_pd = Html::a(
                        '<span><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler 
                                icon-tabler-exclamation-circle" width="40" height="40"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="#ab0a3d" fill="none" 
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 9v4" />
                                <path d="M12 16v.01" />
                              </svg></span>',
                        ['toc-plan-vertical/update-units', 'id' => $unidad['id']]
                    );
                } else {
                    $evaluacion_pd = Html::a(
                        '<span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checklist"
                                width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="#00b341" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" />
                                <path d="M14 19l2 2l4 -4" />
                                <path d="M9 8h4" />
                                <path d="M9 12h2" />
                                </svg></span>',
                        ['toc-plan-vertical/update-units', 'id' => $unidad['id']]
                    );
                }

                ?>
                <!-- FIN CONDICIÓN DE ICONOS DE TABLA -->

                <!-- INICIO DE TABLA -->

                <table class="table align-middle">
                    <thead style="text-align: center;">
                    </thead>
                    <thead>
                        <td width="100px" class="segunda_tabla lin_tabla" style="text-align: center;">
                            TÍTULO DE LA UNIDAD</td>
                        <td width="130px" class="segunda_tabla lin_tabla" style="text-align: center;">
                            OBJETIVOS DE LA UNIDAD</td>
                        <td width="120px" class="align-top segunda_tabla lin_tabla" style="text-align: center;">
                            CONCEPTOS CLAVE</td>
                        <td width="120px" class=" segunda_tabla lin_tabla" style="text-align: center;">CONTENIDO
                        </td>
                        <td width="120px" class="segunda_tabla lin_tabla" style="text-align: center;">
                            HABILIDADES IB
                        </td>
                        <td width="120px" class="segunda_tabla lin_tabla" style="text-align: center;">
                            EVALUACIÓN PD
                        </td>
                        </tr>
                    </thead>
                    <tbody>
                        <td class="vertical-align: middle; links bg-color" style="text-align: center;">
                            <?= $unidad['titulo']; ?>
                        </td>
                        <td class="vertical-align: middle; links bg-color" style="text-align: center;">
                            <?= $objetivos?>
                        </td>
                        <td class="vertical-align: middle; links bg-color" style="text-align: center;">
                            <?=
                                $conceptos_clave
                                ?>
                        </td>
                        <td class="vertical-align: middle; links bg-color" style="text-align: center;">
                            <i class="fa-solid fa-file-check fa-xs" style="color: #22dd26;"></i>
                            <?=
                                $contenido
                                ?>
                        </td>
                        <td class="bg-color" style="text-align: center;">
                            <p style="font-size: 1rem;color: #765f5e;"><b><?= contar_habilidades($unidad->id)?></b></p></td>
                        <td class="vertical-align: middle; links bg-color" style="text-align: center;">
                            <?=
                                $evaluacion_pd
                                ?>
                        </td>
                    </tbody>
                </table>
                
                <!-- FIN DE TABLA  -->
                <!-- <a type="button" class="btn btn-success segunda_tabla boton">Guardar</a>  -->
            </div>
        </div>
    </div>
    <!-- FIN DE UNIDADES -->
    <?php
}

?>

<?php
    function contar_habilidades ($unidadId){
        $habilidades = TocPlanUnidadHabilidad::find()->where([
            'toc_plan_unidad_id' => $unidadId , 
            'is_active' => true    
        ])->all();
        return count($habilidades);
    }

?>

