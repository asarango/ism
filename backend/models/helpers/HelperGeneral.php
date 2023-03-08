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
    public function get_cursos_docente($user,$periodoId)
    {              
        $query = "select 	t.id 
                                    ,t.name
                                    ,sec.code
                    from	scholaris_clase cla
                                    inner join op_faculty fac on fac.id = cla.idprofesor 
                                    inner join res_users use on use.partner_id = fac.partner_id 
                                    inner join op_course_paralelo par on par.id = cla.paralelo_id 
                                    inner join op_course cur on cur.id = par.course_id
                                    inner join op_course_template t on t.id = cur.x_template_id 
                                    inner join op_section sec on  sec.id = cur.section
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id 
                    where	use.login = '$user'
                                    and sop.scholaris_id = $periodoId
                    group by t.id, t.name, sec.code;";
                    // echo '<pre>';
                    // print_r($query);
                    // die();
        $resp = $this->consultaBD($query);                   
        
        return $resp;
    }
    //metodo para desplegar el reporte de planificacion horizontal en toda slas ecciones
    public function get_cursos_docente_reporte($user,$periodoId,$idOpCourseTemp)
    {              
        $query = "select 	t.id 
                                    ,t.name
                                    ,sec.code
                    from	scholaris_clase cla
                                    inner join op_faculty fac on fac.id = cla.idprofesor 
                                    inner join res_users use on use.partner_id = fac.partner_id 
                                    inner join op_course_paralelo par on par.id = cla.paralelo_id 
                                    inner join op_course cur on cur.id = par.course_id
                                    inner join op_course_template t on t.id = cur.x_template_id 
                                    inner join op_section sec on  sec.id = cur.section
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = sec.period_id 
                    where	use.login = '$user'
                                    and sop.scholaris_id = $periodoId   
                                    and t.id = $idOpCourseTemp                             
                    group by t.id, t.name, sec.code;";

                
        $resp = $this->consultaBD($query); 
      
        
        return $resp;
    }
    public function query_asignaturas_x_nivel($nivelId)
    {       
        $query = "select 	cab.id
		,m.nombre as name
		,count(cri.id) as total_criterios_evaluacion
                from 	planificacion_desagregacion_cabecera cab 
                                inner join ism_area_materia iam on iam.id = cab.ism_area_materia_id 
                                inner join ism_malla_area ia on ia.id = iam.malla_area_id 
                                inner join ism_periodo_malla ipm on ipm.id = ia.periodo_malla_id 
                                inner join ism_malla im on im.id = ipm.malla_id
                                inner join ism_materia m on m.id = iam.materia_id 
                                left join planificacion_desagregacion_criterios_evaluacion cri on cri.criterio_evaluacion_id = cab.id 
                where 	im.op_course_template_id = $nivelId
                group by cab.id ,m.nombre;";      
        // echo '<pre>';
        // print_r($query);
        // die();         

        $res =  $this->consultaBD($query);         
        
        return $res;
    }


    public function query_docentes_x_curso($opCourseId){
        
        $query = "select 	concat(f.x_first_name, ' ', f.last_name) as docente 
        from	scholaris_clase cla
                inner join op_course_paralelo par on par.id = cla.paralelo_id
                inner join op_faculty f on f.id = cla.idprofesor 
        where 	par.course_id = $opCourseId
        group by f.x_first_name, f.last_name 
        order by f.x_first_name, f.last_name ;";               

        $res =  $this->consultaBD($query);         
        
        return $res;
    }   
    
    
    public function get_dia_fecha($fecha){
        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        $dia = $dias[date('N', strtotime($fecha))];
        
        return $dia;        
    }

    public function obtener_edad_segun_fecha($fecha_nacimiento)
    {
        $nacimiento = new DateTime($fecha_nacimiento);
        $ahora = new DateTime(date("Y-m-d"));
        $diferencia = $ahora->diff($nacimiento);
        return $diferencia->format("%y");
    }

    public function obtener_docentes_por_curso($areaMateriaId,$periodoId,$templateId)
    {
        //muestra lso profesores que corresponden, enviando el curso/periodo/op course templade
        //utilizado mas en planificaciones
        $con = Yii::$app->db;
        $query = "select 	concat(f.x_first_name,' ', f.last_name) as docente
                            from 	scholaris_clase c 
                                    --inner join scholaris_periodo p on p.codigo = c.periodo_scholaris
                                    inner join ism_area_materia iam on iam.id = c.ism_area_materia_id 
                                    inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                                    inner join ism_periodo_malla ipm on ipm.id  = ima.periodo_malla_id 
                                    inner join ism_malla im on im.id = ipm.malla_id                                     
                                    inner join op_faculty f on f.id = c.idprofesor 
                            where 	c.ism_area_materia_id  = $areaMateriaId
                                    and ipm.scholaris_periodo_id  = $periodoId
                                    and im.op_course_template_id  = $templateId
                            group by f.x_first_name, f.last_name;"; 
                            

                            $resp = $con->createCommand($query)->queryAll();  
        return $resp;
    }

    //muestra, curso/materias/estudiantes asociados a un profesor en un X periodo
    public function obtener_curso_materias_estudiante($usuario,$id_periodo,$curso/*op_course_paralelo*/ )
    {
        $con = Yii::$app->db;
        $query = "select  im.id as idMateria, im.nombre as materia, ocp.id as idcurso,ocp.name as curso, os.id as idEstudiante,
                    concat(os.last_name,' ',os.middle_name,' ',os.first_name)  as estudiante ,
                    nxc.id as idNeeXClase, nxc.clase_id as neeClaseId,nxc.nee_id as idnee,nxc.grado_nee,nxc.fecha_inicia ,
                    nxc.fecha_finaliza ,nxc.diagnostico_inicia 
                    from nee_x_clase nxc 
                    inner join scholaris_clase sc on sc.id = nxc.clase_id 
                    inner join op_course_paralelo ocp on ocp.id = sc.paralelo_id 
                    inner join op_course oc on oc.id = ocp.course_id 
                    inner join ism_area_materia iam on iam.id = sc.ism_area_materia_id 
                    inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                    inner join ism_periodo_malla ipm on ipm.id  = ima.periodo_malla_id 
                    inner join nee n on n.id = nxc.nee_id 
                    inner join op_student os on os.id = n.student_id  
                    inner join op_faculty of2 on of2.id = sc.idprofesor 
                    inner join res_users ru on ru.partner_id = of2.partner_id 
                    inner join ism_materia im on im.id = iam.materia_id  
                    where oc.x_template_id ='$curso' and ipm.scholaris_periodo_id = $id_periodo
                    and ru.login ='$usuario'
                    order by im.nombre,ocp.name,estudiante;"; 

        
                            

        $resp = $con->createCommand($query)->queryAll();  
        // echo '<pre>';
        // print_r($query);
        // die();
        return $resp;
    }
    //Muesta los estudiantes con Nee
    //muestra, curso/materias/estudiantes asociados a un profesor en un X periodo
    public function obtener_curso_materias_estudiante_nee($usuario,$id_periodo,$curso/*op_course_paralelo*/ )
    {
        $con = Yii::$app->db;
        $query = "select  im.id as idMateria, im.nombre as materia, ocp.id as idcurso,ocp.name as curso, os.id as idEstudiante,
                    concat(os.last_name,' ',os.middle_name,' ',os.first_name)  as estudiante ,
                    nxc.id as idNeeXClase, nxc.clase_id as neeClaseId,nxc.nee_id as idnee,nxc.grado_nee,nxc.fecha_inicia ,nxc.diagnostico_inicia 
                    from nee_x_clase nxc 
                    inner join scholaris_clase sc on sc.id = nxc.clase_id 
                    inner join op_course_paralelo ocp on ocp.id = sc.paralelo_id 
                    inner join op_course oc on oc.id = ocp.course_id 
                    inner join ism_area_materia iam on iam.id = sc.ism_area_materia_id 
                    inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                    inner join ism_periodo_malla ipm on ipm.id  = ima.periodo_malla_id 
                    inner join nee n on n.id = nxc.nee_id 
                    inner join op_student os on os.id = n.student_id  
                    inner join op_faculty of2 on of2.id = sc.idprofesor 
                    inner join res_users ru on ru.partner_id = of2.partner_id 
                    inner join ism_materia im on im.id = iam.materia_id  
                    inner join adaptacion_curricular_x_bloque acxb on acxb.id_nee_x_clase  = nxc.id
                    where oc.x_template_id ='$curso' and ipm.scholaris_periodo_id = $id_periodo
                    and ru.login ='$usuario'
                    order by im.nombre,ocp.name,estudiante;"; 
                            

        $resp = $con->createCommand($query)->queryAll();      
        return $resp;
    }
    
    
}


?>