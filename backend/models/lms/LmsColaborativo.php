<?php

namespace backend\models\lms;

use backend\models\Lms;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class LmsColaborativo extends ActiveRecord {

    public function __construct() {}

    public function inyecta_plan_x_hora($totalHoras, $semanaNumero, $ismAreaMateriaId, $uso){
        $usuarioLog = Yii::$app->user->identity->usuario;
        $hoy        = date("Y-m-d H:i:s");
        
        $lms = Lms::find()->where([
            'ism_area_materia_id' => $ismAreaMateriaId,
            'semana_numero' => $semanaNumero
        ])
                ->orderBy('semana_numero')
                ->all();
        
        isset($lms) ? $totalPlanificado = count($lms) : $totalPlanificado = 0;
        
        $totalInyectar = $totalHoras - $totalPlanificado;
        
        $horaNumero = $this->toma_ultima_hora($semanaNumero, $ismAreaMateriaId); //Para toma el ultimo numero de la tabla LMS 
        
        $horaNumero ? $horaNumero = $horaNumero : $horaNumero = 0; //Convierte a 0 si no existe ultimo numero en la tabla lms
        
        
        
        if($totalInyectar > 0){
            //inyectamos las horas con texto sin planificar            
            for($i=0; $i<$totalInyectar; $i++){
                $horaNumero++;
                $modelLms = new Lms();
                $modelLms->ism_area_materia_id          = $ismAreaMateriaId;
                $modelLms->tipo_bloque_comparte_valor   = $uso;
                $modelLms->semana_numero                = $semanaNumero;
                $modelLms->hora_numero                  = $horaNumero;
                $modelLms->tipo_recurso                 = 'TEMA-HORA';
                $modelLms->titulo                       = 'NO CONFIGURADO';
                $modelLms->publicar                     = false;
                $modelLms->created                      = $usuarioLog;
                $modelLms->created_at                   = $hoy;
                $modelLms->updated                      = $usuarioLog;
                $modelLms->updated_at                   = $hoy;
                $modelLms->conceptos                    = '<p><b>Concepto:</b></p><p><b>Atributo:</b></p><p><b>Línea de indagación:</b></p><p><b>Enfoque:</b></p><p><b>ODS:</b></p>';                    
                $modelLms->save();
            }
        }
    }


    private function toma_ultima_hora($semanaNumero, $ismAreaMateriaId){
        $con = Yii::$app->db;
        $query = "select 	max(hora_numero) as ultima 
                    from lms 
                    where ism_area_materia_id = $ismAreaMateriaId 
                    and semana_numero = $semanaNumero;";
        $res = $con->createCommand($query)->queryOne();
        
        if($res){
            return $res['ultima'];
        }else{
            return 0;
        }
        
        
    }

    /**
     * MÉDOTO PARA TOMAR LOS PLANES SEMANALES Y RETORNAR COMO ARREGLO, ES DE TODO EL BLOQUE
     * CREADO POR: Arturo Sarango - 2023-03-30 
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-30 
     */
    public function planes_semanales_x_unidad($arraySemanas, $ismAreaMateriaId, $uso){
        $arreglo = array();
        
        foreach($arraySemanas as $semana){
            $lms = $this->get_lms($semana['semana_numero'], $ismAreaMateriaId, $uso);
            $semana['actividades'] = $lms;

            $tareas = $this->get_lms_actividad($semana['semana_numero'], $ismAreaMateriaId, $uso);
            $semana['tareas'] = $tareas;
            array_push($arreglo, $semana);
        }

        return $arreglo;
    }

    /**
     * MÉDOTO PARA CONSULTAR LMS DE LA SEMANA 
     * CREADO POR: Arturo Sarango - 2023-03-30 
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-30 
     */
    private function get_lms($semanaNumero, $ismAreaMateriaId, $uso){
        $con = Yii::$app->db;
        $query = "select 	id
                            ,dip_inicio
                            ,dip_desarrollo 
                            ,dip_cierre 
                            ,lms.hora_numero
                    from 	lms
                    where 	ism_area_materia_id = $ismAreaMateriaId
                            and tipo_bloque_comparte_valor = $uso
                            and semana_numero = $semanaNumero
                    order by lms.hora_numero;";
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }


    /**
     * MÉDOTO PARA CONSULTAR ACTIVIDADES DEL LMS DE LA SEMANA 
     * CREADO POR: Arturo Sarango - 2023-03-30 
     * ACTUALIZADO POR: Arturo Sarango - 2023-03-30 
     */
    private function get_lms_actividad($semanaNumero, $ismAreaMateriaId, $uso){
        $con = Yii::$app->db;
        $query = "select 	act.id, act.tipo_actividad_id, act.titulo, act.es_calificado 
                    from 	lms
                            inner join lms_actividad act on act.lms_id = lms.id 
                    where 	ism_area_materia_id = $ismAreaMateriaId
                            and tipo_bloque_comparte_valor = $uso
                            and semana_numero = $semanaNumero
                    order by act.id;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    
}
