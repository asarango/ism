<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_plan_semanal_reflexion".
 *
 * @property int $id
 * @property int $plan_semanal_id
 * @property string $antes
 * @property string $durante
 * @property string $despues
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 *
 * @property KidsPlanSemanal $planSemanal
 */
class KidsPlanSemanalReflexion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_plan_semanal_reflexion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_semanal_id', 'created'], 'required'],
            [['plan_semanal_id'], 'default', 'value' => null],
            [['plan_semanal_id'], 'integer'],
            [['antes', 'durante', 'despues'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['plan_semanal_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsPlanSemanal::className(), 'targetAttribute' => ['plan_semanal_id' => 'id']],
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
            'antes' => 'Antes',
            'durante' => 'Durante',
            'despues' => 'Despues',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanSemanal()
    {
        return $this->hasOne(KidsPlanSemanal::className(), ['id' => 'plan_semanal_id']);
    }
}
