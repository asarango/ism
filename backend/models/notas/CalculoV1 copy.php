<?php
namespace backend\models\notas;
use backend\models\IsmMallaArea;
use backend\models\LibBloquesGrupoClase;
use backend\models\helpers\HelperGeneral;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisQuimestre;
use Yii;

class CalculoV1{

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

    public function __construct($periodId, $grupoId, $bloqueId, $uso, $quimestreId, $promedia,
                                $imprime,
                                $porcentaje,
                                $tipo,
                                $areaId,
                                $studentId){

                                    echo $bloqueId;
                                    die();

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

        $this->calcular_pr();

        $this->calcular_pr80_porciento();
        $this->calcular_ex20_porciento();
        $this->calcular_quimestre();
        $this->calcular_fin_qumestres();

        $this->elimina_notas_area();
        $this->registra_area();

        $this->elimina_notas_promedios();
        $this->registra_promedios();
    }

    private function elimina_notas_promedios(){
        $con = Yii::$app->db;
        $query = "delete from lib_bloques_grupo_promedios 
                    where student_id = $this->studentId 
                            and periodo_id = $this->periodoId";
        $con->createCommand($query)->execute();
    }

    private function registra_promedios(){
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


    private function elimina_notas_area(){
        $mallaAreaId = $this->modelMallaArea->id;
        $con = Yii::$app->db;
        $query = "delete from lib_bloques_grupo_area 
                    where student_id = $this->studentId 
                            and periodo_id = $this->periodoId 
                            and ism_malla_area_id = $mallaAreaId";
        $con->createCommand($query)->execute();
    }

    private function registra_area(){        
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


    private function calcular_pr(){
        $con    = Yii::$app->db;
        $query  = "select 	trunc(avg(lib.nota), 2) as pr1
                    from	scholaris_bloque_actividad sba 
                            inner join lib_bloques_grupo_clase lib on lib.bloque_id = sba.id 
                    where 	sba.quimestre_id = $this->quimestreId
                            and sba.tipo_uso = '$this->uso'
                            and sba.tipo_bloque = 'PARCIAL'
                            and lib.grupo_id = $this->grupoId";

        echo $query;
        echo $this->quimestreCodigo;
        die();
        
        $res = $con->createCommand($query)->queryOne();

        if($this->quimestreCodigo == 'QUIMESTRE I'){
            $codigo = 'PR1';
        }else{
            $codigo = 'PR2';
        }

        $model = LibBloquesGrupoClase::find()->where([
            'grupo_id' => $this->grupoId,
            'abreviatura' => $codigo
        ])->one();

        if($model){
            $model->nota        = $res['pr1'];
            $model->updated_at  = $this->ahora;
            $model->updated     = $this->user;
            $model->save();
        }else{
            $bloqueId = $this->get_bloque_id($codigo);
            
            $insert = new LibBloquesGrupoClase();
            $insert->grupo_id   = $this->grupoId;
            $insert->bloque_id  = $bloqueId;
            $insert->nota       = $res['pr1'];
            $insert->created_at = $this->ahora;
            $insert->created    = $this->user;
            $insert->updated_at = $this->ahora;
            $insert->updated    = $this->user;
            $insert->periodo_id = $this->periodId;
            $insert->abreviatura = $codigo;
            $insert->promedia   = $this->promedia;
            $insert->imprime    = $this->imprime;
            $insert->porcentaje = $this->porcentaje;
            $insert->tipo       = $this->tipo;

            $insert->save();
        }
    }

    

    /**
     * METODO QUE CALCULA EL 80%
     */
    private function calcular_pr80_porciento(){        
        
        if($this->quimestreCodigo == 'QUIMESTRE I'){
            $codigo     = 'PR1';
            $codigo80   = 'PR180';
        }else{
            $codigo     = 'PR2';
            $codigo80   = 'PR280';
        }

        $modelPr = LibBloquesGrupoClase::find()->where([
            'grupo_id' => $this->grupoId,
            'abreviatura' => $codigo
        ])->one();

        $notaPr     = $modelPr->nota; ///nota para calcular el 80%
        $notaPr80   = ($notaPr*80) / 100;

        $truncado   = number_format($this->classHelper->truncarNota($notaPr80, $this->digitoTruncar),2); 


        $modelPr80 = LibBloquesGrupoClase::find()->where([
            'grupo_id' => $this->grupoId,
            'abreviatura' => $codigo80
        ])->one();

        if($modelPr80){
            $modelPr80->nota = $truncado;
            $modelPr80->updated_at = $this->ahora;
            $modelPr80->updated = $this->user;
            $modelPr80->save();
        }else{
            $bloqueId = $this->get_bloque_id($codigo80);
            $insert = new LibBloquesGrupoClase();
            $insert->grupo_id   = $this->grupoId;
            $insert->bloque_id  = $bloqueId;
            $insert->nota       = $truncado;
            $insert->created_at = $this->ahora;
            $insert->created    = $this->user;
            $insert->updated_at = $this->ahora;
            $insert->updated    = $this->user;
            $insert->periodo_id = $this->periodId;
            $insert->abreviatura = $codigo80;
            $insert->promedia   = $this->promedia;
            $insert->imprime    = $this->imprime;
            $insert->porcentaje = $this->porcentaje;
            $insert->tipo       = $this->tipo;

            $insert->save();
        }

    }
    // fin de calculo de PR  al 80%

    /**
     * MÉTODO PARA CALCULAR EL 20 % DE EXAMEN
     */
    private function calcular_ex20_porciento(){        
        
        if($this->quimestreCodigo == 'QUIMESTRE I'){
            $codigo     = 'EX1';
            $codigo20   = 'EX120';
        }else{
            $codigo     = 'EX2';
            $codigo20   = 'EX220';
        }

        $modelEx = LibBloquesGrupoClase::find()->where([
            'grupo_id' => $this->grupoId,
            'abreviatura' => $codigo
        ])->one();

        $notaEx = isset( $modelEx->nota ) ? $modelEx->nota : 0;

        // $notaEx     = $modelEx->nota; ///nota para calcular el 80%
        $notaEx20   = ($notaEx*20) / 100;

        $truncado   = number_format($this->classHelper->truncarNota($notaEx20, $this->digitoTruncar),2); 
        
        $modelEx20 = LibBloquesGrupoClase::find()->where([
            'grupo_id' => $this->grupoId,
            'abreviatura' => $codigo20
        ])->one();

        if( $modelEx20 ){
            $modelEx20->nota = $truncado;
            $modelEx20->updated_at = $this->ahora;
            $modelEx20->updated = $this->user;
            $modelEx20->save();
        }else{
            $bloqueId = $this->get_bloque_id($codigo20);
            $insert = new LibBloquesGrupoClase();
            $insert->grupo_id   = $this->grupoId;
            $insert->bloque_id  = $bloqueId;
            $insert->nota       = $truncado;
            $insert->created_at = $this->ahora;
            $insert->created    = $this->user;
            $insert->updated_at = $this->ahora;
            $insert->updated    = $this->user;
            $insert->periodo_id = $this->periodId;
            $insert->abreviatura = $codigo20;
            $insert->promedia   = $this->promedia;
            $insert->imprime    = $this->imprime;
            $insert->porcentaje = $this->porcentaje;
            $insert->tipo       = $this->tipo;

            $insert->save();
        }

    }
    /*******FIN DEL CALCULO DEL 20% DEL EXAMEN* */


    /**
     * MÉTODO PARA CALCULAR QUIMESTRE
     */
    private function calcular_quimestre(){        
        
        if($this->quimestreCodigo == 'QUIMESTRE I'){
            $codigoPr80     = 'PR180';
            $codigoEx20     = 'EX120';        
            $codigoQuim     = 'Q1';
        }else{
            $codigoPr80     = 'PR280';
            $codigoEx20     = 'EX220';
            $codigoQuim     = 'Q2';
        }

        $con    = Yii::$app->db;
        $query  = "select 	sum(nota) as quimestral
        from 	lib_bloques_grupo_clase
        where 	abreviatura in ('$codigoPr80','$codigoEx20');";
        $resSuma = $con->createCommand($query)->queryOne();

        $sumaParcialExamen = $resSuma['quimestral'];


        ///////////////////////////////////

        $modelQuimestre = LibBloquesGrupoClase::find()->where([
            'grupo_id' => $this->grupoId,
            'abreviatura' => $codigoQuim
        ])->one();

        if( $modelQuimestre ){
            $modelQuimestre->nota = $sumaParcialExamen;
            $modelQuimestre->updated_at = $this->ahora;
            $modelQuimestre->updated = $this->user;
            $modelQuimestre->save();
        }else{
            $bloqueId = $this->get_bloque_id($codigoQuim);
            $insert = new LibBloquesGrupoClase();
            $insert->grupo_id   = $this->grupoId;
            $insert->bloque_id  = $bloqueId;
            $insert->nota       = $sumaParcialExamen;
            $insert->created_at = $this->ahora;
            $insert->created    = $this->user;
            $insert->updated_at = $this->ahora;
            $insert->updated    = $this->user;
            $insert->periodo_id = $this->periodId;
            $insert->abreviatura = $codigoQuim;
            $insert->promedia   = $this->promedia;
            $insert->imprime    = $this->imprime;
            $insert->porcentaje = $this->porcentaje;
            $insert->tipo       = $this->tipo;

            $insert->save();
        }

    }
     /**** FIN DE METODO DE CALCULO DE QUIMESTRE */


    /**
     * MÉTODO PARA CALCULAR EL VALOR FINAL DE LOS QUIMESTRES
    */
    private function calcular_fin_qumestres(){

        $con    = Yii::$app->db;
        $query  = "select 	trunc(avg(lib.nota),2) as nota_fq
                    from 	lib_bloques_grupo_clase lib
                            inner join scholaris_bloque_actividad blo on blo.id = lib.bloque_id 
                    where 	lib.grupo_id = $this->grupoId
                            and blo.codigo in ('Q1', 'Q2');";

        $resSuma = $con->createCommand($query)->queryOne();

        $promedio = $resSuma['nota_fq'];


        ///////////////////////////////////

        $modelFq = LibBloquesGrupoClase::find()->where([
            'grupo_id' => $this->grupoId,
            'abreviatura' => 'FQ'
        ])->one();

        if( $modelFq ){
            $modelFq->nota = $promedio;
            $modelFq->updated_at = $this->ahora;
            $modelFq->updated = $this->user;
            $modelFq->save();
        }else{
            $bloqueId = $this->get_bloque_id('FQ');
            $insert = new LibBloquesGrupoClase();
            $insert->grupo_id   = $this->grupoId;
            $insert->bloque_id  = $bloqueId;
            $insert->nota       = $promedio;
            $insert->created_at = $this->ahora;
            $insert->created    = $this->user;
            $insert->updated_at = $this->ahora;
            $insert->updated    = $this->user;
            $insert->periodo_id = $this->periodId;
            $insert->abreviatura = 'FQ';
            $insert->promedia   = $this->promedia;
            $insert->imprime    = $this->imprime;
            $insert->porcentaje = $this->porcentaje;
            $insert->tipo       = $this->tipo;

            $insert->save();
        }

    }
    /***** MÉTODO PARA CALCUAR EL VALOR FINAL DE LOS QUIMESTRES */



    private function get_bloque_id($codigo){

        if($codigo == 'FQ'){
            $con = Yii::$app->db;
            $query = "select 	blo.id 
            from 	scholaris_bloque_actividad blo
                    inner join scholaris_quimestre qui on qui.id = blo.quimestre_id 
            where 	blo.codigo = 'FQ' 
                    and blo.tipo_uso = '8'
                    and qui.scholaris_periodo_id = 1;";
            $res = $con->createCommand($query)->queryOne();

            return $res['id'];

        }else{
            $bloque = ScholarisBloqueActividad::find()->where([
                'tipo_uso'      => $this->uso,
                'quimestre_id'  => $this->quimestreId,
                'codigo'        => $codigo
            ])->one();

            return $bloque->id;
        }                
    }

}