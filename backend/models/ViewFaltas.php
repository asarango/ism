<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "view_faltas".
 *
 * @property int $id
 * @property int $scholaris_perido_id
 * @property string $student
 * @property string $fecha_falta
 * @property string $solicita_justificacion
 * @property string $fecha_solicitud_justificacion
 * @property string $motivo_justificacion
 * @property bool $es_justificado
 * @property string $fecha_justificacion
 * @property string $respuesta_justificacion
 * @property string $usuario_justifica
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 */
class ViewFaltas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_faltas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'scholaris_perido_id'], 'default', 'value' => null],
            [['id', 'scholaris_perido_id'], 'integer'],
            [['student', 'solicita_justificacion', 'motivo_justificacion', 'respuesta_justificacion'], 'string'],
            [['fecha_falta', 'fecha_solicitud_justificacion', 'fecha_justificacion', 'created', 'updated'], 'safe'],
            [['es_justificado'], 'boolean'],
            [['usuario_justifica', 'created_at', 'updated_at'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'scholaris_perido_id' => 'Scholaris Perido ID',
            'student' => 'Student',
            'fecha_falta' => 'Fecha Falta',
            'solicita_justificacion' => 'Solicita Justificacion',
            'fecha_solicitud_justificacion' => 'Fecha Solicitud Justificacion',
            'motivo_justificacion' => 'Motivo Justificacion',
            'es_justificado' => 'Es Justificado',
            'fecha_justificacion' => 'Fecha Justificacion',
            'respuesta_justificacion' => 'Respuesta Justificacion',
            'usuario_justifica' => 'Usuario Justifica',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
        ];
    }
}
