<?php

namespace backend\models\notas;

use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisClase;
use Yii;

class NotasProfesor
{
        private $claseId;
        private $bloqueId;
        private $clase;
        private $trimestre;
        public $grupo;

        public $tipoAporte;
        public $tipoActividades;
        public $actividades;
        private $promediosInsumos;
        public $notas_x_actividad;


        public function __construct($claseId, $bloqueId)
        {
                $this->claseId = $claseId;
                $this->bloqueId = $bloqueId;

                // $this->clase            = ScholarisClase::findOne($claseId);
                // $this->trimestre        = ScholarisBloqueActividad::findOne($bloqueId);


                $this->get_tipo_aportes();
                $this->get_grupo();
                $this->get_tipos_actividades();
                $this->get_actividades();
                $this->get_notas_x_actividad();
                $this->get_promedios_insumos();

                $this->generate_matriz();

                
        }


        private function get_promedios_insumos(){
                $con = Yii::$app->db;
                $query = "select 	gru.estudiante_id 
                                                ,ins.nota
                                                ,ins.grupo_calificacion 
                                from 	lib_promedios_insumos ins
                                                inner join scholaris_grupo_alumno_clase gru on gru.id = ins.grupo_id
                                where 	ins.bloque_id = $this->bloqueId
                                                and gru.clase_id = $this->claseId;";
                $this->promediosInsumos = $con->createCommand($query)->queryAll();
        }


        private function get_tipo_aportes()
        {
                $con = Yii::$app->db;
                $query = "select 	tip.tipo_aporte  
                        from 	scholaris_actividad act
                                        inner join scholaris_tipo_actividad tip on tip.id = act.tipo_actividad_id 
                                        inner join scholaris_grupo_orden_calificacion ord on ord.codigo_tipo_actividad = tip.id
                        where 	act.paralelo_id = $this->claseId
                                        and act.calificado = 'true'
                        group by tip.tipo_aporte
                        order by tip.tipo_aporte desc;";
                $this->tipoAporte = $con->createCommand($query)->queryAll();
        }

        private function get_grupo()
        {
                $con = Yii::$app->db;
                $query = "select 	gru.id as grupo_id
                                ,est.id as estudiante_id
                                ,concat(est.last_name, ' ', est.first_name, ' ', est.middle_name) as estudiante 
                        from 	scholaris_grupo_alumno_clase gru
                                inner join op_student est on est.id = gru.estudiante_id 
                        where 	gru.clase_id = $this->claseId
                        order by est.last_name, est.first_name, est.middle_name;";
                $this->grupo = $con->createCommand($query)->queryAll();
        }



        private function get_tipos_actividades()
        {
                $con = Yii::$app->db;
                $query = "select 	tip.id as tipo_actividad_id
                        ,tip.nombre_nacional as tipo_actividad
                        ,tip.tipo_aporte
                        ,ord.grupo_numero  
                from 	scholaris_actividad act
                        inner join scholaris_tipo_actividad tip on tip.id = act.tipo_actividad_id 
                        inner join scholaris_grupo_orden_calificacion ord on ord.codigo_tipo_actividad = tip.id
                where 	act.paralelo_id = $this->claseId 
                        and act.bloque_actividad_id = $this->bloqueId
                        and act.calificado = 'true'
                group by tip.id ,tip.nombre_nacional ,ord.grupo_numero
                order by ord.grupo_numero;";
                $this->tipoActividades = $con->createCommand($query)->queryAll();
        }


        private function get_actividades()
        {
                $con = Yii::$app->db;
                $query = "select 	tip.id as tipo_actividad_id
                        ,tip.nombre_nacional as tipo_actividad
                        ,act.id as actividad_id
                        ,act.title
                        ,ord.grupo_numero  
                from 	scholaris_actividad act
                        inner join scholaris_tipo_actividad tip on tip.id = act.tipo_actividad_id 
                        inner join scholaris_grupo_orden_calificacion ord on ord.codigo_tipo_actividad = tip.id
                where 	act.paralelo_id = $this->claseId 
                        and act.bloque_actividad_id = $this->bloqueId
                        and act.calificado = 'true'
                order by ord.grupo_numero;";
                $this->actividades = $con->createCommand($query)->queryAll();
        }


