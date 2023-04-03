<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dece_seguimiento_firmas".
 *
 * @property int $id
 * @property int $id_reg_seguimiento
 * @property string $nombre
 * @property string $cedula
 * @property string $parentesco
 * @property string $cargo
 *
 * @property DeceRegistroSeguimiento $regSeguimiento
 */
class DeceSeguimientoFirmas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dece_seguimiento_firmas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_reg_seguimiento', 'nombre', 'cedula', 'parentesco'], 'required'],
            [['id_reg_seguimiento'], 'default', 'value' => null],
            [['id_reg_seguimiento'], 'integer'],
            [['nombre', 'parentesco', 'cargo'], 'string', 'max' => 100],
            [['cedula'], 'string', 'max' => 20],
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
            'nombre' => 'Nombre',
            'cedula' => 'Cedula',
            'parentesco' => 'Parentesco',
            'cargo' => 'Cargo',
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
