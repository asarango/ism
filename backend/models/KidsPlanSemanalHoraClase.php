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
 * @property string $created_at
 * @property string $created
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
            [['created'], 'string', 'max' => 200],
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
            'created_at' => 'Created At',
            'created' => 'Created',
        ];
    }
}
