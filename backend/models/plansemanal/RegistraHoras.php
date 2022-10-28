<?php
namespace backend\models\plansemanal;

use backend\models\Lms;
use backend\models\LmsDocente;
use backend\models\ScholarisClase;
use Yii;
use yii\db\ActiveRecord;

use DateTime;

class RegistraHoras{

    private $claseId;
    private $ismAreaMateriaId;
    private $uso;
    private $semanaNumero;
    private $detalleHorario;
    private $datosSemana;

    public function __construct($semanaNumero, $claseId){
        $modelClase             = ScholarisClase::findOne($claseId);
        
        $this->ismAreaMateriaId  = $modelClase->ism_area_materia_id;
        $this->uso              = $modelClase->tipo_usu_bloque;
        $this->semanaNumero     = $semanaNumero;
        $this->claseId          = $claseId;
        $this->detalleHorario   = $this->consulta_detalle_horario();
        $this->datosSemana      = $this->consulta_dato_semana();

        // echo $this->ismAreaMateraId;
        $this->procesar();        

    }

    private function procesar(){
        
        $lms = Lms::find()->where([
            'ism_area_materia_id' => $this->ismAreaMateriaId,
            'tipo_bloque_comparte_valor' => $this->uso,
            'semana_numero' => $this->semanaNumero
        ])
        ->orderBy('hora_numero')
        ->all();


        $posicion = 0;
        foreach($lms as $l){
            $existe = $this->busca_registro($l->id);
            if(!$existe){
                $this->inserta_lms_docente($l->id, $posicion);
            }
            $posicion ++;
        }

    }


    /**
     * METODO PARA BUSCAR LA EXISTENCIA DE REGISTRO
     */
    private function busca_registro($lmsId){
        $model = LmsDocente::find()->where(['lms_id' => $lmsId])->one();
        return $model;
    }

    /**
     * MÉTODO QUE INGRESA EL REGISTRO DEL LMS DOCENTE
     */
    private function inserta_lms_docente($lmsId, $posicion){
        
        $usuarioLog = Yii::$app->user->identity->usuario;
        $hoy = date('Y-m-d H:i:s');        
                
        $detalleHorario = $this->consulta_detalle_horario();

        $detalle = $detalleHorario[$posicion]; //Array con el dato de la semana
        $fecha = $this->busca_fecha($detalle);        
        $model = new LmsDocente();
        $model->lms_id              = $lmsId;
        $model->horario_detalle_id  = $detalle['id'];
        $model->hora_numero_lms     = $posicion + 1;
        $model->clase_id            = $this->claseId;
        $model->fecha               = $fecha;
        $model->se_realizo          = true;
        $model->created             = $usuarioLog;
        $model->create_at           = $hoy;
        $model->save();
      
    }

    /**
     * MÉTODO QUE BUSCA LA FECHA PARA INSERTAR
     */
    private function busca_fecha($detalle){
        $numeroDiaHorario   = $detalle['dia_id'];
        $fechaInicio        = $this->datosSemana['fecha_inicio'];
        $diferenciaDias     = $numeroDiaHorario - 1;
        
        $fecha =  date("Y-m-d",strtotime($fechaInicio."+ $diferenciaDias days"));
        
        return $fecha;       
    }

    /**
     * MÉTODO QUE ENCUENTRA EL DIA DE LA FECHA DE HOY
     */
    // private function 

    /**
     * METODO QUE CONSULTAR EL DETALLE ID DEL HORARIO DE CLASES
     */
    private function consulta_detalle_horario(){
        $con = Yii::$app->db;
        $query = "select 	det.id, det.dia_id, hor.numero 
                    from 	scholaris_horariov2_horario shh
                            inner join scholaris_horariov2_detalle det on det.id = shh.detalle_id 
                            inner join scholaris_horariov2_hora hor on hor.id = det.hora_id 
                    where 	shh.clase_id = $this->claseId
                    order by det.dia_id, hor.numero;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * MÉTODO PARA CONSULTAR EL DATO DE LA SEMANA
     */
    private function consulta_dato_semana(){
        $con = Yii::$app->db;
        $query = "select 	sem.id, sem.bloque_id, sem.semana_numero
                , sem.nombre_semana, sem.fecha_inicio, sem.fecha_finaliza
                , sem.estado, sem.fecha_limite_inicia, sem.fecha_limite_tope  
                from	scholaris_bloque_actividad blo
                        inner join scholaris_bloque_semanas sem on sem.bloque_id = blo.id 
                where 	blo.tipo_uso = '$this->uso'
                        and sem.semana_numero = $this->semanaNumero
                        and blo.tipo_bloque in ('PARCIAL', 'EXAMEN');";

        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
   
}