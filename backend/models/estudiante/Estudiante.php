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
    // echo $query;
    // die();
      return $con->createCommand($query)->queryAll();   
    }



    /**
     * metodo para devolver los datos de los casos dece para el chart
     */
    public function chart_dece($inscriptionId, $periodoId){
        $dece = $this->consulta_estadisticas_dece($inscriptionId, $periodoId);

        // print_r($dece);

        $arrayLabels = array();
        $arrayValores = array();

        foreach ($dece[0] as $key => $value) {
            $arrayLabels[] = $key;
            $arrayValores[] = $value;
        }

        return [
            'labels' => $arrayLabels,
            'valores' => $arrayValores
        ];
    }


    // metodo que consulta los totales para el cuadro estadistico del DECE
    private function consulta_estadisticas_dece($inscriptionId, $periodoId){
        $con = Yii::$app->db;
        $query = "select 	(select count(id) 
                        from 	dece_casos 
                        where 	id_estudiante = est.id
                            and id_periodo = $periodoId) as total_casos
                        ,(select 	count(d.id) 
                            from 	dece_derivacion d
                                    inner join dece_casos c on c.id = d.id_casos
                            where 	c.id_estudiante = est.id 
                                    and id_periodo = $periodoId) as total_derivacion
                        ,(
                            select 	count(dt.id)
                            from	dece_deteccion dt
                                    inner join dece_casos c on c.id = dt.id_caso 
                            where 	c.id_estudiante = est.id 
                                    and id_periodo = $periodoId
                        ) as total_deteccion
                        ,(
                            select 	count(i.id)
                            from	dece_intervencion i
                                    inner join dece_casos c on c.id = i.numero_caso 
                            where 	i.id_estudiante = est.id 
                                    and id_periodo = $periodoId
                        ) as total_intervencion
                        ,(
                            select 	count(s.id)
                            from	dece_registro_seguimiento s
                                    inner join dece_casos c on c.id = s.id_caso 
                            where 	c.id_estudiante = est.id 
                                    and id_periodo = $periodoId
                        ) as total_seguimiento
                from 	op_student est 
                        inner join op_student_inscription ins on ins.student_id = est.id 
                where 	ins.id = $inscriptionId;";
        return $con->createCommand($query)->queryAll();
    }


    /**
     * METODO QUE DEVUELVE EL CUADRO DE LAS NOVEDADES DEL DECE
     */
    public function detalle_dece($inscriptionId, $periodoId){
        $dece = $this->consulta_dece($inscriptionId, $periodoId);

        return $dece;


    }

    private function consulta_dece($inscriptionId, $periodoId){
        $con = Yii::$app->db;
        $querySeguimiento = "select 	seg.fecha_inicio 
                                        ,seg.fecha_fin 
                                        ,seg.motivo 
                                        ,seg.pronunciamiento 
                                from 	op_student est 
                                        inner join op_student_inscription ins on ins.student_id = est.id 
                                        inner join dece_casos cas on cas.id_estudiante = ins.student_id 
                                        inner join dece_registro_seguimiento seg on seg.id_caso = cas.id 
                                where 	ins.id = $inscriptionId
                                        and cas.id_periodo = $periodoId;";

        $seguimiento = $con->createCommand($querySeguimiento)->queryAll();

        $queryDeteccion = "select 	det.nombre_quien_reporta 
                                    ,det.fecha_reporte 
                                    ,det.descripcion_del_hecho 
                                    ,det.hora_aproximada 
                                    ,det.acciones_realizadas 
                            from 	op_student est 
                                    inner join op_student_inscription ins on ins.student_id = est.id 
                                    inner join dece_casos cas on cas.id_estudiante = ins.student_id 
                                    inner join dece_deteccion det on det.id_caso = cas.id  
                            where 	ins.id = $inscriptionId
                                    and cas.id_periodo = $periodoId;";

        $deteccion = $con->createCommand($queryDeteccion)->queryAll();


        $queryIntervencion = "select 	inte.fecha_intervencion 
                                        , inte.razon 
                                        ,inte.acciones_responsables 
                                        ,inte.objetivo_general 
                                from	dece_intervencion inte
                                        inner join op_student_inscription ins on ins.student_id = inte.id_estudiante
                                        inner join dece_casos cas on cas.id = inte.id_caso 
                                where 	ins.id = $inscriptionId
                                        and cas.id_periodo = $periodoId;";
        $intervencion = $con->createCommand($queryIntervencion)->queryAll();



        $queryDerivacion = "select 	de.nombre_quien_deriva 
                                        ,de.tipo_derivacion 
                                        ,de.fecha_derivacion 
                                        ,de.motivo_referencia 
                                        ,de.accion_desarrollada 
                                from	dece_derivacion de
                                        inner join op_student_inscription ins on ins.student_id = de.id_estudiante
                                        inner join dece_casos cas on cas.id = de.numero_casos 
                                where 	ins.id = $inscriptionId
                                        and cas.id_periodo = $periodoId;";
        $derivacion = $con->createCommand($queryDerivacion)->queryAll();

        return [
            'seguimiento' => $seguimiento,
            'deteccion' => $deteccion,
            'intervencion' => $intervencion,
            'derivacion' => $derivacion 
        ];
    }
    
       
}