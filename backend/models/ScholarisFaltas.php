<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_faltas".
 *
 * @property int $id
 * @property int $scholaris_perido_id
 * @property int $student_id
 * @property string $fecha
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
 *
 * @property OpStudent $student
 * @property ScholarisPeriodo $scholarisPerido
 */
class ScholarisFaltas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_faltas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scholaris_perido_id', 'student_id', 'fecha', 'created', 'created_at'], 'required'],
            [['scholaris_perido_id', 'student_id'], 'default', 'value' => null],
            [['scholaris_perido_id', 'student_id'], 'integer'],
            [['fecha', 'fecha_solicitud_justificacion', 'fecha_justificacion', 'created', 'updated'], 'safe'],
            [['motivo_justificacion', 'respuesta_justificacion'], 'string'],
            [['es_justificado'], 'boolean'],
            [['usuario_justifica', 'created_at', 'updated_at'], 'string', 'max' => 200],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['scholaris_perido_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['scholaris_perido_id' => 'id']],
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
            'student_id' => 'Student ID',
            'fecha' => 'Fecha',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPerido()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'scholaris_perido_id']);
    }
}
