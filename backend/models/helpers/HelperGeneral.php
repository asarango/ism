<?php
namespace backend\models\helpers;

use DateTime;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class HelperGeneral extends ActiveRecord{
    

    //COnsulta la edad en años, meses y dias
    function calcular_edad($fecha){
        $fecha_nac = new DateTime(date('Y/m/d',strtotime($fecha))); // Creo un objeto DateTime de la fecha ingresada
        $fecha_hoy =  new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
        $edad = date_diff($fecha_hoy,$fecha_nac); // La funcion ayuda a calcular la diferencia, esto seria un objeto
        
        return $edad;        
    }
    
    
    public function get_paralelos_por_template_id($opCourseTemplateId){
        $con = Yii::$app->db;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $query = "select 	p.name 
                    from	op_course c
                                    inner join op_course_paralelo p on p.course_id = c.id
                                    inner join op_section s on s.id = c.section
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                    where 	c.x_template_id = $opCourseTemplateId
                                    and sop.scholaris_id = $periodoId
                    order by p.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    } 
    
}


?>