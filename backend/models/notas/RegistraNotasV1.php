<?php
namespace backend\models\notas;
use backend\models\LibBloquesGrupoClase;
use backend\models\LibPromediosInsumos;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisCalificaciones;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisPeriodo;
use Yii;

class RegistraNotasV1{

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

    
        public function __construct($grupoId, $calificacionId, $nota) {        
            $this->user         = Yii::$app->user->identity->usuario;
            $this->periodId     = Yii::$app->user->identity->periodo_id;
            $this->periodo      = ScholarisPeriodo::findOne($this->periodId);
            $this->grupoId      = $grupoId;
            $this->calificacionId = $calificacionId;
            $this->nota         = $nota;

            $this->calificacion = ScholarisCalificaciones::findOne($calificacionId);
            
            
            $grupo = ScholarisGrupoAlumnoClase::findOne($grupoId);
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
                
            $this->sienta_insumos();
            // $this->sienta_promedio_bloque_grupo();
            $this->calculo_notas();
            
        }
        
        // Funcion para sentar los promedios de los insumos por grupo del estudiantes
        private function sienta_insumos(){            
            $con = Yii::$app->db;
            $query = "select 	trunc(avg(cal.calificacion),2) as promedio
                        from 	scholaris_calificaciones cal
                                inner join scholaris_actividad act on act.id = cal.idactividad 
                        where	cal.idalumno = $this->alumnoId
                                and act.bloque_actividad_id = $this->bloqueId
                                and cal.grupo_numero = $this->grupoCalificacion;";          
            
            $res = $con->createCommand($query)->queryOne();

            $model = LibPromediosInsumos::find()->where([
                        'grupo_id'  => $this->grupoId,
                        'bloque_id' => $this->bloqueId,
                        'grupo_calificacion' => $this->grupoCalificacion
                    ])
                    ->one(); 

            if($model){  
                $model->nota = $res['promedio'];
                $model->updated_at = $this->ahora;
                $model->updated = $this->user;               
                $model->save();
            }else{
                $insert = new LibPromediosInsumos();
                $insert->grupo_id       = $this->grupoId;
                $insert->bloque_id      = $this->bloqueId;
                $insert->grupo_calificacion = $this->grupoCalificacion;
                $insert->nota           = $this->nota;
                $insert->created_at     = $this->ahora;
                $insert->created        = $this->user;
                $insert->updated_at     = $this->ahora;
                $insert->updated        = $this->user;
                $insert->periodo_id     = $this->periodId;
                
                $insert->save();
            }
        }
        /**** Termina ingreso de insumos del grupo  */

        /**
         * MÉTODO PARA PROMEDIAR LOS INSUMOS DEL GRUPO ID 
         */
        // private function sienta_promedio_bloque_grupo(){
            
        //     $con = Yii::$app->db;
        //     $query = "select 	trunc(avg(nota),2) as nota
        //     from 	lib_promedios_insumos
        //     where 	grupo_id = $this->grupoId
        //             and bloque_id = $this->bloqueId;";
        //     // $query = "select 	sum(lib.nota*(tip.porcentaje/100)) as nota 
        //     // from 	lib_promedios_insumos lib 
        //     //         inner join scholaris_tipo_actividad tip on tip.orden = lib.grupo_calificacion 
        //     // where 	lib.grupo_id = $this->grupoId 
        //     //         and lib.bloque_id = $this->bloqueId ;";

        //     $res = $con->createCommand($query)->queryOne();

        //     $model = LibBloquesGrupoClase::find()
        //     ->where([
        //             'grupo_id'      => $this->grupoId,
        //             'bloque_id'     => $this->bloqueId  
        //             ])
        //     ->one();

        //     if( $model ){
        //         $model->nota        = $res['nota'];
        //         $model->updated_at  = $this->ahora;
        //         $model->updated     = $this->user;
        //         $model->bloque_id   = $this->bloqueId;
        //         $model->save();
        //     }else{
        //         $insert = new LibBloquesGrupoClase();
        //         $insert->grupo_id       = $this->grupoId;
        //         $insert->bloque_id      = $this->bloqueId;
        //         $insert->nota           = $this->nota;
        //         $insert->created_at     = $this->ahora;
        //         $insert->created        = $this->user;
        //         $insert->updated_at     = $this->ahora;
        //         $insert->updated        = $this->user;
        //         $insert->periodo_id     = $this->periodId;
        //         $insert->abreviatura    = $this->modelBloque->codigo;
        //         $insert->promedia       = $this->promedia;
        //         $insert->imprime        = $this->imprime;
        //         $insert->tipo           = $this->tipo;
        //         $insert->porcentaje     = $this->porcentaje;

        //         $insert->save();
        //     }

        // }
         /** FIN MÉTODO PARA PROMEDIAR LOS INSUMOS DEL GRUPO ID */


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
            }
        }
        
        /** FIN DE MÉTODO QUE BUSCA EL CALCULO DE LOS BLOQUES SEGUN LA VERSIÓN QUE TIENE EL PERÍODO
          * EN LA TABLA scholaris_periodo */
    
    }
    