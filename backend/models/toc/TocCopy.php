<?php
namespace backend\models\toc;

use backend\models\TocPlanUnidad;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use datetime;

class TocCopy extends \yii\db\ActiveRecord
{
    private $claseDesdeId;
    private $claseHastaId;
    private $user;
    private $today;

    public function __construct($claseDesde, $claseHasta)
    {     
        $this->claseDesdeId = $claseDesde;
        $this->claseHastaId = $claseHasta;
        $this->user = Yii::$app->user->identity->usuario;
        $this->today = date("Y-m-d H:i:s");

        echo $this->claseDesdeId;
        echo $this->claseHastaId;

        $this->delete_planes();
        $this->copy();

    }

    private function delete_planes(){
        $con = Yii::$app->db;
        $queryHab = "delete from toc_plan_unidad_habilidad 
                     where toc_plan_unidad_id in  
                        (select id from toc_plan_unidad where clase_id = $this->claseHastaId);";
        $con->createCommand($queryHab)->execute();

        $queryApr = "delete from toc_plan_unidad_aprendizaje
                        where toc_plan_unidad_id in  
                        (select id from toc_plan_unidad where clase_id = $this->claseHastaId);";
        $con->createCommand($queryApr)->execute();

        $queryDet = "delete from toc_plan_unidad_detalle where toc_plan_unidad_id in  
                        (select id from toc_plan_unidad where clase_id = $this->claseHastaId);";
        $con->createCommand($queryDet)->execute();

        $queryUnidades = "delete from toc_plan_unidad where clase_id = $this->claseHastaId;";
        $con->createCommand($queryUnidades)->execute();

        $queryPv = "delete from toc_plan_vertical where clase_id = $this->claseHastaId;";
        $con->createCommand($queryPv)->execute();        

    }

    private function copy(){
        $this->copy_pv();
        $this->copy_unidades();
    }

    private function copy_pv(){
        $con    = Yii::$app->db;
        $query  = "insert into toc_plan_vertical (clase_id, opcion_descripcion, contenido, tipo, created_at, created, updated_at, updated)
                    select $this->claseHastaId, opcion_descripcion, 
                        contenido, tipo, '$this->today', '$this->user', '$this->today', '$this->user'  
                    from toc_plan_vertical where clase_id = $this->claseDesdeId;";
        $con->createCommand($query)->execute();
    }

    private function copy_unidades(){
       $unidades = TocPlanUnidad::find()
        ->where(['clase_id' => $this->claseDesdeId])
        ->orderBy('id')
        ->all();

        foreach($unidades as $unidad){
            $this->insert_unidad($unidad);
        }
    }

    private function insert_unidad($unidad){
        // echo '<pre>';
        // print_r($unidad->id);
        $newUnidad = new TocPlanUnidad();
        $newUnidad->bloque_id   = $unidad->bloque_id;
        $newUnidad->clase_id    = $this->claseHastaId;
        $newUnidad->titulo      = $unidad->titulo;
        $newUnidad->objetivos   = $unidad->objetivos;
        $newUnidad->conceptos_clave = $unidad->conceptos_clave;
        $newUnidad->contenido   = $unidad->contenido;
        $newUnidad->evaluacion_pd = $unidad->evaluacion_pd;
        $newUnidad->created = $this->user;
        $newUnidad->created_at = $this->today;
        $newUnidad->updated = $this->user;
        $newUnidad->updated_at = $this->today;
        $newUnidad->save();
        $id =  $newUnidad->id;
        $this->insert_habilidades($id, $unidad->id);
        $this->insert_detalle($id, $unidad->id);
        $this->insert_aprendizajes($id, $unidad->id);
    }


    private function insert_habilidades($nuevaUnidadId, $desdeUnidadId){

        $con = Yii::$app->db;
        $query = "insert into toc_plan_unidad_habilidad (toc_plan_unidad_id, toc_opciones_id, is_active, created, created_at, updated, updated_at)
                    select 	$nuevaUnidadId, toc_opciones_id, is_active, '$this->user', '$this->today', '$this->user', '$this->today' 
                    from 	toc_plan_unidad_habilidad
                    where 	toc_plan_unidad_id = $desdeUnidadId 
                    order by id asc;";
        $con->createCommand($query)->execute();
        
    }


    private function insert_detalle($nuevaUnidadId, $desdeUnidadId){
        $con = Yii::$app->db;
        $query = "insert into toc_plan_unidad_detalle(toc_plan_unidad_id, evaluacion_pd, descripcion_unidad
                        , preguntas_conocimiento, conocimientos_esenciales, actividades_principales, enfoques_aprendizaje
                        , funciono_bien, no_funciono_bien, observaciones
                        , created, created_at, updated, updated_at, diferenciacion)
                select $nuevaUnidadId, evaluacion_pd, descripcion_unidad, preguntas_conocimiento
                , conocimientos_esenciales, actividades_principales, enfoques_aprendizaje
                , funciono_bien, no_funciono_bien, observaciones
                , '$this->user', '$this->today', '$this->user', '$this->today', diferenciacion  
                from toc_plan_unidad_detalle 
                where toc_plan_unidad_id = $desdeUnidadId;";
        $con->createCommand($query)->execute();
    }


    private function insert_aprendizajes($nuevaUnidadId, $desdeUnidadId){
        $con = Yii::$app->db;
        $query = "insert into toc_plan_unidad_aprendizaje (toc_plan_unidad_id, toc_opcion_id)
                select 	$nuevaUnidadId, toc_opcion_id  
                from 	toc_plan_unidad_aprendizaje
                where 	toc_plan_unidad_id = $desdeUnidadId;";
        $con->createCommand($query)->execute();
    }

      
}


?>