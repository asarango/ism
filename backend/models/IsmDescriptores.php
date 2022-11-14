<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_descriptores".
 *
 * @property int $id
 * @property string $nombre
 * @property string $detalle
 *
 * @property IsmCriterioDescriptorArea[] $ismCriterioDescriptorAreas
 */
class IsmDescriptores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_descriptores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 5],
            [['detalle'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'detalle' => 'Detalle',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioDescriptorAreas()
    {
        return $this->hasMany(IsmCriterioDescriptorArea::className(), ['id_descriptor' => 'id']);
    }
}
