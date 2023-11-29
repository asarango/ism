<?php

use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisBloqueSemanas;
use backend\models\ScholarisClase;
use yii\helpers\Html;

// echo '<pre>';
// print_r($clases);
// die();
?>

<style>
    .centrado {
        margin-top: 10px;
    }

    .font {
        font-size: 15px;
        color: black;
    }

    .custom-table {
        border-collapse: collapse;
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        color: black;
        font-weight: bold;
    }

    .custom-table th,
    .custom-table td {
        padding: 15px;
        text-align: center;
        border: 1px solid #e0e0e0;
    }

    .custom-table th {
        background-color: #f5f5f5;
        color: black;
    }

    .custom-table tr:nth-child(even) {
        background-color: #f9f9f9;
        color: black;
    }

    .custom-table th:first-child,
    .custom-table td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        color: black;
    }

    .custom-table th:last-child,
    .custom-table td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        color: black;
    }
</style>

<table class="custom-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Materia</th>
            <th>Curso</th>
            <th>Paralelo</th>
            <th>Ver lista de estudiantes</th>
            <th>Plan semanal</th>
            <th>Reporte Notas</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $contador = 1;
        foreach ($clases as $clase) {
        ?>
            <tr>
                <td>
                    <?= $contador ?>
                </td>
                <td>
                    <?= $clase['materia'] ?>
                </td>
                <td>
                    <?= $clase['curso'] ?>
                </td>
                <td class="font"><span>
                        <?= $clase['paralelo'] ?>
                    </span></td>
                <td class="font">
                    <?= Html::a(
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-list" width="45" height="45" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff9e18" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                            <path d="M9 12l.01 0" />
                            <path d="M13 12l2 0" />
                            <path d="M9 16l.01 0" />
                            <path d="M13 16l2 0" />
                        </svg>',
                        [
                            'agregar-alumnos',
                            'clase_id' => $clase['clase_id']
                        ],
                        [
                            'title' => 'Visualizar lista de ' . $clase['materia'] . '-' . $clase['paralelo'],
                        ]
                    );
                    ?>
                </td>

                <!-- Plan semanal -->
                <td class="font">

                    <?php
                        $datosPlanSemanal = datos_plan_semanal($clase['clase_id']);
                    ?>

                    <?= Html::a(
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-event" width="45" 
                                height="45" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" 
                                stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                <path d="M16 3l0 4" /><path d="M8 3l0 4" />
                                <path d="M4 11l16 0" />
                                <path d="M8 15h2v2h-2z" />
                        </svg>',
                        [
                            'planificacion-semanal/index1',
                            'bloque_id' => $datosPlanSemanal['bloque_id'],
                            'clase_id' => $clase['clase_id'],
                            'semana_defecto' => $datosPlanSemanal['semana_defecto'],
                            'pud_origen' => $datosPlanSemanal['pud_origen'],
                            'plan_bloque_unidad_id' => $datosPlanSemanal['plan_bloque_unidad_id']
                        ],
                        [
                            'title' => 'Plan semanal',
                        ]
                    );
                    ?>
                </td>
                <!-- Fin plan semanal -->


                <td class="font">
                    <?= Html::a(
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-bar" width="45" height="45" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00b341" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        <path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        <path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        <path d="M4 20l14 0" />
                      </svg>',
                        [
                            'reporte-notas-profesor-nac/index1',
                            'clase_id' => $clase['clase_id']
                        ],
                        [
                            'title' => 'Mostrar reporte de Notas',
                        ]
                    );
                    ?>
                </td>
            </tr>
        <?php
            $contador++;
        }
        ?>
    </tbody>
</table>


<?php
    function datos_plan_semanal($claseId){
        $clase = ScholarisClase::findOne($claseId);
        $uso = $clase->tipo_usu_bloque;
        $bloque = ScholarisBloqueActividad::find()->where([
            'tipo_uso' => $uso,
            'orden' => 1
        ])->one();

        $planBloqueUnidadId = consulta_semana($claseId);

        return array(
            'bloque_id'         => $bloque->id,
            'semana_defecto'    => 1,
            'pud_origen'        => 'mis-clases',
            'plan_bloque_unidad_id' => $planBloqueUnidadId
        );
        
    }


    function consulta_semana($claseId){
        $con = Yii::$app->db;
        $query = "select 	pu.id  
                    from 	planificacion_bloques_unidad pu
                            inner join planificacion_desagregacion_cabecera cab on cab.id = pu.plan_cabecera_id 
                            inner join ism_area_materia iam on iam.id = cab.ism_area_materia_id 
                            inner join scholaris_clase cla on cla.ism_area_materia_id = iam.id 
                            inner join curriculo_mec_bloque cmb on cmb.id = pu.curriculo_bloque_id 
                    where 	cla.id = $claseId
                            and cmb.code = 1;";
        $res = $con->createCommand($query)->queryOne();
        return $res['id'];
    }

?>


<!----------------------------->
<script>
    function muestra_detalle_curso(claseId) {
        muestra_detalle(claseId, 'bloques');
        $('#div-semanas').hide();
    }
</script>