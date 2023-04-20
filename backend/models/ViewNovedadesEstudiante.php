<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "view_novedades_estudiante".
 *
 * @property int $id
 * @property string $fecha
 * @property string $nombre
 * @property string $docente
 * @property int $curso_id
 * @property string $curso
 * @property int $paralelo_id
 * @property string $paralelo
 * @property string $materia
 * @property string $estudiante
 * @property string $codigo
 * @property string $observacion
 * @property bool $es_justificado
 * @property string $solicitud_representante_user_id
 * @property string $solicitud_representante_fecha
 * @property string $solicitud_representante_motivo
 * @property string $justificacion_fecha
 * @property string $justificacion_usuario
 * @property string $acuerdo_justificacion
 * @property int $scholaris_periodo_id
 */
class ViewNovedadesEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_novedades_estudiante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'curso_id', 'paralelo_id', 'scholaris_periodo_id'], 'default', 'value' => null],
            [['id', 'curso_id', 'paralelo_id', 'scholaris_periodo_id'], 'integer'],
            [['fecha', 'solicitud_representante_fecha', 'justificacion_fecha'], 'safe'],
            [['paralelo', 'estudiante', 'solicitud_representante_motivo', 'acuerdo_justificacion'], 'string'],
            [['es_justificado'], 'boolean'],
            [['nombre'], 'string', 'max' => 50],
            [['docente'], 'string', 'max' => 64],
            [['curso'], 'string', 'max' => 32],
            [['materia'], 'string', 'max' => 100],
            [['codigo'], 'string', 'max' => 5],
            [['observacion'], 'string', 'max' => 150],
            [['solicitud_representante_user_id', 'justificacion_usuario'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha' => 'Fecha',
            'nombre' => 'Nombre',
            'docente' => 'Docente',
            'curso_id' => 'Curso ID',
            'curso' => 'Curso',
            'paralelo_id' => 'Paralelo ID',
            'paralelo' => 'Paralelo',
            'materia' => 'Materia',
            'estudiante' => 'Estudiante',
            'codigo' => 'Codigo',
            'observacion' => 'Observacion',
            'es_justificado' => 'Es Justificado',
            'solicitud_representante_user_id' => 'Solicitud Representante User ID',
            'solicitud_representante_fecha' => 'Solicitud Representante Fecha',
            'solicitud_representante_motivo' => 'Solicitud Representante Motivo',
            'justificacion_fecha' => 'Justificacion Fecha',
            'justificacion_usuario' => 'Justificacion Usuario',
            'acuerdo_justificacion' => 'Acuerdo Justificacion',
            'scholaris_periodo_id' => 'Scholaris Periodo ID',
        ];
    }
}
