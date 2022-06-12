<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_criterio".
 *
 * @property int $id
 * @property string $nombre
 * @property bool $es_activo
 *
 * @property IsmCriterioDescriptorArea[] $ismCriterioDescriptorAreas
 * @property IsmCriterioLiteral[] $ismCriterioLiterals
 */
class IsmCriterio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_criterio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['es_activo'], 'boolean'],
            [['nombre'], 'string', 'max' => 1],
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
            'es_activo' => 'Es Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioDescriptorAreas()
    {
        return $this->hasMany(IsmCriterioDescriptorArea::className(), ['id_criterio' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmCriterioLiterals()
    {
        return $this->hasMany(IsmCriterioLiteral::className(), ['criterio_id' => 'id']);
    }
}
