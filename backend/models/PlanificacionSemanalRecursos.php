<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_semanal_recursos".
 *
 * @property int $id
 * @property int $plan_semanal_id
 * @property string $tema
 * @property string $tipo_recurso
 * @property string $url_recurso
 * @property bool $estado
 *
 * @property PlanificacionSemanal $planSemanal
 */
class PlanificacionSemanalRecursos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_semanal_recursos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_semanal_id', 'tema', 'tipo_recurso', 'url_recurso', 'estado'], 'required'],
            [['plan_semanal_id'], 'default', 'value' => null],
            [['plan_semanal_id'], 'integer'],
            [['url_recurso'], 'string'],
            [['estado'], 'boolean'],
            [['tema'], 'string', 'max' => 100],
            [['tipo_recurso'], 'string', 'max' => 50],
            [['plan_semanal_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionSemanal::className(), 'targetAttribute' => ['plan_semanal_id' => 'id']],
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
            'tema' => 'Tema',
            'tipo_recurso' => 'Tipo Recurso',
            'url_recurso' => 'Url Recurso',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanSemanal()
    {
        return $this->hasOne(PlanificacionSemanal::className(), ['id' => 'plan_semanal_id']);
    }
}
