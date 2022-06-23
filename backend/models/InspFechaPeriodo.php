<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "insp_fecha_periodo".
 *
 * @property string $fecha
 * @property int $periodo_id
 * @property int $numero_dia
 * @property bool $hay_asitencia
 * @property bool $es_presencial
 * @property string $observacion
 *
 * @property ScholarisPeriodo $periodo
 */
class InspFechaPeriodo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insp_fecha_periodo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'periodo_id', 'numero_dia'], 'required'],
            [['fecha'], 'safe'],
            [['periodo_id', 'numero_dia'], 'default', 'value' => null],
            [['periodo_id', 'numero_dia'], 'integer'],
            [['hay_asitencia', 'es_presencial'], 'boolean'],
            [['observacion'], 'string', 'max' => 255],
            [['fecha'], 'unique'],
            [['periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fecha' => 'Fecha',
            'periodo_id' => 'Periodo ID',
            'numero_dia' => 'Numero Dia',
            'hay_asitencia' => 'Hay Asitencia',
            'es_presencial' => 'Es Presencial',
            'observacion' => 'Observacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_id']);
    }
}
