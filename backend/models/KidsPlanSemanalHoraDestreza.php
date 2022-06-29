<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_plan_semanal_hora_destreza".
 *
 * @property int $id
 * @property int $hora_clase_id
 * @property int $micro_destreza_id
 *
 * @property KidsMicroDestreza $microDestreza
 * @property KidsPlanSemanalHoraClase $horaClase
 */
class KidsPlanSemanalHoraDestreza extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_plan_semanal_hora_destreza';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hora_clase_id', 'micro_destreza_id'], 'required'],
            [['hora_clase_id', 'micro_destreza_id'], 'default', 'value' => null],
            [['hora_clase_id', 'micro_destreza_id'], 'integer'],
            [['micro_destreza_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsMicroDestreza::className(), 'targetAttribute' => ['micro_destreza_id' => 'id']],
            [['hora_clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsPlanSemanalHoraClase::className(), 'targetAttribute' => ['hora_clase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hora_clase_id' => 'Hora Clase ID',
            'micro_destreza_id' => 'Micro Destreza ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMicroDestreza()
    {
        return $this->hasOne(KidsMicroDestreza::className(), ['id' => 'micro_destreza_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHoraClase()
    {
        return $this->hasOne(KidsPlanSemanalHoraClase::className(), ['id' => 'hora_clase_id']);
    }
}
