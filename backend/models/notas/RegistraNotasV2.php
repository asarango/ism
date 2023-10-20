<?php
namespace backend\models\notas;
use backend\models\LibBloquesGrupoClase;
use backend\models\LibPromediosIndividualGrupal;
use backend\models\LibPromediosInsumos;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisCalificaciones;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisPeriodo;
use Yii;



/**
 * Esta clase realiza los calulos de los promedios de los isumos 
 * esta clase se encuentra deprecated
 * funciona haciendo promedios por cada insumo
 */

class RegistraNotasV2{




    //    Atributos
        private $alumnoId;
        private $grupoId;
        private $studentId;
        private $calificacionId;
        private $calificacion;
        private $nota;
        private $periodId;
        private $periodo;
        private $grupoCalificacion;
        private $user;
        private $ahora;
        private $bloqueId;
        private $modelBloque;
        private $uso;
        private $quimestreId;
        private $promedia;
        private $imprime;
        private $tipo;
        private $porcentaje;
        private $areaId;


        private $claseId;

    
        public function __construct($grupoId, $calificacionId, $nota) {        
            $this->user         = Yii::$app->user->identity->usuario;
            $this->periodId     = Yii::$app->user->identity->periodo_id;
            $this->periodo      = ScholarisPeriodo::findOne($this->periodId);
            $this->grupoId      = $grupoId;
            $this->calificacionId = $calificacionId;
            $this->nota         = $nota;

            $this->calificacion = ScholarisCalificaciones::findOne($calificacionId);
            
            
            $grupo = ScholarisGrupoAlumnoClase::findOne($grupoId);
            $this->claseId = $grupo->clase_id;

            $this->studentId = $grupo->estudiante_id;
            $this->promedia = $grupo->clase->ismAreaMateria->promedia;
            $this->imprime  = $grupo->clase->ismAreaMateria->imprime_libreta;
            $this->tipo     = $grupo->clase->ismAreaMateria->tipo;
            $this->porcentaje = $grupo->clase->ismAreaMateria->porcentaje;
            $this->areaId   = $grupo->clase->ismAreaMateria->mallaArea->id;

            $this->alumnoId     = $this->calificacion->idalumno;
            $this->bloqueId     = $this->calificacion->actividad->bloque_actividad_id;
            $this->modelBloque  = ScholarisBloqueActividad::findOne($this->bloqueId);
            $this->quimestreId  = $this->modelBloque->quimestre_id;
            $this->uso          = $this->modelBloque->tipo_uso;
            $this->grupoCalificacion = $this->calificacion->grupo_numero;
            $this->ahora        = date('Y-m-d H:i:s');
                
            $this->sienta_tipo_aporte();
            $this->calculo_notas();
            
        }
        
        // Funcion para sentar los promedios de los insumos por grupo del estudiantes
        private function sienta_tipo_aporte(){            
            $con = Yii::$app->db;
            
            $query = "select 	tip.tipo_aporte 
                                ,trunc(avg(cal.calificacion),2) as promedio_normal
                                ,case 
                                    when tip.tipo_aporte = 'INDIVIDUAL' then trunc((trunc(avg(cal.calificacion),2)*45)/100,2)
                                    when tip.tipo_aporte = 'GRUPAL' then trunc((trunc(avg(cal.calificacion),2)*45)/100,2)
                                    when tip.tipo_aporte = 'EVALUACION' then trunc((trunc(avg(cal.calificacion),2)*5)/100,2)
                                    when tip.tipo_aporte = 'PROYECTO' then trunc((trunc(avg(cal.calificacion),2)*5)/100,2)
                                end as promedio_transformado
                        from 	scholaris_calificaciones cal 
                                inner join scholaris_actividad act on act.id = cal.idactividad 
                                inner join scholaris_tipo_actividad tip on tip.id = act.tipo_actividad_id 
                        where 	cal.idalumno = $this->alumnoId
                                and act.paralelo_id = $this->claseId
                                and act.bloque_actividad_id = $this->bloqueId
                        group by tip.tipo_aporte ;";       
            
            $res = $con->createCommand($query)->queryAll();


            foreach ($res as $r) {

                $model = LibPromediosIndividualGrupal::find()->where([
                    'grupo_id' => $this->grupoId,
                    'bloque_id' => $this->bloqueId,
                    'tipo_aporte' => $r['tipo_aporte']
                ])->one();
 

                if($model){  
                    $model->promedio_normal = $r['promedio_normal'];
                    $model->promedio_transformado = $r['promedio_transformado'];
                    $model->updated_at = $this->ahora;
                    $model->updated = $this->user;               
                    $model->save();
                }else{
                    $insert = new LibPromediosIndividualGrupal();
                    $insert->grupo_id       = $this->grupoId;
                    $insert->bloque_id      = $this->bloqueId;
                    $insert->tipo_aporte    = $r['tipo_aporte'];
                    $insert->promedio_normal = $r['promedio_normal'];
                    $insert->promedio_transformado = $r['promedio_transformado'];
                    $insert->created_at     = $this->ahora;
                    $insert->created        = $this->user;
                    $insert->updated_at     = $this->ahora;
                    $insert->updated        = $this->user;
                    $insert->periodo_id     = $this->periodId;
                    
                    $insert->save();
                }
            }

            
        }
        /**** Termina ingreso de insumos del grupo  */

         /**
          * MÉTODO QUE BUSCA EL CALCULO DE LOS BLOQUES SEGUN LA VERSIÓN QUE TIENE EL PERÍODO
          * EN LA TABLA scholaris_periodo
          */
        
        private function calculo_notas(){
            switch ($this->periodo->version_calculo_notas){
                case 'V1':
                    new CalculoV1(  $this->periodId, 
                                    $this->grupoId, 
                                    $this->bloqueId, 
                                    $this->uso, 
                                    $this->quimestreId,
                                    $this->promedia,
                                    $this->imprime,
                                    $this->porcentaje,
                                    $this->tipo, 
                                    $this->areaId,
                                    $this->studentId
                                    );
                    break;

                case 'V2':
                    new CalculoV2($this->periodId, 
                                    $this->grupoId, 
                                    $this->bloqueId, 
                                    $this->uso, 
                                    $this->quimestreId,
                                    $this->promedia,
                                    $this->imprime,
                                    $this->porcentaje,
                                    $this->tipo, 
                                    $this->areaId,
                                    $this->studentId);
                    break;
            }
        }
        
        /** FIN DE MÉTODO QUE BUSCA EL CALCULO DE LOS BLOQUES SEGUN LA VERSIÓN QUE TIENE EL PERÍODO
          * EN LA TABLA scholaris_periodo */
    
    }
    