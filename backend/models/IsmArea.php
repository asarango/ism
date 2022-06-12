<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_area".
 *
 * @property int $id
 * @property string $nombre
 * @property string $siglas
 *
 * @property IsmCriterioDescriptorArea[] $ismCriterioDescriptorAreas
 * @property IsmCriterioLiteralArea[] $ismCriterioLiteralAreas
 * @property IsmMallaArea[] $ismMallaAreas
 */
class IsmArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'siglas'], 'required'],
            [['nombre'], 'string', 'max' => 100],
            [['siglas'], 'string', 'max' => 10],
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
            'siglas' => 'Siglas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioDescriptorAreas()
    {
        return $this->hasMany(IsmCriterioDescriptorArea::className(), ['id_area' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioLiteralAreas()
    {
        return $this->hasMany(IsmCriterioLiteralArea::className(), ['ism_area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmMallaAreas()
    {
        return $this->hasMany(IsmMallaArea::className(), ['area_id' => 'id']);
    }
}
