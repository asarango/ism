<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_plan_semanal".
 *
 * @property int $id
 * @property int $kids_unidad_micro_id
 * @property int $semana_id
 * @property string $created_at
 * @property string $created
 * @property string $estado
 * @property string $sent_at
 * @property string $sent_by
 * @property string $approved_at
 * @property string $approved_by
 *
 * @property KidsUnidadMicro $kidsUnidadMicro
 * @property ScholarisBloqueSemanas $semana
 */
class KidsPlanSemanal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_plan_semanal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kids_unidad_micro_id', 'semana_id', 'created_at', 'created', 'estado'], 'required'],
            [['kids_unidad_micro_id', 'semana_id'], 'default', 'value' => null],
            [['kids_unidad_micro_id', 'semana_id'], 'integer'],
            [['created_at', 'sent_at', 'approved_at'], 'safe'],
            [['created', 'sent_by', 'approved_by'], 'string', 'max' => 200],
            [['estado'], 'string', 'max' => 30],
            [['kids_unidad_micro_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsUnidadMicro::className(), 'targetAttribute' => ['kids_unidad_micro_id' => 'id']],
            [['semana_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueSemanas::className(), 'targetAttribute' => ['semana_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kids_unidad_micro_id' => 'Kids Unidad Micro ID',
            'semana_id' => 'Semana ID',
            'created_at' => 'Created At',
            'created' => 'Created',
            'estado' => 'Estado',
            'sent_at' => 'Sent At',
            'sent_by' => 'Sent By',
            'approved_at' => 'Approved At',
            'approved_by' => 'Approved By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidsUnidadMicro()
    {
        return $this->hasOne(KidsUnidadMicro::className(), ['id' => 'kids_unidad_micro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemana()
    {
        return $this->hasOne(ScholarisBloqueSemanas::className(), ['id' => 'semana_id']);
    }
}
