<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_seguimiento_acuerdos".
 *
 * @property int $id
 * @property int $id_reg_seguimiento
 * @property int $secuencial
 * @property string $acuerdo
 * @property string $responsable
 * @property string $fecha_max_cumplimiento
 * @property bool $cumplio
 * @property string $parentesco
 *
 * @property DeceRegistroSeguimiento $regSeguimiento
 */
class DeceSeguimientoAcuerdos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_seguimiento_acuerdos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_reg_seguimiento', 'secuencial', 'acuerdo', 'responsable', 'fecha_max_cumplimiento'], 'required'],
            [['id_reg_seguimiento', 'secuencial'], 'default', 'value' => null],
            [['id_reg_seguimiento', 'secuencial'], 'integer'],
            [['acuerdo'], 'string'],
            [['fecha_max_cumplimiento'], 'safe'],
            [['cumplio'], 'boolean'],
            [['responsable'], 'string', 'max' => 100],
            [['parentesco'], 'string', 'max' => 50],
            [['id_reg_seguimiento'], 'exist', 'skipOnError' => true, 'targetClass' => DeceRegistroSeguimiento::className(), 'targetAttribute' => ['id_reg_seguimiento' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_reg_seguimiento' => 'Id Reg Seguimiento',
            'secuencial' => 'Secuencial',
            'acuerdo' => 'Acuerdo',
            'responsable' => 'Responsable',
            'fecha_max_cumplimiento' => 'Fecha Max Cumplimiento',
            'cumplio' => 'Cumplio',
            'parentesco' => 'Parentesco',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegSeguimiento()
    {
        return $this->hasOne(DeceRegistroSeguimiento::className(), ['id' => 'id_reg_seguimiento']);
    }
}
