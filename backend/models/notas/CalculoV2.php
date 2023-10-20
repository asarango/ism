<?php

namespace backend\models\notas;

use backend\models\IsmMallaArea;
use backend\models\LibBloquesGrupoClase;
use backend\models\helpers\HelperGeneral;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisQuimestre;
use Yii;


/**
 * Esta clase realiza los calulos de notas para tomar en cuenta los promedios de los isumos que se realizan en la clase RegistraNotasV1
 */

class CalculoV2
{

        private $periodId;
        private $bloqueId;
        private $grupoId;
        private $studentId;
        private $uso;
        private $quimestreId;
        private $quimestre;
        private $quimestreCodigo;
        private $user;
        private $ahora;
        private $classHelper;
        private $digitoTruncar = 2;
        private $promedia;
        private $imprime;
        private $tipo;
        private $porcentaje;
        private $areaId;
        private $periodoId;
        private $modelMallaArea;

        public function __construct(
                $periodId,
                $grupoId,
                $bloqueId,
                $uso,
                $quimestreId,
                $promedia,
                $imprime,
                $porcentaje,
                $tipo,
                $areaId,
                $studentId
        ) {

                $this->periodId = $periodId;
                $this->grupoId  = $grupoId;
                $this->bloqueId = $bloqueId;

                $this->uso      = $uso;
                $this->quimestreId = $quimestreId;
                $this->quimestre = ScholarisQuimestre::findOne($quimestreId);
                $this->quimestreCodigo = $this->quimestre->codigo;
                $this->user     = Yii::$app->user->identity->usuario;
                $this->ahora    = date('Y-m-d H:i:s');
                $this->classHelper = new HelperGeneral();

                $this->promedia = $promedia;
                $this->imprime  = $imprime;
                $this->tipo     = $tipo;
                $this->porcentaje = $porcentaje;
                $this->studentId = $studentId;

                $this->periodoId = $periodId;

                $this->modelMallaArea = IsmMallaArea::findOne($areaId);

                $this->calcular_trimestres();

                $this->elimina_notas_area();
                $this->registra_area();

                $this->elimina_notas_promedios();
                $this->registra_promedios();
        }

        private function elimina_notas_promedios()
        {
                $con = Yii::$app->db;
                $query = "delete from lib_bloques_grupo_promedios 
                    where student_id = $this->studentId 
                            and periodo_id = $this->periodoId";
                $con->createCommand($query)->execute();
        }

        private function registra_promedios()
        {
                $con = Yii::$app->db;
                $query = "insert into lib_bloques_grupo_promedios(student_id, bloque_id, nota, abreviatura, periodo_id, created_at, created, updated_at, updated)
                    select 	$this->studentId
                            ,bloque_id
                            ,avg(nota) as nota
                            ,abreviatura
                            ,$this->periodoId
                            ,'$this->ahora'
                            ,'$this->user'
                            ,'$this->ahora'
                            ,'$this->user'
                    from (
                            select 	mat.bloque_id 
                                    ,mat.abreviatura 
                                    ,mat.nota 
                            from 	lib_bloques_grupo_clase mat
                                    inner join scholaris_grupo_alumno_clase gru on gru.id = mat.grupo_id 
                            where 	mat.promedia = true
                                    and gru.estudiante_id = $this->studentId
                                    and mat.periodo_id = $this->periodoId
                            union all 
                            select 	bloque_id 
                                    ,abreviatura 
                                    ,nota 
                            from	lib_bloques_grupo_area
                            where 	periodo_id = $this->periodId
                                    and student_id = $this->studentId
                                    and promedia = true
                    ) as nota
                    group by bloque_id
                            ,abreviatura;";
                $con->createCommand($query)->execute();
        }


        private function elimina_notas_area()
        {
                $mallaAreaId = $this->modelMallaArea->id;
                $con = Yii::$app->db;
                $query = "delete from lib_bloques_grupo_area 
                    where student_id = $this->studentId 
                            and periodo_id = $this->periodoId 
                            and ism_malla_area_id = $mallaAreaId";
                $con->createCommand($query)->execute();
        }

