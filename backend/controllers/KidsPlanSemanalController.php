<?php

namespace backend\controllers;

use backend\models\KidsPca;
use Yii;
use backend\models\KidsPlanSemanal;
use backend\models\KidsPlanSemanalSearch;
use backend\models\KidsUnidadMicro;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KidsPlanSemanalController implements the CRUD actions for KidsPlanSemanal model.
 */
class KidsPlanSemanalController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all KidsPlanSemanal models.
     * @return mixed
     */
    public function actionIndex()
    {

        $pcaId = $_GET['pca_id'];
        $pca = KidsPca::findOne($pcaId);
        $experiencias = KidsUnidadMicro::find()
        ->where(['pca_id' => $pcaId])
        ->orderBy('orden')
        ->all();
        
        return $this->render('index', [
            'pca' => $pca,
            'experiencias' => $experiencias
        ]);
    }

    /**
     * Muestra las semanas con las experiencia elejidas
     */
    public function actionAjaxSemanas(){ 

        $experienciaId  = $_GET['experiencia_id'];        
        $opCourseId     = $_GET['op_course_id'];        

        $planSemanal = $this->get_plan_semanal_cab($opCourseId);

        return $this->renderPartial('_ajax-semanas',[
            'planSemanal' => $planSemanal,
            'experienciaId' => $experienciaId
        ]);

    }

    private function get_plan_semanal_cab($courseId){
        $con = Yii::$app->db;
        $query = "select 	s.id 
                            ,s.nombre_semana 
                            ,ex.experiencia
                            ,ks.id as plan_semanal_id, ks.kids_unidad_micro_id, ks.semana_id, ks.created_at
                            ,ks.created, ks.estado, ks.sent_at, ks.sent_by, ks.approved_at, ks.approved_by  
                    from	scholaris_clase c
                            inner join op_course_paralelo p on p.id = c.paralelo_id
                            inner join scholaris_bloque_actividad b on b.tipo_uso = c.tipo_usu_bloque 
                            inner join scholaris_bloque_semanas s on s.bloque_id = b.id 
                            left join kids_plan_semanal ks on ks.semana_id = s.id 
                            left join kids_unidad_micro ex on ex.id = ks.kids_unidad_micro_id 
                    where 	p.course_id = $courseId
                    group by s.id, s.nombre_semana, ks.id, ex.experiencia
                    order by s.semana_numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



    public function actionAjaxInsertExperiencia(){
        
        $kidsUnidadMicroId = $_POST['experiencia_id'];
        $semanaId = $_POST['semana_id'];
        $today = date('Y-m-d H:i:s');
        $userLog = Yii::$app->user->identity->usuario;

        $model = new KidsPlanSemanal();
        $model->kids_unidad_micro_id = $kidsUnidadMicroId;
        $model->semana_id = $semanaId;
        $model->created_at = $today;
        $model->created = $userLog;
        $model->estado = 'INICIANDO';

        if($model->save()){
            return true;
        }else{
            return false;
        }

    }
}
