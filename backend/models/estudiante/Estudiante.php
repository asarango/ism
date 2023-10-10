<?php
namespace backend\models\estudiante;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class Estudiante extends ActiveRecord{


    /**
     * TOMA LOS PROMEDIOS DE LOS TRIMESTRES
     */
    public function promedios($studentId, $periodoCodigo){
        $arrayPromedio = array();
        $promedios = $this->cosulta_promedios_trimestre($studentId, $periodoCodigo);

        $general = 0;
        foreach ($promedios as $promedio) {
            $general = $general + $promedio['nota'];
            array_push($arrayPromedio, $promedio);
        }

        $prom = [
            'general' => $general
        ];
        array_push($arrayPromedio, $prom);

        return $arrayPromedio;

    }


    private function cosulta_promedios_trimestre($studentId, $periodoCodigo){
        $con = Yii::$app->db;
        $query = "select 	blo.id 
                            ,blo.name as bloque
                            ,(select 	nota 
                                from 	lib_bloques_grupo_promedios 
                                where 	student_id = $studentId
                                        and bloque_id = blo.id)
                    from 	scholaris_bloque_actividad blo
                    where 	blo.scholaris_periodo_codigo = '$periodoCodigo'
                    order by orden;";
        return $con->createCommand($query)->queryAll();
    }



    public function chart_general_clases($inscriptionId){
        $arrayValores = array();
        $arrayLabels = array();
        $notas = $this->consulta_promedios_x_clase($inscriptionId);
        foreach ($notas as $nota) {
            array_push($arrayLabels, $nota['materia']);
            array_push($arrayValores, $nota['nota']);
        }

        return [
            'labels' => $arrayLabels,
            'valores' => $arrayValores
        ];
    }

    private function consulta_promedios_x_clase($inscriptionId){
      $con = Yii::$app->db;
      $query = "select 	cla.id as clase_id 
                    ,mat.nombre as materia
                    ,(
                        select 	l.nota  
                        from 	lib_bloques_grupo_clase l
                                inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id 
                                inner join op_student_inscription i on i.student_id = g.estudiante_id 
                        where 	i.id = ins.id 
                                and g.clase_id = gru.clase_id 
                    )
                from	scholaris_grupo_alumno_clase gru
                    inner join op_student_inscription ins on ins.student_id = gru.estudiante_id 
                    inner join scholaris_clase cla on cla.id = gru.clase_id 
                    inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                    inner join ism_materia mat on mat.id = iam.materia_id 
                where 	ins.id = $inscriptionId
                    and iam.promedia = true
                order by mat.nombre;";
      return $con->createCommand($query)->queryAll();   
    }
       
}