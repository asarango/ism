<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_literal_descriptores".
 *
 * @property int $id
 * @property string $descripcion
 *
 * @property IsmCriterioDescriptorArea[] $ismCriterioDescriptorAreas
 */
class IsmLiteralDescriptores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_literal_descriptores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioDescriptorAreas()
    {
        return $this->hasMany(IsmCriterioDescriptorArea::className(), ['id_literal_descriptor' => 'id']);
    }
}
