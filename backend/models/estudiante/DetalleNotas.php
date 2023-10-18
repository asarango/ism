<?php

namespace backend\models\estudiante;

use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisClase;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisPeriodo;
use Yii;

class DetalleNotas
{
        private $claseNombre;

        private $inscriptionId;
        private $grupoId;
        private $grupo;
        private $clase;
        private $trimestres;
        private $periodo;
        private $periodoCodigo; 
        public $tareas;

        public $promediosInsumos;
        public $promediosTrimestres;


        public function __construct($nombreClase, $inscriptionId)
        {
                $periodoId = Yii::$app->user->identity->periodo_id;
                $this->periodo = ScholarisPeriodo::findOne($periodoId);
                $this->periodoCodigo = $this->periodo->codigo;

                $this->claseNombre = $nombreClase;
                $this->inscriptionId    = $inscriptionId;

                $this->tareas();
                $this->promediosInsumo();
                $this->promedioTrimestre();
                
        }

        public function tareas(){
                $con = Yii::$app->db;
                $query = "select 	blo.id as trimestre_id
                                                ,blo.name as trimestre
                                                ,tip.id as tipo_actividad_id
                                                ,tip.nombre_nacional 
                                                ,tip.tipo_aporte 
                                                ,tip.categoria
                                                ,tip.orden 
                                                ,act.title as titulo
                                                ,cal.calificacion 
                                from	scholaris_calificaciones cal
                                                inner join scholaris_actividad act on act.id = cal.idactividad 
                                                inner join scholaris_clase cla on cla.id = act.paralelo_id 
                                                inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                                                inner join ism_materia mat on mat.id = iam.materia_id 
                                                inner join op_student_inscription ins on ins.student_id = cal.idalumno 
                                                inner join scholaris_bloque_actividad blo on blo.id = act.bloque_actividad_id 
                                                inner join scholaris_tipo_actividad tip on tip.id = act.tipo_actividad_id 
                                where 	mat.nombre = '$this->claseNombre'
                                                and ins.id = $this->inscriptionId
                                order by blo.orden, tip.tipo_aporte desc, tip.orden ;";

                
                $this->tareas = $con->createCommand($query)->queryAll();
        }


        public function promediosInsumo(){
                $con = Yii::$app->db;
                $query = "select 	lib.bloque_id as trimestre_id
                                                ,lib.grupo_calificacion
                                                ,lib.nota 
                                from 	lib_promedios_insumos lib
                                                inner join scholaris_grupo_alumno_clase gru on gru.id = lib.grupo_id
                                                inner join op_student_inscription ins on ins.student_id = gru.estudiante_id
                                where 	ins.id = $this->inscriptionId
                                order by lib.grupo_calificacion;";
                                
                $this->promediosInsumos = $con->createCommand($query)->queryAll();
        }


        public function promedioTrimestre(){
                $con = Yii::$app->db;
                $query = "select 	lib.nota as promedio_general
                        ,lib.bloque_id as trimestre_id
                from	lib_bloques_grupo_clase lib
                                inner join scholaris_grupo_alumno_clase gru on gru.id = lib.grupo_id 
                                inner join op_student_inscription ins on ins.student_id = gru.estudiante_id
                where 	ins.id = $this->inscriptionId;";
                
                $this->promediosTrimestres = $con->createCommand($query)->queryAll();
        }
}
