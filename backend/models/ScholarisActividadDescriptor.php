<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_actividad_descriptor".
 *
 * @property int $id
 * @property int $actividad_id
 * @property int $plan_vert_pai_descriptor_id
 *
 * @property PlanificacionVerticalPaiDescriptores $planVertPaiDescriptor
 */
class ScholarisActividadDescriptor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_actividad_descriptor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['actividad_id', 'plan_vert_pai_descriptor_id'], 'required'],
            [['actividad_id', 'plan_vert_pai_descriptor_id'], 'default', 'value' => null],
            [['actividad_id', 'plan_vert_pai_descriptor_id'], 'integer'],
            [['plan_vert_pai_descriptor_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionVerticalPaiDescriptores::className(), 'targetAttribute' => ['plan_vert_pai_descriptor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'actividad_id' => 'Actividad ID',
            'plan_vert_pai_descriptor_id' => 'Plan Vert Pai Descriptor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanVertPaiDescriptor()
    {
        return $this->hasOne(PlanificacionVerticalPaiDescriptores::className(), ['id' => 'plan_vert_pai_descriptor_id']);
    }
}
