<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_criterio_literal_area".
 *
 * @property int $id
 * @property int $ism_criterio_literal_id
 * @property int $ism_area_id
 *
 * @property IsmCriterioDescriptor[] $ismCriterioDescriptors
 * @property IsmArea $ismArea
 * @property IsmCriterioLiteral $ismCriterioLiteral
 */
class IsmCriterioLiteralArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_criterio_literal_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ism_criterio_literal_id', 'ism_area_id'], 'required'],
            [['ism_criterio_literal_id', 'ism_area_id'], 'default', 'value' => null],
            [['ism_criterio_literal_id', 'ism_area_id'], 'integer'],
            [['ism_area_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmArea::className(), 'targetAttribute' => ['ism_area_id' => 'id']],
            [['ism_criterio_literal_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmCriterioLiteral::className(), 'targetAttribute' => ['ism_criterio_literal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ism_criterio_literal_id' => 'Ism Criterio Literal ID',
            'ism_area_id' => 'Ism Area ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioDescriptors()
    {
        return $this->hasMany(IsmCriterioDescriptor::className(), ['ism_criterio_literal_area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmArea()
    {
        return $this->hasOne(IsmArea::className(), ['id' => 'ism_area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioLiteral()
    {
        return $this->hasOne(IsmCriterioLiteral::className(), ['id' => 'ism_criterio_literal_id']);
    }
}
