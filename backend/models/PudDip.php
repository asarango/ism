<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pud_dip".
 *
 * @property int $id
 * @property int $planificacion_bloques_unidad_id
 * @property string $codigo
 * @property string $campo_de
 * @property bool $opcion_boolean
 * @property string $opcion_texto
 *
 * @property PlanificacionBloquesUnidad $planificacionBloquesUnidad
 */
class PudDip extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pud_dip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planificacion_bloques_unidad_id', 'codigo', 'campo_de'], 'required'],
            [['planificacion_bloques_unidad_id'], 'default', 'value' => null],
            [['planificacion_bloques_unidad_id'], 'integer'],
            [['opcion_boolean'], 'boolean'],
            [['opcion_texto'], 'string'],
            [['codigo'], 'string', 'max' => 50],
            [['campo_de'], 'string', 'max' => 20],
            [['planificacion_bloques_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['planificacion_bloques_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'planificacion_bloques_unidad_id' => 'Planificacion Bloques Unidad ID',
            'codigo' => 'Codigo',
            'campo_de' => 'Campo De',
            'opcion_boolean' => 'Opcion Boolean',
            'opcion_texto' => 'Opcion Texto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionBloquesUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'planificacion_bloques_unidad_id']);
    }
}
