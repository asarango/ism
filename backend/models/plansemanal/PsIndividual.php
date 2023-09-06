<?php
namespace backend\models\plansemanal;

use backend\models\PlanificacionSemanal;
use backend\models\ScholarisBloqueSemanas;
use Yii;
use yii\db\ActiveRecord;

use DateTime;

class PsIndividual
{
    private $claseId;
    private $semanaId;
    private $fechaInicioSemana;

    public function __construct($claseId, $semanaId)
    {
        $this->claseId = $claseId;
        $this->semanaId = $semanaId;

        $semana = ScholarisBloqueSemanas::findOne($this->semanaId);
        $this->fechaInicioSemana = $semana->fecha_inicio;

        $this->recupera_horario();
    }


    private function recupera_horario()
    {
        $horario = $this->get_horario();     
        // echo '<pre>';
        // print_r($horario);
        // die();   
        $i=0;
        foreach ($horario as $hora) {
            $i++;
            $fecha = $this->busca_fecha($hora['orden_dia']);
            // echo '<br>'.$fecha;
            // echo '<br>'.$hora['orden_dia'];
            // echo '<br>'.$hora['hora_id'];
            $this->agrega_hora($hora['hora_id'], $i, $fecha);
        }
    }


    private function get_horario()
    {
        $con = Yii::$app->db;
        $query = "select 	hor.id as hora_id
                            ,hor.numero as orden_hora
                            ,dia.numero as orden_dia
                    from 	scholaris_horariov2_horario hh
                            inner join scholaris_horariov2_detalle det on det.id = hh.detalle_id 
                            inner join scholaris_horariov2_dia dia on dia.id = det.dia_id 
                            inner join scholaris_horariov2_hora hor on hor.id = det.hora_id 
                    where 	hh.clase_id = $this->claseId
                    order by dia.numero, hor.numero;";
        // echo $query;
        // echo $this->claseId;
        // die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function agrega_hora($hora_id, $orden_hora, $fecha)
    {
        $ps = PlanificacionSemanal::find()->where([
            'semana_id' => $this->semanaId,
            'hora_id' => $hora_id,
            'orden_hora_semana' => $orden_hora,
            'fecha' => $fecha,
            'clase_id' => $this->claseId
        ])->one();    

        if(!$ps){
            //inyectar plan semanal
            $user = Yii::$app->user->identity->usuario;
            $today = date('Y-m-d H:i:s');
            $model = new PlanificacionSemanal();
            $model->semana_id   = $this->semanaId;
            $model->clase_id    = $this->claseId;
            $model->fecha       = $fecha;
            $model->hora_id     = $hora_id;
            $model->orden_hora_semana = $orden_hora;
            $model->tema        = 'none';
            $model->actividades = 'none';
            $model->diferenciacion_nee = 'none';
            $model->recursos    = 'none';
            $model->created     = $user;
            $model->created_at  = $today;
            $model->updated     = $user;
            $model->updated_at  = $today;
            $model->save();
        }
    }

    private function busca_fecha($ordenNumero)
    {
        $orden = $ordenNumero - 1;
        $nuevaFecha = date('Y-m-d', strtotime($this->fechaInicioSemana . ' + ' . $orden . ' days'));
        return $nuevaFecha;
    }
}