<?php

namespace backend\controllers;

use backend\models\kids\ScriptsKids;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


class KidsMenuController extends Controller{

    public function actionIndex1(){

        $userLog    = Yii::$app->user->identity->usuario;
        $periodId   = Yii::$app->user->identity->periodo_id;

        $this->insert_automatic_pca($periodId, $userLog);

        $plans = $this->get_plans($periodId);

        return $this->render('index', [
            'plans' => $plans
        ]);
    }


    private function insert_automatic_pca($periodId, $userLog){
        $con = Yii::$app->db;
        $query = "insert into kids_pca (op_course_id, estado, created_at, created, updated_at, updated)
        select 	c.id, 'INICIANDO', current_timestamp, '$userLog', current_timestamp, '$userLog' 
        from 	op_course c
                inner join op_section s on s.id = c.section
                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
        where 	sop.scholaris_id = $periodId
                and s.code = 'PRES'
                and c.id not in (select op_course_id from kids_pca);";
        $con->createCommand($query)->execute();
    }

    private function get_plans($periodId){
        $con = Yii::$app->db;
        $query = "select 	pca.id
                        ,pca.numero_semanas_trabajo 
                        ,pca.carga_horaria_semanal 
                        ,pca.estado 
                        ,c.name as curso		
                from 	kids_pca pca
                        inner join op_course c on c.id = pca.op_course_id 
                        inner join op_section s on s.id = c.section
                        inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                where 	sop.scholaris_id = $periodId;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}