        private function registra_area()
        {
                $mallaAreaId    = $this->modelMallaArea->id;
                $porcentaje     = $this->modelMallaArea->porcentaje ? $this->modelMallaArea->porcentaje : 0;
                $tipo           = $this->modelMallaArea->tipo;
                $promedia       = $this->modelMallaArea->promedia ? 1 : 0;
                $imprime        = $this->modelMallaArea->imprime_libreta ? 1 : 0;

                // echo '<pre>';
                // print_r($promedia);
                // die();

                $con = Yii::$app->db;
                $query = "insert into lib_bloques_grupo_area (ism_malla_area_id, student_id, bloque_id, nota, promedia, abreviatura, imprime, porcentaje, tipo, periodo_id, created_at, created, updated_at, updated)
                    select $mallaAreaId as ism_malla_area_id
                            , $this->studentId as estudiante_id
                            ,bloque_id
                            ,avg(promedio) as nota
                            ,bool($promedia) as promedia
                            ,abreviatura 
                            ,bool($imprime) as imprime
                            ,$porcentaje as porcentaje
                            ,'$tipo' as tipo
                            ,$this->periodoId as periodo
                            ,'$this->ahora'
                            ,'$this->user'
                            ,'$this->ahora'
                            ,'$this->user'
                    from( 
                            select 	(iam.porcentaje*nota)/100 as promedio
                                    ,lib.abreviatura, lib.bloque_id  
                            from 	lib_bloques_grupo_clase lib
                                    inner join scholaris_grupo_alumno_clase gru on gru.id = lib.grupo_id 
                                    inner join scholaris_clase cla on cla.id = gru.clase_id 
                                    inner join ism_area_materia iam on iam.id = cla.ism_area_materia_id 
                                    inner join ism_malla_area ima on ima.id = iam.malla_area_id
                            where 	gru.estudiante_id = $this->studentId
                                    and ima.id = $mallaAreaId
                                    and periodo_id = $this->periodoId
                        ) as promedio
                    group by abreviatura,bloque_id;";

                $con->createCommand($query)->execute();
        }


        private function calcular_trimestres()
        {
                $con    = Yii::$app->db;

                $query = "select 	sba.id as bloque_id
                            ,sba.name as bloque
                            ,sba.abreviatura
                    from	scholaris_bloque_actividad sba 
                    where 	sba.quimestre_id = $this->quimestreId
                            and sba.tipo_uso = '$this->uso'
                            and sba.tipo_bloque = 'PARCIAL'
                    order by orden;";

                $bloques = $con->createCommand($query)->queryAll();

                foreach ($bloques as $bloque) {
                        $this->calcula_aportes($bloque);
                }
        }

        private function calcula_aportes($arrayBloque)
        {
                $con    = Yii::$app->db;
                $bloqueId = $arrayBloque['bloque_id'];

                $queryProemedio = "select 	sum(promedio_transformado) as nota	 
                                from 	lib_promedios_individual_grupal
                                where	grupo_id = $this->grupoId
                                                and bloque_id = $this->bloqueId;";

                $res = $con->createCommand($queryProemedio)->queryOne();

                $model = LibBloquesGrupoClase::find()->where([
                        'grupo_id' => $this->grupoId,
                        'abreviatura' => 'total',
                        'bloque_id' => $bloqueId
                ])->one();

                if ($model) {
                        $model->nota        = $res['nota'];
                        $model->updated_at  = $this->ahora;
                        $model->updated     = $this->user;
                        $model->save();
                } else {
                        $insert = new LibBloquesGrupoClase();
                        $insert->grupo_id   = $this->grupoId;
                        $insert->bloque_id  = $bloqueId;
                        $insert->nota       = $res['nota'];
                        $insert->created_at = $this->ahora;
                        $insert->created    = $this->user;
                        $insert->updated_at = $this->ahora;
                        $insert->updated    = $this->user;
                        $insert->periodo_id = $this->periodId;
                        $insert->abreviatura = 'total'; //Total de aportes + no aportes
                        $insert->promedia   = $this->promedia;
                        $insert->imprime    = $this->imprime;
                        $insert->porcentaje = $this->porcentaje;
                        $insert->tipo       = $this->tipo;

                        $insert->save();
                }
        }
}
