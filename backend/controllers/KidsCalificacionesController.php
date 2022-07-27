<?php

namespace backend\controllers;

use backend\models\KidsCalificaTarea;
use backend\models\KidsEscalaCalificacion;
use backend\models\KidsDestrezaTarea;
use backend\models\ViewKidsTareasSearch;
use backend\models\ScholarisQuimestre;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


class KidsCalificacionesController extends Controller{
    
    
    public function actionIndex1(){

        $usuario = Yii::$app->user->identity->usuario;

        $searchModel = new ViewKidsTareasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $usuario);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    } 

    public function actionCalificar(){
        $tareaId = $_GET['tarea_id'];
        $modelTarea = KidsDestrezaTarea::findOne($tareaId);
        $claseId = $modelTarea->planDestreza->horaClase->clase_id;
        $escala = KidsEscalaCalificacion::find()->where(['escala' => 'I'])->one();
        
        $quimestreCodigo = $modelTarea->planDestreza->horaClase->planSemanal->semana->bloque->quimestre;
        $quimestre = ScholarisQuimestre::find()
        ->where([
            'codigo' => $quimestreCodigo
        ])->one();

        $destrezaId = $modelTarea->planDestreza->microDestreza->destreza_id;

        $this->genera_espacio_calificacion($tareaId, $escala->id, $claseId);
        $this->inserta_reporte_quimestral($quimestre->id,$destrezaId,$claseId,$escala->id);
        

        return $this->render('calificar', [
            'modelTarea' => $modelTarea
        ]);
        
    }

    private function get_calificaciones($tareaId){
        $con = Yii::$app->db;
        $query = "select 	c.id 
                        ,concat(s.last_name, ' ', s.first_name, ' ', s.middle_name ) as estudiante
                        ,e.escala 
                        ,e.icono_font_awesome 
                        ,c.es_activo 
                from 	kids_califica_tarea c
                        inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id
                        inner join op_student s on s.id = g.estudiante_id 
                        inner join kids_escala_calificacion e on e.id = c.escala_id 
                where 	c.tarea_id = $tareaId
                order by s.last_name, s.first_name, s.middle_name;	";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function genera_espacio_calificacion($tareaId, $escalaId, $claseId){
        $hoy = date('Y-m-d H:i:s');
        $usuario = Yii::$app->user->identity->usuario;

        $con = Yii::$app->db;
        $query = "insert into kids_califica_tarea(tarea_id, grupo_id, escala_id, es_activo, created_at, created)
        select 	$tareaId, g.id, $escalaId, true, '$hoy', '$usuario'
        from 	scholaris_grupo_alumno_clase g 
        where 	g.clase_id = $claseId
                and g.id not in (select grupo_id 
                                    from kids_califica_tarea
                                    where tarea_id = $tareaId);";
        // echo $query;
        // die();


        $con->createCommand($query)->execute();
    }

    /*
    MÉTODO QUE INSERTA REPORTE QUIMESTRAL
    */
    private function inserta_reporte_quimestral($quimestreId, $destrezaId,$claseId,$escalaId){
        $con = Yii::$app->db;
        $query = "insert into kids_calificaciones_quimestre(quimestre_id, grupo_id, escala_id, destreza_id)
        select 	$quimestreId, g.id, $escalaId, $destrezaId
        from	scholaris_grupo_alumno_clase g
        where 	g.clase_id = $claseId
                and g.estado = true
                and g.id not in (select grupo_id  
                                    from kids_calificaciones_quimestre 
                                    where quimestre_id = $quimestreId 
                                        and grupo_id = g.id  
                                        and destreza_id = $destrezaId);";
        // echo $query;
        // die();


        $con->createCommand($query)->execute();

    }


    /**
     * METODO QUE PRESENTA MEDIANTE AJAX LAS CALIFICACIONES DE LOS ESTUDIANTES
     */
    public function actionAjaxListaCalificacion(){
        $tareaId = $_GET['tarea_id'];
        $escalas = KidsEscalaCalificacion::find()->orderBy('equivalencia')->all();
        $calificaciones = $this->get_calificaciones($tareaId);

        $modelTarea = KidsDestrezaTarea::findOne($tareaId);
        
        $quimestreCodigo = $modelTarea->planDestreza->horaClase->planSemanal->semana->bloque->quimestre;
        $quimestre = ScholarisQuimestre::find()
        ->where([
            'codigo' => $quimestreCodigo
        ])->one();

        $destrezaId = $modelTarea->planDestreza->microDestreza->destreza_id;

        
        return $this->renderPartial('_ajax-lista-calificacion',[
            'escalas' => $escalas,
            'calificaciones' => $calificaciones,
            'quimestreId' => $quimestre->id,
            'destrezaId' => $destrezaId
        ]);
    }


    /**
     * METODO QUE ACTUALIZA LA CALIFCACION DEL ESTUDIANTE
     */
    public function actionUpdateCalificacion(){
        $escalaId       = $_POST['id'];
        $calificacionId = $_POST['calificacion_id'];
        $quimestreId = $_POST['quimestre_id'];
        $destrezaId = $_POST['destreza_id'];
        $hoy            = date('Y-m-d H:i:s');
        $usuario        = Yii::$app->user->identity->usuario;

        $model = KidsCalificaTarea::findOne($calificacionId);
        $model->escala_id = $escalaId;
        $model->updated = $usuario;
        $model->updated_at = $hoy;
        $model->save();

        new \backend\models\kids\CalificacionQuimestral($model->grupo_id,$destrezaId,$quimestreId,$escalaId);



    }



}