<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pud_dip_evaluaciones".
 *
 * @property int $id
 * @property int $plan_unidad_id
 * @property int $opcion_id
 * @property string $formativa
 * @property string $sumativa
 *
 * @property PlanificacionBloquesUnidad $planUnidad
 */
class PudDipEvaluaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pud_dip_evaluaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_unidad_id', 'opcion_id'], 'required'],
            [['plan_unidad_id', 'opcion_id'], 'default', 'value' => null],
            [['plan_unidad_id', 'opcion_id'], 'integer'],
            [['formativa', 'sumativa'], 'string'],
            [['plan_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['plan_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_unidad_id' => 'Plan Unidad ID',
            'opcion_id' => 'Opcion ID',
            'formativa' => 'Formativa',
            'sumativa' => 'Sumativa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'plan_unidad_id']);
    }
}
