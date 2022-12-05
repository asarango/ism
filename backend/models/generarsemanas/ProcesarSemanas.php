<?php
namespace backend\models\generarsemanas;

use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisBloqueSemanas;
use DateTime;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class ProcesarSemanas extends ActiveRecord{
    
    private $bloqueId;
    private $modelBloque;
    private $totalDias;
        
    public function __construct($bloqueId) {
        $this->bloqueId = $bloqueId;
        $this->modelBloque = ScholarisBloqueActividad::findOne($bloqueId);
    }

    public function process(){
        $validate = $this->validate_dates();
        if($validate['status']){
            $this->register_weeks();
            return array(
                'status' => true
            );
        }else{
            return $validate;
        }
    }


    public function validate_dates(){
        $desde = new DateTime($this->modelBloque->bloque_inicia);        
        //$desde = new DateTime(date('Y-m-d'));        
        $hasta = new DateTime($this->modelBloque->bloque_finaliza);

        if($hasta <= $desde){
            $response = array(
                "status" => false,
                "msg" => '¡La fecha de fin no puede ser menos o igual a la fecha de inicio!'
            );
            die($response['msg']);
        }elseif(date('w',strtotime($this->modelBloque->bloque_inicia)) != 1 || date('w',strtotime($this->modelBloque->bloque_finaliza))!=5){
            $response = array(
                "status" => false,
                "msg" => '¡El dia de inico del bloque debe ser Lunes y el día de fin debe ser viernes!'
            );
            die($response['msg']);
        }else{
            $response = array(
                "status" => true
            );
        }
        
        return $response;
       
    }

    private function register_weeks(){
        $fecha = date("Y-m-d",strtotime($this->modelBloque->bloque_inicia."- 1 days"));
        $hasta = $this->modelBloque->bloque_finaliza;
        
        $i=0;

        while($fecha <= $hasta){            
            $fecha = date("Y-m-d",strtotime($fecha."+ 1 days"));
            $diaNumero = date('w',strtotime($fecha));
            if($diaNumero == 1){
                $this->register_wek($fecha, $diaNumero);
            }
            $i++;
        }

    }

    private function register_wek($fecha, $diaNumero){
        //echo $fecha.' -- '.$diaNumero.'<br>';
        $model = ScholarisBloqueSemanas::find()->where([
            'bloque_id' => $this->bloqueId,
            'fecha_inicio' => $fecha
        ])->one();


        if(!$model){
            $modelS = ScholarisBloqueSemanas::find()
                ->where([
                    'bloque_id' => $this->bloqueId
                ])
                ->orderBy(['semana_numero' => SORT_DESC])
                ->one();
            if(!isset($modelS->semana_numero)){
                $numeroSemana = 1;
            }else{
                $numeroSemana = $modelS->semana_numero + 1;
            }
            $insert = new ScholarisBloqueSemanas();
            $insert->bloque_id      = $this->bloqueId;
            $insert->semana_numero  = $numeroSemana;
            $insert->nombre_semana  = 'Sem'.$numeroSemana;
            $insert->fecha_inicio   = $fecha;
            $insert->fecha_finaliza = date("Y-m-d",strtotime($fecha."+ 4 days"));
            $insert->estado = 1;
            $insert->fecha_limite_inicia = $fecha;
            $insert->fecha_limite_tope  = date("Y-m-d",strtotime($fecha."+ 4 days"));
            $insert->save();
        }
    }

     
}