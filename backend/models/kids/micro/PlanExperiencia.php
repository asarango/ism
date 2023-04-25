<?php
namespace backend\models\kids\micro;

use backend\models\KidsUnidadMicro;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class PlanExperiencia extends ActiveRecord{
    private $microId;
    private $micro;
    private $opCourseTemplateId;
    public $response;
    private $userLog;
    private $periodId;


    public function __construct($microId){

        $this->userLog = \Yii::$app->user->identity->usuario;
        $this->periodId = \Yii::$app->user->identity->periodo_id;
        
        $this->microId = $microId;
        $this->micro = KidsUnidadMicro::findOne($this->microId);
        $this->opCourseTemplateId = $this->micro->pca->op_course_id;    
        
        
        $this->response();
    }

    private function response(){
        $this->response = array(
            'disponibles' => $this->get_disponibles(),     
            'seleccionadas' => $this->get_seleccionadas()       
        );
    }
    
    private function get_disponibles(){
        $con = Yii::$app->db;
        // $query = "select 	d.id
        //                                 ,a.codigo as ambito_codigo 
        //                                 ,a.nombre as ambito
        //                                 ,d.codigo as destreza_codigo
        //                                 ,d.nombre as destreza
        //                                 ,d.imprescindible 		
        //                 from	cur_curriculo_destreza d
        //                                 inner join cur_curriculo_ambito a on a.id = d.ambito_id 
        //                 where 	d.ambito_id in (select 	iam.ambito_id 
        //                                                                         from	scholaris_clase cla
        //                                                                                         inner join op_faculty fac on fac.id = cla.idprofesor 
        //                                                                                         inner join res_users use on use.partner_id = fac.partner_id
        //                                                                                         inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
        //                                                                                         inner join ism_malla_area ima on ima.id = iam.malla_area_id 
        //                                                                                         inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
        //                                                                         where 	use.login = '$this->userLog'
        //                                                                                         and ipm.scholaris_periodo_id = $this->periodId
        //                                                                         group by iam.ambito_id)
        //                                 and d.id not in (select destreza_id from kids_micro_destreza where micro_id = $this->microId) 
        //                 order by a.nombre, d.id;";
        
        $query = "select 	d.id
                                        ,a.codigo as ambito_codigo 
                                        ,a.nombre as ambito
                                        ,d.codigo as destreza_codigo
                                        ,d.nombre as destreza
                                        ,d.imprescindible 		
                        from	cur_curriculo_destreza d
                                        inner join cur_curriculo_ambito a on a.id = d.ambito_id 
                        where 	d.id not in (select destreza_id from kids_micro_destreza where micro_id = $this->microId) 
                        order by a.nombre, d.id;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_seleccionadas(){
        $con = Yii::$app->db;
        $query = "select 	kd.id 
                            ,e.nombre as eje
                            ,a.nombre as ambito
                            ,d.nombre as detreza
                    from 	kids_micro_destreza kd
                            inner join cur_curriculo_destreza d on d.id = kd.destreza_id 
                            inner join cur_curriculo_ambito a on a.id = d.ambito_id 
                            inner join cur_curriculo_eje e on e.id = a.eje_id
                    where 	kd.micro_id = $this->microId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


}