        private function get_notas_x_actividad()
        {
                $con = Yii::$app->db;
                $query = "select 	act.id as actividad_id
                            ,act.title as actividad
                            ,cal.idtipoactividad as tipo_actividad_id
                            ,cal.idalumno as estudiante_id
                            ,gru.id as grupo_id
                            ,cal.calificacion 
                            ,cal.grupo_numero
                    from 	scholaris_calificaciones cal 
                            inner join scholaris_actividad act on act.id = cal.idactividad 
                            inner join scholaris_grupo_alumno_clase gru on gru.clase_id = act.paralelo_id 
                                    and gru.estudiante_id = cal.idalumno 
                    where 	act.paralelo_id = $this->claseId
                            and act.bloque_actividad_id = $this->bloqueId
                            and act.calificado = 'true';";

                $this->notas_x_actividad = $con->createCommand($query)->queryAll();
        }



        private function generate_matriz()
        {
                $arrayEstudiantes = array();
                $arrayNotasXActividad = array();
                $arrayTipoActividad = array();
                $arrayAportes = array();
                $arrayTa = array();
                $arrayPromedioInsumos = array();


                // echo '<pre>';
                // print_r($this->promediosInsumos);
                // die();

                foreach($this->promediosInsumos as $key => $promedioInsumo){
                        $arrayPromedioInsumos[$promedioInsumo['estudiante_id']][$promedioInsumo['grupo_calificacion']]['promedio'] = $promedioInsumo['nota'];
                }

                foreach ($this->tipoAporte as $key => $ta) {
                        $arrayTa[] = $ta['tipo_aporte'];
                }


                foreach ($this->tipoActividades as $key => $tipoActividad) {
                        $arrayTipoActividad[$tipoActividad['tipo_actividad_id']]['tipo_actividad_id'] = $tipoActividad['tipo_actividad_id'];
                        $arrayTipoActividad[$tipoActividad['tipo_actividad_id']]['tipo_actividad'] = $tipoActividad['tipo_actividad'];
                        $arrayTipoActividad[$tipoActividad['tipo_actividad_id']]['tipo_aporte'] = $tipoActividad['tipo_aporte'];
                        $arrayTipoActividad[$tipoActividad['tipo_actividad_id']]['grupo_numero'] = $tipoActividad['grupo_numero'];
                }

                foreach ($this->grupo as $keyE => $estudiante) {
                        $arrayEstudiantes[$estudiante['estudiante_id']]['estudiante'] = $estudiante['estudiante'];

                        /** Ingresamos al arrayEstudiantes los index de individual y grupal */
                        foreach ($arrayTa as $keyTa => $ta) {
                                foreach($this->tipoActividades as $keyTip => $tipoActividad) {
                                       if($ta == $tipoActividad['tipo_aporte']){
                                        $arrayEstudiantes[$estudiante['estudiante_id']][$ta][$tipoActividad['tipo_actividad_id']]['tipo_actividad_id'] = $tipoActividad['tipo_actividad_id'];
                                        $arrayEstudiantes[$estudiante['estudiante_id']][$ta][$tipoActividad['tipo_actividad_id']]['tipo_actividad'] = $tipoActividad['tipo_actividad'];
                                        $arrayEstudiantes[$estudiante['estudiante_id']][$ta][$tipoActividad['tipo_actividad_id']]['grupo_numero'] = $tipoActividad['grupo_numero'];
                                        $arrayEstudiantes[$estudiante['estudiante_id']][$ta][$tipoActividad['tipo_actividad_id']]['promedio'] = ( isset($arrayPromedioInsumos[$estudiante['estudiante_id']][$tipoActividad['grupo_numero']] )) 
                                                        ?  $arrayPromedioInsumos[$estudiante['estudiante_id']][$tipoActividad['grupo_numero']]['promedio'] : 0 ;                                        //$arrayEstudiantes[$estudiante['estudiante_id']][$ta][$tipoActividad['tipo_actividad_id']]['promedio'] =   $arrayPromedioInsumos[$estudiante['estudiante_id']][$tipoActividad['grupo_numero']]['promedio'];
                                       }
                                }
                                
                        }
                }                




                echo '<pre>';
                print_r($arrayEstudiantes);
                // print_r($arrayNotasXActividad);
                die();
        }
}
