<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_actividad".
 *
 * @property int $id
 * @property string $create_date Created on
 * @property string $write_date Last Updated on
 * @property int $create_uid Created by
 * @property int $write_uid Last Updated by
 * @property string $title Nombre
 * @property string $descripcion Descripción
 * @property resource $archivo Archivo
 * @property string $descripcion_archivo Descripción del Archivo
 * @property string $color Color
 * @property string $inicio Inicio
 * @property string $fin Fin
 * @property int $tipo_actividad_id Tipo de Actividad
 * @property int $bloque_actividad_id Bloque-Actividad
 * @property string $a_peso A Peso
 * @property string $b_peso B Peso
 * @property string $c_peso C Peso
 * @property string $d_peso D Peso
 * @property int $paralelo_id Paralelo
 * @property int $materia_id Materia
 * @property string $calificado calificado
 * @property string $tipo_calificacion
 * @property string $tareas
 * @property int $hora_id
 * @property int $actividad_original
 * @property int $semana_id
 * @property string $momento_detalle
 * @property bool $con_nee
 * @property string $grado_nee
 * @property string $observacion_nee
 * @property int $destreza_id
 * @property string $formativa_sumativa
 * @property string $videoconfecia
 * @property string $respaldo_videoconferencia
 * @property string $link_aula_virtual
 * @property bool $es_aprobado
 * @property string $fecha_revision
 * @property string $usuario_revisa
 * @property string $comentario_revisa
 * @property string $respuesta_revisa
 *
 * @property AcholarisMaterialApoyo[] $acholarisMaterialApoyos
 * @property ScholarisActividadDeber[] $scholarisActividadDebers
 * @property ScholarisActividadIndagacionDetalle[] $scholarisActividadIndagacionDetalles
 * @property ScholarisActividadRecursos[] $scholarisActividadRecursos
 * @property ScholarisActividadSeguimiento[] $scholarisActividadSeguimientos
 */
class ScholarisActividad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_actividad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_date', 'write_date', 'inicio', 'fin', 'fecha_revision'], 'safe'],
            [['create_uid', 'write_uid', 'tipo_actividad_id', 'bloque_actividad_id', 'paralelo_id', 'materia_id', 'hora_id', 'actividad_original', 'semana_id', 'destreza_id'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'tipo_actividad_id', 'bloque_actividad_id', 'paralelo_id', 'materia_id', 'hora_id', 'actividad_original', 'semana_id', 'destreza_id'], 'integer'],
            [['descripcion', 'archivo', 'descripcion_archivo', 'momento_detalle', 'observacion_nee', 'comentario_revisa', 'respuesta_revisa'], 'string'],
            [['inicio', 'fin', 'tipo_actividad_id', 'bloque_actividad_id', 'paralelo_id'], 'required'],
            [['con_nee', 'es_aprobado'], 'boolean'],
            [['title', 'tareas'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 10],
            [['a_peso', 'b_peso', 'c_peso', 'd_peso', 'calificado'], 'string', 'max' => 5],
            [['tipo_calificacion'], 'string', 'max' => 1],
            [['grado_nee'], 'string', 'max' => 50],
            [['formativa_sumativa'], 'string', 'max' => 30],
            [['videoconfecia', 'respaldo_videoconferencia', 'usuario_revisa'], 'string', 'max' => 200],
            [['link_aula_virtual'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_date' => 'Create Date',
            'write_date' => 'Write Date',
            'create_uid' => 'Create Uid',
            'write_uid' => 'Write Uid',
            'title' => 'Title',
            'descripcion' => 'Descripcion',
            'archivo' => 'Archivo',
            'descripcion_archivo' => 'Descripcion Archivo',
            'color' => 'Color',
            'inicio' => 'Inicio',
            'fin' => 'Fin',
            'tipo_actividad_id' => 'Tipo Actividad ID',
            'bloque_actividad_id' => 'Bloque Actividad ID',
            'a_peso' => 'A Peso',
            'b_peso' => 'B Peso',
            'c_peso' => 'C Peso',
            'd_peso' => 'D Peso',
            'paralelo_id' => 'Paralelo ID',
            'materia_id' => 'Materia ID',
            'calificado' => 'Calificado',
            'tipo_calificacion' => 'Tipo Calificacion',
            'tareas' => 'Tareas',
            'hora_id' => 'Hora ID',
            'actividad_original' => 'Actividad Original',
            'semana_id' => 'Semana ID',
            'momento_detalle' => 'Momento Detalle',
            'con_nee' => 'Con Nee',
            'grado_nee' => 'Grado Nee',
            'observacion_nee' => 'Observacion Nee',
            'destreza_id' => 'Destreza ID',
            'formativa_sumativa' => 'Formativa Sumativa',
            'videoconfecia' => 'Videoconfecia',
            'respaldo_videoconferencia' => 'Respaldo Videoconferencia',
            'link_aula_virtual' => 'Link Aula Virtual',
            'es_aprobado' => 'Es Aprobado',
            'fecha_revision' => 'Fecha Revision',
            'usuario_revisa' => 'Usuario Revisa',
            'comentario_revisa' => 'Comentario Revisa',
            'respuesta_revisa' => 'Respuesta Revisa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcholarisMaterialApoyos()
    {
        return $this->hasMany(AcholarisMaterialApoyo::className(), ['actividad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadDebers()
    {
        return $this->hasMany(ScholarisActividadDeber::className(), ['actividad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadIndagacionDetalles()
    {
        return $this->hasMany(ScholarisActividadIndagacionDetalle::className(), ['actividad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadRecursos()
    {
        return $this->hasMany(ScholarisActividadRecursos::className(), ['actividad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadSeguimientos()
    {
        return $this->hasMany(ScholarisActividadSeguimiento::className(), ['actividad_id' => 'id']);
    }
    
     public function getInsumo(){
        return $this->hasOne(ScholarisTipoActividad::className(), ['id' => 'tipo_actividad_id']);
    }   
    
    public function getBloque(){
        return $this->hasOne(\backend\models\ScholarisBloqueActividad::className(), ['id' => 'bloque_actividad_id']);
    }
    
    public function getClase(){
        return $this->hasOne(ScholarisClase::className(), ['id' => 'paralelo_id']);
    }
}
