<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lms_docente".
 *
 * @property int $id
 * @property int $lms_id
 * @property int $horario_detalle_id
 * @property int $hora_numero_lms
 * @property int $clase_id
 * @property string $fecha
 * @property bool $se_realizo
 * @property string $motivo_no_realizado
 * @property string $justificativo
 * @property string $created
 * @property string $create_at
 * @property string $updated
 * @property string $updated_at
 *
 * @property Lms $lms
 * @property ScholarisClase $clase
 * @property ScholarisHorariov2Detalle $horarioDetalle
 */
class LmsDocente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lms_docente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lms_id', 'horario_detalle_id', 'hora_numero_lms', 'clase_id', 'fecha', 'created', 'create_at'], 'required'],
            [['lms_id', 'horario_detalle_id', 'hora_numero_lms', 'clase_id'], 'default', 'value' => null],
            [['lms_id', 'horario_detalle_id', 'hora_numero_lms', 'clase_id'], 'integer'],
            [['fecha', 'create_at', 'updated_at'], 'safe'],
            [['se_realizo'], 'boolean'],
            [['motivo_no_realizado', 'justificativo'], 'string'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['lms_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lms::className(), 'targetAttribute' => ['lms_id' => 'id']],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
            [['horario_detalle_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisHorariov2Detalle::className(), 'targetAttribute' => ['horario_detalle_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lms_id' => 'Lms ID',
            'horario_detalle_id' => 'Horario Detalle ID',
            'hora_numero_lms' => 'Hora Numero Lms',
            'clase_id' => 'Clase ID',
            'fecha' => 'Fecha',
            'se_realizo' => 'Se Realizo',
            'motivo_no_realizado' => 'Motivo No Realizado',
            'justificativo' => 'Justificativo',
            'created' => 'Created',
            'create_at' => 'Create At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLms()
    {
        return $this->hasOne(Lms::className(), ['id' => 'lms_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHorarioDetalle()
    {
        return $this->hasOne(ScholarisHorariov2Detalle::className(), ['id' => 'horario_detalle_id']);
    }
}
