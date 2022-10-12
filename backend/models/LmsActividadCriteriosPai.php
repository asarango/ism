<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lms_actividad_criterios_pai".
 *
 * @property int $id
 * @property int $lms_actividad_id
 * @property int $plan_vertical_descriptor_id
 *
 * @property LmsActividad $lmsActividad
 * @property PlanificacionVerticalPaiDescriptores $planVerticalDescriptor
 */
class LmsActividadCriteriosPai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lms_actividad_criterios_pai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lms_actividad_id', 'plan_vertical_descriptor_id'], 'required'],
            [['lms_actividad_id', 'plan_vertical_descriptor_id'], 'default', 'value' => null],
            [['lms_actividad_id', 'plan_vertical_descriptor_id'], 'integer'],
            [['lms_actividad_id'], 'exist', 'skipOnError' => true, 'targetClass' => LmsActividad::className(), 'targetAttribute' => ['lms_actividad_id' => 'id']],
            [['plan_vertical_descriptor_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionVerticalPaiDescriptores::className(), 'targetAttribute' => ['plan_vertical_descriptor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lms_actividad_id' => 'Lms Actividad ID',
            'plan_vertical_descriptor_id' => 'Plan Vertical Descriptor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLmsActividad()
    {
        return $this->hasOne(LmsActividad::className(), ['id' => 'lms_actividad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanVerticalDescriptor()
    {
        return $this->hasOne(PlanificacionVerticalPaiDescriptores::className(), ['id' => 'plan_vertical_descriptor_id']);
    }
}
