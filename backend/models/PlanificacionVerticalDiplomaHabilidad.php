<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_vertical_diploma_habilidad".
 *
 * @property int $id
 * @property int $plan_vertical_id
 * @property int $opcion_habilidad_id
 *
 * @property EnfoquesDiplomaSbOpcion $opcionHabilidad
 * @property PlanificacionVerticalDiploma $planVertical
 */
class PlanificacionVerticalDiplomaHabilidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_vertical_diploma_habilidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_vertical_id', 'opcion_habilidad_id'], 'required'],
            [['plan_vertical_id', 'opcion_habilidad_id'], 'default', 'value' => null],
            [['plan_vertical_id', 'opcion_habilidad_id'], 'integer'],
            [['opcion_habilidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => EnfoquesDiplomaSbOpcion::className(), 'targetAttribute' => ['opcion_habilidad_id' => 'id']],
            [['plan_vertical_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionVerticalDiploma::className(), 'targetAttribute' => ['plan_vertical_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_vertical_id' => 'Plan Vertical ID',
            'opcion_habilidad_id' => 'Opcion Habilidad ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpcionHabilidad()
    {
        return $this->hasOne(EnfoquesDiplomaSbOpcion::className(), ['id' => 'opcion_habilidad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanVertical()
    {
        return $this->hasOne(PlanificacionVerticalDiploma::className(), ['id' => 'plan_vertical_id']);
    }
}
