<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_conexion_tdc".
 *
 * @property int $id
 * @property int $plan_vertical_id
 * @property int $opcion_tdc_id
 * @property bool $es_activo
 *
 * @property DipConexionesTdcOpciones $opcionTdc
 * @property PlanificacionVerticalDiploma $planVertical
 */
class PlanificacionConexionTdc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_conexion_tdc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_vertical_id', 'opcion_tdc_id'], 'required'],
            [['plan_vertical_id', 'opcion_tdc_id'], 'default', 'value' => null],
            [['plan_vertical_id', 'opcion_tdc_id'], 'integer'],
            [['es_activo'], 'boolean'],
            [['opcion_tdc_id'], 'exist', 'skipOnError' => true, 'targetClass' => DipConexionesTdcOpciones::className(), 'targetAttribute' => ['opcion_tdc_id' => 'id']],
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
            'opcion_tdc_id' => 'Opcion Tdc ID',
            'es_activo' => 'Es Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpcionTdc()
    {
        return $this->hasOne(DipConexionesTdcOpciones::className(), ['id' => 'opcion_tdc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanVertical()
    {
        return $this->hasOne(PlanificacionVerticalDiploma::className(), ['id' => 'plan_vertical_id']);
    }
}
