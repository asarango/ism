<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_alumnos_novedades".
 *
 * @property int $id
 * @property int $asistencia_profesor_id
 * @property int $comportamiento_detalle_id
 * @property string $observacion
 * @property int $grupo_id
 * @property bool $es_justificado
 * @property string $codigo_justificacion
 * @property string $acuerdo_justificacion
 * @property string $solicitud_representante_user_id
 * @property string $solicitud_representante_fecha
 * @property string $solicitud_representante_motivo
 * @property string $justificacion_fecha
 * @property string $justificacion_usuario
 *
 * @property ScholarisAsistenciaComportamientoDetalle $comportamientoDetalle
 * @property ScholarisAsistenciaProfesor $asistenciaProfesor
 * @property ScholarisGrupoAlumnoClase $grupo
 */
class ScholarisAsistenciaAlumnosNovedades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_alumnos_novedades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asistencia_profesor_id', 'comportamiento_detalle_id', 'grupo_id'], 'required'],
            [['asistencia_profesor_id', 'comportamiento_detalle_id', 'grupo_id'], 'default', 'value' => null],
            [['asistencia_profesor_id', 'comportamiento_detalle_id', 'grupo_id'], 'integer'],
            [['es_justificado'], 'boolean'],
            [['acuerdo_justificacion', 'solicitud_representante_motivo'], 'string'],
            [['solicitud_representante_fecha', 'justificacion_fecha'], 'safe'],
            [['observacion'], 'string', 'max' => 255],
            [['codigo_justificacion'], 'string', 'max' => 10],
            [['solicitud_representante_user_id', 'justificacion_usuario'], 'string', 'max' => 200],
            [['comportamiento_detalle_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisAsistenciaComportamientoDetalle::className(), 'targetAttribute' => ['comportamiento_detalle_id' => 'id']],
            [['asistencia_profesor_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisAsistenciaProfesor::className(), 'targetAttribute' => ['asistencia_profesor_id' => 'id']],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisGrupoAlumnoClase::className(), 'targetAttribute' => ['grupo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'asistencia_profesor_id' => 'Asistencia Profesor ID',
            'comportamiento_detalle_id' => 'Comportamiento Detalle ID',
            'observacion' => 'Observacion',
            'grupo_id' => 'Grupo ID',
            'es_justificado' => 'Es Justificado',
            'codigo_justificacion' => 'Codigo Justificacion',
            'acuerdo_justificacion' => 'Acuerdo Justificacion',
            'solicitud_representante_user_id' => 'Solicitud Representante User ID',
            'solicitud_representante_fecha' => 'Solicitud Representante Fecha',
            'solicitud_representante_motivo' => 'Solicitud Representante Motivo',
            'justificacion_fecha' => 'Justificacion Fecha',
            'justificacion_usuario' => 'Justificacion Usuario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComportamientoDetalle()
    {
        return $this->hasOne(ScholarisAsistenciaComportamientoDetalle::className(), ['id' => 'comportamiento_detalle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsistenciaProfesor()
    {
        return $this->hasOne(ScholarisAsistenciaProfesor::className(), ['id' => 'asistencia_profesor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }
}
