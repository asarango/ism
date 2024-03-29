<?php
namespace backend\models\kids\micro;

use backend\models\KidsUnidadMicro;
use backend\models\KidsMicroObjetivos;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class Datos extends ActiveRecord{

    private $microId;
    private $micro;
    private $opCourseId;
    public $response;


    public function __construct($microId){
        $this->microId = $microId;
        $this->micro = KidsUnidadMicro::findOne($this->microId);
        $this->opCourseId = $this->micro->pca->op_course_id;
        
        $this->response();
    }

    private function response(){
        $this->response = array(
            'docentes' => $this->get_docentes(),
            'subnivel' => $this->micro->pca->opCourse->section0->code,
            'objetivos_disponibles' => $this->get_objetivos_disponibles(),
            'objetivos_seleccionados' => KidsMicroObjetivos::find()
                                        ->where(['micro_id' => $this->microId])
                                        ->all()
        );
    }
    
    private function get_docentes(){
        $con = Yii::$app->db;
        $query = "select 	concat(f.x_first_name,' ',f.last_name ) as docente
                    from 	scholaris_clase c
                            inner join op_faculty f on f.id = c.idprofesor
                            inner join op_course_paralelo p on p.id = c.paralelo_id
                    where  	p.course_id  = $this->opCourseId group by 1 order by 1;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_objetivos_disponibles(){
        $con = Yii::$app->db;
        $query = "select 	obj.id, obj.codigo, obj.detalle  
                    from 	cur_curriculo_objetivo_integrador obj
                    where 	curriculo_mec_nivel_id = 1
                            and obj.estado = true
                            and obj.id not in (select id from kids_micro_objetivos where micro_id = $this->microId)
                    order by orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}