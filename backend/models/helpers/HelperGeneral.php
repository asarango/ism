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

    //realiza consultas a la base de datos, y devuelve el dato
    private function consultaBD($query)
    {
        $con = Yii::$app->db;
        $resp = $con->createCommand($query)->queryAll();
        return $resp;
    }
    

    //COnsulta la edad en años, meses y dias
    function calcular_edad($fecha){
        $fecha_nac = new DateTime(date('Y/m/d',strtotime($fecha))); // Creo un objeto DateTime de la fecha ingresada
        $fecha_hoy =  new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
        $edad = date_diff($fecha_hoy,$fecha_nac); // La funcion ayuda a calcular la diferencia, esto seria un objeto
        
        return $edad;        
    }
    
    
    public function get_paralelos_por_template_id($opCourseTemplateId)
    {        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $query = "select 	p.name 
                    from	op_course c
                                    inner join op_course_paralelo p on p.course_id = c.id
                                    inner join op_section s on s.id = c.section
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                    where 	c.x_template_id = $opCourseTemplateId
                                    and sop.scholaris_id = $periodoId
                    order by p.name;";
        $res = $this->consultaBD($query);
        return $res;
    } 

    public function getCargaHorariaSemanal($idPlanDesCab)
    {
        //proceso
        //1.- obtenemos el id del area materia
        $query = "select ism_area_materia_id as dato  from planificacion_desagregacion_cabecera where id = $idPlanDesCab;";
        $resp = $this->consultaBD($query);
        $dato = $resp[0]['dato'];
       
        //2.- obtenemos el id de uno de los registros de scholaris_class, asociado al id area materia
        $query = "select id as dato from scholaris_clase where ism_area_materia_id = $dato  limit 1;";
        $resp = $this->consultaBD($query);
        $dato = $resp[0]['dato'];

        //3.- Con el id de scholaris clase, contamos el numero de horas por semana 
        $query = "select count(*) from scholaris_horariov2_horario where clase_id = $dato;";
        $resp = $this->consultaBD($query);

        return $resp;
    }
    public function getCargaSemanasTrabajo($idPlanBloqUnidad)
    {
        //proceso
        //1.- obtenemos el id del area materia
        $query = "select pc.ism_area_materia_id as areaMateria,pb.curriculo_bloque_id as curriculoMec  
                from planificacion_bloques_unidad pb
                inner join planificacion_desagregacion_cabecera pc on pc.id = pb.plan_cabecera_id 
                where pc.id = $idPlanBloqUnidad order by pb.curriculo_bloque_id ;";
        $resp = $this->consultaBD($query);      

        $sumaHorasSemanas =0;
        $arraySemas = array();
        

        foreach($resp as $r)
        {
            $dato1 = $r['areamateria'];
            $dato2 = $r['curriculomec'];
            //2.- extraemos el code , de curriculo mec bloqe
            $query = "select code as dato  from curriculo_mec_bloque where id = $dato2;";
            $resp = $this->consultaBD($query);
            $code =$resp[0]['dato'];

            //3.- se extrae el tipo usu de scholaris class
            $query = "select tipo_usu_bloque as dato from scholaris_clase where ism_area_materia_id = $dato1 limit 1;";
            $resp = $this->consultaBD($query);
            $tipo_usu_bloque =$resp[0]['dato'];

            //extraemos id de bloque actividad
            $query = "select id as dato from scholaris_bloque_actividad where orden = '$code' and tipo_uso = '$tipo_usu_bloque' order by id;";
            $resp = $this->consultaBD($query);
            $id =$resp[0]['dato'];            

            //
            $query = "select count(*)  from scholaris_bloque_semanas where bloque_id = $id;";
            $resp = $this->consultaBD($query);

         
            $arraySemas[] = $resp[0]['count'];

            $sumaHorasSemanas = $sumaHorasSemanas + $resp[0]['count'];

        } 
        

        return $arraySemas;
    }
    
}


?>