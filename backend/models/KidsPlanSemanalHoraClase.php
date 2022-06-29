<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_plan_semanal_hora_clase".
 *
 * @property int $id
 * @property int $plan_semanal_id
 * @property int $clase_id
 * @property int $detalle_id
 * @property string $fecha
 * @property string $actividades
 * @property string $created_at
 * @property string $created
 *
 * @property KidsPlanSemanal $planSemanal
 * @property ScholarisClase $clase
 * @property ScholarisHorariov2Detalle $detalle
 * @property KidsPlanSemanalHoraDestreza[] $kidsPlanSemanalHoraDestrezas
 */
class KidsPlanSemanalHoraClase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_plan_semanal_hora_clase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_semanal_id', 'clase_id', 'detalle_id', 'fecha', 'created_at', 'created'], 'required'],
            [['plan_semanal_id', 'clase_id', 'detalle_id'], 'default', 'value' => null],
            [['plan_semanal_id', 'clase_id', 'detalle_id'], 'integer'],
            [['fecha', 'created_at'], 'safe'],
            [['actividades'], 'string'],
            [['created'], 'string', 'max' => 200],
            [['plan_semanal_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsPlanSemanal::className(), 'targetAttribute' => ['plan_semanal_id' => 'id']],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
            [['detalle_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisHorariov2Detalle::className(), 'targetAttribute' => ['detalle_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_semanal_id' => 'Plan Semanal ID',
            'clase_id' => 'Clase ID',
            'detalle_id' => 'Detalle ID',
            'fecha' => 'Fecha',
            'actividades' => 'Actividades',
            'created_at' => 'Created At',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanSemanal()
    {
        return $this->hasOne(KidsPlanSemanal::className(), ['id' => 'plan_semanal_id']);
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
    public function getDetalle()
    {
        return $this->hasOne(ScholarisHorariov2Detalle::className(), ['id' => 'detalle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKidsPlanSemanalHoraDestrezas()
    {
        return $this->hasMany(KidsPlanSemanalHoraDestreza::className(), ['hora_clase_id' => 'id']);
    }
}
