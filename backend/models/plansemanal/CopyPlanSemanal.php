<?php
namespace backend\models\plansemanal;

use backend\models\PlanificacionSemanal;
use backend\models\ScholarisActividad;
use backend\models\ScholarisBloqueSemanas;
use Yii;
use yii\db\ActiveRecord;

use DateTime;

class CopyPlanSemanal
{
    private $claseIdDesde;
    private $claseIdHasta;
    private $semanaId;

    private $modelPlanSemanalDesde;

    private $modelPlanSemanalHasta;

    public function __construct($claseIdDesde, $claseIdHasta, $semanaId)
    {
        $this->claseIdDesde = $claseIdDesde;
        $this->claseIdHasta = $claseIdHasta;
        $this->semanaId = $semanaId;

        $this->modelPlanSemanalDesde = PlanificacionSemanal::find()->where([
            'clase_id' => $claseIdDesde,
            'semana_id' => $semanaId
        ])
        ->all();

        $this->modelPlanSemanalHasta = PlanificacionSemanal::find()->where([
            'clase_id' => $claseIdHasta,
            'semana_id' => $semanaId
        ])
        ->all();


        $this->proceso_copia_plan_semanal();

    }

    private function proceso_copia_plan_semanal(){
        $today = date('Y-m-d H:i:s');

        foreach($this->modelPlanSemanalHasta as $hasta){

            $this->delete_recursos($hasta->id);
            
            $modelDesde = PlanificacionSemanal::find()->where([
                'clase_id' => $this->claseIdDesde,
                'semana_id' => $this->semanaId,
                'orden_hora_semana' => $hasta->orden_hora_semana
            ])->one();

            $hasta->tema = $modelDesde->tema;
            $hasta->actividades = $modelDesde->actividades;
            $hasta->diferenciacion_nee = $modelDesde->diferenciacion_nee;
            $hasta->recursos = $modelDesde->recursos;
            $hasta->updated = Yii::$app->user->identity->usuario;
            $hasta->updated_at = $today;

            $hasta->save();

            $this->copia_recursos($hasta->id, $modelDesde->id);
            $this->copia_actividades($hasta->id, $modelDesde->id, $hasta->hora_id);

        }
    }


    private function delete_recursos($planSemanalHastaId){
        $con = Yii::$app->db;
        $queryRecursos = "delete from planificacion_semanal_recursos where plan_semanal_id = $planSemanalHastaId;";
        $queryActivida = "delete from scholaris_actividad where plan_semanal_id = $planSemanalHastaId;";
        $con->createCommand($queryRecursos)->execute();
        $con->createCommand($queryActivida)->execute();
    }


    private function copia_recursos($planSemanalHastaId, $planSemanalDesdeId){
        $con = Yii::$app->db;
        $query = "insert into planificacion_semanal_recursos(plan_semanal_id, tema, tipo_recurso, url_recurso, estado)
                    select 	$planSemanalHastaId,tema, tipo_recurso, url_recurso, estado 
                    from 	planificacion_semanal_recursos
                    where	plan_semanal_id = $planSemanalDesdeId;
        ";
        $con->createCommand($query)->execute();
    }


    private function copia_actividades($planSemanalHastaId, $planSemanalDesdeId, $horaIdHasta){
        $claseHastaId = $this->modelPlanSemanalHasta[0]->clase_id;
        $con = Yii::$app->db;
        $query = "insert into scholaris_actividad (create_date, write_date, create_uid, write_uid, title, descripcion, archivo
                                                    , descripcion_archivo, color, inicio, fin, tipo_actividad_id, bloque_actividad_id, a_peso, b_peso
                                                    , c_peso, d_peso, paralelo_id, materia_id, calificado, tipo_calificacion, tareas, hora_id
                                                    , actividad_original, semana_id, momento_detalle, con_nee, grado_nee, observacion_nee, destreza_id
                                                    , formativa_sumativa, videoconfecia, respaldo_videoconferencia, link_aula_virtual, es_aprobado
                                                    , fecha_revision, usuario_revisa, comentario_revisa, respuesta_revisa, lms_actvidad_id, es_heredado_lms
                                                    , estado, plan_semanal_id, ods_pud_dip_id)
        select create_date, write_date, create_uid, write_uid, title, descripcion, archivo, descripcion_archivo, color, inicio, fin
                , tipo_actividad_id, bloque_actividad_id, a_peso, b_peso, c_peso, d_peso, $claseHastaId, materia_id, calificado, tipo_calificacion
                , tareas, $horaIdHasta, actividad_original, semana_id, momento_detalle, con_nee, grado_nee, observacion_nee, destreza_id
                , formativa_sumativa, videoconfecia, respaldo_videoconferencia, link_aula_virtual, es_aprobado, fecha_revision
                , usuario_revisa, comentario_revisa, respuesta_revisa, lms_actvidad_id, es_heredado_lms, estado, $planSemanalHastaId
                , ods_pud_dip_id  from scholaris_actividad where plan_semanal_id = $planSemanalDesdeId;";

        $con->createCommand($query)->execute();

    }

}