<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pud_dip_proceso_aprendizaje".
 *
 * @property int $id
 * @property int $plan_unidad_id
 * @property int $opcion_id
 * @property bool $es_activo
 *
 * @property PlanificacionBloquesUnidad $planUnidad
 * @property PlanificacionOpciones $opcion
 */
class PudDipProcesoAprendizaje extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pud_dip_proceso_aprendizaje';
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
            [['es_activo'], 'boolean'],
            [['plan_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['plan_unidad_id' => 'id']],
            [['opcion_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionOpciones::className(), 'targetAttribute' => ['opcion_id' => 'id']],
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
            'es_activo' => 'Es Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'plan_unidad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpcion()
    {
        return $this->hasOne(PlanificacionOpciones::className(), ['id' => 'opcion_id']);
    }
}
