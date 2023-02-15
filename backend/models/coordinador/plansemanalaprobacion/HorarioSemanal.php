<?php
namespace backend\models\coordinador\plansemanalaprobacion;

use backend\models\helpers\CalendarioSemanal;
use backend\models\ScholarisBloqueSemanas;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class HorarioSemanal extends ActiveRecord{
    
    private $weekId;   
    private $userTeacher;   
    private $week;
    public $dates;
    public $hours;
    private $periodId;
    
    public function __construct($weekId, $userTeacher) {
        $this->periodId = Yii::$app->user->identity->periodo_id;
        $this->weekId = $weekId;
        $this->userTeacher = $userTeacher;
        $this->week = ScholarisBloqueSemanas::findOne($weekId);

        $this->dates = $this->get_dates($this->week->fecha_inicio, 
                        $this->week->fecha_finaliza, 
                        $userTeacher, $this->periodId);

        $this->hours = $this->get_hours($userTeacher, $this->periodId);
    }


    private function get_dates($fechaInicio, $fechaFinaliza,  $user, $periodId){                
        $days = new CalendarioSemanal($fechaInicio, $fechaFinaliza, $user);        
        return $days->fechas;
    }


    private function get_hours($user, $periodId){
        $con = Yii::$app->db;
        $query = "select 	det.id as detalle_id 
                        ,cla.id as clase_id 
                        ,dia.id as dia_id
                        ,dia.nombre as dia
                        ,dia.numero as dia_numero
                        ,hor.id as hora_id
                        ,hor.nombre as hora
                        ,hor.numero as hora_numero
                        ,hor.desde 
                        ,hor.hasta 
                        ,iam .id as ism_area_materia_id
                        ,mat.id as materia_id
                        ,mat.nombre as materia
                        ,cur.name as curso
                        ,par.name as paralelo
                        ,iam.responsable_planificacion
                from 	scholaris_horariov2_horario hho
                        inner join scholaris_horariov2_detalle det on det.id = hho.detalle_id 
                        inner join scholaris_horariov2_dia dia on dia.id = det.dia_id 
                        inner join scholaris_horariov2_hora hor on hor.id = det.hora_id 
                        inner join scholaris_clase cla on cla.id = hho.clase_id 
                        inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                        inner join ism_materia mat on mat.id = iam.materia_id 
                        inner join op_course_paralelo par on par.id = cla.paralelo_id
                        inner join op_course cur on cur.id = par.course_id
                where 	hho.clase_id in (select 	c.id as clase_id 
                            from	scholaris_clase c
                                    inner join op_faculty f on f.id = c.idprofesor 
                                    inner join res_users r on r.partner_id = f.partner_id 
                                    inner join ism_area_materia i on i.id = c.ism_area_materia_id 
                                    inner join ism_malla_area ma on ma.id = i.malla_area_id 
                                    inner join ism_periodo_malla ip on ip.id = ma.periodo_malla_id 
                            where 	r.login = '$user'
                                    and ip.scholaris_periodo_id = $periodId)
                order by dia.numero, hor.numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
       
}