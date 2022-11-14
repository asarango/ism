<?php

namespace backend\controllers;

use backend\models\IsmCriterioDescriptorArea;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * IsmCriterioDescriptorAreaController implements the CRUD actions for IsmCriterioDescriptorArea model.
 */
class MigrarCriteriosController extends Controller{
    
    public function actionIndex(){
        $cursos = $this->get_cursos_template();
        foreach($cursos as $curso){
            echo $curso['id'].' -- '.$curso['name'].'<br>';
            $this->process_course($curso['id']);
        }
    }


    private function get_cursos_template(){
        $con = Yii::$app->db;
        $query = "select 	tem.id, tem.name 
        from 	op_course_template tem
        where 	tem.id in (7,6,5,4,3)
        order by id desc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

   
    /**
     * METODO PARA TRATAR POR CURSO
     */
    private function process_course($templateId){
        $con = Yii::$app->db;
        $query = "select 	ia.id 
                            ,ia.nombre 
                    from 	ism_malla mal
                            inner join ism_periodo_malla pma on pma.malla_id = mal.id 
                            inner join ism_malla_area ima on ima.periodo_malla_id = pma.malla_id 
                            inner join ism_area ia on ia.id = ima.area_id 
                    where 	mal.op_course_template_id = $templateId
                    order by ia.nombre;";
        $areas = $con->createCommand($query)->queryAll();
        
        foreach($areas as $area){
            echo '----->'.$area['id'].' - '.$area['nombre'].'<br>';
            $this->get_criterios($templateId, $area['nombre'], $area['id']);
        }
    }


    /**
     * METODO QUE COLOCA LOS CRITERIOS
     */
    private function get_criterios($templateId, $areaName, $areaId){
        $con = Yii::$app->db;
        
        $query = "select 	icl.id as criterio_literal_id, icl.criterio_id, icl.nombre_espanol, icl.nombre_ingles, icl.nombre_frances, icl.es_activo  
        from 	ism_criterio_literal icl 
        where 	icl.nombre_espanol in (
                    select cri.detalle 
                from 	esquema_odoo.scholaris_criterio cri
                        inner join esquema_odoo.scholaris_area ar on ar.id = cri.area_id 
                        inner join esquema_odoo.scholaris_criterio_detalle des on des.idcriterio = cri.id 
                        inner join esquema_odoo.op_course oc on oc.id = des.curso_id 
                where 	ar.period_id = '2018-2019'
                        and ar.name = '$areaName'
                        and oc.x_template_id = $templateId
                group by  cri.detalle
        );";
        $criterios = $con->createCommand($query)->queryAll();

        foreach($criterios as $criterio){
            echo '*****'.$criterio['criterio_literal_id'].'-'.$criterio['criterio_id'].'-'.$criterio['nombre_espanol'];
            echo '<br>';

            echo '==================================================';
            echo '<br>';
            $this->get_descriptores($areaName, $templateId, $criterio['criterio_literal_id'],$criterio['criterio_id'], $areaId);
            echo '<br>';
            echo '==================================================';
            echo '<br>';
        }
    }


    private function get_descriptores($areaName, $templateId, $criterio_literal_id, $ismCriterioId, $areaId){
        $con = Yii::$app->db;
        
        $query = "select 	ild.id 
                            ,des.orden 
                    from 	esquema_odoo.scholaris_criterio_detalle des
                            inner join esquema_odoo.op_course oc on oc.id = des.curso_id 
                            inner join esquema_odoo.scholaris_criterio cri on cri.id = des.idcriterio 
                            inner join esquema_odoo.scholaris_area ar on ar.id = cri.area_id
                            inner join ism_literal_descriptores ild on ild.descripcion = des.descricpcion 
                    where 	cri.detalle = 'Conocimiento y Comprensión'
                            and oc.x_template_id = $templateId
                            and ar.period_id = '2018-2019'
                            and oc.period_id = 62
                            and oc.x_institute = 2
                            and ar.name = '$areaName'
                    order by des.orden;";

        $descriptores = $con->createCommand($query)->queryAll();         

        foreach($descriptores as $des){
            echo '        '.$des['orden'];
            echo '<br>';            

           $this->sentar_criteria($areaId, $templateId, $criterio_literal_id, $des['orden'], $des['id'], $ismCriterioId);
            
        }
    }


    public function sentar_criteria($idArea, $idCurso, $idLiteralCriterio,$idDescriptor,$idLiteralDescriptor, $ismCriterioId){
        $con = Yii::$app->db;
        $query = "insert into ism_criterio_descriptor_area (id_area, id_curso, id_criterio, id_literal_criterio, id_descriptor, id_literal_descriptor) 
                 values($idArea, $idCurso, $ismCriterioId, $idLiteralCriterio,$idDescriptor,$idLiteralDescriptor)";
        $con->createCommand($query)->execute();        
    }

}