<?php

namespace backend\controllers;

use backend\models\ScholarisFaltas;
use backend\models\ScholarisParametrosOpciones;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\helpers\Scripts;
use backend\models\messages\Messages;
use backend\models\OpStudent;
use backend\models\ScholarisAsistenciaComportamientoSearch;

/**
 * ScholarisAsistenciaProfesorController implements the CRUD actions for ScholarisAsistenciaProfesor model.
 */
class TareasProgramadasController extends Controller {

    public function actionEmailNovedades(){

        $hoy = date('Y-m-d');

        $students = $this->get_students_novedad($hoy);   

        if($students > 0){
            foreach ($students as $student) {
                $this->send_email_novedades($hoy, $student['id']);
            }
        }else{
            echo '****** NO HAY NUEVAS NOVEDADES ******';
        }

    }


    private function send_email_novedades($hoy, $studentId){

        $padres = $this->consulta_padres($studentId);
        $novedades = $this->consulta_novedades($hoy, $studentId);
        $student = OpStudent::findOne($studentId);


        foreach($padres as $padre){                        
            $htmlBody = '<b>Estimado padre de familia</b>';    
            $htmlBody .= $this->generate_list($novedades, $hoy, $student);
            $htmlBody .= '<br><br>';
            $htmlBody .= 'Por favor no responda a este correo, el reporte fue generado automáticamente por el sistema de gestión educativa EDUX. ';

            $email = new Messages();
            $email->send_email($padre['email'], 'info@ism.edu.ec', 'Información de novedades EDUX', '', $htmlBody);

        }

        
    }


    private function generate_list($novedades, $hoy, $student){
        
        $html = '';
        $html .= '<br><br>';

        $html .= '<b>Reporte de novedades del día de: </b>'.$student->first_name.' '.$student->last_name;

        $html .= '<br><br>';

        $html .= '<table border="1" width="60%">';
        $html .= '<tr>';
        $html .= '<th>ASIGNATURA</th>';
        $html .= '<th>DOCENTE</th>';
        $html .= '<th>HORA</th>';
        $html .= '<th>CÓDIGO</th>';
        $html .= '<th>NOVEDAD</th>';
        $html .= '<th>OBSERVACIÓN</th>';
        $html .= '</tr>';

        foreach($novedades as $novedad){
            $html .= '<tr>';
            $html .= '<td>'.$novedad['materia'].'</td>';
            $html .= '<td>'.$novedad['docente'].'</td>';
            $html .= '<td>'.$novedad['sigla'].'</td>';
            $html .= '<td>'.$novedad['codigo'].'</td>';
            $html .= '<td>'.$novedad['novedad'].'</td>';
            $html .= '<td>'.$novedad['observacion'].'</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        



        return $html;
    }


    private function get_students_novedad($hoy){
        $con = Yii::$app->db;
        $query = "select 	est.id 
                            ,concat(est.last_name,' ',est.first_name, ' ', est.middle_name) as estudiante
                    from	scholaris_asistencia_alumnos_novedades nov
                            inner join scholaris_asistencia_profesor asi on asi.id = nov.asistencia_profesor_id 
                            inner join scholaris_asistencia_comportamiento_detalle det on det.id = nov.comportamiento_detalle_id 
                            inner join scholaris_grupo_alumno_clase gru on gru.id = nov.grupo_id
                            inner join op_student est on est.id = gru.estudiante_id
                            inner join op_parent_op_student_rel rel on rel.op_student_id = est.id
                            inner join op_parent opa on opa.id = rel.op_parent_id 
                            inner join res_partner par on par.id = opa.name
                    where 	asi.fecha = '$hoy'
                    group by est.id, 2
                    order by 1;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }






    private function consulta_novedades($hoy, $studentId){        
        $con = Yii::$app->db;
        $query = "select 	mat.nombre as materia
                            ,concat(fac.x_first_name,' ', fac.last_name) as docente 
                            ,hor.sigla 
                            ,det.codigo ,det.nombre as novedad ,nov.observacion 
                    from 	scholaris_asistencia_alumnos_novedades nov 
                            inner join scholaris_asistencia_profesor asi on asi.id = nov.asistencia_profesor_id 
                            inner join scholaris_asistencia_comportamiento_detalle det on det.id = nov.comportamiento_detalle_id 
                            inner join scholaris_grupo_alumno_clase gru on gru.id = nov.grupo_id 
                            inner join op_student est on est.id = gru.estudiante_id
                            inner join scholaris_clase cla on cla.id = gru.clase_id 
                            inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                            inner join ism_materia mat on mat.id = iam.materia_id 
                            inner join op_faculty fac on fac.id = cla.idprofesor 
                            inner join scholaris_horariov2_hora hor on hor.id = asi.hora_id 
                    where 	asi.fecha = '$hoy' 
                            and est.id = $studentId 
                    order by 1;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    private function consulta_padres($studentId){        
        $con = Yii::$app->db;
        $query = "select 	par.name  as padre
                            ,par.email as email
                    from 	op_student est
                            inner join op_parent_op_student_rel rel on rel.op_student_id = est.id
                            inner join op_parent opa on opa.id = rel.op_parent_id 
                            inner join res_partner par on par.id = opa.name
                    where 	est.id = $studentId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}