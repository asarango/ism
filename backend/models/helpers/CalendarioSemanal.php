<?php
namespace backend\models\helpers;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class CalendarioSemanal extends ActiveRecord{
    
    private $desde;
    private $hasta;
    private $dias;
    private $usuarioLog;
    private $periodoId;
    public $fechas = array();
    
    public function __construct($fechaDesde, $fechaHasta, $usuarioLog) {
        $this->desde = $fechaDesde;
        $this->hasta = $fechaHasta;
        $this->usuarioLog = $usuarioLog;
        $this->periodoId = Yii::$app->user->identity->periodo_id;
        
//        $this->dias = \backend\models\ScholarisHorariov2Dia::find()->orderBy('numero')->asArray()->all();
        $this->dias = $this->get_dias();
        
        $this->generar_calendario();
    }
    
    
    private function get_dias(){
        $con = Yii::$app->db;
        $query = "select 	dia.numero 
		,dia.nombre 
                from 	scholaris_horariov2_horario hh
                                inner join scholaris_clase cl on cl.id = hh.clase_id
                                inner join op_faculty fa on fa.id = cl.idprofesor 
                                inner join res_users us on us.partner_id = fa.partner_id 
                                inner join ism_area_materia am on am.id = cl.ism_area_materia_id 
                                inner join ism_malla_area ma on ma.id = am.malla_area_id 
                                inner join ism_periodo_malla pm on pm.id = ma.periodo_malla_id 
                                inner join scholaris_horariov2_detalle hd on hd.id = hh.detalle_id
                                inner join scholaris_horariov2_dia dia on dia.id = hd.dia_id 
                where 	us.login = '$this->usuarioLog'
                                and pm.scholaris_periodo_id = $this->periodoId group by dia.numero, dia.nombre order by dia.numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    private function generar_calendario(){
       
        foreach ($this->dias as $dia){            
            for($i=0;$i<=5;$i++){
               $nuevaFecha = date("Y-m-d", strtotime($this->desde."+ $i days"));
               if($dia['numero'] == (date("N", strtotime($nuevaFecha))) ){
                   $dia['fecha'] = $nuevaFecha;
               } 
            }
            array_push($this->fechas, $dia);
        }
    }        
    
}